<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolAuthController extends Controller
{
    public function showLogin()
    {
        return view('school.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            // check role
            if (auth()->user()->hasRole('school')) {
                return redirect()->route('school.dashboard', auth()->user()->getMeta('user_school_id'));
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Not a school account.']);
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('school.login');
    }
}
