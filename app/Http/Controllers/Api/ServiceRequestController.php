<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Models\ChambeadorProfile;
use App\Models\Contract;
use App\Models\Review;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\CloudMessage;

class ServiceRequestController extends Controller
{
 public function workerJobs($uid)
{
    try {
        $user = User::where('uid', $uid)->first();
        if (!$user) {
            Log::warning('User not found for workerJobs', ['uid' => $uid]);
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no autenticado',
            ], 401);
        }

        $jobs = ServiceRequest::whereIn('status', ['Pendiente', 'En curso', 'Completado'])
            ->where(function ($query) use ($user) {
                $query->whereHas('proposals', function ($subQuery) use ($user) {
                    $subQuery->where('worker_id', $user->id);
                })->orWhereHas('contract', function ($subQuery) use ($user) {
                    $subQuery->where('worker_id', $user->id);
                });
            })
            ->with([
                'proposals' => function ($query) use ($user) {
                    $query->where('worker_id', $user->id);
                },
                'contract' => function ($query) use ($user) {
                    $query->where('worker_id', $user->id);
                },
                'user'
            ])
            ->get();

        $formattedJobs = $jobs->map(function ($job) use ($user) {
            $client = User::find($job->created_by);
            $contract = $job->contract;
            $worker = $contract ? User::find($contract->worker_id) : null;

            // ✅ Determine worker-specific status
            if ($job->status === 'Completado') {
                $workerStatus = 'Completado';
            } elseif ($contract && $contract->worker_id === $user->id) {
                $workerStatus = 'En curso'; // Only contracted worker sees "En curso"
            } else {
                $workerStatus = 'Pendiente'; // Others with proposals but no contract
            }

            $latestUpdate = $job->updated_at;
            if ($job->proposals->isNotEmpty()) {
                $latestProposalUpdate = $job->proposals->max('updated_at');
                if ($latestProposalUpdate && $latestProposalUpdate->gt($latestUpdate)) {
                    $latestUpdate = $latestProposalUpdate;
                }
            }
            if ($contract && $contract->updated_at && $contract->updated_at->gt($latestUpdate)) {
                $latestUpdate = $contract->updated_at;
            }

            // Client reviews
            $client_id = $job->created_by;
            $reviews = Review::with(['client:id,name'])
                ->join('service_requests', 'reviews.service_request_id', '=', 'service_requests.id')
                ->where('reviews.client_id', $client_id)
                ->where('reviews.review_type', 'worker_to_client')
                ->get();

            $client_rating = $reviews->isNotEmpty() ? $reviews->avg('rating') : 0.0;
            $client_review_count = $reviews->count();

            return [
                'id' => $job->id,
                'created_by' => $job->created_by,
                'worker_id' => $contract ? $contract->worker_id : null,
                'worker_name' => $worker ? $worker->name : null,
                'worker_rating' => $worker ? ($worker->rating ?? 0.0) : null,
                'status' => $workerStatus, // ✅ worker-specific status
                'title' => $job->title ?? "{$job->category} - " . ($job->subcategory ?? 'Sin subcategoría'),
                'category' => $job->category,
                'subcategory' => $job->subcategory,
                'location' => $job->location ?? 'Sin ubicación',
                'location_details' => $job->location_details,
                'budget' => $job->budget,
                'start_time' => $job->start_time,
                'end_time' => $job->end_time,
                'is_time_undefined' => $job->is_time_undefined ?? false,
                'date' => !empty($job->date) ? Carbon::createFromFormat('d/m/Y', $job->date)->format('d/m/Y') : null,
                'payment_method' => $job->payment_method ?? 'No especificado',
                'description' => $job->description,
                'image' => $job->image ? asset('storage/' . $job->image) : null,
                'client_name' => $client ? $client->name : 'Usuario Desconocido',
                'client_rating' => round($client_rating, 1),
                'client_review_count' => $client_review_count,
                'client_id' => $job->created_by,
                'proposals' => $job->proposals->map(function ($proposal) use ($user) {
                    $worker = User::find($proposal->worker_id);
                    return [
                        'id' => $proposal->id,
                        'service_request_id' => $proposal->service_request_id,
                        'worker_id' => $proposal->worker_id,
                        'worker_firebase_uid' => $worker ? $worker->uid : null,
                        'proposed_budget' => $proposal->proposed_budget,
                        'availability' => $proposal->availability,
                        'time_to_complete' => $proposal->time_to_complete,
                        'message' => $proposal->message,
                        'status' => $proposal->status ?? 'pending',
                        'created_at' => $proposal->created_at->toIso8601String(),
                        'updated_at' => $proposal->updated_at->toIso8601String(),
                        'worker_name' => $worker ? $worker->name : 'Usuario ' . $proposal->worker_id,
                        'worker_rating' => $worker ? ($worker->rating ?? 0.0) : 0.0,
                        'worker_role' => $worker ? ($worker->account_type ?? 'Trabajador') : 'Trabajador',
                    ];
                })->sortByDesc('updated_at')->values()->toArray(),
                'price_range' => $job->budget ? 'BOB ' . $job->budget : 'No especificado',
                'time_ago' => $job->created_at ? $this->timeAgo($job->created_at) : 'Hace desconocido',
                'categories' => array_filter([$job->category, $job->subcategory]),
                'updated_at' => $latestUpdate,
            ];
        });

