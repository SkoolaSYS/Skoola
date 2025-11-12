<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassAttendance;
use App\Models\Student;
use App\Models\Teacher; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User; 

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // -------------------------------
    // Redirects for admin/country
    // -------------------------------
    if ($user->hasRole('admin|country')) {
        return redirect()->route('dashboard.country');
    }

    // -------------------------------
    // Redirects for state/ppd/school
    // -------------------------------
    if ($user->hasRole('state|ppd|school')) {
        $userStateId = $user->getMeta('user_state_id');
        $userDistrictId = $user->getMeta('user_district_id');
        $userSchoolId = $user->getMeta('user_school_id');

        if ($userStateId) {
            return redirect()->route('dashboard.state', ['state' => $userStateId]);
        } elseif ($userDistrictId) {
            return redirect()->route('dashboard.ppd', ['ppd' => $userDistrictId]);
        } elseif ($userSchoolId) {
            return redirect()->route('dashboard.school', ['school' => $userSchoolId]);
        } else {
            return abort(403, 'Unauthorized.');
        }
    }

    // -------------------------------
// Teacher dashboard (Dynamic classes)
// -------------------------------
if ($user->hasRole('teacher')) {
    $teacherId = $user->id; // use logged-in teacher
    $attendanceData = [];

    // Get all unique grade + class_name combinations for this teacher
    $classes = ClassAttendance::where('teacher_id', $teacherId)
        ->select('grade', 'class_name')
        ->distinct()
        ->get()
        ->pluck('grade', 'class_name') // creates ['3A' => 'Darjah 3']
        ->toArray();

    foreach ($classes as $className => $grade) {
        $students = Student::where('grade', $grade)
            ->where('class_name', $className)
            ->pluck('id');

        // --- Daily ---
        $dailyPresent = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereDate('attendance_time', now()->toDateString())
            ->where('status', 'Present')
            ->count();
        $dailyAbsent = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereDate('attendance_time', now()->toDateString())
            ->where('status', 'Absent')
            ->count();
        $dailyLate = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereDate('attendance_time', now()->toDateString())
            ->where('status', 'Late')
            ->count();

        // --- Weekly ---
        $weeklyPresent = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereBetween('attendance_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'Present')
            ->count();
        $weeklyAbsent = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereBetween('attendance_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'Absent')
            ->count();
        $weeklyLate = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereBetween('attendance_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'Late')
            ->count();

        // --- Monthly ---
        $monthlyPresent = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereMonth('attendance_time', now()->month)
            ->where('status', 'Present')
            ->count();
        $monthlyAbsent = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereMonth('attendance_time', now()->month)
            ->where('status', 'Absent')
            ->count();
        $monthlyLate = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereMonth('attendance_time', now()->month)
            ->where('status', 'Late')
            ->count();

        $attendanceData[$className] = [
            'daily'   => [$dailyPresent, $dailyAbsent, $dailyLate],
            'weekly'  => [$weeklyPresent, $weeklyAbsent, $weeklyLate],
            'monthly' => [$monthlyPresent, $monthlyAbsent, $monthlyLate],
        ];
    }

    return view('teacher.dashboard', compact('attendanceData', 'classes'));
}



    // -------------------------------
    // Parent dashboard section
    // -------------------------------
    $parent = $user; // logged in parent

    // Get all student IDs linked to this parent from pivot
    $allStudentIds = $parent->students()->pluck('students.id')->toArray();

    // Student names
    $students = Student::whereIn('id', $allStudentIds)
        ->pluck('name', 'id')
        ->toArray();

    // Attendance list
    $attendanceList = Attendance::whereIn('student_id', $allStudentIds)
        ->take(5)
        ->orderByDesc('id')
        ->get();

    // Absent count
    $attendancesAbsent = Attendance::whereIn('student_id', $allStudentIds)
        ->where('status', 'absent')
        ->groupBy('student_id')
        ->select('student_id', DB::raw('count(*) as total'))
        ->pluck('total', 'student_id');

    // Attend count
    $attendancesAttend = Attendance::whereIn('student_id', $allStudentIds)
        ->where('status', 'attend')
        ->groupBy('student_id')
        ->select('student_id', DB::raw('count(*) as total'))
        ->pluck('total', 'student_id');

    // Format chart data
    $attendanceData = [];
    foreach ($students as $id => $name) {
        $attend = $attendancesAttend[$id] ?? 0;
        $absent = $attendancesAbsent[$id] ?? 0;
        $attendanceData[$id] = json_encode([$attend, $absent]);
    }

    return view('parents.dashboard', compact('parent', 'attendanceData', 'students', 'attendanceList'));
}

public function pwaParentDashboard(Request $request)
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
    return view('parents.dashboard', compact(
        'email',
        'verified',
        'parent',
        'students',
        'attendanceList',
        'attendanceData'
    ));
}






    


}
