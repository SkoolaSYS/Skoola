<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
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
    $teacher = auth()->user();
    $schoolId = $teacher->school_id; // the teacher's school

    $selectedGrade = $request->grade ?? null;
    $selectedClass = $request->class_name ?? null;
    $selectedSubject = $request->subject ?? null;

    // ✅ Get all grades added by this school
    $grades = \App\Models\SchoolGrade::where('school_id', $schoolId)
        ->orderBy('id')
        ->pluck('grade_name');

    // ✅ Get all classes added by this school
    $classes = \App\Models\SchoolClass::whereHas('grade', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })
        ->with('grade')
        ->orderBy('class_name')
        ->get();

    $students = collect();
    $attendanceRecords = [];
    $subjectOrder = [];

    if ($selectedGrade && $selectedClass) {
        // Get students for this class and grade
        $students = \App\Models\Student::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->orderBy('name')
            ->get();

        $today = \Carbon\Carbon::today();

        // Get all attendance records for this class and grade for today
        $todayAttendances = \App\Models\ClassAttendance::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->whereDate('attendance_time', $today)
            ->orderBy('attendance_time', 'asc')
            ->get();

        // Determine the order of subjects
        $subjects = $todayAttendances->pluck('subject')->unique()->values();

        foreach ($subjects as $index => $subject) {
            $subjectOrder[$subject] = $index + 1;
        }

        // Lookup: $attendanceRecords[student_id][subject] = status
        foreach ($todayAttendances as $record) {
            $attendanceRecords[$record->student_id][$record->subject] = $record->status;
        }
    }

    return view('class_attendance.index', compact(
        'grades',
        'classes',
        'students',
        'attendanceRecords',
        'selectedGrade',
        'selectedClass',
        'selectedSubject',
        'subjectOrder'
    ));
}


public function edit(Request $request)
{
    $selectedGrade = $request->query('grade');
    $selectedClass = $request->query('class_name');
    $selectedSubject = $request->query('subject');

    if (!$selectedGrade || !$selectedClass || !$selectedSubject) {
        return redirect()
            ->route('class_attendance.index')
            ->with('error', 'Please select grade, class, and subject first.');
    }

    // Get students
    $students = Student::where('grade', $selectedGrade)
        ->where('class_name', $selectedClass)
        ->orderBy('name')
        ->get();

    // Get existing attendance records for today (🟢 removed teacher_id restriction)
    $today = now()->toDateString();
    $attendanceRecords = ClassAttendance::where('grade', $selectedGrade)
        ->where('class_name', $selectedClass)
        ->where('subject', $selectedSubject)
        ->whereDate('attendance_time', $today)
        ->pluck('status', 'student_id')
        ->toArray();

    return view('class_attendance.edit', compact(
        'selectedGrade',
        'selectedClass',
        'selectedSubject',
        'students',
        'attendanceRecords'
    ));
}


public function updateAttendance(Request $request)
{
    $request->validate([
        'grade' => 'required|string',
        'class_name' => 'required|string',
        'subject' => 'required|string',
        'attendance' => 'required|array',
        'attendance.*' => 'required|string',
    ]);

    $teacherId = Auth::id();
    $todayDate = now()->toDateString();

    foreach ($request->attendance as $studentId => $status) {
        ClassAttendance::where('student_id', $studentId)
            ->where('grade', $request->grade)
            ->where('class_name', $request->class_name)
            ->where('subject', $request->subject)
            ->whereDate('attendance_time', $todayDate)
            ->where('teacher_id', $teacherId)
            ->update([
                'status' => $status,
                'attendance_time' => now(),
            ]);
    }

    return redirect()
        ->route('class_attendance.index', [
            'grade' => $request->grade,
            'class_name' => $request->class_name,
            'subject' => $request->subject,
        ])
        ->with('success', 'Attendance updated successfully!');
}




public function add(Request $request)
{
    $selectedGrade = $request->query('grade');
    $selectedClass = $request->query('class_name');
    $selectedSubject = $request->query('subject');

    if (!$selectedGrade || !$selectedClass || !$selectedSubject) {
        return redirect()
            ->route('class_attendance.index')
            ->with('error', 'Please select grade, class, and subject first.');
    }

    // Get students for the selected class and grade
    $students = Student::where('grade', $selectedGrade)
        ->where('class_name', $selectedClass)
        ->orderBy('name')
        ->get();

    $today = Carbon::today();

    // 🟢 Get existing attendance for the same class & grade (ignore subject)
    $existingAttendance = \App\Models\ClassAttendance::where('grade', $selectedGrade)
        ->where('class_name', $selectedClass)
        ->whereDate('attendance_time', $today)
        ->pluck('status', 'student_id')
        ->toArray();

    // 🟢 Show same attendance for all subjects today
    return view('class_attendance.add', compact(
        'selectedGrade',
        'selectedClass',
        'selectedSubject',
        'students',
        'existingAttendance'
    ));
}





/**
 * Save attendance records for a specific class and subject
 */
public function saveClassAttendance(Request $request)
{
    $request->validate([
        'grade' => 'required|string',
        'class_name' => 'required|string',
        'subject' => 'required|string',
        'attendance' => 'required|array',
        'attendance.*' => 'required|string',
    ]);

    $teacherId = Auth::id();
    $todayDate = now()->toDateString();

    foreach ($request->attendance as $studentId => $status) {
        // ✅ Only differentiate by grade, class, subject, and date
        $existing = ClassAttendance::where('student_id', $studentId)
            ->where('grade', $request->grade)
            ->where('class_name', $request->class_name)
            ->where('subject', $request->subject)
            ->whereDate('attendance_time', $todayDate)
            ->first();

        if ($existing) {
            $existing->update(['status' => $status]);
        } else {
            ClassAttendance::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'grade' => $request->grade,
                'class_name' => $request->class_name,
                'subject' => $request->subject,
                'status' => $status ?? 'Present',
                'attendance_time' => now(),
            ]);
        }
    }

    return redirect()
        ->route('class_attendance.index', [
            'grade' => $request->grade,
            'class_name' => $request->class_name,
            'subject' => $request->subject,
        ])
        ->with('success', 'Attendance saved successfully!');
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

public function downloadPdf(Request $request)
{
    $grade = trim($request->grade);
    $class_name = trim($request->class_name);

    // Get all students for this class & grade
    $students = Student::where('grade', $grade)
        ->where('class_name', $class_name)
        ->orderBy('name')
        ->get();

    // Get all attendance records for today for this class
    $today = now()->toDateString();
    $attendances = ClassAttendance::where('grade', $grade)
        ->where('class_name', $class_name)
        ->whereDate('attendance_time', $today)
        ->where('teacher_id', auth()->id())
        ->get();

    // Determine all subjects for today
    $subjects = $attendances->pluck('subject')->unique()->values()->toArray();

    // Build attendance lookup: $attendanceRecords[student_id][subject] = status
    $attendanceRecords = [];
    foreach ($attendances as $record) {
        $attendanceRecords[$record->student_id][$record->subject] = $record->status;
    }

    // Load PDF view
    $pdf = Pdf::loadView('pdf.class_attendance', [
        'grade' => $grade,
        'class_name' => $class_name,
        'students' => $students,
        'attendanceRecords' => $attendanceRecords,
        'subjects' => $subjects,
        'teacher' => auth()->user()->name,
        'date' => now()->format('d/m/Y'),
    ])->setPaper('A4', 'landscape'); // use landscape if many subjects

    return $pdf->stream("Class_Attendance_{$class_name}.pdf");
}












}