        // Sort jobs by latest update
        $formattedJobs = $formattedJobs->sortByDesc(function ($job) {
            return Carbon::parse($job['updated_at'] ?? now());
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => $formattedJobs,
        ], 200);
    } catch (\Exception $e) {
        Log::error('Error in ServiceRequestController::workerJobs', [
            'uid' => $uid,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch worker jobs',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning('Unauthorized access to ServiceRequestController::index');
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
            }

            $serviceRequests = ServiceRequest::where('created_by', $user->id)
                ->with(['proposals', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $serviceRequests->map(function ($serviceRequest) use ($user) {
                    return $this->formatServiceRequest($serviceRequest, $user);
                }),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch service requests',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $serviceRequest = ServiceRequest::with(['proposals', 'user'])->find($id);
            if (!$serviceRequest) {
                Log::warning('Service request not found', ['id' => $id]);
                return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
            }

            $authUser = Auth::user();
            $isAuthorized = $serviceRequest->created_by === $authUser->id ||
                $serviceRequest->proposals->contains(function ($proposal) use ($authUser) {
                    return $proposal->worker_id === $authUser->id;
                });

            if (!$isAuthorized) {
                Log::warning('Unauthorized access to service request', [
                    'id' => $id,
                    'user_id' => $authUser->id,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }

            return response()->json([
                'status' => 'success',
                'data' => $this->formatServiceRequest($serviceRequest, $authUser),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::show', [
                'id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch service request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function jobs()
    {
        try {
            $query = ServiceRequest::with('user')
                ->where('status', 'Pendiente')
                ->latest();


            // Exclude jobs created by the logged-in user
            if (auth()->check()) {
                $query->where('created_by', '!=', auth()->id());
            }

            $serviceRequests = $query->get();

            return response()->json([
                'success' => true,
                'data' => $serviceRequests->map(function ($serviceRequest) {
                    return $this->formatServiceRequest($serviceRequest);
                }),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::jobs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch jobs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function jobDetail($id)
    {
        try {
            $serviceRequest = ServiceRequest::find($id);
            if (!$serviceRequest) {
                Log::warning('Service request not found in jobDetail', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Service request not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatServiceRequest($serviceRequest),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::jobDetail', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch job detail',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


public function start(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $serviceRequest = ServiceRequest::findOrFail($id);
            $contract = Contract::where('service_request_id', $id)
                ->where('worker_id', $user->id)
                ->where('status', 'accepted')
                ->first();

            if (!$contract) {
                Log::warning('Contract not found or not accepted', [
                    'service_request_id' => $id,
                    'user_id' => $user->id,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Contract not found or not accepted',
                ], 403);
            }

            // Find worker's profile for commission deduction
            $worker = User::find($contract->worker_id);
            if (!$worker) {
                DB::rollBack();
                Log::error('Worker user not found', [
                    'service_request_id' => $id,
                    'worker_id' => $contract->worker_id,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Worker user not found',
                ], 404);
            }

            $profile = ChambeadorProfile::where('uid', $worker->uid)->first();
            if (!$profile) {
                DB::rollBack();
                Log::error('Worker profile not found', [
                    'service_request_id' => $id,
                    'worker_id' => $contract->worker_id,
                    'uid' => $worker->uid,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Worker profile not found',
                ], 404);
            }

            // Deduct 20% commission from worker's wallet balance
            $jobAmount = floatval($contract->agreed_budget); // Job value
            $commissionRate = 0.20; // 20% commission
            $commissionAmount = $jobAmount * $commissionRate;

            // Check for sufficient balance
            if ($profile->balance < $commissionAmount) {
                DB::rollBack();
                Log::error('Insufficient balance for commission deduction', [
                    'service_request_id' => $id,
                    'worker_id' => $contract->worker_id,
                    'uid' => $worker->uid,
                    'balance' => $profile->balance,
                    'commission' => $commissionAmount,
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance to deduct commission',
                ], 400);
            }

            // Deduct commission from worker's balance
            $profile->balance -= $commissionAmount;
            $profile->save();

            // Log the commission deduction
            Log::info('Commission deducted after service start', [
                'uid' => $worker->uid,
                'service_request_id' => $id,
                'job_amount' => $jobAmount,
                'commission' => $commissionAmount,
                'new_balance' => $profile->balance,
            ]);

            // Update service and contract status
            $serviceRequest->update(['status' => 'En curso']);
            $contract->update(['status' => 'in progress']);

            // Notify client
            $client = User::find($serviceRequest->created_by);
            $clientFcmToken = $client->fcm_token;

            if ($clientFcmToken) {
                $this->sendFcmNotification(
                    $clientFcmToken,
                    'Servicio Iniciado',
                    'El trabajador ha comenzado a trabajar en tu solicitud.',
                    [
                        'service_request_id' => (string) $id,
                        'type' => 'service_started',
                        'worker_id' => (string) $user->id,
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Servicio iniciado exitosamente',
                'data' => $this->formatServiceRequest($serviceRequest),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in ServiceRequestController::start', [
                'id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to start service',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function complete(Request $request, $id)
{
    try {
        DB::beginTransaction();
        $user = Auth::user();
        $serviceRequest = ServiceRequest::findOrFail($id);
        $contract = Contract::where('service_request_id', $id)
            ->whereIn('status', ['in progress'])
            ->first();

        if (!$contract) {
            Log::warning('Contract not found or not accepted/active', [
                'service_request_id' => $id,
                'user_id' => $user->id,
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Contract not found or not in accepted/active state',
            ], 403);
        }

        // Allow either client or worker to complete the service
        // if ($serviceRequest->created_by !== $user->id && $contract->worker_id !== $user->id) {
        //     Log::warning('Unauthorized access to complete service', [
        //         'service_request_id' => $id,
        //         'user_id' => $user->id,
        //     ]);
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized to complete this service',
        //     ], 403);
        // }

        // Update service and contract status
        $serviceRequest->update(['status' => 'Completado']);
        $contract->update(['status' => 'completed']);

        // Ensure the service request is refreshed to reflect updated data
        $serviceRequest->refresh();

        // Notify the other party
        $recipient = ($user->id === $serviceRequest->created_by)
            ? User::find($contract->worker_id)
            : User::find($serviceRequest->created_by);
        $recipientFcmToken = $recipient ? $recipient->fcm_token : null;

        if ($recipientFcmToken) {
            $this->sendFcmNotification(
                $recipientFcmToken,
                'Servicio Completado',
                'La solicitud de servicio ha sido completada.',
                [
                    'service_request_id' => (string) $id,
                    'type' => 'service_completed',
                    'worker_id' => (string) $contract->worker_id,
                ]
            );
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Servicio completado exitosamente',
            'data' => $this->formatServiceRequest($serviceRequest),
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in ServiceRequestController::complete', [
            'id' => $id,
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to complete service',
            'error' => $e->getMessage(),
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
            Log::error('FCM notification failed', [
                'token' => $fcmToken,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|string|max:255',
                'subcategory' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'location_details' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'budget' => 'nullable|numeric',
                'payment_method' => 'nullable|string',
                'description' => 'nullable|string',
                'date' => 'nullable|date_format:d/m/Y',
                'start_time' => 'nullable|string',
                'end_time' => 'nullable|string',
                // 'is_time_undefined' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Added image validation
            ]);

            $user = Auth::user();
            $data = array_merge($validated, [
                'created_by' => $user->id,
                'status' => 'Pendiente',
            ]);

            // Handle image upload

            if ($request->hasFile('image')) {
                // Get uploaded file
                $attribute = $request->file('image');
                $destination = 'service_requests';

                // Generate a unique filename
                $file_name = time() . '-' . Str::random(10) . '.' . $attribute->getClientOriginalExtension();

                // Move the file to public/uploads/blog/
                $attribute->move(public_path('uploads/' . $destination), $file_name);

                // Save the relative path
                $image = 'uploads/' . $destination . '/' . $file_name;

                // Update your data array or model
                $data['image'] = $image;
            }

            $serviceRequest = ServiceRequest::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Service request created successfully',
                'data' => $this->formatServiceRequest($serviceRequest),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create service request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function formatServiceRequest($serviceRequest, $authUser = null)
    {
        try {
            $client = User::find($serviceRequest->created_by);
            $contract = $serviceRequest->contract()->first();
            $worker = $contract ? User::find($contract->worker_id) : null;

            return [
                'id' => $serviceRequest->id,
                'created_by' => $serviceRequest->created_by,
                'worker_id' => $contract ? $contract->worker_id : null,
                'worker_firebase_uid' => $worker ? $worker->uid : null,
                'date' => !empty($serviceRequest->date)
                    ? Carbon::createFromFormat('d/m/Y', $serviceRequest->date)->format('d/m/Y')
                    : null,
                'start_time' => $serviceRequest->start_time,
                'end_time' => $serviceRequest->end_time,
                'is_time_undefined' => $serviceRequest->is_time_undefined ?? false,
                'location' => $serviceRequest->location,
                'location_details' => $serviceRequest->location_details,
                'latitude' => $serviceRequest->latitude,
                'longitude' => $serviceRequest->longitude,
                'category' => $serviceRequest->category,
                'subcategory' => $serviceRequest->subcategory,
                'description' => $serviceRequest->description,
                'budget' => $serviceRequest->budget,
                'payment_method' => $serviceRequest->payment_method,
                'image' => $serviceRequest->image ? asset('storage/' . $serviceRequest->image) : null,
                'client_profile_photo' => $serviceRequest->user && $serviceRequest->user->profile_photo
                    ? 'Uploads/profile_photos/' . basename($serviceRequest->user->profile_photo)
                    : null,
                'created_at' => $serviceRequest->created_at->toIso8601String(),
                'updated_at' => $serviceRequest->updated_at->toIso8601String(),
                'client_name' => $client ? $client->name : 'Usuario Desconocido',
                'client_rating' => $client ? ($client->rating ?? 0.0) : 0.0,
                'status' => $serviceRequest->status ?? 'Pendiente',
                'title' => "{$serviceRequest->category} - {$serviceRequest->subcategory}",
                'categories' => [$serviceRequest->category, $serviceRequest->subcategory],
                'price_range' => $serviceRequest->budget ? "BOB {$serviceRequest->budget}" : 'No especificado',
                'time_ago' => $serviceRequest->created_at ? $this->timeAgo($serviceRequest->created_at) : 'Hace desconocido',
                'proposals' => $serviceRequest->proposals->map(function ($proposal) use ($authUser) {
                    $worker = User::find($proposal->worker_id);
                    return [
                        'id' => $proposal->id,
                        'service_request_id' => $proposal->service_request_id,
                        'worker_id' => $proposal->worker_id,
                        'worker_firebase_uid' => $worker ? $worker->uid : null,
                        'proposed_budget' => $proposal->proposed_budget,
                        'availability' => $proposal->availability,
                        'time_to_complete' => $proposal->time_to_complete,
                        'message' => $proposal->message,
                        'status' => $proposal->status ?? 'pending',
                        'created_at' => $proposal->created_at->toIso8601String(),
                        'updated_at' => $proposal->updated_at->toIso8601String(),
                        'worker_name' => $worker ? $worker->name : 'Usuario ' . $proposal->worker_id,
                        'worker_rating' => $worker ? ($worker->rating ?? 0.0) : 0.0,
                        'worker_role' => $worker ? ($worker->account_type ?? 'Trabajador') : 'Trabajador',
                    ];
                })->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::formatServiceRequest', [
                'service_request_id' => $serviceRequest->id,
                'user_id' => $authUser ? $authUser->id : null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function timeAgo($date)
    {
        try {
            $now = now();
            $diff = $now->diff($date);
            if ($diff->d > 0) return "Hace {$diff->d} días";
            if ($diff->h > 0) return "Hace {$diff->h} horas";
            return "Hace {$diff->i} minutos";
        } catch (\Exception $e) {
            Log::error('Error in ServiceRequestController::timeAgo', [
                'date' => $date,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 'Hace desconocido';
        }
    }
}
