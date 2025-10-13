<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class SocialController extends Controller
{
    // Redirect to provider (Google/Facebook)
    public function redirect($provider)
{
    if ($provider === 'facebook') {
        return Socialite::driver('facebook')
            ->with(['auth_type' => 'reauthenticate']) // force login prompt
            ->redirect();
    }

    if ($provider === 'google') {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account']) // 👈 forces Google to ask each time
            ->redirect();
    }

    return Socialite::driver($provider)->redirect();
}



    // Handle callback
    public function callback($provider)
{
    // ✅ Handle user cancel or missing code
    if (!request()->has('code')) {
        return redirect()->route('login')->with('error', 'Facebook login was cancelled.');
    }

    $socialUser = \Laravel\Socialite\Facades\Socialite::driver($provider)->stateless()->user();

    $user = \App\Models\User::updateOrCreate([
        'email' => $socialUser->getEmail(),
    ], [
        'name' => $socialUser->getName(),
        'username' => $socialUser->getNickname() ?? $socialUser->getId(),
        'password' => bcrypt(str()->random(16)),
    ]);

    // Ensure "parent" role exists
    \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'parent']);

    // Assign role if not already
    if (!$user->hasRole('parent')) {
        $user->assignRole('parent');
    }

    Auth::login($user);

    return redirect()->route('dashboard');
}



}
