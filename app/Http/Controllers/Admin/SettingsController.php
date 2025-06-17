<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'max_ekstrakurikuler_per_siswa' => 2,
            'auto_approve_threshold' => 80,
            'academic_year' => '2024/2025',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'max_ekstrakurikuler_per_siswa' => 'required|integer|min:1|max:5',
            'auto_approve_threshold' => 'required|numeric|min:0|max:100',
            'academic_year' => 'required|string',
        ]);

        // Update settings logic here
        // This would typically update a settings table or config files

        return back()->with('success', 'Pengaturan sistem berhasil diupdate');
    }
}
