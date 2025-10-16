<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserProfileApi extends Controller
{
    public function getProfile(Request $request)
    {
        try {
            $uid = $request->attributes->get('uid');
            $user = User::where('uid', $uid)->first();

            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Profile not found'], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'name' => $user->name,
                    'last_name' => $user->username,
                    'birth_date' => $user->birth_date ? Carbon::parse($user->birth_date)->format('d/m/Y') : null,
                    'phone' => $user->whatsapp_number,
                    'location' => $user->address,
                    'profile_photo' => $user->profile_photo,
                    'account_type' => $user->account_type ?? 'Client',
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get profile error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch profile'], 500);
        }
    }
 public function show($id)
    {
        try {
            // Try finding user by uid first, then by id
            $user = User::where('uid', $id)->first() ?? User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'uid' => $user->uid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'account_type' => $user->account_type ?? 'Trabajador',
                    'rating' => $user->rating ?? 0.0, // Use stored rating or default
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('User fetch error', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getClientProfilePhotoByUuid($uuid)
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

            // Find user by Firebase UUID
            $user = User::where('uid', $uuid)->first();

            if (!$user) {
                Log::warning("User not found for UUID: {$uuid}");
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }

            // Check if profile image exists
            $profileImage = $user->profile_photo ?? null;

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
    public function getClients(Request $request)
        {
            try {
                $clients = User::where('account_type', 'Client')
                    ->select('id', 'name', 'rating', 'profile_photo')
                    ->get()
                    ->map(function ($client) {
                        return [
                            'id' => $client->id,
                            'name' => $client->name ?? 'Usuario Desconocido',
                            'rating' => $client->rating ? floatval($client->rating) : 0.0,
                            'profile_photo' => $client->profile_photo, // e.g., Uploads/profile_photos/...
                        ];
                    });

                Log::info('Fetched clients', ['count' => $clients->count()]);

                return response()->json([
                    'status' => 'success',
                    'data' => $clients,
                ], 200);
            } catch (\Exception $e) {
                Log::error('Failed to fetch clients', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to fetch clients: ' . $e->getMessage(),
                ], 500);
            }
        }

public function mapIdToUid($id)
{
    $user = User::find($id);
    if (!$user) {
        Log::error("No user found for id: $id");
        return response()->json(['message' => 'User not found'], 404);
    }
    return response()->json([
        'status' => 'success',
        'data' => ['uid' => $user->uid]
    ], 200);
}
    public function updateProfile(Request $request)
    {
        try {
            $uid = $request->attributes->get('uid');
            $firebaseUser = $request->attributes->get('firebaseUser');

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birth_date' => 'required|date_format:d/m/Y',
                'phone' => 'required|string|max:20',
                'location' => 'required|string|max:255',
            ]);

            $user = User::updateOrCreate(
                ['uid' => $uid],
                [
                    'email' => $firebaseUser->claims()->get('email') ?? null,
                    'name' => $validated['name'],
                    'username' => $validated['last_name'],
                    'birth_date' => Carbon::createFromFormat('d/m/Y', $validated['birth_date'])->format('Y-m-d'),
                    'whatsapp_number' => $validated['phone'],
                    'address' => $validated['location'],
                    'account_type' => $user->account_type ?? 'Client',
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => [
                    'name' => $user->name,
                    'last_name' => $user->username,
                    'birth_date' => $user->birth_date ? Carbon::parse($user->birth_date)->format('d/m/Y') : null,
                    'phone' => $user->whatsapp_number,
                    'location' => $user->address,
                    'account_type' => $user->account_type,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Update profile error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update profile'], 500);
        }
    }

    public function uploadProfileImage(Request $request)
{
    try {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $uid = $request->attributes->get('uid');
        $user = User::where('uid', $uid)->firstOrFail();

        $image = $request->file('image');
        $timestamp = time();
        $filename = $uid . '_profile_' . $timestamp . '.' . $image->getClientOriginalExtension();

        $destination = 'uploads/profile_photos';
        $image->move(public_path($destination), $filename);

        $relativePath = $destination . '/' . $filename;

        // Save relative path in DB
        $user->update(['profile_photo' => $relativePath]);

        return response()->json([
            'status' => 'success',
            'message' => 'Image uploaded successfully',
            'image_path' => asset($relativePath),
        ], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error: ' . json_encode($e->errors()));
        return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('Upload image error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Failed to upload image'], 500);
    }
}

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user->fcm_token = $request->input('fcm_token');
        $user->save();

        \Log::info('FCM token updated for user: ' . $user->id);

        return response()->json([
            'message' => 'FCM token updated successfully.',
        ]);
    }
  public function getAccountType($uid)
{
    Log::info('Received getAccountType request for UID: ' . $uid);
    try {
        if (!$uid) {
            Log::error('Missing UID in request');
            return response()->json([
                'status' => 'error',
                'message' => 'UID required'
            ], 400);
        }

        $user = User::where('uid', $uid)->first();
        if (!$user) {
            Log::error('User not found for UID: ' . $uid);
            return response()->json([
                'status' => 'error',
                'message' => 'Profile not found'
            ], 404);
        }

        $validAccountTypes = ['Client', 'Chambeador'];
        if ($user->account_type === null || !in_array($user->account_type, $validAccountTypes)) {
            Log::error('Invalid or null account type for UID: ' . $uid . ', account_type: ' . $user->account_type);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid or missing account type'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'account_type' => $user->account_type
            ]
        ], 200);
    } catch (\Exception $e) {
        Log::error('Get account type error for UID: ' . $uid . ': ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch account type'
        ], 500);
    }
}
}
