<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\School;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\AdminStudentExport;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    public function index(School $school, Attendance $attendance)
    {
        $students = Student::where('school_id', $school->id)->get();
        $totalPelajar = Student::where('school_id', $school->id)->count(); //count total students in 1 school

        $attendancesAttend = Attendance::whereIn('student_id', $students->pluck('id'))
            ->where('status', 'attend')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');

        $attendancesAbsent = Attendance::whereIn('student_id', $students->pluck('id'))
            ->where('status', 'absent')
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');

        $totalAttendances = [];
        $attendancePercentage = [];
        $formattedAttendancePercentage = [];

        $totalPercentageAttendancesSchool = 0;
        $attendancePercentageSchool = 0;
        $formattedAttendancePercentageSchool = 0;

        $user = auth()->user();
        if ($user->hasRole('admin|country|state|ppd')) {
            foreach ($attendancesAttend as $key => $attendCount) {
                $absentCount = isset($attendancesAbsent[$key]) ? $attendancesAbsent[$key] : 0;

                $totalAttendances[$key] = $attendCount + $absentCount;
                $attendancePercentage[$key] = ($attendancesAttend[$key] / $totalAttendances[$key]) * 100;
                $formattedAttendancePercentage[$key] = number_format($attendancePercentage[$key], 1); // percentage for each student in 1 school
            }


            foreach ($attendancePercentage as $attendance) {
                $totalPercentageAttendancesSchool += $attendance; //sum of all students attend value 
                $attendancePercentageSchool = (($totalPercentageAttendancesSchool / 100) / $totalPelajar) * 100;
                $formattedAttendancePercentageSchool = number_format($attendancePercentageSchool, 1); // avg percentage 1 school

            }
        } else if ($user->hasRole('school') && $user->getMeta('user_school_id') == $school->id) {
            foreach ($attendancesAttend as $key => $attendCount) {
                $absentCount = isset($attendancesAbsent[$key]) ? $attendancesAbsent[$key] : 0;

                $totalAttendances[$key] = $attendCount + $absentCount;                $attendancePercentage[$key] = ($attendancesAttend[$key] / $totalAttendances[$key]) * 100;
                $formattedAttendancePercentage[$key] = number_format($attendancePercentage[$key], 1); // percentage for each student in 1 school
            }


            foreach ($attendancePercentage as $attendance) {
                $totalPercentageAttendancesSchool += $attendance; //sum of all students attend value 
                $attendancePercentageSchool = (($totalPercentageAttendancesSchool / 100) / $totalPelajar) * 100;
                $formattedAttendancePercentageSchool = number_format($attendancePercentageSchool, 1); // avg percentage 1 school

            }
        } else {
            return abort(403, 'USER DOES NOT HAVE THE RIGHT ROLES.');
        }

        return view('school.dashboard', [
            'students' => $students,
            'school' => $school,
            'totalPelajar' => $totalPelajar,
            'formattedAttendancePercentage' => $formattedAttendancePercentage,
            'formattedAttendancePercentageSchool' => $formattedAttendancePercentageSchool,
            'school_id' => $school->id,
        ]);
    }

    public function showStudentAttendance($studentId)
    {
        $student = Student::findOrFail($studentId);

        $attendanceList = Attendance::where('student_id', $studentId)
            ->orderByDesc('id')
            ->get();

        $attendancesAbsent = Attendance::where('student_id', $studentId)
            ->where('status', 'absent')
            ->count();

        $attendancesAttend = Attendance::where('student_id', $studentId)
            ->where('status', 'attend')
            ->count();

        $attendanceData = json_encode([$attendancesAttend, $attendancesAbsent]);

        return view('school.student-attendance', compact('attendanceData', 'student', 'attendanceList'));
    }

    public function export($school_id)
{
    $school = School::find($school_id);
    $schoolName = $school->name;

    // Get current app language
    $lang = app()->getLocale(); // 'ms' or 'en'

    // Pass the language to the export class
    return Excel::download(
        new AdminStudentExport($school_id, $schoolName, $lang),
        'list_of_students.xlsx'
    );
}

}
