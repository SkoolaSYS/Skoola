<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Check if the logged-in user has the correct role

        

        if ($user->hasRole('admin|country')) {
            return redirect()->route('dashboard.country');
        }
        if ($user->hasRole('state|ppd|school')) {
            $userStateId = $user->getMeta('user_state_id');
            $userDistrictId = $user->getMeta('user_district_id');
            $userSchoolId = $user->getMeta('user_school_id');

            if ($userStateId) {
                return redirect()->route('dashboard.state', ['state' => $userStateId]);
            } 
            else if ($userDistrictId) {
                return redirect()->route('dashboard.ppd', ['ppd' => $userDistrictId]);
            }
            else if ($userSchoolId) {
                return redirect()->route('dashboard.school', ['school' => $userSchoolId]);
            }
            else {
                return abort(403, 'Unauthorized.');
            }
        }
        
        // Get authenticated user
    $parent = auth()->user();

    // 1. Students where this parent is the main guardian
    $mainStudents = Student::where('parent_id', $parent->id)->pluck('id')->toArray();

    // 2. Students linked in pivot table parent_student
    $pivotStudents = DB::table('parent_student')
        ->where('parent_id', $parent->id)
        ->pluck('student_id')
        ->toArray();

    // Merge both sets of student IDs
    $allStudentIds = array_unique(array_merge($mainStudents, $pivotStudents));

    // Get student names for display
    $students = Student::whereIn('id', $allStudentIds)->pluck('name', 'id')->toArray();

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
    foreach ($attendancesAttend as $key => $attendCount) {
        $absentCount = $attendancesAbsent[$key] ?? 0;
        $attendanceData[$key] = json_encode([$attendCount, $absentCount]);
    }

    return view('parents.dashboard', compact('parent', 'attendanceData', 'students', 'attendanceList'));
}

}
