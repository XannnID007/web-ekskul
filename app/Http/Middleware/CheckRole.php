<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request with strict role-based access control
     * Implementation of "One Role, One Focus" principle
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Validate user has a role
        if (empty($user->role)) {
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Invalid user role'], 403);
            }

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda tidak memiliki role yang valid.']);
        }

        // Check if user's role is in allowed roles
        if (!in_array($user->role, $roles)) {

            // Log unauthorized access attempt
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'required_roles' => $roles,
                'requested_url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'Anda tidak memiliki akses ke halaman ini.'
                ], 403);
            }

            // Redirect to appropriate dashboard based on ONE ROLE, ONE FOCUS
            return $this->redirectToAuthorizedArea($user->role);
        }

        // Additional security: Verify role-URL consistency
        if (!$this->isUrlConsistentWithRole($request, $user->role)) {

            Log::warning('Role-URL inconsistency detected', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'requested_url' => $request->fullUrl()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access Denied',
                    'message' => 'URL tidak konsisten dengan role Anda.'
                ], 403);
            }

            return $this->redirectToAuthorizedArea($user->role);
        }

        return $next($request);
    }

    /**
     * Redirect user to their authorized area based on "One Role, One Focus"
     */
    private function redirectToAuthorizedArea($role)
    {
        $redirects = [
            'admin' => [
                'route' => 'admin.dashboard',
                'message' => 'Anda dialihkan ke Area System Manager.'
            ],
            'pembina' => [
                'route' => 'pembina.dashboard',
                'message' => 'Anda dialihkan ke Area Activity Manager.'
            ],
            'siswa' => [
                'route' => 'siswa.dashboard',
                'message' => 'Anda dialihkan ke Portal Siswa.'
            ]
        ];

        if (isset($redirects[$role])) {
            return redirect()->route($redirects[$role]['route'])
                ->with('warning', $redirects[$role]['message']);
        }

        // Fallback: logout for invalid roles
        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Role akun tidak valid.']);
    }

    /**
     * Verify URL consistency with user role (One Role, One Focus validation)
     */
    private function isUrlConsistentWithRole(Request $request, $role)
    {
        $path = $request->path();

        // Define role-specific URL patterns based on "One Role, One Focus"
        $rolePatterns = [
            'admin' => [
                'admin',
                'admin/*'
            ],
            'pembina' => [
                'pembina',
                'pembina/*'
            ],
            'siswa' => [
                'siswa',
                'siswa/*'
            ]
        ];

        if (!isset($rolePatterns[$role])) {
            return false;
        }

        // Check if current path matches any allowed pattern for the role
        foreach ($rolePatterns[$role] as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get role capabilities for debugging/logging
     */
    public static function getRoleCapabilities($role)
    {
        $capabilities = [
            'admin' => [
                'name' => 'System Manager',
                'description' => 'HANYA kelola data master dan monitoring sistem',
                'areas' => ['Data Master', 'Monitoring', 'Laporan Sistem', 'Settings'],
                'restrictions' => ['Tidak terlibat operasional harian']
            ],
            'pembina' => [
                'name' => 'Activity Manager',
                'description' => 'HANYA kelola kegiatan yang dibina',
                'areas' => ['Kelola Anggota', 'Approve Pendaftaran', 'Input Kehadiran', 'Pengumuman'],
                'restrictions' => ['Tidak bisa lihat data ekstrakurikuler lain']
            ],
            'siswa' => [
                'name' => 'End User',
                'description' => 'HANYA konsumen layanan',
                'areas' => ['Browse Ekstrakurikuler', 'Lihat Rekomendasi', 'Daftar Kegiatan', 'Monitor Status'],
                'restrictions' => ['Tidak ada akses management']
            ]
        ];

        return $capabilities[$role] ?? [
            'name' => 'Unknown Role',
            'description' => 'Role tidak dikenali',
            'areas' => [],
            'restrictions' => ['Semua akses ditolak']
        ];
    }
}
