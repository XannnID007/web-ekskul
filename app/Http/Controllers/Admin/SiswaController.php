<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('kelas', 'like', "%{$search}%");
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $siswa = $query->paginate(10);
        $kelasList = Siswa::distinct()->pluck('kelas');

        return view('admin.siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nisn' => 'required|string|unique:siswa,nisn',
            'kelas' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'phone' => 'nullable|string',
            'minat' => 'array',
            'nilai_akademik' => 'required|numeric|min:0|max:100'
        ]);

        DB::transaction(function () use ($request) {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password123'), // Default password
                'role' => 'siswa',
                'phone' => $request->phone
            ]);

            // Create siswa profile
            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nisn' => $request->nisn,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'minat' => $request->minat ?? [],
                'nilai_akademik' => $request->nilai_akademik
            ]);

            // Initialize default assessment scores
            $kriteria = Kriteria::where('is_active', true)->get();
            foreach ($kriteria as $k) {
                PenilaianSiswa::create([
                    'siswa_id' => $siswa->id,
                    'kriteria_id' => $k->id,
                    'nilai' => 50 // Default score
                ]);
            }
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'pendaftaran.ekstrakurikuler', 'penilaianSiswa.kriteria']);

        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load('user');
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'nisn' => 'required|string|unique:siswa,nisn,' . $siswa->id,
            'kelas' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'phone' => 'nullable|string',
            'minat' => 'array',
            'nilai_akademik' => 'required|numeric|min:0|max:100'
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
                'nisn' => $request->nisn,
                'kelas' => $request->kelas,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'minat' => $request->minat ?? [],
                'nilai_akademik' => $request->nilai_akademik
            ]);
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diupdate');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            $siswa->user->delete(); // This will cascade delete siswa
        });

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }
}
