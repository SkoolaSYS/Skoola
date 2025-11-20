<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


class ParentController extends Controller
{
    
    public function dashboard(Request $request)
{
    // 1️⃣ Logged-in parent
    if (auth()->check()) {
        $parent = auth()->user();
        info('Dashboard accessed via login', ['parent' => $parent->email]);
    } 
    // 2️⃣ PWA access via verified email cookie
    elseif ($email = $request->cookie('verified_email')) {
        $parent = \App\Models\User::where('email', $email)->first();

        if (!$parent) {
            info('Parent not found via PWA email', ['email' => $email]);
            return redirect('/attendance-parent')->with('error', 'Parent account not found.');
        }

        $verified = \DB::table('email_verifications')
            ->where('email', $email)
            ->whereNotNull('verified_at')
            ->exists();

        if (!$verified) {
            info('Email not verified via PWA', ['email' => $email]);
            return redirect('/attendance-parent')->with('error', 'Please verify your email first.');
        }

        // ✅ Log in the parent for this session
        Auth::login($parent);

        info('Dashboard accessed via PWA', ['parent' => $parent->email]);
    } 
    else {
        info('No login or verified email');
        return redirect('/attendance-parent')->with('error', 'Please login or verify your email.');
    }

    // Get all student IDs safely
    $allStudentIds = $parent->students ? $parent->students->pluck('id')->toArray() : [];
    info('Student IDs', ['ids' => $allStudentIds]);

    // Student names
    $students = \App\Models\Student::whereIn('id', $allStudentIds)
        ->pluck('name', 'id')
        ->toArray();
    info('Students array', $students);

    // Last 5 attendance records (eager load student and school)
    $attendanceList = Attendance::whereIn('student_id', $allStudentIds)
        ->with(['student.school'])
        ->take(5)
        ->orderByDesc('id')
        ->get();
    info('Attendance list', $attendanceList->toArray());

    // Count absences and attendances
    $attendancesAbsent = Attendance::whereIn('student_id', $allStudentIds)
        ->where('status', 'absent')
        ->groupBy('student_id')
        ->select('student_id', \DB::raw('count(*) as total'))
        ->pluck('total', 'student_id')
        ->toArray();

    $attendancesAttend = Attendance::whereIn('student_id', $allStudentIds)
        ->where('status', 'attend')
        ->groupBy('student_id')
        ->select('student_id', \DB::raw('count(*) as total'))
        ->pluck('total', 'student_id')
        ->toArray();

    info('Attendances (attend)', $attendancesAttend);
    info('Attendances (absent)', $attendancesAbsent);

    // Prepare chart data
    $attendanceData = [];
    foreach ($students as $id => $name) {
        $attend = $attendancesAttend[$id] ?? 0; 
        $absent = $attendancesAbsent[$id] ?? 0;  
        $attendanceData[$id] = json_encode([$attend, $absent]);
    }
    info('Attendance data for charts', $attendanceData);

    return view('parents.dashboard', compact(
        'parent',
        'students',
        'attendanceList',
        'attendanceData'
    ));
}








    public function attendancePage(Request $request)
{
    // Read email from: query → input → cookie
    $email = $request->query('email')
        ?? $request->input('email')
        ?? $request->cookie('verified_email');

    // No email provided anywhere
    if (!$email) {
        return view('parents.enter_email');
    }

    // Check if email exists in users table
    $userExists = DB::table('users')->where('email', $email)->exists();

    if (!$userExists) {
        return view('parents.email_not_found', compact('email'));
    }

    // Check if email is already VERIFIED in the DB
    $verified = DB::table('email_verifications')
        ->where('email', $email)
        ->whereNotNull('verified_at')
        ->exists();

    // ⭐ CASE 1: Already verified → ALWAYS allow dashboard
    if ($verified) {
    $parent = User::where('email', $email)->first();
    Auth::login($parent); // ✅ ensure full session auth

    return redirect()->route('parent.dashboard')
        ->cookie('verified_email', $email, 525600); // 1 year
}


    // ⭐ CASE 2: Not verified → send verification link
    $token = Str::random(32);

    DB::table('email_verifications')->updateOrInsert(
        ['email' => $email],
        [
            'token' => $token,
            'verified_at' => null,
            'created_at' => now()
        ]
    );

    $link = url('attendance-parent-verify?email=' . $email . '&token=' . $token);

    Mail::send('emails.parent_verify', ['link' => $link], function ($message) use ($email) {
        $message->to($email)->subject('Verify your parent account');
    });

    return view('parents.verify_email_instructions', compact('email'));
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

    // Log the user in so all routes recognize them
    $parent = User::where('email', $request->email)->first();
    Auth::login($parent);

    // ✅ Assign parent role if not already assigned
    if (!$parent->hasRole('parent')) {
        $parent->assignRole('parent');
    }

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

    // ✅ Mark user as verified automatically
    if ($parent->verified !== '01') {
        $parent->update(['verified' => '01']);
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
