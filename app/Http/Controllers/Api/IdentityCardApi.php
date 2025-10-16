<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\IdentityCard;
use Illuminate\Support\Facades\Auth;

class IdentityCardApi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.firebase');
    }

    public function updateIdentityCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_number' => 'required|string|max:50',
            'front_image' => 'required|image|mimes:jpeg,png,jpg',
            'back_image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $uid = Auth::user()->uid;
            $destination = 'identity_cards';

            $frontImage = $request->file('front_image');
            $backImage = $request->file('back_image');

            $timestamp = time(); // To keep both files in sync

            $frontFilename = $uid . '_front_' . $timestamp . '.' . $frontImage->getClientOriginalExtension();
            $backFilename = $uid . '_back_' . $timestamp . '.' . $backImage->getClientOriginalExtension();

            $frontImage->move(public_path('uploads/' . $destination), $frontFilename);
            $backImage->move(public_path('uploads/' . $destination), $backFilename);

            $frontPath = 'uploads/' . $destination . '/' . $frontFilename;
            $backPath = 'uploads/' . $destination . '/' . $backFilename;

            $identityCard = IdentityCard::updateOrCreate(
                ['uid' => $uid],
                [
                    'id_number' => $request->id_number,
                    'front_image' => $frontPath,
                    'back_image' => $backPath,
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Identity card updated successfully',
                'data' => [
                    'id_number' => $identityCard->id_number,
                    'front_image' => asset($frontPath),
                    'back_image' => asset($backPath),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update identity card: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getIdentityCard(Request $request)
    {
        try {
            $uid = Auth::user()->uid;
            $identityCard = IdentityCard::where('uid', $uid)->first();

            if (!$identityCard) {
                return response()->json([
                    'status' => 'success',
                    'data' => null,
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id_number' => $identityCard->id_number,
                    'front_image' => asset($identityCard->front_image),
                    'back_image' => asset($identityCard->back_image),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch identity card: ' . $e->getMessage(),
            ], 500);
        }
    }
}
