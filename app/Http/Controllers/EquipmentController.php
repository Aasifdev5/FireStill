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
    // Centralize QR code directory creation with writability check
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

    // Test QR code generation
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

    // Display all equipments
    public function index()
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $equipments = Equipment::with('client')->latest()->get();

        $equipmentsData = $equipments->map(function ($eq) {
            return [
                'id' => $eq->id,
                'code' => $eq->code,
                'type' => ucfirst($eq->type),
                'client' => $eq->client->name ?? 'N/A',
                'location' => $eq->location ?? '—',
                'status' => $eq->status ?? 'pending',
                'created' => $eq->created_at?->format('d M Y'),
                'qr_code' => $eq->qr_code,
                'qr_code_exists' => $eq->qr_code && file_exists(public_path($eq->qr_code)) ? true : false,
            ];
        });

        return view('admin.equipments.index', compact('equipmentsData', 'user_session'));
    }

    // Show create equipment form
    public function create()
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $clients = Client::all();

        return view('admin.equipments.create', compact('clients', 'user_session'));
    }

    // Store new equipment and generate QR code
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $code = strtoupper('EQ-' . uniqid());
        $qrFileName = $code . '.png';
        $qrFolder = $this->ensureQrCodeDirectory();
        $qrPath = $qrFolder . '/' . $qrFileName;

        try {
            // Log the QR code generation attempt
            Log::info('Generating QR code for equipment code: ' . $code . ', path: ' . $qrPath);
            // Generate QR code using Endroid\QrCode v5
            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($code)
                ->size(300)
                ->margin(10)
                ->build();
            $result->saveToFile($qrPath);
            // Verify the QR code file was created
            if (!file_exists($qrPath)) {
                throw new \Exception('QR code file was not created at ' . $qrPath);
            }
            Log::info('QR code generated successfully for code: ' . $code);
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code for equipment code ' . $code . ': ' . $e->getMessage());
            // Create equipment record without QR code
            Equipment::create([
                'client_id' => $request->client_id,
                'type' => $request->type,
                'location' => $request->location,
                'notes' => $request->notes,
                'code' => $code,
                'qr_code' => null,
            ]);
            return redirect()->route('equipments.index')
                ->with('error', 'Equipment created, but QR code generation failed: ' . $e->getMessage());
        }

        Equipment::create([
            'client_id' => $request->client_id,
            'type' => $request->type,
            'location' => $request->location,
            'notes' => $request->notes,
            'code' => $code,
            'qr_code' => 'uploads/qr_codes/' . $qrFileName,
        ]);

        return redirect()->route('equipments.index')
            ->with('success', 'Equipment created successfully with QR code.');
    }
    public function showQrScanner(Request $request)
    {
        $user_session = null;
        if (Session::has('LoggedIn')) {
            $user_session = \App\Models\User::find(Session::get('LoggedIn'));
        }

        // Optionally prefill the code from the query parameter
        $code = $request->query('code');
        return view('admin.equipments.scan_qr', compact('user_session', 'code'));
    }
public function checkStatus(Request $request)
    {
        $code = $request->input('code');
        $equipment = Equipment::where('code', $code)->first();
        if ($equipment) {
            $lastInspection = $equipment->inspections()->latest()->first();
            $qrCodeUrl = $equipment->qr_code && file_exists(public_path($equipment->qr_code)) ? asset($equipment->qr_code) : null;
            return response()->json([
                'success' => true,
                'data' => [
                    'status' => $lastInspection ? $lastInspection->status : 'pending',
                    'last_inspection' => $lastInspection ? $lastInspection->inspected_at->format('d/m/Y') : 'N/A',
                    'observations' => $lastInspection ? $lastInspection->observations : 'Ninguna',
                    'qr_code_url' => $qrCodeUrl
                ]
            ]);
        }
        return response()->json(['success' => false]);
    }
    // Show edit form
    public function edit($id)
    {
        $user_session = Session::has('LoggedIn') ? \App\Models\User::find(Session::get('LoggedIn')) : null;
        $equipment = Equipment::findOrFail($id);
        $clients = Client::all();

        return view('admin.equipments.edit', compact('equipment', 'clients', 'user_session'));
    }

    // Update equipment and regenerate QR code if missing
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|string',
            'location' => 'required|string|max:255',
            'agent_type' => 'nullable|string|max:255',
            'installation_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $equipment->update($request->all());

        // Regenerate QR code if missing or file deleted
        if (!$equipment->qr_code || !file_exists(public_path($equipment->qr_code))) {
            $qrFileName = $equipment->code . '.png';
            $qrFolder = $this->ensureQrCodeDirectory();
            $qrPath = $qrFolder . '/' . $qrFileName;

            try {
                // Log the QR code regeneration attempt
                Log::info('Regenerating QR code for equipment ID: ' . $equipment->id . ', code: ' . $equipment->code . ', path: ' . $qrPath);
                // Generate QR code using Endroid\QrCode v5
                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($equipment->code)
                    ->size(300)
                    ->margin(10)
                    ->build();
                $result->saveToFile($qrPath);
                // Verify the QR code file was created
                if (!file_exists($qrPath)) {
                    throw new \Exception('QR code file was not created at ' . $qrPath);
                }
                Log::info('QR code regenerated successfully for equipment ID: ' . $equipment->id);
                $equipment->update(['qr_code' => 'uploads/qr_codes/' . $qrFileName]);
            } catch (\Exception $e) {
                Log::error('Failed to regenerate QR code for equipment ID ' . $equipment->id . ': ' . $e->getMessage());
                return redirect()->route('equipments.index')
                    ->with('error', 'Equipment updated, but QR code generation failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('equipments.index')
            ->with('success', 'Equipment updated successfully.');
    }

    // Approve equipment
    public function approve(Request $request)
    {
        $eq = Equipment::findOrFail($request->id);
        $eq->status = 'approved';
        $eq->save();

        return response()->json(['message' => 'Equipment approved']);
    }

    // Reject equipment
    public function reject(Request $request)
    {
        $eq = Equipment::findOrFail($request->id);
        $eq->status = 'rejected';
        $eq->save();

        return response()->json(['message' => 'Equipment rejected']);
    }

    // Delete equipment and QR code
    public function destroy(Equipment $equipment)
    {
        if ($equipment->qr_code && file_exists(public_path($equipment->qr_code))) {
            Log::info('Deleting QR code file: ' . public_path($equipment->qr_code));
            unlink(public_path($equipment->qr_code));
        }

        $equipment->delete();
        return response()->json(['success' => true]);
    }
}
