<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProfilController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        return view('siswa.profil.index', compact('siswa'));
    }

    public function update(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'phone' => 'nullable|string',
            'alamat' => 'required|string',
            'minat' => 'array'
        ]);

        DB::transaction(function () use ($request, $siswa) {
            // Update user
            $siswa->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);

            // Update siswa
            $siswa->update([
                'alamat' => $request->alamat,
                'minat' => $request->minat ?? []
            ]);
        });

        return back()->with('success', 'Profil berhasil diupdate');
    }
}
