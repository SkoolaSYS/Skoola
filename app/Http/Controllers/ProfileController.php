<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Citie;
use App\Models\Postcode;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function index()
    {
        $parent = auth()->user();
        $guardian = User::where('parent_id', $parent->id)->get();
        $state = $parent->state;
        $citie = $parent->citie;
        $postcode = $parent->postcode;
        return view('parents.profile', compact('parent', 'state', 'citie', 'postcode', 'guardian'));
    }

    public function edit()
    {
        $user = Auth::user();
        $state = $user->state;
        $city = $user->citie;
        $postcode = $user->postcode;
        return view("parents.edit", compact('user', 'state', 'city', 'postcode'));
    }

    public function update(Request $request)
    {
        $profile = User::find($request->id);
        $profile->name = $request->name;
        $profile->phone_num = $request->phone_num;
        $profile->ic = $request->ic;
        $profile->email = $request->email;
        $profile->address = $request->address;

        // Get the selected state, city, and postcode IDs from the form
        $stateId = $request->input('state');
        $cityId = $request->input('city');
        $postcodeId = $request->input('postcode');

        // Check if the IDs are not null, and then update the user's foreign key fields
        if (!is_null($stateId)) {
            $state = State::find($stateId);
            $profile->state()->associate($state);
        }

        if (!is_null($cityId)) {
            $city = Citie::find($cityId);
            $profile->citie()->associate($city);
        }

        if (!is_null($postcodeId)) {
            $postcode = Postcode::find($postcodeId);
            $profile->postcode()->associate($postcode);
        }

        $profile->save();

        Session::flash('success', 'Profile updated successfully.');
        if (!$profile) {
            Session::flash('error', 'Failed to update profile. Please try again.');
        }
        return redirect()->route('profile.show');
    }

}
