<?php

namespace App\Http\Controllers\Pembina;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengumumanController extends Controller
{
    public function index()
    {
        $pembina = auth()->user();
        $pengumuman = Pengumuman::where('author_id', $pembina->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pembina.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        return view('pembina.pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:ekstrakurikuler',
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'author_id' => auth()->id(),
            'is_published' => $request->has('publish'),
            'published_at' => $request->has('publish') ? now() : null
        ]);

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    public function edit(Pengumuman $pengumuman)
    {
        // Check authorization
        if ($pengumuman->author_id !== auth()->id()) {
            abort(403);
        }

        return view('pembina.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        // Check authorization
        if ($pengumuman->author_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|in:ekstrakurikuler',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'kategori' => $request->kategori,
            'is_published' => $request->has('publish'),
            'published_at' => $request->has('publish') ? now() : $pengumuman->published_at
        ]);

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diupdate');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        // Check authorization
        if ($pengumuman->author_id !== auth()->id()) {
            abort(403);
        }

        $pengumuman->delete();

        return redirect()->route('pembina.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
