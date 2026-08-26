<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\District;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Exports\SchoolExport;
use Maatwebsite\Excel\Facades\Excel;

class DistrictController extends Controller
{
    public function index(School $school, District $ppd, Attendance $attendance)
    {
        $user = auth()->user();
        $schools = School::where('district_id', $ppd->id)->get();
        $totalPelajarPPD = Student::where('district_id', $ppd->id)->count(); //Count total students in 1 ppd

        if ($user->hasRole('admin|country|state')) {
            $totalStudents = [];
            $averageAttendanceSchool = [];
            foreach ($schools as $key => $school) {
                $totalStudents[$key] = Student::where('school_id', $school->id)->count(); // Count total students in each school

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

            // Calculate the average percentage for the PPD
            $sumOfProducts = 0;
            foreach ($averageAttendanceSchool as $key => $averagePercentage) {
                $sumOfProducts += ($totalStudents[$key] * $averagePercentage);
            }

            $averagePercentagePPD = 0;
            if ($totalPelajarPPD > 0) {
                $averagePercentagePPD = ($sumOfProducts / $totalPelajarPPD);
            }

            $formattedAttendancePercentagePPD = number_format($averagePercentagePPD, 1);
        } else if ($user->hasRole('ppd') && $user->getMeta('user_district_id') == $ppd->id) { //To make sure that the district user will always in the right district page
            $totalStudents = [];
            $averageAttendanceSchool = [];
            foreach ($schools as $key => $school) {
                $totalStudents[$key] = Student::where('school_id', $school->id)->count(); // Count total students in each school

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
    
                    $totalAttendances[$key][$studentId] = $attendTotal + $absentTotal;                    $attendancePercentage[$key][$studentId] = ($data['attend_total'] / $totalAttendances[$key][$studentId]) * 100;
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

            // Calculate the average percentage for the PPD
            $sumOfProducts = 0;
            foreach ($averageAttendanceSchool as $key => $averagePercentage) {
                $sumOfProducts += ($totalStudents[$key] * $averagePercentage);
            }

            $averagePercentagePPD = 0;
            if ($totalPelajarPPD > 0) {
                $averagePercentagePPD = ($sumOfProducts / $totalPelajarPPD);
            }

            $formattedAttendancePercentagePPD = number_format($averagePercentagePPD, 1);
        } else {
            return abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }
        return view('ppd.dashboard', [
            'schools' => $schools,
            'ppd' => $ppd,
            'totalPelajarPPD' => $totalPelajarPPD,
            'totalStudents' => $totalStudents,
            'formattedAttendancePercentageSchool' => $formattedAttendancePercentageSchool,
            'formattedAttendancePercentagePPD' => $formattedAttendancePercentagePPD,
            'ppd_id' => $ppd->id,
        ]);
    }

    public function export($ppd_id)
    {
        $ppd = District::find($ppd_id);
        $ppdName = $ppd->ppd;
        return Excel::download(new SchoolExport($ppd_id, $ppdName), 'list_of_schools.xlsx');
    }
}
