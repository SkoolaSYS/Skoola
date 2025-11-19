<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;



class ParentController extends Controller
{
    // Parent web dashboard
    public function dashboard()
    {
        $parent = auth()->user(); // logged-in parent

        // Get all student IDs linked to this parent
        $allStudentIds = $parent->students()->pluck('students.id')->toArray();

        // Student names
        $students = Student::whereIn('id', $allStudentIds)
            ->pluck('name', 'id')
            ->toArray();

        // Last 5 attendance records
        $attendanceList = Attendance::whereIn('student_id', $allStudentIds)
            ->take(5)
            ->orderByDesc('id')
            ->get();

        // Count absences and attendances
        $attendancesAbsent = Attendance::whereIn('student_id', $allStudentIds)
            ->where('status', 'absent')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');

        $attendancesAttend = Attendance::whereIn('student_id', $allStudentIds)
            ->where('status', 'attend')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');

        // Prepare chart data
        $attendanceData = [];
        foreach ($students as $id => $name) {
            $attend = $attendancesAttend[$id] ?? 0;
            $absent = $attendancesAbsent[$id] ?? 0;
            $attendanceData[$id] = json_encode([$attend, $absent]);
        }

        return view('parents.dashboard', compact(
            'parent',
            'students',
            'attendanceList',
            'attendanceData'
        ));
    }



    public function attendancePage(Request $request)
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
    return view('parents.verify_email_instructions', compact('email'));
}

public function sendVerificationLink(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    $token = Str::random(32);

    // Save token in DB
    DB::table('email_verifications')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $token, 'verified_at' => null, 'created_at' => now()]
    );

    $link = url('attendance-parent-verify?email='.$request->email.'&token='.$token);

    // Send email
    Mail::raw("Click the link to verify: $link", function($message) use ($request) {
        $message->to($request->email)
                ->subject('Verify your parent account');
    });

    return view('parents.verify_email_sent', ['email' => $request->email]);
}

public function verifyEmail(Request $request)
{
    $verification = DB::table('email_verifications')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();

    if (!$verification) {
        return "Invalid verification link.";
    }

    // Mark as verified
    DB::table('email_verifications')
        ->where('email', $request->email)
        ->update(['verified_at' => now()]);

    // Set cookie for 1 year and redirect to dashboard
    return redirect()->route('parent.dashboard')
        ->cookie('verified_email', $request->email, 60*24*365);
}

public function pwaDashboard(Request $request)
{
    $email = $request->cookie('verified_email');

    if (!$email) {
        return "Unauthorized: no verified email.";
    }

    // Parent by email (not auth)
    $parent = User::where('email', $email)->first();

    if (!$parent) {
        return "Parent not found";
    }

    // SAME logic as dashboard, but WITHOUT auth()
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

    $attendanceData = [];
    foreach ($students as $id => $name) {
        $attend = $attendancesAttend[$id] ?? 0;
        $absent = $attendancesAbsent[$id] ?? 0;
        $attendanceData[$id] = json_encode([$attend, $absent]);
    }

    return view('parents.dashboard_pwa', compact(
        'parent',
        'students',
        'attendanceList',
        'attendanceData'
    ));
}





}
