<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BagianController extends Controller
{
    public function index()
    {
        $bagians = Bagian::all();
        return view('admin.bagian_index', compact('bagians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kepala' => 'required|string|max:255',
        ]);

        Bagian::create([
            'nama' => $request->nama,
            'kepala' => $request->kepala,
        ]);

        return redirect()
            ->route('admin.bagian.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bagian = Bagian::findOrFail($id);
        return view('admin.bagian_edit', compact('bagian'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:25',
            'kepala' => 'required|string|max:255',
        ]);

        $bagian = Bagian::findOrFail($id);
        $bagian->update($request->only('nama'));

        return redirect()
            ->route('admin.bagian.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $bagian = Bagian::findOrFail($id);

        if ($bagian->isUsed()) {
            return redirect()
                ->route('admin.bagian.index')
                ->with('error', 'Bagian tidak dapat dihapus karena sudah digunakan');
        }

        $bagian->delete();

        return redirect()
            ->route('admin.bagian.index')
            ->with('success', 'Bagian berhasil dihapus');
    }

}

