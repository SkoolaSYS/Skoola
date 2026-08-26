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

    $teacherId = $user->id;

    // Get assigned classes from class_user
    $assignedClasses = DB::table('class_user')
        ->join('school_classes', 'class_user.school_class_id', '=', 'school_classes.id')
        ->join('school_grades', 'school_classes.school_grade_id', '=', 'school_grades.id')
        ->where('class_user.user_id', $teacherId)
        ->select(
            'school_classes.id as class_id',
            'school_classes.class_name',
            'school_grades.grade_name'
        )
        ->get();

    // ❌ Redirect if teacher has no assigned classes
    if ($assignedClasses->isEmpty()) {
        return redirect()->route('class_attendance.index')
                         ->with('info', 'You do not have any assigned class. Redirected to attendance page.');
    }

    $classes = [];
    $attendanceData = [];

    foreach ($assignedClasses as $class) {

        $classes[$class->class_name] = $class->grade_name;

        // Get all attendance for this class (any teacher)
        $classAttendanceQuery = ClassAttendance::where('grade', $class->grade_name)
            ->where('class_name', $class->class_name);

        if ($classAttendanceQuery->exists()) {
            $calculateCounts = function($query) {
                $present = (clone $query)->where('status', 'Present')->count();
                $absent  = (clone $query)->where('status', 'Absent')->count();
                $total   = $query->count();
                $others  = $total - $present - $absent;

                return [$present, $absent, $others];
            };

            $dailyCounts = $calculateCounts(
                (clone $classAttendanceQuery)->whereDate('attendance_time', now())
            );

            $weeklyCounts = $calculateCounts(
                (clone $classAttendanceQuery)->whereBetween('attendance_time', [now()->startOfWeek(), now()->endOfWeek()])
            );

            $monthlyCounts = $calculateCounts(
                (clone $classAttendanceQuery)->whereMonth('attendance_time', now()->month)
            );

            $attendanceData[$class->class_name] = [
                'daily' => $dailyCounts,
                'weekly' => $weeklyCounts,
                'monthly' => $monthlyCounts,
            ];
        } else {
            // No attendance yet for this class
            $attendanceData[$class->class_name] = null;
        }
    }

    return view('teacher.dashboard', compact(
        'attendanceData',
        'classes'
    ));
}







$user = auth()->user();

if ($user->hasRole('parent')) {
    return redirect()->route('parent.dashboard');
}




    
}








    


}
