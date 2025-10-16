<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ServiceRequest;
use App\Models\Proposal;
use App\Models\User;
use App\Helpers\FirebaseHelper;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Notifications\ContractCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ChatController;

class ContractController extends Controller
{
    public function contractSummary()
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->uid) {
                Log::warning('Unauthorized access to ContractController::contractSummary');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            $contracts = Contract::where('worker_id', $user->id)->get();

            $totalBalance = $contracts
                ->where('status', 'completed')
                ->sum('agreed_budget');

            $ongoingServices = $contracts
                ->whereIn('status', ['active', 'accepted'])
                ->count();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_balance' => $totalBalance,
                    'ongoing_services' => $ongoingServices,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ContractController::contractSummary', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch contract summary',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function checkContractStatus(Request $request, $serviceRequestId)
    {
        try {
            // Validate service_request_id
            if (!is_numeric($serviceRequestId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid service request ID'
                ], 400);
            }

            // Find contract with service_request_id and status 'in progress'
            $contract = Contract::where('service_request_id', $serviceRequestId)
                ->where('status', 'in progress')
                ->first();

            // Return response
            if ($contract) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'has_contract_in_progress' => true,
                        'contract' => $contract
                    ],
                    'message' => 'Contract found with status in progress'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'has_contract_in_progress' => false
                ],
                'message' => 'No contract found with status in progress'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking contract status: ' . $e->getMessage()
            ], 500);
        }
    }
    public function hire(Request $request, $serviceRequestId)
    {
        Log::info('Starting hire process', ['serviceRequestId' => $serviceRequestId, 'input' => $request->all()]);

        if (!$request->input('proposal_id') && !$request->input('worker_id')) {
            Log::warning('Missing proposal_id and worker_id', ['input' => $request->all()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Se requiere proposal_id o worker_id.',
            ], 422);
        }

        try {
            $client = Auth::user();
            if (!$client || !$client->uid) {
                Log::error('Unauthorized or missing Firebase UID', ['client' => $client]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);
            Log::info('Service request found', ['serviceRequestId' => $serviceRequestId, 'status' => $serviceRequest->status]);
            if ($serviceRequest->created_by !== $client->id) {
                Log::error('Unauthorized to hire this service', ['client_id' => $client->id, 'created_by' => $serviceRequest->created_by]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado para contratar este servicio.',
                ], 403);
            }

            $workerFirebaseUid = $request->input('worker_id');
            $numericWorkerId = null;

            if ($request->input('proposal_id')) {
                $proposal = Proposal::findOrFail($request->input('proposal_id'));
                Log::info('Proposal found', ['proposal_id' => $request->input('proposal_id'), 'proposal' => $proposal->toArray()]);
                if ($proposal->service_request_id !== $serviceRequest->id) {
                    Log::error('Proposal does not belong to this service request', ['proposal_id' => $proposal->id, 'service_request_id' => $serviceRequest->id]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'La propuesta no pertenece a esta solicitud',
                    ], 400);
                }
                $numericWorkerId = $proposal->worker_id;
                $worker = User::where('id', $numericWorkerId)->firstOrFail();
                $workerFirebaseUid = $worker->uid;
            } else {
                $worker = User::where('uid', $workerFirebaseUid)->firstOrFail();
                $numericWorkerId = $worker->id;
            }
            Log::info('Worker found', ['worker_id' => $numericWorkerId, 'worker_uid' => $workerFirebaseUid]);

            // Check for existing contract
            $existingContract = Contract::where('service_request_id', $serviceRequestId)
                ->where('worker_id', $numericWorkerId)
                ->first();
            if ($existingContract) {
                Log::warning('Contract already exists for this service request and worker', [
                    'service_request_id' => $serviceRequestId,
                    'worker_id' => $numericWorkerId,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ya has enviado una oferta o contratado a este trabajador para esta solicitud de servicio.',
                ], 400);
            }

            $agreedBudget = $request->input('agreed_budget', 0);
            Log::info('Creating contract', [
                'service_request_id' => $serviceRequest->id,
                'proposal_id' => $request->input('proposal_id'),
                'client_id' => $client->id,
                'worker_id' => $numericWorkerId,
                'agreed_budget' => $agreedBudget,
                'status' => 'accepted',
            ]);

            $contract = DB::transaction(function () use ($serviceRequest, $request, $client, $worker, $numericWorkerId, $agreedBudget) {
                $contract = Contract::create([
                    'service_request_id' => $serviceRequest->id,
                    'proposal_id' => $request->input('proposal_id'),
                    'client_id' => $client->id,
                    'worker_id' => $numericWorkerId,
                    'agreed_budget' => $agreedBudget,
                    'status' => 'accepted',
                ]);

                $serviceRequest->update([
                    'worker_id' => $numericWorkerId,
                    'budget' => $agreedBudget,
                    'status' => 'En curso'
                ]);

                if ($request->input('proposal_id')) {
                    Log::info('Updating proposal', ['proposal_id' => $request->input('proposal_id')]);
                    Proposal::where('id', $request->input('proposal_id'))
                        ->update(['status' => 'accepted']);
                }

                return $contract;
            });
            Log::info('Contract created', ['contract_id' => $contract->id]);

            // Initialize chat only for this hired worker
            $chatController = new ChatController();
            $chatRequest = new Request([
                'request_id' => $serviceRequest->id,
                'worker_id' => $worker->uid,
                'account_type' => 'Client',
                'client_id' => $client->uid,
            ]);
            Log::info('Initializing chat for hired worker', ['chat_request' => $chatRequest->all()]);
            $chatResponse = $chatController->initializeChat($chatRequest);
            if ($chatResponse->getStatusCode() !== 200) {
                Log::warning('Failed to initialize chat for hired worker', [
                    'contract_id' => $contract->id,
                    'status_code' => $chatResponse->getStatusCode(),
                    'response' => $chatResponse->getData(true)
                ]);
            }

            // Send FCM notification only to hired worker
            try {
                if ($worker->fcm_token) {
                    Log::info('Sending FCM notification to hired worker', ['worker_id' => $worker->id, 'fcm_token' => $worker->fcm_token]);
                    $this->sendFcmNotification(
                        $worker->fcm_token,
                        'Nuevo Contrato',
                        'Has sido contratado para un nuevo servicio.',
                        [
                            'service_request_id' => (string) $serviceRequest->id,
                            'type' => 'contract_created',
                            'client_id' => $client->uid,
                        ]
                    );
                }

                // Only notify this worker, not all
                Notification::send($worker, new ContractCreated($serviceRequest, $contract));
            } catch (\Exception $e) {
                Log::warning('FCM notification failed for hired worker', [
                    'worker_id' => $worker->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Contrato creado exitosamente.',
                'contract' => $contract,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Hire API error: ' . $e->getMessage(), ['input' => $request->all(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el contrato: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request, $serviceRequestId)
    {
        Log::info('Starting contract cancellation', ['serviceRequestId' => $serviceRequestId]);

        try {
            $client = Auth::user();
            if (!$client || !$client->uid) {
                Log::warning('Unauthorized access to ContractController::cancel', ['client' => $client]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            $serviceRequest = ServiceRequest::findOrFail($serviceRequestId);
            if ($serviceRequest->created_by !== $client->id) {
                Log::error('Unauthorized to cancel this service contract', ['client_id' => $client->id, 'created_by' => $serviceRequest->created_by]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado para cancelar este contrato.',
                ], 403);
            }

            $contract = Contract::where('service_request_id', $serviceRequestId)->first();

            if (!$contract) {
                Log::warning('No contract found for cancellation', ['service_request_id' => $serviceRequestId]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontró un contrato para cancelar.',
                ], 400);
            }

            if ($contract->status === 'in progress') {
                Log::warning('Cannot cancel contract as worker has started the service', ['service_request_id' => $serviceRequestId, 'contract_id' => $contract->id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'El trabajador ha iniciado el servicio, ahora no puedes cancelar el contrato.',
                ], 400);
            }

            if ($contract->status !== 'accepted') {
                Log::warning('No accepted contract found for cancellation', ['service_request_id' => $serviceRequestId, 'contract_status' => $contract->status]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontró un contrato aceptado para cancelar o el servicio ya está en curso.',
                ], 400);
            }

            $contract = DB::transaction(function () use ($serviceRequest, $contract) {
                $contract->update(['status' => 'cancelled']);
                $serviceRequest->update([
                    'worker_id' => null,
                    'budget' => null,
                    'status' => 'Pendiente'
                ]);

                if ($contract->proposal_id) {
                    Log::info('Updating proposal to cancelled', ['proposal_id' => $contract->proposal_id]);
                    Proposal::where('id', $contract->proposal_id)
                        ->update(['status' => 'cancelled']);
                }

                return $contract;
            });

            $worker = User::find($contract->worker_id);
            if ($worker && $worker->fcm_token) {
                Log::info('Sending FCM notification to worker about cancellation', ['worker_id' => $worker->id, 'fcm_token' => $worker->fcm_token]);
                $this->sendFcmNotification(
                    $worker->fcm_token,
                    'Contrato Cancelado',
                    'El cliente ha cancelado el contrato para la solicitud de servicio.',
                    [
                        'service_request_id' => (string) $serviceRequest->id,
                        'type' => 'contract_cancelled',
                        'client_id' => $client->uid,
                    ]
                );
            }

            Log::info('Contract cancelled successfully', ['contract_id' => $contract->id]);
            return response()->json([
                'status' => 'success',
                'message' => 'Contrato cancelado exitosamente.',
                'contract' => $contract,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Cancel API error: ' . $e->getMessage(), [
                'serviceRequestId' => $serviceRequestId,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cancelar el contrato: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getContractOffer(Request $request, $serviceRequestId = null)
    {
        try {
            // Authenticate the user
            $user = Auth::user();
            if (!$user || !$user->uid) {
                Log::warning('Unauthorized access to ContractController::getContractOffer', [
                    'user_id' => Auth::id(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            // Ensure the user is a worker (Chambeador)
            // if ($user->account_type !== 'Chambeador') {
            //     Log::warning('User is not a Chambeador', [
            //         'user_id' => $user->id,
            //         'account_type' => $user->account_type,
            //     ]);
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Only workers can check contract offers',
            //     ], 403);
            // }

            // Build the query for contracts
            $query = Contract::where('worker_id', $user->id)
                ->whereIn('status', ['accepted', 'pending']); // Only fetch active offers

            // Filter by serviceRequestId if provided
            if ($serviceRequestId) {
                $serviceRequest = ServiceRequest::find($serviceRequestId);
                if (!$serviceRequest) {
                    Log::warning('Service request not found', [
                        'serviceRequestId' => $serviceRequestId,
                        'worker_id' => $user->id,
                    ]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Service request not found',
                    ], 404);
                }
                $query->where('service_request_id', $serviceRequestId);
            }

            // Fetch contracts with related data
            $contracts = $query->with(['serviceRequest', 'client'])->get();

            if ($contracts->isEmpty()) {
                Log::info('No contract offers found for worker', [
                    'worker_id' => $user->id,
                    'serviceRequestId' => $serviceRequestId ?? 'none',
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'No contract offers found',
                    'data' => [],
                ], 200);
            }

            // Format the response
            $contractData = $contracts->map(function ($contract) {
                return [
                    'contract_id' => $contract->id,
                    'service_request_id' => $contract->service_request_id,
                    'proposal_id' => $contract->proposal_id,
                    'client_id' => $contract->client_id,
                    'client_name' => $contract->client->name ?? 'Unknown',
                    'agreed_budget' => $contract->agreed_budget,
                    'status' => $contract->status,
                    'created_at' => $contract->created_at->toDateTimeString(),
                    'updated_at' => $contract->updated_at->toDateTimeString(),
                    'service_request' => [
                        'title' => $contract->serviceRequest->title ?? 'N/A',
                        'description' => $contract->serviceRequest->description ?? 'N/A',
                        'location' => $contract->serviceRequest->location ?? 'N/A',
                    ],
                ];
            });

            Log::info('Contract offers retrieved successfully', [
                'worker_id' => $user->id,
                'serviceRequestId' => $serviceRequestId ?? 'none',
                'count' => $contracts->count(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Contract offers retrieved successfully',
                'data' => $contractData,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ContractController::getContractOffer', [
                'worker_id' => Auth::id(),
                'serviceRequestId' => $serviceRequestId ?? 'none',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch contract offers: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function accept(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->uid) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            $serviceRequest = ServiceRequest::findOrFail($id);
            $contract = Contract::where('service_request_id', $id)
                ->where('worker_id', User::where('uid', $user->uid)->first()->id)
                ->first();

            if (!$contract) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found or unauthorized',
                ], 403);
            }

            $contract->update(['status' => 'accepted']);
            $serviceRequest->update(['status' => 'En curso']);

            if ($contract->proposal_id) {
                Proposal::where('id', $contract->proposal_id)
                    ->update(['status' => 'accepted']);
            }

            $client = User::find($serviceRequest->created_by);
            $clientFcmToken = $client->fcm_token;

            if ($clientFcmToken) {
                $this->sendFcmNotification(
                    $clientFcmToken,
                    'Contrato Aceptado',
                    'El trabajador ha aceptado tu solicitud de servicio.',
                    [
                        'service_request_id' => (string) $id,
                        'type' => 'contract_accepted',
                        'worker_id' => $user->uid,
                    ]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Contrato aceptado exitosamente',
                'data' => $contract,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ContractController::accept: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to accept contract: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function acceptProposal(Request $request, $proposalId)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->uid) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            $proposal = Proposal::findOrFail($proposalId);
            $serviceRequest = ServiceRequest::findOrFail($proposal->service_request_id);

            if ($serviceRequest->created_by !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado para aceptar esta propuesta',
                ], 403);
            }

            $proposal->update(['status' => 'accepted']);

            return response()->json([
                'status' => 'success',
                'message' => 'Propuesta aceptada exitosamente',
                'data' => $proposal,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ContractController::acceptProposal: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to accept proposal: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function rehire(Request $request, $serviceRequestId)
    {
        Log::info('Rehire request received', ['input' => $request->all(), 'serviceRequestId' => $serviceRequestId]);

        try {
            $client = Auth::user();
            if (!$client || !$client->uid) {
                Log::warning('Unauthorized access to ContractController::rehire', ['input' => $request->all()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized or missing Firebase UID',
                ], 401);
            }

            Log::info('Fetching service request', ['serviceRequestId' => $serviceRequestId]);
            $originalRequest = ServiceRequest::findOrFail($serviceRequestId);
            if ($originalRequest->created_by !== $client->id) {
                Log::warning('Unauthorized service request access', [
                    'serviceRequestId' => $serviceRequestId,
                    'client_id' => $client->id,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado para recontratar este servicio.',
                ], 403);
            }

            if ($originalRequest->status !== 'Completado') {
                Log::warning('Service request not completed', [
                    'serviceRequestId' => $serviceRequestId,
                    'status' => $originalRequest->status,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'La solicitud original no está completada.',
                ], 400);
            }

            $workerId = $request->input('worker_id');


            if (!$workerId) {
                Log::warning('Missing worker_id', ['input' => $request->all()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'worker_id is required.',
                ], 422);
            }

            Log::info('Fetching worker', ['worker_id' => $workerId]);
            $worker = User::find($workerId);
            if (!$worker) {
                Log::error('Worker not found', ['worker_id' => $workerId]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'El trabajador no existe.',
                ], 404);
            }

            // if ($worker->account_type !== 'Chambeador') {
            //     Log::warning('Worker is not a Chambeador', [
            //         'worker_id' => $worker->id,
            //         'account_type' => $worker->account_type,
            //     ]);
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'El trabajador no es un Chambeador',
            //     ], 400);
            // }

            $contract = DB::transaction(function () use ($originalRequest, $request, $client, $worker) {
                Log::info('Creating new service request', ['client_id' => $client->id]);
                $newServiceRequest = ServiceRequest::create([
                    'created_by' => $client->id,
                    'subcategory' => $request->input('subcategory'),
                    'location' => $originalRequest->location,
                    'location_details' => $originalRequest->location_details,
                    'description' => $originalRequest->description,
                    'budget' => $originalRequest->budget,
                    'status' => 'Pendiente',
                    'worker_id' => $worker->id,
                ]);

                Log::info('Creating new proposal', [
                    'service_request_id' => $newServiceRequest->id,
                    'worker_id' => $worker->id,
                ]);
                $proposal = Proposal::create([
                    'service_request_id' => $newServiceRequest->id,
                    'worker_id' => $worker->id,
                    'proposed_budget' => $originalRequest->budget ?? 0,
                    'status' => 'accepted',
                    'description' => $originalRequest->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Creating new contract', ['service_request_id' => $newServiceRequest->id]);
                $contract = Contract::create([
                    'service_request_id' => $newServiceRequest->id,
                    'client_id' => $client->id,
                    'worker_id' => $worker->id,
                    'proposal_id' => $proposal->id,
                    'agreed_budget' => $originalRequest->budget ?? 0,
                    'status' => 'accepted',
                ]);

                $newServiceRequest->update(['status' => 'En curso']);
                Log::info('Service request status updated to En curso', ['service_request_id' => $newServiceRequest->id]);

                return $contract;
            });

            try {
                $chatController = new ChatController();
                $chatRequest = new Request([
                    'request_id' => $contract->service_request_id,
                    'worker_id' => $worker->uid,
                    'account_type' => 'Client',
                ]);
                $chatResponse = $chatController->initializeChat($chatRequest);
                if ($chatResponse->getStatusCode() !== 200) {
                    Log::warning('Failed to initialize chat for rehire contract', [
                        'contract_id' => $contract->id,
                        'response' => $chatResponse->getContent(),
                    ]);
                } else {
                    Log::info('Chat initialized successfully for contract', ['contract_id' => $contract->id]);
                }
            } catch (\Exception $e) {
                Log::warning('Chat initialization failed in rehire', [
                    'contract_id' => $contract->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            try {
                Log::info('Sending ContractCreated to worker: ' . $worker->id . ', FCM token: ' . ($worker->fcm_token ?? 'none'));
                Notification::send($worker, new ContractCreated($contract->serviceRequest, $contract));
                if ($worker->fcm_token) {
                    $this->sendFcmNotification(
                        $worker->fcm_token,
                        'Nuevo Contrato',
                        'Has sido recontratado para un nuevo servicio.',
                        [
                            'service_request_id' => (string) $contract->service_request_id,
                            'type' => 'contract_created',
                            'client_id' => $client->uid,
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::warning('FCM notification failed in rehire', [
                    'worker_id' => $worker->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Recontratación realizada exitosamente.',
                'contract' => $contract,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Rehire API error: ' . $e->getMessage(), [
                'input' => $request->all(),
                'serviceRequestId' => $serviceRequestId,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al recontratar: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function sendFcmNotification($fcmToken, $title, $body, $data = [])
    {
        try {
            $messaging = FirebaseHelper::messaging();
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification([
                    'title' => $title,
                    'body' => $body,
                ])
                ->withData($data);

            $messaging->send($message);
            Log::info('FCM notification sent to token: ' . $fcmToken);
        } catch (\Exception $e) {
            Log::error('FCM notification failed: ' . $e->getMessage());
        }
    }
}
