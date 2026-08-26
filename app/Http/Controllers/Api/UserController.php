<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get parent information
     * 
     * @urlParam user_id integer required The ID of the user.
     * @responseFile 200 storage/responses/get-user-success.json scenario="success get user information"
     * @responseFile 200 storage/responses/get-user-failed.json scenario="user not exist"
     */
    public function index($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $students = Student::where('parent_id', $user->id)->get();
            $studentsData = [];
            foreach ($students as $student) {
                $studentsData[] = [
                    'id' => $student->id,
                    'name' => $student->name,
                    'nationalId' => $student->ic,
                    'state' => $student->state?->name,
                    'school' => $student->school?->name,
                ];
            }
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->phone_num,
                    'nationalId' => $user->ic,
                    'students' => $studentsData
                ],
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'user not exist'
            ]);
        }
    }
}
