<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\School;
use App\Models\State;
use App\Models\Student;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Exports\PPDExport;
use Maatwebsite\Excel\Facades\Excel;

class StateController extends Controller
{
    public function index(State $state)
    {
        $user = auth()->user();
        $schools = School::where('state_id', $state->id)->get();
        $districts = District::where('state_id', $state->id)->get();
        $totalPelajar = Student::where('state_id', $state->id)->count(); // Count total students in the state

        if ($user->hasRole('admin|country')) {
            $stateData = $this->stateCalculation($schools, $districts, $totalPelajar);
        } else if ($user->hasRole('state') && $user->getMeta('user_state_id') == $state->id) { //To make sure that the state will always in the right state page
            $stateData = $this->stateCalculation($schools, $districts, $totalPelajar);
        } else {
            return abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }
        return view('state.dashboard', array_merge([
            'schools' => $schools,
            'state' => $state,
            'districts' => $districts,
            'totalPelajar' => $totalPelajar,
            'state_id' => $state->id,
        ], $stateData));
    }

    private function stateCalculation($schools, $districts, $totalPelajar)
    {
        $totalStudents = [];
        $averageAttendanceSchool = [];
        foreach ($schools as $key => $school) {
            $totalStudents[$key] = Student::where('school_id', $school->id)->count();

            // Retrieve the attendances for this school
            $attendancesAttend[$key] = Attendance::whereHas('student', function (Builder $query) use ($school) {
                $query->where('school_id', $school->id);
            })
                ->where('status', 'attend')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as attend_total'))
                ->get(); // Retrieve the grouped data

            $attendancesAbsent[$key] = Attendance::whereHas('student', function (Builder $query) use ($school) {
                $query->where('school_id', $school->id);
            })
                ->where('status', 'absent')
                ->groupBy('student_id')
                ->select('student_id', DB::raw('count(*) as absent_total'))
                ->get(); // Retrieve the grouped data

            $attendanceData[$key] = [];
            foreach ($attendancesAttend[$key] as $attendance) {
                $studentId = $attendance->student_id;
                $attendTotal = $attendance->attend_total;
                // Access the attend_total value for this student in this school
                $attendanceData[$key][$studentId]['attend_total'] = $attendTotal;
            }

            foreach ($attendancesAbsent[$key] as $attendance) {
                $studentId = $attendance->student_id;
                $absentTotal = $attendance->absent_total;
                // Access the absent_total value for this student in this school
                $attendanceData[$key][$studentId]['absent_total'] = $absentTotal;
            }

            $totalAttendances[$key] = [];
            $attendancePercentage[$key] = [];
            $formattedAttendancePercentage[$key] = [];
            foreach ($attendanceData[$key] as $studentId => $data) {
                $attendTotal = isset($attendanceData[$key][$studentId]['attend_total']) ? $attendanceData[$key][$studentId]['attend_total'] : 0;
                $absentTotal = isset($attendanceData[$key][$studentId]['absent_total']) ? $attendanceData[$key][$studentId]['absent_total'] : 0;

                $totalAttendances[$key][$studentId] = $attendTotal + $absentTotal;
                $attendancePercentage[$key][$studentId] = ($attendTotal / $totalAttendances[$key][$studentId]) * 100;
                $formattedAttendancePercentage[$key][$studentId] = number_format($attendancePercentage[$key][$studentId], 1);
            }

            $totalPercentageAttendancesSchool[$key] = 0;
            $attendancePercentageSchool[$key] = 0;
            $formattedAttendancePercentageSchool[$key] = 0;

            foreach ($attendancePercentage[$key] as $studentId => $studentAttendance) {
                // Sum up the attendance percentages for all students in a school
                $totalPercentageAttendancesSchool[$key] += ($studentAttendance);
            }

            // Calculate the average attendance percentage for the school
            if ($totalStudents[$key]) {
                // Calculate the average attendance percentage for the school
                $attendancePercentageSchool[$key] = (($totalPercentageAttendancesSchool[$key] / 100) / $totalStudents[$key]) * 100;
            } else {
                $attendancePercentageSchool[$key] = 0; // Set to zero if no students
            }

            // Format the average attendance percentage for the school
            $formattedAttendancePercentageSchool[$key] = number_format($attendancePercentageSchool[$key], 1);
            $averageAttendanceSchool[$key] = $attendancePercentageSchool[$key] ?? 0;
        }

        // Calculate the average percentage for the state
        $sumOfProducts = 0;
        foreach ($averageAttendanceSchool as $key => $averagePercentage) {
            $sumOfProducts += ($totalStudents[$key] * $averagePercentage);
        }

        foreach ($districts as $district) {
            $totalStudents = Student::whereHas('district', function (Builder $query) use ($district) {
                $query->where('id', $district->id);
            })->count();
            $district->totalStudents = $totalStudents;
        } // count total students each ppd

        $averagePercentageState = 0;
        if ($totalPelajar > 0) {
            $averagePercentageState = ($sumOfProducts / $totalPelajar);
        }

        $formattedAttendancePercentageState = number_format($averagePercentageState, 1);
        return [
            'totalStudents' => $totalStudents,
            'averageAttendanceSchool' => $averageAttendanceSchool,
            'formattedAttendancePercentageState' => $formattedAttendancePercentageState,
        ];
    }

    public function export($state_id)
    {
        $state = State::find($state_id);
        $stateName = $state->name;
        return Excel::download(new PPDExport($state_id, $stateName), 'list_of_PPDs.xlsx');
    }
}
