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
    $teacherId = $user->id; // logged-in teacher
    $attendanceData = [];

    // Get all unique grade + class_name combinations for this teacher
    $classes = ClassAttendance::where('teacher_id', $teacherId)
        ->select('grade', 'class_name')
        ->distinct()
        ->get()
        ->pluck('grade', 'class_name') // ['3A' => 'Darjah 3']
        ->toArray();

    foreach ($classes as $className => $grade) {
        $students = Student::where('grade', $grade)
            ->where('class_name', $className)
            ->pluck('id');

        // Helper to calculate counts for a period
        $calculateCounts = function($query) use ($students, $teacherId) {
            $present = (clone $query)->where('status', 'Present')->count();
            $absent  = (clone $query)->where('status', 'Absent')->count();
            $total   = $query->count();
            $others  = $total - $present - $absent;

            return [$present, $absent, $others];
        };

        // Daily
        $dailyQuery = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereDate('attendance_time', now()->toDateString());
        $dailyCounts = $calculateCounts($dailyQuery);

        // Weekly
        $weeklyQuery = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereBetween('attendance_time', [now()->startOfWeek(), now()->endOfWeek()]);
        $weeklyCounts = $calculateCounts($weeklyQuery);

        // Monthly
        $monthlyQuery = ClassAttendance::whereIn('student_id', $students)
            ->where('teacher_id', $teacherId)
            ->whereMonth('attendance_time', now()->month);
        $monthlyCounts = $calculateCounts($monthlyQuery);

        $attendanceData[$className] = [
            'daily'   => $dailyCounts,
            'weekly'  => $weeklyCounts,
            'monthly' => $monthlyCounts,
        ];
    }

    return view('teacher.dashboard', compact('attendanceData', 'classes'));
}


$user = auth()->user();

if ($user->hasRole('parent')) {
    return redirect()->route('parent.dashboard');
}




    
}








    


}
