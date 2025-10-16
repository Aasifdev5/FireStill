<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\FirebaseHelper;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\ChambeadorProfile;
use App\Models\BackgroundCertificate;
use App\Models\IdentityCard;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChambeadorProfileApi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.firebase');
    }
   public function checkBalance(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'uid' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::error('Validation error in checkBalance', [
                    'uid' => $request->uid,
                    'errors' => $validator->errors()->toArray(),
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'El UID es requerido.',
                    'data' => null,
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }

            // Verify authenticated user
            $uid = Auth::user()->uid ?? null;
            if (!$uid) {
                Log::error('No authenticated user found in checkBalance', [
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No estás autenticado. Por favor, inicia sesión.',
                    'data' => null,
                ], 401);
            }

            // Ensure the provided UID matches the authenticated user's UID
            if ($uid !== $request->uid) {
                Log::warning('UID mismatch in checkBalance', [
                    'provided_uid' => $request->uid,
                    'user_uid' => $uid,
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'No tienes permiso para verificar este saldo.',
                    'data' => null,
                ], 403);
            }

            // Fetch ChambeadorProfile using UID
            $profile = ChambeadorProfile::where('uid', $uid)->first();
            if (!$profile) {
                Log::error('Worker profile not found in checkBalance', [
                    'uid' => $uid,
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Perfil de trabajador no encontrado.',
                    'data' => null,
                ], 404);
            }

            // Handle NULL balance and status
            $balance = $profile->balance !== null ? number_format($profile->balance, 2, '.', '') : '0.00';
            $status = $profile->status ?? 'pending'; // Default to 'pending' if status is NULL

            // Prepare response
            $response = [
                'status' => 'success',
                'data' => [
                    'balance' => $balance,
                    'worker_status' => $status,
                ],
                'message' => 'Saldo verificado exitosamente.',
            ];

            // Check worker status
            if ($status !== 'approved') {
                Log::info('Worker status not approved in checkBalance', [
                    'uid' => $uid,
                    'status' => $status,
                    'ip' => $request->ip(),
                ]);
                $response['status'] = 'error';
                $response['message'] = $status === 'rejected'
                    ? 'Tus documentos han sido rechazados. Por favor, revisa tu perfil.'
                    : 'Tus documentos están en revisión. Una vez aprobados podrás acceder a tu cuenta de trabajador.';
                return response()->json($response, 403);
            }

            // Log successful balance check
            Log::info('Balance check successful', [
                'uid' => $uid,
                'balance' => $balance,
                'worker_status' => $status,
                'ip' => $request->ip(),
            ]);

            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Error in ChambeadorProfileApi::checkBalance', [
                'uid' => $request->uid ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al verificar el saldo. Por favor, intenta de nuevo.',
                'data' => null,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

public function verifyProfileCompletion(Request $request)
    {
        try {
            $uid = Auth::user()->uid;

            // Check if the user has a profile in chambeador_profiles
            $hasProfile = ChambeadorProfile::where('uid', $uid)->exists();

            // Check if the user has a background certificate
            $hasCertificate = BackgroundCertificate::where('uid', $uid)->exists();

            // Check if the user has an identity card
            $hasIdentityCard = IdentityCard::where('uid', $uid)->exists();

            // If all three exist, allow navigation to HomeScreen
            if ($hasProfile && $hasCertificate && $hasIdentityCard) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Perfil completo. Puede acceder a la pantalla de inicio.',
                    'can_access_homescreen' => true,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'incomplete',
                    'message' => 'El perfil está incompleto. Complete las secciones requeridas.',
                    'can_access_homescreen' => false,
                    'details' => [
                        'has_profile' => $hasProfile,
                        'has_certificate' => $hasCertificate,
                        'has_identity_card' => $hasIdentityCard,
                    ],
                ], 403);
            }
        } catch (\Exception $e) {
            Log::error('Failed to verify profile completion', [
                'uid' => Auth::user()->uid ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error al verificar el perfil: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getProfile(Request $request)
    {
        try {
            $uid = Auth::user()->uid;
            $profile = ChambeadorProfile::where('uid', $uid)->first();

            if (!$profile) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'name' => null,
                        'last_name' => null,
                        'profession' => null,
                        'birth_date' => null,
                        'phone' => null,
                        'email' => null,
                        'gender' => null,
                        'address' => null,
                        'profile_image' => null,
                        'about_me' => null,
                        'skills' => [],
                        'category' => null,
                        'subcategories' => [],
                    ],
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'name' => $profile->name,
                    'last_name' => $profile->last_name,
                    'profession' => $profile->profession,
                    'birth_date' => $profile->birth_date ? Carbon::parse($profile->birth_date)->format('d/m/Y') : null,
                    'phone' => $profile->phone,
                    'email' => $profile->email,
                    'gender' => $profile->gender,
                    'address' => $profile->address,
                    'profile_image' => $profile->profile_image ? asset($profile->profile_image) : null,
                    'about_me' => $profile->about_me,
                    'skills' => $profile->skills ?? [],
                    'category' => $profile->category,
                    'subcategories' => $profile->subcategories ?? [],
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch profile: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'profession' => 'nullable|string|max:100',
            'birth_date' => 'required|date_format:d/m/Y',

            'email' => 'nullable|email|max:255',
            'gender' => 'nullable|string|in:Masculino,Femenino,Otro',
            'address' => 'nullable|string|max:255',
            'about_me' => 'nullable|string|max:500',
            'skills' => 'nullable|array',
            'category' => 'nullable|string|max:100',
            'subcategories' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $uid = Auth::user()->uid;
            $firebaseUser = $request->attributes->get('firebaseUser');

            // Update Firebase User
            FirebaseHelper::auth()->updateUser($uid, [
                'displayName' => $request->name . ' ' . $request->last_name,
                'phoneNumber' => $request->phone,
            ]);

            // Parse birth date
            $birthDate = Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d');

            // Update or create profile
            $profile = ChambeadorProfile::updateOrCreate(
                ['uid' => $uid],
                [
                    'name' => $request->name,
                    'last_name' => $request->last_name,
                    'profession' => $request->profession,
                    'birth_date' => $birthDate,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'about_me' => $request->about_me,
                    'skills' => $request->skills ?? [],
                    'category' => $request->category,
                    'subcategories' => $request->subcategories ?? [],
                ]
            );

            // Fetch existing user or null
            $existingUser = User::where('uid', $uid)->first();

            // Update or create User
            User::updateOrCreate(
                ['uid' => $uid],
                [
                    'email' => $request->email,
                    'name' => $request->name,
                    'username' => $request->last_name,
                    'birth_date' => $birthDate,
                    'whatsapp_number' => $request->phone,
                    'address' => $request->address,
                    'account_type' => $existingUser->account_type ?? 'Chambeador',
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => [
                    'name' => $profile->name,
                    'last_name' => $profile->last_name,
                    'profession' => $profile->profession,
                    'birth_date' => $profile->birth_date ? Carbon::parse($profile->birth_date)->format('d/m/Y') : null,
                    'phone' => $profile->phone,
                    'email' => $profile->email,
                    'gender' => $profile->gender,
                    'address' => $profile->address,
                    'profile_image' => $profile->profile_image ? asset($profile->profile_image) : null,
                    'about_me' => $profile->about_me,
                    'skills' => $profile->skills ?? [],
                    'category' => $profile->category,
                    'subcategories' => $profile->subcategories ?? [],
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function uploadProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $uid = Auth::user()->uid;

            if ($request->hasFile('image')) {
                // Get uploaded file
                $attribute = $request->file('image');
                $destination = 'chambeador_profiles';

                // Generate a unique filename
                $file_name = $uid . '_' . time() . '.' . $attribute->getClientOriginalExtension();

                // Move the file to public/uploads/chambeador_profiles/
                $attribute->move(public_path('uploads/' . $destination), $file_name);

                // Save the relative path (to be stored in DB)
                $image_path = 'uploads/' . $destination . '/' . $file_name;

                // Create or update profile
                $profile = ChambeadorProfile::updateOrCreate(
                    ['uid' => $uid],
                    ['profile_image' => $image_path]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Image uploaded successfully',
                'data' => ['image_path' => asset($image_path)],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addSubcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $uid = Auth::user()->uid;
            $profile = ChambeadorProfile::where('uid', $uid)->first();

            $subcategories = $profile && $profile->subcategories
                ? $profile->subcategories
                : [];

            if (!array_key_exists($request->subcategory, $subcategories)) {
                $subcategories[$request->subcategory] = false;

                ChambeadorProfile::updateOrCreate(
                    ['uid' => $uid],
                    ['subcategories' => $subcategories]
                );
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Subcategory added successfully',
                'data' => ['subcategories' => $subcategories],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add subcategory: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function getNearbyChambeadores()
    {
        try {
            $profiles = ChambeadorProfile::whereNotNull('lat')
                ->whereNotNull('lng')
                ->get(['uid', 'name', 'last_name', 'profession', 'lat', 'lng']);

            $data = $profiles->map(function ($profile) {
                return [
                    'uid' => $profile->uid,
                    'name' => $profile->name ?: 'Unknown',
                    'last_name' => $profile->last_name ?: '',
                    'profession' => $profile->profession ?: 'Chambeador',
                    'lat' => $profile->lat,
                    'lng' => $profile->lng,
                ];
            });

            Log::info('Retrieved nearby chambeadores', ['count' => $data->count()]);

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve chambeadores', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve chambeadores',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
       public function getChambeadorProfilePhotoByUuid($uuid)
    {
        try {
            // Validate UUID format
            if (empty($uuid) || !is_string($uuid) || strlen($uuid) < 10) {
                Log::warning("Invalid UUID format: {$uuid}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid UUID format',
                ], 400);
            }

            // Find chambeador profile by Firebase UUID
            $profile = ChambeadorProfile::where('uid', $uuid)->first();

            if (!$profile) {
                Log::warning("Profile not found for UUID: {$uuid}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Profile not found',
                ], 404);
            }

            // Check if profile image exists
            $profileImage = $profile->profile_image ?? null;

            if (!$profileImage) {
                Log::info("No profile image found for UUID: {$uuid}");
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'profile_photo_url' => null,
                    ],
                ], 200);
            }

            // Convert relative path to full URL if necessary
            $profilePhotoUrl = filter_var($profileImage, FILTER_VALIDATE_URL)
                ? $profileImage
                : 'https://chambea.lat/' . ltrim($profileImage, '/');

            // Validate the URL
            if (!filter_var($profilePhotoUrl, FILTER_VALIDATE_URL)) {
                Log::warning("Invalid profile image URL for UUID: {$uuid}, URL: {$profilePhotoUrl}");
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'profile_photo_url' => null,
                    ],
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'profile_photo_url' => $profilePhotoUrl,
                ],
            ], 200);
        } catch (ValidationException $e) {
            Log::error("Validation error for UUID: {$uuid}, Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid UUID format',
            ], 400);
        } catch (\Exception $e) {
            Log::error("Error fetching profile photo for UUID: {$uuid}, Error: {$e->getMessage()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }
 public function getChambeadoresWithRatings(Request $request)
{
    try {
        // Define cache key and TTL (e.g., 10 minutes)
        $cacheKey = 'chambeadores_ratings_' . md5($request->fullUrl());

        // Attempt to fetch from cache
        $data = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            // Build query without pagination
            $query = ChambeadorProfile::whereNotNull('lat')
                ->whereNotNull('lng')
                ->join('users', 'chambeador_profiles.uid', '=', 'users.uid')
                ->select(
                    'chambeador_profiles.uid',
                    'chambeador_profiles.name',
                    'chambeador_profiles.last_name',
                    'chambeador_profiles.profession',
                    'chambeador_profiles.lat',
                    'chambeador_profiles.lng',
                    'chambeador_profiles.profile_image',
                    'users.rating'
                );

            // Optional: Add geospatial filtering (uncomment if needed)
            /*
            $lat = request()->input('lat');
            $lng = request()->input('lng');
            $radius = request()->input('radius', 10); // km
            if ($lat && $lng) {
                $query->whereRaw(
                    'ST_Distance_Sphere(
                        POINT(chambeador_profiles.lng, chambeador_profiles.lat),
                        POINT(?, ?)
                    ) <= ?',
                    [$lng, $lat, $radius * 1000] // Convert km to meters
                );
            }
            */

            // Fetch all results without pagination
            $profiles = $query->get();

            // Map results to desired format
            $data = $profiles->map(function ($profile) {
                return [
                    'uid' => $profile->uid,
                    'name' => $profile->name ?? 'Unknown',
                    'last_name' => $profile->last_name ?? '',
                    'profession' => $profile->profession ?? 'Chambeador',
                    'lat' => floatval($profile->lat),
                    'lng' => floatval($profile->lng),
                    'profile_photo_path' => $profile->profile_image,
                    'rating' => $profile->rating ? floatval($profile->rating) : 0.0,
                ];
            });

            return [
                'data' => $data,
            ];
        });

        Log::info('Retrieved chambeadores with ratings', [
            'count' => count($data['data']),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $data['data'],
        ], 200);
    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('Database error in getChambeadoresWithRatings', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'status' => 'error',
            'message' => 'Database error occurred',
            'error' => $e->getMessage(),
        ], 500);
    } catch (\Exception $e) {
        Log::error('Failed to retrieve chambeadores with ratings', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to retrieve chambeadores with ratings',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}
