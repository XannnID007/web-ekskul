<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Illuminate\Http\Request;

class AdminEkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $query = Ekstrakurikuler::with('pembina');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_ekskul', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%");
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $ekstrakurikuler = $query->withCount(['pendaftaran' => function ($q) {
            $q->where('status', 'approved');
        }])->paginate(10);

        $kategoriList = Ekstrakurikuler::distinct()->pluck('kategori');

        return view('admin.ekstrakurikuler.index', compact('ekstrakurikuler', 'kategoriList'));
    }

    public function create()
    {
        $pembina = User::where('role', 'pembina')->get();
        return view('admin.ekstrakurikuler.create', compact('pembina'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            'kapasitas_maksimal' => 'required|integer|min:1',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'tempat' => 'required|string',
            'pembina_id' => 'required|exists:users,id'
        ]);

        Ekstrakurikuler::create($request->all());

        return redirect()->route('admin.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan');
    }

    public function show(Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->load(['pembina', 'anggotaAktif.siswa.user']);

        return view('admin.ekstrakurikuler.show', compact('ekstrakurikuler'));
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler)
    {
        $pembina = User::where('role', 'pembina')->get();
        return view('admin.ekstrakurikuler.edit', compact('ekstrakurikuler', 'pembina'));
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $request->validate([
            'nama_ekskul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string',
            'kapasitas_maksimal' => 'required|integer|min:1',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'tempat' => 'required|string',
            'pembina_id' => 'required|exists:users,id'
        ]);

        $ekstrakurikuler->update($request->all());

        return redirect()->route('admin.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil diupdate');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler)
    {
        $ekstrakurikuler->delete();

        return redirect()->route('admin.ekstrakurikuler.index')
            ->with('success', 'Ekstrakurikuler berhasil dihapus');
    }
}
