<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /* ================== PROFIL ================== */

    public function index()
    {
        $user = User::with(['pembimbingProfile.bagian'])
            ->findOrFail(Auth::id());

        return view('pembimbing.profile_index', compact('user'));
    }

    public function edit()
    {
        $user = User::with(['pembimbingProfile.bagian'])
            ->findOrFail(Auth::id());

        return view('pembimbing.profile_edit', compact('user'));
    }

    /* ================== UPDATE DATA ================== */

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'no_hp'     => 'nullable|string|max:20',
            'alamat'    => 'nullable|string',

            'foto'      => 'nullable|image|max:2048',

            // pembimbing profile
            'nip'       => 'nullable|string|max:50',
            'jabatan'   => 'nullable|string|max:100',
            'bagian_id' => 'nullable|exists:bagians,id',
        ]);

        /* ===== FOTO USER ===== */
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            $validated['foto'] = $request->file('foto')
                ->store('foto_users', 'public');
        }

        $user->update([
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'no_hp'  => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'foto'   => $validated['foto'] ?? $user->foto,
        ]);

        /* ===== PEMBIMBING PROFILE ===== */
        $user->pembimbingProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip'       => $validated['nip'] ?? null,
                'jabatan'   => $validated['jabatan'] ?? null,
                'bagian_id' => $validated['bagian_id'] ?? null,
            ]
        );

        return redirect()
            ->route('pembimbing.profile.index')
            ->with('success', 'Profil pembimbing berhasil diperbarui');
    }

    /* ================== UPDATE PASSWORD ================== */

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama salah',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}
