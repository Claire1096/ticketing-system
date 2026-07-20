<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSessionIsCurrent
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $loginAt = $request->session()->get('login_at');

            if ($user->force_logout_at && (!$loginAt || $user->force_logout_at->timestamp > $loginAt)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('status', 'Your account permissions were updated. Please log in again.');
            }
        }

        return $next($request);
    }
}
