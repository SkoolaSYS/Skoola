<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Models\State;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        
        
        $user = User::where('parent_id', null)
            ->with('roles')
            ->get(); //Get the user that have passwords only (not include guardian) 
        return view('admin.index', compact('user'));
    }

    public function add()
    {
        $user = User::where('parent_id', null)
            ->get(); //Get the user that have passwords only (not include guardian) 
        return view('admin.add-user', compact('user'));
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->phone_num = $request->phone_num;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save(); //save user details first before assign roles and setMeta

        // Store selected roles
        $selectedRoles = $request->input('roles', []);
        if ($selectedRoles == 1) {
            $user->assignRole('admin');
        } elseif ($selectedRoles == 2) {
            $user->assignRole('country');
        } elseif ($selectedRoles == 3) {
            $user->assignRole('state');
        } elseif ($selectedRoles == 4) {
            $user->assignRole('ppd');
        } elseif ($selectedRoles == 5) {
            $user->assignRole('school');
        } else {
            $user->assignRole('parent');
        }

        $user->save();

        //setMeta data
        $stateId = $request->input('state');
        $ppdId = $request->input('ppd');
        $schoolId = $request->input('school');

        if ($stateId) {
            $user->setMeta('user_state_id', $stateId); // Storing only the ID
        } elseif ($ppdId) {
            $user->setMeta('user_district_id', $ppdId); // Storing only the ID
        } elseif ($schoolId) {
            $user->setMeta('user_school_id', $schoolId); // Storing only the ID
        }

        $user->save();

        $user->roles()->sync($selectedRoles);

        return redirect()->route('admin.show')->with('success', 'New user added successfully');
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        $availableRoles = Role::all();

        return view('admin.edit-user', compact('user', 'availableRoles'));
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Update user information
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone_num = $request->input('phone_num');

        // Check if a new password is provided
        $newPassword = $request->input('password');
        if (!empty($newPassword)) {
            $user->password = Hash::make($newPassword);
        }

        // Update user roles
        $selectedRoles = $request->input('roles', []);

        //setMeta data
        $stateId = $request->input('state');
        $ppdId = $request->input('ppd');
        $schoolId = $request->input('school');

        if ($stateId) {
            $user->setMeta('user_state_id', $stateId); // Storing only the ID
        } elseif ($ppdId) {
            $user->setMeta('user_district_id', $ppdId); // Storing only the ID
        } elseif ($schoolId) {
            $user->setMeta('user_school_id', $schoolId); // Storing only the ID
        }

        $user->roles()->sync($selectedRoles);

        $user->save();

        return redirect()->route('admin.show')->with('success', 'User information and roles updated successfully');
    }

    public function delete($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();
            return redirect()->route('admin.show')->with('success', 'User deleted successfully');
        } else {
            return redirect()->route('admin.show')->with('error', 'User not found');
        }
    }
}
