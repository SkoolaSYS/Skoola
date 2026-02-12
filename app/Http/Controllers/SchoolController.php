<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolGrade;
use App\Models\SchoolClass;
use App\Models\School;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\AdminStudentExport;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
{
    public function index(School $school, Attendance $attendance)
    {
        $students = Student::where('school_id', $school->id)->get();
        $totalPelajar = Student::where('school_id', $school->id)->count(); //count total students in 1 school
        $currentYear = now()->year;

        $attendancesAttend = Attendance::whereIn('student_id', $students->pluck('id'))
            ->where('status', 'attend')
            ->whereYear('created_at', $currentYear)
            ->groupBy('student_id')
            ->select('student_id', DB::raw('count(*) as total'))
            ->pluck('total', 'student_id');

        $attendancesAbsent = Attendance::whereIn('student_id', $students->pluck('id'))
            ->where('status', 'absent')
            ->whereYear('created_at', $currentYear)
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

    public function studentManagement($school_id)
{
    $school = School::findOrFail($school_id);
    $students = $school->allStudents; // or $school->student() relationship
    return view('school.students.management', compact('school', 'school_id', 'students'));
}

    public function classManagement()
{
    $schoolId = Auth::user()->school_id;

    // Predefined grades
    $defaultGrades = [
        'Darjah 1', 'Darjah 2', 'Darjah 3',
        'Darjah 4', 'Darjah 5', 'Darjah 6',
        'Tingkatan 1', 'Tingkatan 2', 'Tingkatan 3',
        'Tingkatan 4', 'Tingkatan 5', 'Tingkatan 6',
    ];

    // Grades stored in DB (including newly added special grades)
    $dbGrades = SchoolGrade::where('school_id', $schoolId)
                ->pluck('grade_name')
                ->toArray();

    // Merge and remove duplicates
    $allGrades = array_unique(array_merge($defaultGrades, $dbGrades));

    // Get activated grades
    $activatedGrades = SchoolGrade::where('school_id', $schoolId)
                        ->where('is_active', true)
                        ->pluck('grade_name')
                        ->toArray();

    return view('school.class_management', compact('allGrades', 'activatedGrades'));
}



    public function activateGrade(Request $request)
{
    $request->validate([
        'grade_name' => 'required|string'
    ]);

    $schoolId = Auth::user()->school_id;

    $grade = SchoolGrade::firstOrCreate(
        [
            'school_id' => $schoolId,
            'grade_name' => $request->grade_name,
        ]
    );

    // Always force activate
    $grade->is_active = 1;
    $grade->save();

    return back()->with('success', 'Grade activated successfully.');
}


    public function editClass()
    {
        $schoolId = Auth::user()->school_id;

        $activatedGrades = SchoolGrade::with('classes')
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        return view('school.edit_class', compact('activatedGrades'));
    }


    public function updateClassNames(Request $request)
{
    $schoolId = Auth::user()->school_id;

    // 🔵 Update existing class names
    if($request->has('class_name')) {
        foreach($request->class_name as $classId => $name) {
            $class = SchoolClass::find($classId);
            if($class && $class->grade->school_id == $schoolId) {
                $class->update(['class_name' => $name]);
            }
        }
    }

    // 🟢 Add new classes
    if($request->has('new_class')) {
        foreach($request->new_class as $gradeId => $newClasses) {
            foreach($newClasses as $name) {
                if(!empty($name)) {
                    SchoolClass::create([
                        'school_grade_id' => $gradeId,
                        'class_name' => $name
                    ]);
                }
            }
        }
    }

    // 🔴 Delete classes
    if($request->has('delete_class')) {
        foreach($request->delete_class as $classId) {
            $class = SchoolClass::find($classId);
            if($class && $class->grade->school_id == $schoolId) {
                $class->delete();
            }
        }
    }

    return redirect()->route('dashboard.school.edit_class')
        ->with('success', 'Class names updated successfully!');
}

public function deactivateGrade(Request $request)
{
    $request->validate([
        'grade_name' => 'required|string'
    ]);

    $schoolId = auth()->user()->school_id;

    $grade = SchoolGrade::where('school_id', $schoolId)
        ->where('grade_name', $request->grade_name)
        ->first();

    if ($grade) {
        $grade->is_active = false;
        $grade->save();

        return redirect()->back()->with('success', 'Grade deactivated successfully.');
    }

    return redirect()->back()->with('success', 'Grade not found.');
}

public function addGrade(Request $request)
{
    $request->validate([
        'grade_name' => 'required|string|max:50',
    ]);

    $schoolId = Auth::user()->school_id;

    $grade = SchoolGrade::firstOrCreate(
        ['school_id' => $schoolId, 'grade_name' => $request->grade_name],
        ['is_active' => true]
    );

    // Respond with JSON for AJAX
    return response()->json(['success' => 'Grade added successfully!']);
}







}
