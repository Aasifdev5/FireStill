<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
class ProposalController extends Controller
{
public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_request_id' => 'required|exists:service_requests,id',
            'proposed_budget' => 'required|numeric|min:0',
            'message' => 'nullable|string|max:255',
            'availability' => 'required|string|max:255',
            'time_to_complete' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Verify if the worker has already submitted a proposal
        $existingProposal = Proposal::where('service_request_id', $request->service_request_id)
            ->where('worker_id', auth()->id())
            ->first();

        if ($existingProposal) {
            return response()->json([
                'message' => 'Ya has enviado una propuesta para este trabajo.',
            ], 422);
        }

        $data = $request->only([
            'service_request_id',
            'proposed_budget',
            'message',
            'availability',
            'time_to_complete',
        ]);
        $data['worker_id'] = auth()->id();
        $data['status'] = 'pending';

        try {
            $proposal = Proposal::create($data);
            return response()->json([
                'message' => 'Propuesta enviada exitosamente',
                'data' => $proposal,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating proposal: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al crear la propuesta',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



  public function index($serviceRequestId)
{
    try {
        // Check for an existing contract with relevant statuses
        $contract = Contract::where('service_request_id', $serviceRequestId)
            ->whereIn('status', ['accepted', 'in progress', 'completed'])
            ->first();

        if ($contract) {
            // Single record: proposal linked to that contract
            $proposal = Proposal::where('id', $contract->proposal_id)
                ->where('service_request_id', $serviceRequestId)
                ->where('status', 'accepted')
                ->first();

            return response()->json([
                'data' => $proposal ? $proposal : null, // single object
                'contract_status' => $contract->status,
            ], 200);
        } else {
            // Otherwise return all proposals for the request, excluding cancelled and rejected
            $proposals = Proposal::where('service_request_id', $serviceRequestId)
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->get();

            return response()->json([
                'data' => $proposals,
            ], 200);
        }
    } catch (\Exception $e) {
        Log::error('Error fetching proposals', [
            'service_request_id' => $serviceRequestId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Error al obtener las propuestas.',
        ], 500);
    }
}


}
