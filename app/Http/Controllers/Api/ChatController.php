<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        $questionId = $request->question_id;
        $guardianId = $request->guardian_id;
        $teacherId = $request->teacher_id;

        if ($questionId == 1) {
            return response()->json([
                'reply' => 'Yes, there is school tomorrow.'
            ]);
        }

        if ($questionId == 2) {

                $guardian = Guardian::find($guardianId);

                if (!$guardian || !$guardian->student_id) {
                    return response()->json([
                        'reply' => 'No student is linked to this parent.'
                    ]);
                }

                $attendance = Attendance::where('student_id', $guardian->student_id)
                ->whereDate('created_at', today())
                ->first();

            if ($attendance) {
                return response()->json([
                    'reply' => 'Yes, your child arrived at ' .
                    date('h:i A', strtotime($attendance->check_in))
                ]);
            }

            return response()->json([
                'reply' => 'No attendance has been recorded today.'
            ]);
        }

        if ($questionId == 'homeroom_teacher') {

    $guardian = Guardian::find($guardianId);

    if (!$guardian || !$guardian->student_id) {
        return response()->json([
            'reply' => 'No student is linked to this parent.'
        ]);
    }

    $student = Student::find($guardian->student_id);

    $student = Student::find($guardian->student_id);

if (!$student || !$student->class_name) {
    return response()->json([
        'reply' => 'No class is linked to this student.'
    ]);
}

$class = DB::table('school_classes')
    ->where('class_name', $student->class_name)
    ->first();

if (!$class) {
    return response()->json([
        'reply' => 'Class not found: ' . $student->class_name
    ]);
}

$homeroomTeacher = DB::table('class_user')
    ->join('users', 'class_user.user_id', '=', 'users.id')
    ->where('class_user.school_class_id', $class->id)
    ->select('users.id', 'users.name', 'users.email')
    ->first();

    if (!$homeroomTeacher) {
        return response()->json([
            'reply' => 'No homeroom teacher is assigned for this class.'
        ]);
    }

    return response()->json([
        'reply' => 'Your child\'s homeroom teacher is ' . $homeroomTeacher->name .
                   ' for class ' . ($class->name ?? $student->school_class_id) . '.'
    ]);
}

if ($questionId == 'subject_teacher') {

    $guardian = Guardian::find($guardianId);

    if (!$guardian || !$guardian->student_id) {
        return response()->json([
            'reply' => 'No student is linked to this parent.'
        ]);
    }

    $subjects = DB::table('class_attendances')
        ->where('student_id', $guardian->student_id)
        ->whereNotNull('subject')
        ->select('subject')
        ->distinct()
        ->orderBy('subject')
        ->get();

    if ($subjects->isEmpty()) {
        return response()->json([
            'reply' => 'No subjects found for your child.'
        ]);
    }

    return response()->json([
        'reply' => 'Please choose a subject.',
        'subjects' => $subjects
    ]);
}

        $apiKey = env('AI_API_KEY');

if (!$apiKey) {
    return response()->json([
        'reply' => 'Please contact the school office for this question.'
    ]);
}

$response = Http::withHeaders([
    'Content-Type' => 'application/json',
])->post(
    'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey,
    [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => 'You are a school chatbot for parents. Answer briefly and politely. Question: ' . $questionId
                    ]
                ]
            ]
        ]
    ]
);

$data = $response->json();

return response()->json([
    'reply' => $data['candidates'][0]['content']['parts'][0]['text']
        ?? 'Please contact the school office for this question.'
]);
    }
}
