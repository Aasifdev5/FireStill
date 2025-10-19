<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    private function generateQrCode($equipmentCode)
    {
        $qrFolder = public_path('uploads/qr_codes');
        if (!file_exists($qrFolder)) {
            mkdir($qrFolder, 0755, true);
            Log::info('Created QR code directory: ' . $qrFolder);
        }
        $qrFileName = 'inspection_' . $equipmentCode . '.png';
        $qrPath = $qrFolder . '/' . $qrFileName;

        try {
            $inspectionHistoryUrl = url('/inspections/history?code=' . $equipmentCode);
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($inspectionHistoryUrl)
                ->size(300)
                ->margin(10)
                ->build();
            $result->saveToFile($qrPath);
            if (!file_exists($qrPath)) {
                throw new \Exception('QR code file was not created at ' . $qrPath);
            }
            return 'uploads/qr_codes/' . $qrFileName;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code: ' . $e->getMessage());
            return null;
        }
    }

    public function index()
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $inspections = Inspection::with(['equipment.client', 'user'])->latest()->get();
        return view('admin.inspections.index', compact('inspections', 'user_session'));
    }

    public function create(Equipment $equipment)
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        if (!$equipment->inspection_qr_code) {
            $qrPath = $this->generateQrCode($equipment->code);
            if ($qrPath) {
                $equipment->update(['inspection_qr_code' => $qrPath]);
            }
        }
        return view('admin.inspections.create', compact('equipment', 'user_session'));
    }

    public function store(Request $request)
    {
        try {
            if (!Session::has('LoggedIn')) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            $userId = Session::get('LoggedIn');
            $user = \App\Models\User::find($userId);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Invalid user'], 401);
            }

            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipments,id',
                'inspected_at' => 'required|date',
                'checklist' => 'required|array',
                'checklist.*' => 'required|in:yes,no',
                'observations' => 'nullable|string',
                'expiration_date' => 'required|date',
                'photos.*' => 'image',
            ]);

            $equipment = Equipment::findOrFail($request->equipment_id);

            if (!$equipment->inspection_qr_code) {
                $qrPath = $this->generateQrCode($equipment->code);
                if ($qrPath) {
                    $equipment->update(['inspection_qr_code' => $qrPath]);
                }
            }

            $inspection = Inspection::create([
                'equipment_id' => $request->equipment_id,
                'user_id' => $userId,
                'inspected_at' => $request->inspected_at,
                'checklist' => $request->checklist,
                'observations' => $request->observations,
                'status' => $this->determineStatus($request->checklist),
                'expiration_date' => $request->expiration_date,
            ]);

            if ($request->hasFile('photos')) {
                $photoDestination = public_path('uploads/inspections/photos');
                if (!file_exists($photoDestination)) {
                    mkdir($photoDestination, 0755, true);
                }

                foreach ($request->file('photos') as $index => $file) {
                    $filename = $userId . '_photo_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move($photoDestination, $filename);
                    $path = 'uploads/inspections/photos/' . $filename;
                    $inspection->photos()->create(['path' => $path]);
                }
            }

            $pdfDestination = public_path('uploads/inspections/reports');
            if (!file_exists($pdfDestination)) {
                mkdir($pdfDestination, 0755, true);
            }

            $pdf = Pdf::loadView('admin.inspections.report_pdf', compact('inspection'))
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'dpi' => 150,
                    'defaultFont' => 'Arial',
                ]);

            $filename = $userId . '_report_' . time() . '.pdf';
            $pdf->save($pdfDestination . '/' . $filename);
            $path = 'uploads/inspections/reports/' . $filename;
            $inspection->update(['report_path' => $path]);

            $equipment->notifyClient($inspection->status);

            return response()->json([
                'success' => true,
                'message' => 'Inspección guardada exitosamente',
                'pdf_path' => $path
            ]);
        } catch (\Exception $e) {
            Log::error('Error in store method: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la inspección: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function determineStatus($checklist)
    {
        $positive_fields = [
            'has_pressure',
            'has_seal',
            'paint_condition',
            'location_correct',
            'has_signage_identification',
            'has_instruction_label',
            'nozzle_clean_free_dust'
        ];
        $negative_fields = ['presents_abnormalities', 'is_obstructed'];

        $is_critical = false;

        foreach ($positive_fields as $field) {
            if (isset($checklist[$field]) && $checklist[$field] === 'no') {
                $is_critical = true;
            }
        }

        foreach ($negative_fields as $field) {
            if (isset($checklist[$field]) && $checklist[$field] === 'yes') {
                $is_critical = true;
            }
        }

        return $is_critical ? 'critical' : 'good';
    }

    public function pdf(Inspection $inspection)
    {
        $filePath = public_path($inspection->report_path);
        if (!file_exists($filePath)) {
            Log::error('PDF file not found: ' . $inspection->report_path);
            return response()->json(['success' => false, 'message' => 'PDF file not found'], 404);
        }
        return response()->file($filePath);
    }

    public function history(Request $request)
    {
        $code = $request->query('code');
        if (!$code) {
            return redirect()->route('scan.qr')->with('error', 'Código QR requerido.');
        }

        $equipment = Equipment::where('code', $code)->with(['client', 'inspections'])->first();
        if (!$equipment) {
            return redirect()->route('scan.qr')->with('error', 'Equipo no encontrado.');
        }

        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        return view('admin.inspections.history', compact('equipment', 'user_session'));
    }
}
