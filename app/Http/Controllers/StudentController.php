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

class StudentController extends Controller
{

    public function create()
    {
        $states = State::all();
        $districts = School::all();
        $schools = School::all();

        return view('student.add-student', compact("schools", "districts", "states"));
    }

    public function store(Request $request)
    {
        $student = new Student;
        $student->parent_id = auth()->id();

        $student->name = $request->name;
        $student->ic = $request->ic;
        $birthdate = $this->extractBirthDateFromIC($request->ic);
        if ($birthdate) {
            $age = $this->calculateAge($birthdate);
            $student->age = $age;
        }

        // Get the selected state, city, and postcode IDs from the form
        $stateId = $request->input('state');
        $districtId = $request->input('district');
        $schoolId = $request->input('school');

        // Check if the IDs are not null, and then update the user's foreign key fields
        if (!is_null($stateId)) {
            $state = State::find($stateId);
            $student->state()->associate($state);
        }

        if (!is_null($districtId)) {
            $district = District::find($districtId);
            $student->district()->associate($district);
        }

        if (!is_null($schoolId)) {
            $school = School::find($schoolId);
            $student->school()->associate($school);
        }

        $student->save();

        return redirect('/student');
    }

    public function show()
    {
        $user = Auth::user();
        $students = Student::where('parent_id', $user->id)->get();
        // Calculate age for each student
        foreach ($students as $student) {
            $ic = $student->ic;
            $birthdate = $this->extractBirthDateFromIC($ic);
            $age = $birthdate ? $this->calculateAge($birthdate) : null;
            $student->age = $age;
        }

        return view('student.view-list-student', ['students' => $students]);
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

    public function update(Student $student, Request $req)
    {
            $student->name = $req->name;
            $student->ic = $req->ic;
            $stateId = $req->input('state');
            $districtId = $req->input('district');
            $schoolId = $req->input('school');

            // Check if the IDs are not null, and then update the user's foreign key fields
            if (!is_null($stateId)) {
                $state = State::find($stateId);
                $student->state()->associate($state);
            }

            if (!is_null($districtId)) {
                $district = District::find($districtId);
                $student->district()->associate($district);
            }

            if (!is_null($schoolId)) {
                $school = School::find($schoolId);
                $student->school()->associate($school);
            }

            $student->save();

            return redirect('/student')->with('success', 'Student updated successfully');
    }

    public function delete($id)
    {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return redirect('/student')->with('success', 'Student deleted successfully');
        } else {
            return redirect('/student')->with('error', 'Student not found');
        }
    }
}
