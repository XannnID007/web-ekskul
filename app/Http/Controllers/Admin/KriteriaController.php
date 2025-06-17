<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class AdminKriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::orderBy('nama_kriteria')->paginate(10);
        return view('admin.kriteria.index', compact('kriteria'));
    }

    public function create()
    {
        return view('admin.kriteria.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'tipe' => 'required|in:benefit,cost',
            'deskripsi' => 'nullable|string'
        ]);

        // Validasi total bobot tidak melebihi 1
        $totalBobot = Kriteria::where('is_active', true)->sum('bobot');
        if (($totalBobot + $request->bobot) > 1) {
            return back()->withErrors(['bobot' => 'Total bobot kriteria tidak boleh melebihi 1.00']);
        }

        Kriteria::create($request->all());

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function edit(Kriteria $kriteria)
    {
        return view('admin.kriteria.edit', compact('kriteria'));
    }

    public function update(Request $request, Kriteria $kriteria)
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'tipe' => 'required|in:benefit,cost',
            'deskripsi' => 'nullable|string'
        ]);

        // Validasi total bobot
        $totalBobot = Kriteria::where('is_active', true)
            ->where('id', '!=', $kriteria->id)
            ->sum('bobot');

        if (($totalBobot + $request->bobot) > 1) {
            return back()->withErrors(['bobot' => 'Total bobot kriteria tidak boleh melebihi 1.00']);
        }

        $kriteria->update($request->all());

        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil diupdate');
    }

    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();
        return redirect()->route('admin.kriteria.index')
            ->with('success', 'Kriteria berhasil dihapus');
    }

    public function toggleStatus(Kriteria $kriteria)
    {
        $kriteria->update(['is_active' => !$kriteria->is_active]);

        $status = $kriteria->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return response()->json([
            'success' => true,
            'message' => "Kriteria berhasil {$status}",
            'status' => $kriteria->is_active
        ]);
    }
}
