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
    $selectedGrade = $request->grade ?? null;
    $selectedClass = $request->class_name ?? null;
    $selectedSubject = $request->subject ?? null;

    $allClasses = Student::distinct()->pluck('class_name');
    $students = collect();
    $attendanceRecords = [];
    $subjectOrder = [];

    if ($selectedGrade && $selectedClass) {
        // Get all students for this class and grade
        $students = Student::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->orderBy('name')
            ->get();

        $today = \Carbon\Carbon::today();

        // ✅ Get all attendance records for this class and grade for today
        $todayAttendances = ClassAttendance::where('grade', $selectedGrade)
            ->where('class_name', $selectedClass)
            ->whereDate('attendance_time', $today)
            ->orderBy('attendance_time', 'asc')
            ->get();

        // ✅ Determine the order of subjects (1, 2, 3, etc.)
        $subjects = $todayAttendances->pluck('subject')->unique()->values();

        foreach ($subjects as $index => $subject) {
            $subjectOrder[$subject] = $index + 1;
        }

        // ✅ Create a simple lookup: $attendanceRecords[student_id][subject] = status
        foreach ($todayAttendances as $record) {
            $attendanceRecords[$record->student_id][$record->subject] = $record->status;
        }
    }

    return view('class_attendance.index', compact(
        'allClasses',
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

    // 🟢 Get existing attendance data
    $existingAttendance = \App\Models\ClassAttendance::where('grade', $selectedGrade)
        ->where('class_name', $selectedClass)
        ->where('subject', $selectedSubject)
        ->pluck('status', 'student_id'); // key = student_id, value = status

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
        // Check if attendance already exists for this student, class, subject, and date
        $existing = ClassAttendance::where('student_id', $studentId)
            ->where('grade', $request->grade)
            ->where('class_name', $request->class_name)
            ->where('subject', $request->subject)
            ->whereDate('attendance_time', $todayDate)
            ->first();

        if ($existing) {
            // Update only status (don’t overwrite teacher_id)
            $existing->update(['status' => $status]);
        } else {
            // Create new record if not exist
            ClassAttendance::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'grade' => $request->grade,
                'class_name' => $request->class_name,
                'subject' => $request->subject,
                'status' => $status,
                'attendance_time' => now(),
            ]);
        }
    }

    // Redirect back to the attendance page with current filters
    return redirect()->route('class_attendance.index', [
        'grade' => $request->grade,
        'class_name' => $request->class_name,
        'subject' => $request->subject,
    ])->with('success', 'Attendance saved successfully!');
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
    $grade = $request->grade;
    $class_name = $request->class_name;
    $subject = $request->subject;

    // Get students
    $students = Student::where('grade', $grade)
        ->where('class_name', $class_name)
        ->get();

    // Get attendance records
    $attendanceRecords = ClassAttendance::where('grade', $grade)
        ->where('class_name', $class_name)
        ->where('subject', $subject)
        ->where('teacher_id', auth()->id())
        ->get()
        ->groupBy('student_id');

    // Convert records into easier format
    $records = [];
    foreach ($students as $student) {
        $records[$student->id] = $attendanceRecords[$student->id][0]->status ?? '-';
    }

    $pdf = Pdf::loadView('pdf.class_attendance', [
        'grade' => $grade,
        'class_name' => $class_name,
        'subject' => $subject,
        'students' => $students,
        'records' => $records,
        'teacher' => auth()->user()->name,
        'date' => now()->format('d/m/Y'),
    ])->setPaper('A4', 'portrait');

    return $pdf->stream("Class_Attendance_{$class_name}_{$subject}.pdf");
}










}
