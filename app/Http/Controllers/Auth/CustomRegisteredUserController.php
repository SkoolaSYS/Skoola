<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegisteredUserController;
use App\Models\Student;

class CustomRegisteredUserController extends FortifyRegisteredUserController
{
    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
    {
        $user = $creator->create($request->all());

        // Assign the parent role to the newly registered user
        $user->assignRole('parent');
        
        Auth::login($user);

        // Save all students from the registration form
        if ($request->has('students')) {
            foreach ($request->input('students') as $studentData) {
                $student = new Student();
                $student->parent_id = $user->id;
                $student->name = $studentData['name'] ?? null;
                $student->ic = $studentData['ic'] ?? null;
                $student->state_id = $studentData['state'] ?? null;
                $student->district_id = $studentData['district'] ?? null;
                $student->school_id = $studentData['school'] ?? null;
                // Optionally calculate age if needed
                $student->save();
            }
        }

        // Redirect only parents to student form
        if ($user->hasRole('parent')) {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect('/student/create');
                }
            };
        }

        // Default redirect for other roles
        return app(RegisterResponse::class);
    }
}
