<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireStudentProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->hasRole('parent')) {
            // Allow access to student creation and logout
            $allowedRoutes = [
                'student.create',
                'student.store',
                'logout',
            ];
            if (!$user->students()->exists() && !$request->routeIs($allowedRoutes)) {
                return redirect()->route('student.create');
            }
        }
        return $next($request);
    }
} 