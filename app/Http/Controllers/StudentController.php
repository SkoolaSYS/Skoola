<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\State;
use App\Models\District;
use App\Models\School;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use App\Exports\StudentsTemplateExport;

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
    // ✅ Validation for multiple students
    $request->validate([
        'students.*.name'        => 'required|string|max:255',
        'students.*.ic'          => 'required|string|max:255',
        'students.*.birth_cert_no' => 'nullable|string|max:255',
        'students.*.dob'         => 'nullable|date',
        'students.*.gender'      => 'nullable|string|in:Male,Female',
        'students.*.grade'       => 'nullable|string|max:255',
        'students.*.race'        => 'nullable|string|max:255',
        'students.*.religion'    => 'nullable|string|max:255',
        'students.*.nationality' => 'nullable|string|max:255',
        'students.*.orphan'      => 'nullable|string|in:Yes,No',
        'students.*.address'     => 'nullable|string',
        'students.*.oku'         => 'nullable|string|in:Yes,No',
        'students.*.state'       => 'nullable|exists:states,id',
        'students.*.district'    => 'nullable|exists:districts,id',
        'students.*.school'      => 'nullable|exists:schools,id',
    ]);

    $mainParentId = auth()->id();
    $guardians = [$mainParentId]; // attach logged-in parent automatically

    // save each student
    foreach ($request->students as $studentData) {
        $student = new \App\Models\Student();
        $student->name          = $studentData['name'];
        $student->ic            = $studentData['ic'];
        $student->birth_cert_no = $studentData['birth_cert_no'] ?? null;
        $student->dob           = $studentData['dob'] ?? null;
        $student->age           = !empty($studentData['dob']) ? \Carbon\Carbon::parse($studentData['dob'])->age : null;
        $student->grade         = $studentData['grade'] ?? null;
        $student->gender        = $studentData['gender'] ?? null;
        $student->race          = $studentData['race'] ?? null;
        $student->religion      = $studentData['religion'] ?? null;
        $student->nationality   = $studentData['nationality'] ?? null;
        $student->orphan        = $studentData['orphan'] ?? 'No';
        $student->address       = $studentData['address'] ?? null;
        $student->oku           = $studentData['oku'] ?? 'No';
        $student->state_id      = $studentData['state'] ?? null;
        $student->district_id   = $studentData['district'] ?? null;
        $student->school_id     = $studentData['school'] ?? null;

        $student->save();

        // attach parent(s)
        $student->guardians()->attach($guardians);
    }

    return redirect()->route('student.show')
                     ->with('success', 'Student(s) added successfully');
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

public function downloadTemplate()
{
    // Generate a simple Excel template
    return Excel::download(new StudentsTemplateExport, 'student_template.xlsx');
}

public function import(Request $request)
{
    $request->validate([
        'import_file' => 'required|file|mimes:xlsx,xls',
        'school_id' => 'required|integer|exists:schools,id',
    ]);

    Excel::import(new StudentsImport($request->school_id), $request->file('import_file'));

    return redirect()->back()->with('success', 'Students imported successfully!');
}


public function schoolCreate(Request $request)
{
    $school_id = $request->school_id;
    $school = School::findOrFail($school_id);

    return view('school.students.create', compact('school'));
}

public function schoolStore(Request $request)
{
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'ic'             => 'required|string|max:20',
        'birth_cert_no'  => 'nullable|string|max:50',
        'dob'            => 'nullable|date',
        'gender'         => 'required|string|max:10',
        'race'           => 'nullable|string|max:50',
        'religion'       => 'nullable|string|max:50',
        'nationality'    => 'nullable|string|max:50',
        'orphan'         => 'nullable|string|max:10',
        'oku'            => 'nullable|string|max:10',
        'address'        => 'nullable|string|max:255',
        'grade'          => 'required|string|max:50',
        'class_name'     => 'required|string|max:50',
        'school_id'      => 'required|integer',
    ]);

    // Auto-calculate age if dob is provided
    if (!empty($validated['dob'])) {
        $validated['age'] = \Carbon\Carbon::parse($validated['dob'])->age;
    }

    $student = new \App\Models\Student();
    $student->fill($validated);
    $student->save();

    return redirect()
        ->route('dashboard.school', ['school' => $request->school_id])
        ->with('success', 'Student added successfully.');
}


public function schoolEdit(Student $student)
{
    return view('school.students.edit', compact('student'));
}

public function schoolUpdate(Request $request, Student $student)
{
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'ic'             => 'required|string|max:20',
        'birth_cert_no'  => 'nullable|string|max:50',
        'dob'            => 'nullable|date',
        'gender'         => 'required|string|max:10',
        'race'           => 'nullable|string|max:50',
        'religion'       => 'nullable|string|max:50',
        'nationality'    => 'nullable|string|max:50',
        'orphan'         => 'nullable|string|max:10',
        'oku'            => 'nullable|string|max:10',
        'address'        => 'nullable|string|max:255',
        'grade'          => 'required|string|max:50',
        'class_name'     => 'required|string|max:50',
    ]);

    // Auto-calculate age if dob is provided
    if (!empty($validated['dob'])) {
        $validated['age'] = \Carbon\Carbon::parse($validated['dob'])->age;
    }

    $student->update($validated);

    return redirect()
        ->route('dashboard.school', ['school' => $student->school_id])
        ->with('success', 'Student updated successfully.');
}


public function details($id)
{
    $student = Student::with('school')->findOrFail($id); // load relationships if needed
    $attendances = Attendance::where('student_id', $id)->get();

    return view('school.students.details', compact('student', 'attendances'));
}

public function destroy(Student $student)
{
    $student->delete();

    return redirect()
        ->route('dashboard.school', ['school' => $student->school_id])
        ->with('success', 'Student deleted successfully');
}







}
