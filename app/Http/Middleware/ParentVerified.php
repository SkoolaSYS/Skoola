<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Check for verified email cookie
        $email = $request->cookie('verified_email');

        if (!$email) {
            return redirect('/attendance-parent')->with('error', 'Please verify your email first.');
        }

        // Check if verified in database
        $verified = DB::table('email_verifications')
            ->where('email', $email)
            ->whereNotNull('verified_at')
            ->exists();

        if (!$verified) {
            return redirect('/attendance-parent')->with('error', 'Email not verified yet.');
        }

        return $next($request);
    }
}
