<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Get student information
     * 
     * @urlParam student_id integer required The ID of the student.
     * @responseFile 200 storage/responses/get-student-success.json scenario="success get student information"
     * @responseFile 200 storage/responses/get-student-failed.json scenario="success not exist"
     */
    public function index($student_id)
    {
        $student = Student::find($student_id);
        if ($student) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'nationalId' => $student->ic,
                    'state' => $student->state?->name,
                    'school' => $student->school?->name,
                ],
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'student not exist'
            ]);
        }
    }
}
