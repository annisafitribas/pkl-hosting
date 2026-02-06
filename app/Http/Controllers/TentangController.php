<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TentangController extends Controller
{
    public function index()
    {
        $tentang = Tentang::first();
        return view('admin.tentang_index', compact('tentang'));
    }

    public function create()
    {
        return view('admin.tentang_form', ['tentang' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'surel' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'masa_jabatan' => 'nullable|string|max:255',
            'foto_stuktur' => 'nullable|image|max:2048',
            'foto_logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto_stuktur');

        if ($request->hasFile('foto_stuktur')) {
            $data['foto_stuktur'] = $request->file('foto_stuktur')->store('struktur_org', 'public');
        }

        Tentang::create($data);

        return redirect()->route('admin.tentang.index')->with('success', 'Informasi aplikasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tentang = Tentang::findOrFail($id);
        return view('admin.tentang_form', compact('tentang'));
    }

    public function update(Request $request, $id)
    {
        $tentang = Tentang::findOrFail($id);

        $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'alamat' => 'nullable|string|max:255',
            'surel' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'masa_jabatan' => 'nullable|string|max:255',
            'foto_stuktur' => 'nullable|image|max:2048',
            'foto_logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except('foto_stuktur');

        if ($request->hasFile('foto_stuktur')) {
            if ($tentang->foto_stuktur) {
                Storage::disk('public')->delete($tentang->foto_stuktur);
            }
            $data['foto_stuktur'] = $request->file('foto_stuktur')->store('struktur_org', 'public');
        }

        $tentang->update($data);

        return redirect()->route('admin.tentang.index')->with('success', 'Informasi aplikasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tentang = Tentang::findOrFail($id);

        // Hapus file foto dari storage kalau ada
        if ($tentang->foto_stuktur) {
            Storage::disk('public')->delete($tentang->foto_stuktur);
        }

        $tentang->delete();

        return redirect()->route('admin.tentang.index')->with('success', 'Informasi aplikasi berhasil dihapus!');
    }

}
