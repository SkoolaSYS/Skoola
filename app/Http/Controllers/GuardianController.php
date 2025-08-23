<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Citie;
use App\Models\Postcode;
use App\Models\State;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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
        $guardian = new User();
        $guardian->name = $request->name;
        $guardian->phone_num = $request->phone_num;
        $guardian->ic = $request->ic;
        $guardian->email = $request->email;
        $guardian->address = $request->address;

        // Get the selected state, city, and postcode IDs from the form
        $stateId = $request->input('state');
        $cityId = $request->input('city');
        $postcodeId = $request->input('postcode');

        // Check if the IDs are not null, and then update the user's foreign key fields
        if (!is_null($stateId)) {
            $state = State::find($stateId);
            $guardian->state()->associate($state);
        }

        if (!is_null($cityId)) {
            $city = Citie::find($cityId);
            $guardian->citie()->associate($city);
        }

        if (!is_null($postcodeId)) {
            $postcode = Postcode::find($postcodeId);
            $guardian->postcode()->associate($postcode);
        }
        // Set the guardian's parent_id to the id of the parent (primary user)
        $guardian->parent_id = $parent->id;

        $guardian->save();
        Session::flash('success', 'Additional Guardian has been added successfully.');
        if (!$guardian) {
            Session::flash('error', 'Failed to add Additional Guardian. Please try again.');
        }
        return redirect()->route('profile.show');
    }

    public function show(User $guardian)
    {
        $state = $guardian->state->name;
        $city = $guardian->citie->name;
        $postcode = $guardian->postcode->name;
        return view('parents.additional-guardian.show', compact('guardian','state', 'city', 'postcode'));
    }

    public function edit(User $guardian)
    {
        $state = $guardian->state->name;
        $city = $guardian->citie->name;
        $postcode = $guardian->postcode;
        return view('parents.additional-guardian.edit', compact('guardian','state', 'city', 'postcode'));
    }

    public function update(User $guardian, Request $request)
    {
        $guardian->name = $request->name;
        $guardian->phone_num = $request->phone_num;
        $guardian->ic = $request->ic;
        $guardian->email = $request->email;
        $guardian->address = $request->address;

        // Get the selected state, city, and postcode IDs from the form
        $stateId = $request->input('state');
        $cityId = $request->input('city');
        $postcodeId = $request->input('postcode');

        // Check if the IDs are not null, and then update the user's foreign key fields
        if (!is_null($stateId)) {
            $state = State::find($stateId);
            $guardian->state()->associate($state);
        }

        if (!is_null($cityId)) {
            $city = Citie::find($cityId);
            $guardian->citie()->associate($city);
        }

        if (!is_null($postcodeId)) {
            $postcode = Postcode::find($postcodeId);
            $guardian->postcode()->associate($postcode);
        }

        $guardian->save();

        Session::flash('success', 'Profile updated successfully.');
        if (!$guardian) {
            Session::flash('error', 'Failed to update profile. Please try again.');
        }

        return redirect()->route('profile.guardian.show', ['guardian' => $guardian->id]);
    }

    public function delete(User $guardian)
    {
        $guardian->delete();
        return redirect()->route('profile.show');
    }
}
