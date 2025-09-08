<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\State;
use App\Models\District;
use App\Models\School;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StudentController extends Controller
{

    public function create()
{
    $states = State::all();
    $districts = District::all();
    $schools = School::all();

    // Only users who have the "parent" role
    $guardians = User::role('parent')->get();

    return view('student.add-student', compact("schools", "districts", "states", "guardians"));
}


    public function store(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'ic'            => 'required|string|max:255',
        'birth_cert_no' => 'nullable|string|max:255',
        'dob'           => 'nullable|date',
        'gender'        => 'nullable|string|in:Male,Female',
        'grade'         => 'nullable|string|max:255',
        'race'          => 'nullable|string|max:255',
        'religion'      => 'nullable|string|max:255',
        'nationality'   => 'nullable|string|max:255',
        'orphan'        => 'nullable|string|in:Yes,No',
        'address'       => 'nullable|string',
        'oku'           => 'nullable|string|in:Yes,No',
        'state'         => 'nullable|exists:states,id',
        'district'      => 'nullable|exists:districts,id',
        'school'        => 'nullable|exists:schools,id',
        'guardian_id'   => 'nullable|exists:users,id',
    ]);

    $student = new Student;
    $student->parent_id      = auth()->id();
    $student->name           = $request->name;
    $student->ic             = $request->ic;
    $student->birth_cert_no  = $request->birth_cert_no;
    $student->dob            = $request->dob;
    $student->gender         = $request->gender;
    $student->grade          = $request->grade;
    $student->race           = $request->race;
    $student->religion       = $request->religion;
    $student->nationality    = $request->nationality;
    $student->orphan         = $request->orphan ?? 'No';
    $student->address        = $request->address;
    $student->oku            = $request->oku ?? 'No';

    if ($request->filled('state')) {
        $student->state()->associate(State::find($request->state));
    }
    if ($request->filled('district')) {
        $student->district()->associate(District::find($request->district));
    }
    if ($request->filled('school')) {
        $student->school()->associate(School::find($request->school));
    }

    if ($request->dob) {
        $student->age = \Carbon\Carbon::parse($request->dob)->age;
    } elseif ($birthdate = $this->extractBirthDateFromIC($request->ic)) {
        $student->age = $this->calculateAge($birthdate);
    }

    $student->save();

    // === Guardian linking ===
    $mainParentId = auth()->id();

    // Step 1: always include logged-in parent
    $idsToAttach = [$mainParentId];

    // Step 2: find other parents already linked to this parent via existing students
    $otherGuardianIds = \DB::table('parent_student')
        ->whereIn('student_id', function($query) use ($mainParentId) {
            $query->select('student_id')
                  ->from('parent_student')
                  ->where('parent_id', $mainParentId);
        })
        ->where('parent_id', '!=', $mainParentId)
        ->pluck('parent_id')
        ->toArray();

    $idsToAttach = array_merge($idsToAttach, $otherGuardianIds);

    // Step 3: Attach without duplicates
    $student->guardians()->syncWithoutDetaching($idsToAttach);

    return redirect('/student')->with('success', 'Student added successfully');
}





    public function show()
{
    $user = Auth::user();

    // students linked to this parent
    $students = $user->students;

    foreach ($students as $student) {
        $ic = $student->ic;
        $birthdate = $this->extractBirthDateFromIC($ic);
        $student->age = $birthdate ? $this->calculateAge($birthdate) : null;
    }

    return view('student.view-list-student', compact('students'));
}




    // Helper function to extract birthdate from IC
    private function extractBirthDateFromIC($ic)
    {
        if (empty($ic) || strlen($ic) < 6) {
            return null;
        }

        $year = substr($ic, 0, 2);
        $month = (int)substr($ic, 2, 2);
        $day = (int)substr($ic, 4, 2);

        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return null;
        }

        $birthYear = (int)("20" . $year);

        return Carbon::createFromDate($birthYear, $month, $day);
    }

    // Helper function to calculate age based on birthdate
    private function calculateAge($birthdate)
    {
        if (!$birthdate) {
            return null;
        }
        $currentDate = Carbon::now();
        return $birthdate->diffInYears($currentDate);
    }

    public function edit(Student $student)
    {
        $state = $student->state;
        $district = $student->districts;
        $school = $student->school;

        return view('student.edit', compact("student", "state", "district", "school"));
    }

    public function update(Request $request, Student $student)
{
    // Step 1: Debug incoming data
    //dd($request->all()); // <--- this will stop here and show all submitted form values

    $request->validate([
        'name'          => 'required|string|max:255',
        'ic'            => 'required|string|max:255',
        'birth_cert_no' => 'nullable|string|max:255',
        'dob'           => 'nullable|date',
        'gender'        => 'nullable|string|in:Male,Female',
        'grade'         => 'nullable|string|max:255',
        'race'          => 'nullable|string|max:255',
        'religion'      => 'nullable|string|max:255',
        'nationality'   => 'nullable|string|max:255',
        'orphan'        => 'nullable|string|in:Yes,No',
        'address'       => 'nullable|string',
        'oku'           => 'nullable|string|in:Yes,No',
        'state'         => 'nullable|exists:states,id',
        'district'      => 'nullable|exists:districts,id',
        'school'        => 'nullable|exists:schools,id',
    ]);

    // Step 2: Debug student before update
    // dd($student->toArray());

    $student->fill([
        'name'        => $request->name,
        'ic'          => $request->ic,
        'birth_cert_no' => $request->birth_cert_no,
        'dob'         => $request->dob,
        'gender'      => $request->gender,
        'grade'       => $request->grade,
        'race'        => $request->race,
        'religion'    => $request->religion,
        'nationality' => $request->nationality,
        'address'     => $request->address,
        'orphan' => $request->orphan, // will save "Yes" or "No"
        'oku'    => $request->oku,    // will save "Yes" or "No"
        'state_id'    => $request->state,
        'district_id' => $request->district,
        'school_id'   => $request->school,
    ]);

    // Step 3: Debug filled data
    // dd($student->toArray());

    if ($request->dob) {
        $student->age = \Carbon\Carbon::parse($request->dob)->age;
    }

    $student->save();

    // Step 4: Debug after save
    // dd($student->toArray());

    return redirect('/student')->with('success', 'Student updated successfully');
}



    public function delete($id)
{
    $student = Student::find($id);

    if ($student) {
        // Detach all guardians first
        $student->guardians()->detach();

        // Now delete the student
        $student->delete();

        return redirect('/student')->with('success', 'Student deleted successfully');
    } else {
        return redirect('/student')->with('error', 'Student not found');
    }
}

}
