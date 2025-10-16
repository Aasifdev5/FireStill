<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BackgroundCertificate;
use Illuminate\Support\Facades\Auth;

class BackgroundCertificateApi extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.firebase');
    }

    public function uploadCertificate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'certificate' => 'required|file|mimes:jpeg,png,jpg', // Removed PDF support for frontend compatibility
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $uid = Auth::user()->uid;
            $certificate = $request->file('certificate');
            $destination = 'certificates';

            // Generate unique filename
            $filename = $uid . '_certificate_' . time() . '.' . $certificate->getClientOriginalExtension();

            // Move to public/uploads/certificates/
            $certificate->move(public_path('uploads/' . $destination), $filename);

            // Save relative path
            $path = 'uploads/' . $destination . '/' . $filename;

            // Create or update record
            $certificateRecord = BackgroundCertificate::updateOrCreate(
                ['uid' => $uid],
                ['certificate_path' => $path]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Certificate uploaded successfully',
                'certificate_path' => asset($path),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload certificate: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCertificate(Request $request)
    {
        try {
            $uid = Auth::user()->uid;
            $certificate = BackgroundCertificate::where('uid', $uid)->first();

            if (!$certificate) {
                return response()->json([
                    'status' => 'success',
                    'data' => null,
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'certificate_path' => asset($certificate->certificate_path),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch certificate: ' . $e->getMessage(),
            ], 500);
        }
    }
}
