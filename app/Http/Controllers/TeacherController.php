<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\ClassUser;
use App\Models\SchoolClass;


class TeacherController extends Controller
{
    public function index()
{
    // if the school is the currently logged-in user:
    $school = auth()->user();
    $schoolId = $school->school_id; // ID from schools table

    $teachers = User::whereHas('roles', fn($q) => $q->where('name','teacher'))
                    ->where('school_id', $schoolId)
                    ->get();


    // pass both variables to the view
    return view('school.teachers.index', compact('school', 'teachers'));
}


    public function create()
    {
        return view('school.teachers.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'teacher_id' => 'required|string|max:50|unique:users,teacher_id',
        'name'       => 'required|string|max:255',
        'email'      => 'required|email|unique:users,email',
        'password'   => 'required|confirmed|min:6',
        'ic'         => 'nullable|string|max:20',
        'address'    => 'nullable|string|max:255',
        'phone_num'  => 'nullable|string|max:20',
    ]);

    // Debug
    // dd($request->all());

    $schoolId = auth()->user()->school_id; // ID from schools table

    $teacher = User::create([
        'teacher_id' => $request->teacher_id,
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'school_id'  => $schoolId,  // use the schools table id
        'status'     => 'active',
        'ic'         => $request->ic,
        'address'    => $request->address,
        'phone_num'  => $request->phone_num,
    ]);


    // Debug
    // dd($teacher);

    $teacher->assignRole('teacher');

    return redirect()
        ->route('school.teachers.index')
        ->with('success', 'Teacher registered successfully!');
}



public function show(User $teacher)
{
    // Eager load the classes relationship
    $teacher->load('classes.grade'); // load grade if you want to show it

    return view('school.teachers.show', compact('teacher'));
}


public function edit(User $teacher)
{
    // Only teachers
    if (!$teacher->hasRole('teacher')) {
        abort(403, 'This user is not a teacher.');
    }

    $schoolUser = auth()->user(); // logged-in school user

    // Teacher must belong to this school
    if ($teacher->school_id !== $schoolUser->school->id) {
        abort(403, 'This teacher does not belong to your school.');
    }

    // Load teacher's classes
    $teacher->load('classes');

    // Get all classes under grades that belong to this school
    $classes = \App\Models\SchoolClass::whereHas('grade', function ($q) use ($schoolUser) {
        $q->where('school_id', $schoolUser->school->id);
    })
    ->whereDoesntHave('teachers', function($q) use ($teacher) {
        // Exclude classes assigned to other teachers
        $q->where('user_id', '!=', $teacher->id);
    })
    ->orWhereHas('teachers', function($q) use ($teacher) {
        // Include classes already assigned to this teacher
        $q->where('user_id', $teacher->id);
    })
    ->get();

    return view('school.teachers.edit', compact('teacher', 'classes'));
}





public function update(Request $request, $id)
{
    $teacher = User::findOrFail($id);

    if (!$teacher->hasRole('teacher')) {
        abort(403, 'This user is not a teacher.');
    }

    $schoolUser = auth()->user();

    // Check teacher belongs to logged-in school
    if ($teacher->school_id !== $schoolUser->school->id) {
        abort(403, 'This teacher does not belong to your school.');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'ic' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'phone_num' => 'nullable|string|max:20',
        'status' => 'required|string|in:active,inactive',
        'classes' => 'nullable|array',
        'classes.*' => 'exists:school_classes,id',
    ]);

    // Update teacher data
    $teacher->update($validated);

    // Sync assigned classes (many-to-many)
    $teacher->classes()->sync($request->classes ?? []);

    return redirect()->route('school.teachers.index')
                     ->with('success', 'Teacher updated successfully.');
}



public function toggleStatus($id)
{
    $teacher = \App\Models\User::findOrFail($id);

    $teacher->status = $teacher->status === 'active' ? 'inactive' : 'active';
    $teacher->save();

    return redirect()->route('school.teachers.index')->with('success', 'Teacher status updated successfully.');
}







}
