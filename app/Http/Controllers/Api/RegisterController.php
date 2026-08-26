<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Get user information to register in the d8p system
     * 
     * @bodyParam username string required The id of the user. Example: 9
     * @bodyParam password string The id of the room.
     * @responseFile storage/responses/register-success.json
     * @responseFile 200 storage/responses/register-success.json scenario="success"
     * @responseFile 200 storage/responses/register-failed-username-password.json scenario="wrong username or password"
     * @responseFile 422 storage/responses/register-missing-field.json scenario="missing field"
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'error' => $validator->errors()], 422);
        }
        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
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
                'message' => 'Please check username or password'
            ]);
        }
    }
}
