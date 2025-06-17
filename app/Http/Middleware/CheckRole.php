<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            // Redirect to appropriate dashboard based on user role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'pembina':
                    return redirect()->route('pembina.dashboard');
                case 'siswa':
                    return redirect()->route('siswa.dashboard');
                default:
                    abort(403, 'Unauthorized access');
            }
        }

        return $next($request);
    }
}
