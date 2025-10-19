<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class EquipmentController extends Controller
{
    private function ensureQrCodeDirectory()
    {
        $qrFolder = public_path('uploads/qr_codes');
        Log::info('Checking QR code directory: ' . $qrFolder);
        if (!file_exists($qrFolder)) {
            if (!mkdir($qrFolder, 0755, true)) {
                Log::error('Failed to create QR code directory: ' . $qrFolder);
                throw new \Exception('Cannot create QR code directory.');
            }
            Log::info('Created QR code directory: ' . $qrFolder);
        }
        if (!is_writable($qrFolder)) {
            Log::error('QR code directory is not writable: ' . $qrFolder);
            throw new \Exception('QR code directory is not writable.');
        }
        Log::info('QR code directory is writable: ' . $qrFolder);
        return $qrFolder;
    }

    private function generateQrCode($data, $filename)
    {
        $qrFolder = $this->ensureQrCodeDirectory();
        $qrPath = $qrFolder . '/' . $filename;

        try {
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($data)
                ->size(300)
                ->margin(10)
                ->build();
            $result->saveToFile($qrPath);
            if (!file_exists($qrPath)) {
                throw new \Exception('QR code file was not created at ' . $qrPath);
            }
            return 'uploads/qr_codes/' . $filename;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code: ' . $e->getMessage());
            return null;
        }
    }

    public function testQr()
    {
        $code = 'TEST-' . uniqid();
        $qrPath = public_path('uploads/qr_codes/test.png');
        try {
            $this->ensureQrCodeDirectory();
            Log::info('Generating test QR code for: ' . $code . ', path: ' . $qrPath);
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($code)
                ->size(300)
                ->margin(10)
                ->build();
            $result->saveToFile($qrPath);
            if (!file_exists($qrPath)) {
                throw new \Exception('QR code file was not created at ' . $qrPath);
            }
            Log::info('Test QR code generated successfully');
            return response()->json(['message' => 'QR code created at ' . $qrPath, 'url' => asset('uploads/qr_codes/test.png')]);
        } catch (\Exception $e) {
            Log::error('Failed to generate test QR code: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

   public function index()
{
    $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;

    $equipments = Equipment::with('client')->latest()->get();

    $equipmentsData = $equipments->map(function ($eq) {
        return [
            'id' => $eq->id,
            'code' => $eq->code,
            'type' => $eq->getTypeDisplay(),
            'client' => $eq->client->name ?? 'N/A',
            'location' => $eq->location ?? '—',
            'manufacturer_code' => $eq->manufacturer_code ?? '—',
            'status_indicator' => $eq->getStatusIndicator(), // for badge display
            'status' => $eq->status, // raw DB status for logic
            'created' => $eq->created_at?->format('d M Y'),
            'qr_code' => $eq->qr_code,
            'qr_code_exists' => $eq->qr_code && file_exists(public_path($eq->qr_code)),
        ];
    });

    return view('admin.equipments.index', compact('equipmentsData', 'user_session'));
}


    public function create()
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $clients = Client::all();
        return view('admin.equipments.create', compact('clients', 'user_session'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|string',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'manufacturer_code' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'agent_type' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'last_recharge_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $code = strtoupper('EQ-' . uniqid());
        $qrFileName = $code . '.png';
        $inspectionQrFileName = 'inspection_' . $code . '.png';

        $labelUrl = url('/equipment/label?code=' . $code);
        $qrPath = $this->generateQrCode($labelUrl, $qrFileName);

        $inspectionUrl = url('/inspections/history?code=' . $code);
        $inspectionQrPath = $this->generateQrCode($inspectionUrl, $inspectionQrFileName);

        try {
            Equipment::create($request->all() + [
                'code' => $code,
                'qr_code' => $qrPath,
                'inspection_qr_code' => $inspectionQrPath,
                'status' => 'pending',
            ]);

            return redirect()->route('equipments.index')
                ->with('success', 'Equipo creado exitosamente con códigos QR.');
        } catch (\Exception $e) {
            Log::error('Equipment creation failed: ' . $e->getMessage());
            Equipment::create($request->all() + [
                'code' => $code,
                'qr_code' => null,
                'inspection_qr_code' => null,
                'status' => 'pending',
            ]);
            return redirect()->route('equipments.index')
                ->with('error', 'Equipo creado, pero falló la generación de códigos QR.');
        }
    }

    public function edit($id)
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $equipment = Equipment::findOrFail($id);
        $clients = Client::all();
        return view('admin.equipments.edit', compact('equipment', 'clients', 'user_session'));
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|string',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'manufacturer_code' => 'nullable|string|max:255',
            'serial_number' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'agent_type' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'last_recharge_date' => 'nullable|date',
            'installation_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $equipment->update($request->all());

        if (!$equipment->qr_code || !file_exists(public_path($equipment->qr_code))) {
            $qrFileName = $equipment->code . '.png';
            $labelUrl = url('/equipment/label?code=' . $equipment->code);
            $qrPath = $this->generateQrCode($labelUrl, $qrFileName);
            $equipment->update(['qr_code' => $qrPath]);
        }

        if (!$equipment->inspection_qr_code || !file_exists(public_path($equipment->inspection_qr_code))) {
            $inspectionQrFileName = 'inspection_' . $equipment->code . '.png';
            $inspectionUrl = url('/inspections/history?code=' . $equipment->code);
            $inspectionQrPath = $this->generateQrCode($inspectionUrl, $inspectionQrFileName);
            $equipment->update(['inspection_qr_code' => $inspectionQrPath]);
        }

        return redirect()->route('equipments.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function approve(Request $request)
    {
        $eq = Equipment::findOrFail($request->id);
        $eq->status = 'approved';
        $eq->save();
        return response()->json(['message' => 'Equipment approved']);
    }

    public function reject(Request $request)
    {
        $eq = Equipment::findOrFail($request->id);
        $eq->status = 'rejected';
        $eq->save();
        return response()->json(['message' => 'Equipment rejected']);
    }

    public function destroy(Equipment $equipment)
    {
        if ($equipment->qr_code && file_exists(public_path($equipment->qr_code))) {
            unlink(public_path($equipment->qr_code));
        }
        if ($equipment->inspection_qr_code && file_exists(public_path($equipment->inspection_qr_code))) {
            unlink(public_path($equipment->inspection_qr_code));
        }
        $equipment->delete();
        return response()->json(['success' => true]);
    }

    public function showQrScanner(Request $request)
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $code = $request->query('code');
        return view('admin.equipments.scan_qr', compact('user_session', 'code'));
    }

    public function profile(Request $request)
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
        return view('admin.equipments.profile', compact('equipment', 'user_session'));
    }

    public function label(Request $request)
    {
        $code = $request->query('code');
        if (!$code) {
            return response('Código QR inválido.', 400);
        }

        $equipment = Equipment::where('code', $code)->first();
        if (!$equipment) {
            return response('Equipo no encontrado.', 404);
        }

        return view('public.equipment_label', compact('equipment'));
    }
}
