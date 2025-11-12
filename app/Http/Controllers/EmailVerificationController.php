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
        // Capture email from query string
    $email = $request->query('email');

    // Optional: show message if no email provided
    if (!$email) {
        $email = ''; // or "No email provided"
    }

    // Check if email is verified
    $verified = false;
    if ($email) {
        $verified = DB::table('email_verifications')
            ->where('email', $email)
            ->whereNotNull('verified_at')
            ->exists();
    }

    // Load parent data if email exists
    $parent = null;
    $students = [];
    $attendanceList = [];
    $attendanceData = [];

    if ($email && $verified) {
        $parent = User::where('email', $email)->first();
        if ($parent) {
            $studentIds = $parent->students()->pluck('students.id')->toArray();
            $students = Student::whereIn('id', $studentIds)->pluck('name', 'id')->toArray();

            $attendanceList = Attendance::whereIn('student_id', $studentIds)
                ->orderByDesc('id')
                ->take(5)
                ->get();

            $attendancesAbsent = Attendance::whereIn('student_id', $studentIds)
                ->where('status', 'absent')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as total'))
                ->pluck('total', 'student_id');

            $attendancesAttend = Attendance::whereIn('student_id', $studentIds)
                ->where('status', 'attend')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as total'))
                ->pluck('total', 'student_id');

            foreach ($students as $id => $name) {
                $attend = $attendancesAttend[$id] ?? 0;
                $absent = $attendancesAbsent[$id] ?? 0;
                $attendanceData[$id] = json_encode([$attend, $absent]);
            }
        }
    }

    // Pass email to Blade
    return view('parents.email', compact(
        'email',
        'verified',
        'parent',
        'students',
        'attendanceList',
        'attendanceData'
    ));
    }

}
