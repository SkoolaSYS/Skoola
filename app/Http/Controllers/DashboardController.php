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
        
        $students = Student::where('parent_id', auth()->id())
            ->pluck('name', 'id')->toArray();

        $attendanceList = Attendance::whereIn('student_id', array_keys($students))
            ->take(5)
            ->orderByDesc('id') // Sort in descending order based on the 'id' or 'created_at' column
            ->get();

        $attendancesAbsent = Attendance::whereIn('student_id', array_keys($students))
            ->where('status', 'absent')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');

        $attendancesAttend = Attendance::whereIn('student_id', array_keys($students))
            ->where('status', 'attend')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');


        $attendanceData = [];
        foreach ($attendancesAttend as $key => $attendCount) {
            if (isset($attendancesAbsent[$key])) {
                $absentCount = $attendancesAbsent[$key];
            } else {
                $absentCount = 0;
            }
        
            $attendanceData[$key] = json_encode([$attendCount, $absentCount]);
        }
        //dd($attendanceData);
        return view('parents.dashboard', compact('parent', 'attendanceData', 'students', 'attendanceList'));
    }

}
