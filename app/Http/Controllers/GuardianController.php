<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Citie;
use App\Models\Postcode;
use App\Models\State;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuardianController extends Controller
{
    
    public function create()
    {
        $sec_parents = auth()->user();
        return view('parents.additional-guardian.add', compact('sec_parents'));
    }

    public function store(Request $request)
{
    $parent = auth()->user();

    // Validate form input
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'phone_num' => 'required|string|max:20',
        'ic' => 'required|string|max:20',
        'relationship' => 'required|string',
        'occupation' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'password' => 'required|string|confirmed|min:8',
        'state' => 'nullable|exists:states,id',
        'city' => 'nullable|exists:cities,id',
        'postcode' => 'nullable|exists:postcodes,id',
    ]);

    // 1. Create guardian
    $guardian = new User();
    $guardian->name = $request->name;
    $guardian->username = $request->username;
    $guardian->phone_num = $request->phone_num;
    $guardian->ic = $request->ic;
    $guardian->email = $request->email;
    $guardian->address = $request->address;
    $guardian->occupation = $request->occupation;
    $guardian->relationship = $request->relationship;
    $guardian->password = Hash::make($request->password);

    // State, city, postcode
    if ($request->filled('state')) {
        $guardian->state()->associate(State::find($request->state));
    }
    if ($request->filled('city')) {
        $guardian->citie()->associate(Citie::find($request->city));
    }
    if ($request->filled('postcode')) {
        $guardian->postcode()->associate(Postcode::find($request->postcode));
    }

    $guardian->save();

    // 2. Attach guardian to all of parent's students
    foreach ($parent->students as $student) {
        DB::table('parent_student')->insert([
            'parent_id'  => $guardian->id,
            'student_id' => $student->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    Session::flash('success', 'Additional Guardian has been added successfully.');
    return redirect()->route('profile.show');
}


    public function show($id)
{
    $guardian = User::find($id);

    if (!$guardian) {
        Session::flash('error', 'Guardian not found.');
        return redirect()->route('profile.show');
    }

    $state    = $guardian->state?->name ?? '';
    $city     = $guardian->citie?->name ?? '';
    $postcode = $guardian->postcode?->name ?? ''; // make sure 'name' is the column in postcodes table

    return view('parents.additional-guardian.show', compact('guardian', 'state', 'city', 'postcode'));
}








    public function edit(User $guardian)
{
    // Make sure the relationships are loaded
    $guardian->load(['state', 'citie', 'postcode']);

    return view('parents.additional-guardian.edit', [
        'guardian' => $guardian,
        'selectedState' => $guardian->state?->id,
        'selectedCity' => $guardian->citie?->id,
        'selectedPostcode' => $guardian->postcode?->id,
    ]);
}






    public function update(User $guardian, Request $request)
{
    // Validate inputs
    $request->validate([
        'name'         => 'required|string|max:255',
        'username'     => 'required|string|max:255|unique:users,username,' . $guardian->id,
        'phone_num'    => 'nullable|string|max:20',
        'ic'           => 'nullable|string|max:20',
        'email'        => 'nullable|email|max:255|unique:users,email,' . $guardian->id,
        'address'      => 'nullable|string|max:500',
        'occupation'   => 'nullable|string|max:255',
        'relationship' => 'nullable|string|max:50',
        'state'        => 'nullable|exists:states,id',
        'city'         => 'nullable|exists:cities,id',
        'postcode'     => 'nullable|string|max:10', // just a string, not a relationship
    ]);

    // Update guardian fields
    $guardian->name         = $request->name;
    $guardian->username     = $request->username;
    $guardian->phone_num    = $request->phone_num;
    $guardian->ic           = $request->ic;
    $guardian->email        = $request->email;
    $guardian->address      = $request->address;
    $guardian->occupation   = $request->occupation;
    $guardian->relationship = $request->relationship;

    // Update relationships
    if ($request->filled('state')) {
        $guardian->state()->associate(State::find($request->state));
    }

    if ($request->filled('city')) {
        $guardian->citie()->associate(Citie::find($request->city));
    }

    if ($request->filled('postcode')) {
    $guardian->postcode()->associate(Postcode::find($request->postcode));
}


    // Save
    $guardian->save();

    Session::flash('success', 'Profile updated successfully.');

    return redirect()->route('profile.guardian.show', ['guardian' => $guardian->id]);
}





    public function delete(User $guardian)
    {
        $guardian->delete();
        return redirect()->route('profile.show');
    }

    public function guardianFormPartial(Request $request)
{
    // Get the index from query string, default to 0
    $index = $request->query('index', 0);

    // Pass the index to the partial view
    return view('auth._guardian-form', compact('index'))->render();
}

}
