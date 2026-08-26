<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    /**
     * 📨 Send verification link
     */
    public function sendVerification(Request $request)
    {
        // Validate incoming email
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $token = Str::random(60);

        // Create or update verification record
        DB::table('email_verifications')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'verified_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        // Generate the verification link
        $verifyUrl = route('attendance.verify', ['token' => $token]);

        // Try sending the email
        try {
            Mail::raw("Click the link below to verify your email:\n\n{$verifyUrl}", function ($message) use ($email) {
                $message->to($email)->subject('Verify your email');
            });
        } catch (\Exception $e) {
            // Return JSON error for PWA
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email.',
                'error' => $e->getMessage()
            ], 500);
        }

        // Return JSON success for PWA
        return response()->json([
            'status' => 'success',
            'message' => 'Verification email sent successfully.',
            'email' => $email,
            'verify_url' => $verifyUrl
        ]);
    }

    /**
     * ✅ Handle verification link
     */
    public function verifyEmail($token)
{
    $record = DB::table('email_verifications')->where('token', $token)->first();

    if (!$record) {
        return response()->view('parents.invalid_link', [], 404);
    }

    // Mark email as verified
    DB::table('email_verifications')
        ->where('email', $record->email)
        ->update(['verified_at' => now()]);

    // Redirect to parent dashboard
    return redirect()->route('attendance.parent', ['email' => $record->email]);
}

public function show(Request $request)
{
    // PWA sends the email via query string or POST
    $email = $request->query('email') ?? $request->input('email');

    if (!$email) {
        return "Email not provided.";
    }

    // Check verified email in DB
    $verification = DB::table('email_verifications')
        ->where('email', $email)
        ->whereNotNull('verified_at')
        ->first();

    if ($verification) {
        // Set a cookie for 1 year
        return redirect()->route('parent.pwa')
    ->cookie('verified_email', $email, 525600);

    }

    // Email not verified → show page with instructions
    return view('parents.email', compact('email'));
}

}
