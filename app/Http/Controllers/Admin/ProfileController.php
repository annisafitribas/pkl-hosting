<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil admin (SHOW)
     */
    public function show(Request $request)
    {
        return view('admin.profile_show', [
            'admin' => $request->user(),
        ]);
    }

    /**
     * Tampilkan form edit profil admin
     */
    public function edit(Request $request)
    {
        return view('admin.profile_edit', [
            'admin' => $request->user(),
        ]);
    }

    /**
     * Update profil admin
     */
    public function update(Request $request)
    {
        $admin = $request->user();

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $admin->id,
            'password'  => 'nullable|confirmed|min:6',
            'foto'      => 'nullable|image|max:2048',

            // data pribadi
            'gender'    => 'nullable|in:L,P',
            'tgl_lahir' => 'nullable|date',
            'no_hp'     => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',
        ]);

        // FOTO
        if ($request->hasFile('foto')) {
            if ($admin->foto) {
                Storage::disk('public')->delete($admin->foto);
            }
            $admin->foto = $request->file('foto')->store('foto_users', 'public');
        }

        // UPDATE DATA
        $admin->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'gender'    => $request->gender,
            'tgl_lahir' => $request->tgl_lahir,
            'no_hp'     => $request->no_hp,
            'alamat'    => $request->alamat,
            'password'  => $request->filled('password')
                ? Hash::make($request->password)
                : $admin->password,
        ]);

        return redirect()
            ->route('admin.profile.show')
            ->with('success', 'Profil admin berhasil diperbarui');
    }
}
