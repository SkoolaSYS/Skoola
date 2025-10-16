<?php

namespace App\Http\Controllers;

use App\Models\ClassAttendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClassAttendanceController extends Controller
{
    /**
     * Show grouped timetable by class and grade
     */
    public function index(Request $request)
{
    $selectedGrade = $request->grade ?? null;
    $selectedClass = $request->class_name ?? null;
    $selectedSubject = $request->subject ?? null;

    // Get all distinct classes from students table
    $allClasses = Student::distinct()->pluck('class_name');

    $students = [];
    $attendanceRecords = [];

    if ($selectedGrade && $selectedClass && $selectedSubject) {
        // Get students in this class and grade
        $students = Student::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->get();

        // Get attendance for today for these students, this class & subject
        $today = Carbon::today();
        $records = ClassAttendance::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->where('subject', $selectedSubject)
            ->whereDate('attendance_time', $today)
            ->get();

        // Map attendance by student_id for easy lookup in Blade
        foreach ($records as $record) {
            $attendanceRecords[$record->student_id] = $record->status;
        }
    }

    return view('class_attendance.index', compact(
        'allClasses',
        'students',
        'attendanceRecords',
        'selectedGrade',
        'selectedClass',
        'selectedSubject'
    ));
}





    /**
     * Show attendance for a specific class
     */
    public function show($classId)
    {
        // Get the class (if you don’t have teacher table, remove 'teacher')
        $class = ClassAttendance::findOrFail($classId);

        // Students in this class
        $students = Student::where('class_name', $class->class_name)
            ->where('grade', $class->grade)
            ->get();

        // Past attendance records
        $attendances = ClassAttendance::with('student')
            ->whereIn('student_id', $students->pluck('id'))
            ->orderBy('attendance_time', 'desc')
            ->get();

        return view('class_attendance.show', [
            'class' => $class,
            'students' => $students,
            'attendances' => $attendances
        ]);
    }

    /**
     * Store new attendance record
     */
    public function store(Request $request)
{
    $request->validate([
        'grade' => 'required|string',
        'class_name' => 'required|string',
        'subject' => 'required|string',
        'attendance' => 'required|array',
        'attendance.*' => 'required|string',
    ]);

    foreach ($request->attendance as $studentId => $status) {
        ClassAttendance::updateOrCreate(
            [
                'student_id' => $studentId,
                'grade' => $request->grade,
                'class_name' => $request->class_name,
                'subject' => $request->subject,
                'attendance_time' => now()->toDateString(), // only use date for lookup
            ],
            [
                'status' => $status,
                'teacher_id' => Auth::id(),
                'attendance_time' => now(), // full current timestamp
            ]
        );
    }

    return redirect()
        ->route('teacher.dashboard') // change to your actual dashboard route name
        ->with('success', 'Attendance saved successfully!');
}










}
