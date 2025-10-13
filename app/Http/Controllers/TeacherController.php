<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class TeacherController extends Controller
{
    public function index()
{
    // if the school is the currently logged-in user:
    $school = auth()->user();
    $schoolId = $school->id;

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
    ]);

    $teacher = User::create([
        'teacher_id' => $request->teacher_id,
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'school_id'  => auth()->id(),
        'status'     => 'active', // ✅ default for teacher users only
        'ic'         => $request->ic,
        'address'    => $request->address,
        'phone_num'  => $request->phone_num,

    ]);

    // Assign teacher role using Spatie
    $teacher->assignRole('teacher');

    return redirect()
        ->route('school.teachers.index')
        ->with('success', 'Teacher registered successfully!');
}

public function show(User $teacher)
{
    return view('school.teachers.show', compact('teacher'));
}

public function edit(User $teacher)
{
    return view('school.teachers.edit', compact('teacher'));
}

public function update(Request $request, $id)
{
    $teacher = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'ic' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'phone_num' => 'nullable|string|max:20',
        'status' => 'required|string|in:active,inactive',
    ]);

    $teacher->update($validated);

    return redirect()->route('school.teachers.index')->with('success', 'Teacher updated successfully.');
}

public function toggleStatus($id)
{
    $teacher = \App\Models\User::findOrFail($id);

    $teacher->status = $teacher->status === 'active' ? 'inactive' : 'active';
    $teacher->save();

    return redirect()->route('school.teachers.index')->with('success', 'Teacher status updated successfully.');
}







}
