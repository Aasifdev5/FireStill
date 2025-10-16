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
    // Centralize QR code generation
    private function generateQrCode($equipmentCode)
    {
        $qrFolder = public_path('uploads/qr_codes');
        if (!file_exists($qrFolder)) {
            mkdir($qrFolder, 0755, true);
        }
        $qrFileName = $equipmentCode . '.png';
        $qrPath = $qrFolder . '/' . $qrFileName;

        try {
            Log::info('Generating QR code for equipment code: ' . $equipmentCode . ', path: ' . $qrPath);
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($equipmentCode)
                ->size(300)
                ->margin(10)
                ->build();
            $result->saveToFile($qrPath);
            if (!file_exists($qrPath)) {
                throw new \Exception('QR code file was not created at ' . $qrPath);
            }
            Log::info('QR code generated successfully for code: ' . $equipmentCode);
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

        // Generate QR code if not exists
        if (!$equipment->qr_code) {
            $qrPath = $this->generateQrCode($equipment->code);
            if ($qrPath) {
                $equipment->update(['qr_code' => $qrPath]);
            }
        }

        return view('admin.inspections.create', compact('equipment', 'user_session'));
    }

  public function store(Request $request)
{
    try {
        $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'inspected_at' => 'required|date',
            'checklist' => 'required|array',
            'checklist.*' => 'required|in:yes,no',
            'observations' => 'nullable|string',
            'expiration_date' => 'required|date',
            'photos.*' => 'image', // 5MB per photo
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        // Generate QR code if not exists
        if (!$equipment->qr_code) {
            $qrPath = $this->generateQrCode($equipment->code);
            if ($qrPath) $equipment->update(['qr_code' => $qrPath]);
        }

        // Create inspection
        $inspection = Inspection::create([
            'equipment_id' => $request->equipment_id,
            'user_id' => Session::get('LoggedIn'),
            'inspected_at' => $request->inspected_at,
            'checklist' => $request->checklist,
            'observations' => $request->observations,
            'status' => $this->determineStatus($request->checklist),
            'expiration_date' => $request->expiration_date,
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            $photoDestination = public_path('uploads/inspections/photos');
            if (!file_exists($photoDestination)) mkdir($photoDestination, 0755, true);

            foreach ($request->file('photos') as $index => $file) {
                $uid = Session::get('LoggedIn');
                $filename = $uid . '_photo_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();
                $file->move($photoDestination, $filename);
                $path = 'uploads/inspections/photos/' . $filename;

                $inspection->photos()->create(['path' => $path]);
            }
        }

        // Generate PDF report
        $pdfDestination = public_path('uploads/inspections/reports');
        if (!file_exists($pdfDestination)) mkdir($pdfDestination, 0755, true);

        $pdf = PDF::loadView('admin.inspections.report_pdf', compact('inspection'))
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'dpi' => 150,
                'defaultFont' => 'Arial',
            ]);

        $uid = Session::get('LoggedIn');
        $filename = $uid . '_report_' . time() . '.pdf';
        $pdf->save($pdfDestination . '/' . $filename);

        $path = 'uploads/inspections/reports/' . $filename;
        $inspection->update(['report_path' => $path]);

        $this->scheduleNotification($equipment);

        return response()->json([
            'success' => true,
            'message' => 'Inspección guardada exitosamente',
            'pdf_path' => $path
        ]);

    } catch (\Exception $e) {
        Log::error('Error in store method: ' . $e->getMessage());
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
        return response()->file(public_path($inspection->report_path));
    }

    // Basic notification scheduling (to be enhanced with email/sms)
    private function scheduleNotification($equipment)
    {
        $lastInspection = $equipment->inspections()->latest()->first();
        if ($lastInspection) {
            $nextInspection = $lastInspection->inspected_at->addMonths(6); // Semiannual
            if ($nextInspection->isFuture() && $nextInspection->diffInDays(now()) <= 30) {
                Log::info("Reminder scheduled for equipment {$equipment->code} on {$nextInspection}");
                // Add logic to send email/SMS here (e.g., using Laravel Notification)
            }
            if ($lastInspection->status === 'critical') {
                Log::info("Alert triggered for critical equipment {$equipment->code}");
                // Add alert logic here
            }
        }
    }
}
