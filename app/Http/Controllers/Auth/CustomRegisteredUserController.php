<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Http\Controllers\RegisteredUserController as FortifyRegisteredUserController;
use App\Models\Student;
use Carbon\Carbon;

class CustomRegisteredUserController extends FortifyRegisteredUserController
{
    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
{
    // Create main parent
    $user = $creator->create($request->all());
    $user->assignRole('parent');
    Auth::login($user);

    // Save additional guardians
    if ($request->has('guardians')) {
        foreach ($request->input('guardians') as $index => $guardianData) {
            if ($index === 0) continue; // skip main parent
            if (empty($guardianData['name'])) continue;

            $guardian = new \App\Models\User();
            $guardian->name = $guardianData['name'];
            $guardian->username = $guardianData['username'] ?? null;
            $guardian->email = $guardianData['email'] ?? null;
            $guardian->phone_num = $guardianData['phone_num'] ?? null;
            $guardian->ic = $guardianData['ic'] ?? null;
            $guardian->relationship = $guardianData['relationship'] ?? null;
            $guardian->occupation = $guardianData['occupation'] ?? null;
            $guardian->address = $guardianData['address'] ?? null;
            if (!empty($guardianData['password'])) {
    $guardian->password = Hash::make($guardianData['password']);
}
            $guardian->assignRole('parent');
            $guardian->save();
        }
    }

    // Save students
    if ($request->has('students')) {
        foreach ($request->input('students') as $studentData) {
            $student = new Student();
            $student->parent_id = $user->id;
            $student->name = $studentData['name'] ?? null;
            $student->ic = $studentData['ic'] ?? null;
            $student->birth_cert_no = $studentData['birth_cert_no'] ?? null;
            $student->dob = $studentData['dob'] ?? null;
            $student->age = !empty($studentData['dob']) ? Carbon::parse($studentData['dob'])->age : null;
            $student->grade = $studentData['grade'] ?? null;
            $student->gender = $studentData['gender'] ?? null;
            $student->race = $studentData['race'] ?? null;
            $student->religion = $studentData['religion'] ?? null;
            $student->nationality = $studentData['nationality'] ?? null;
            $student->orphan = $studentData['orphan'] ?? null;
            $student->address = $studentData['address'] ?? null;
            $student->oku = $studentData['oku'] ?? null;
            $student->state_id = $studentData['state'] ?? null;
            $student->district_id = $studentData['district'] ?? null;
            $student->school_id = $studentData['school'] ?? null;
            $student->save();
        }
    }

    // Redirect parent
    if ($user->hasRole('parent')) {
        return new class implements RegisterResponse {
            public function toResponse($request)
            {
                return redirect('/student');
            }
        };
    }

    return app(RegisterResponse::class);
}

}
