<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        // If already authenticated, redirect based on role
        if (auth()->check()) {
            return $this->redirectBasedOnRole(auth()->user());
        }

        return view('auth.login');
    }

    /**
     * Handle login attempt with rate limiting
     */
    public function login(Request $request)
    {
        // Rate limiting check
        $key = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // Attempt authentication
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);

            // Regenerate session
            $request->session()->regenerate();

            $user = auth()->user();

            // Verify user has valid role
            if (!in_array($user->role, ['admin', 'pembina', 'siswa'])) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak memiliki role yang valid. Hubungi administrator.',
                ]);
            }

            // Log successful login
            Log::info('User logged in', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => $request->ip()
            ]);

            // Redirect based on role with intended URL support
            return $this->redirectBasedOnRole($user, $request->get('intended'));
        }

        // Record failed attempt
        RateLimiter::hit($key, 300); // 5 minutes lockout

        // Log failed login attempt
        Log::warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        // Log logout
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role
            ]);
        }

        // Logout user
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user, $intended = null)
    {
        // If there's an intended URL and it's for the same role, use it
        if ($intended && $this->isValidIntendedUrl($intended, $user->role)) {
            return redirect($intended);
        }

        // Default redirects based on role - ONE ROLE, ONE FOCUS
        switch ($user->role) {
            case 'admin':
                // ADMIN = SYSTEM MANAGER
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang, System Manager!');

            case 'pembina':
                // PEMBINA = ACTIVITY MANAGER  
                return redirect()->route('pembina.dashboard')
                    ->with('success', 'Selamat datang, Activity Manager!');

            case 'siswa':
                // SISWA = END USER
                return redirect()->route('siswa.dashboard')
                    ->with('success', 'Selamat datang kembali!');

            default:
                // Invalid role - should not happen but safety first
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Role akun tidak valid. Hubungi administrator.'
                ]);
        }
    }

    /**
     * Check if intended URL is valid for user role
     */
    private function isValidIntendedUrl($url, $role)
    {
        // Extract path from URL
        $path = parse_url($url, PHP_URL_PATH);

        // Role-based URL validation - ONE ROLE, ONE FOCUS
        switch ($role) {
            case 'admin':
                return str_starts_with($path, '/admin');
            case 'pembina':
                return str_starts_with($path, '/pembina');
            case 'siswa':
                return str_starts_with($path, '/siswa');
            default:
                return false;
        }
    }

    /**
     * Get user info for current session (API endpoint)
     */
    public function user(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = auth()->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'dashboard_url' => $this->getDashboardUrl($user->role)
        ]);
    }

    /**
     * Get dashboard URL for role
     */
    private function getDashboardUrl($role)
    {
        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
            case 'pembina':
                return route('pembina.dashboard');
            case 'siswa':
                return route('siswa.dashboard');
            default:
                return route('login');
        }
    }
}
