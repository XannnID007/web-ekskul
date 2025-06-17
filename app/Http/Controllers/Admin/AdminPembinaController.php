<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminPembinaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pembina');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $pembina = $query->withCount('ekstrakurikulerPembina')->paginate(10);

        return view('admin.pembina.index', compact('pembina'));
    }

    public function create()
    {
        return view('admin.pembina.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|min:6|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'pembina'
        ]);

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Data pembina berhasil ditambahkan');
    }

    public function show(User $pembina)
    {
        if ($pembina->role !== 'pembina') {
            abort(404);
        }

        $pembina->load(['ekstrakurikulerPembina.pendaftaran' => function ($q) {
            $q->where('status', 'approved');
        }]);

        return view('admin.pembina.show', compact('pembina'));
    }

    public function edit(User $pembina)
    {
        if ($pembina->role !== 'pembina') {
            abort(404);
        }

        return view('admin.pembina.edit', compact('pembina'));
    }

    public function update(Request $request, User $pembina)
    {
        if ($pembina->role !== 'pembina') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pembina->id,
            'phone' => 'nullable|string',
            'password' => 'nullable|min:6|confirmed'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $pembina->update($data);

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Data pembina berhasil diupdate');
    }

    public function destroy(User $pembina)
    {
        if ($pembina->role !== 'pembina') {
            abort(404);
        }

        // Check if pembina has active ekstrakurikuler
        if ($pembina->ekstrakurikulerPembina()->where('is_active', true)->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus pembina yang masih aktif membina ekstrakurikuler');
        }

        $pembina->delete();

        return redirect()->route('admin.pembina.index')
            ->with('success', 'Data pembina berhasil dihapus');
    }
}
