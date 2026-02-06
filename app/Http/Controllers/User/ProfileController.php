<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    // Tampilkan halaman profil
    public function index()
    {
        $user = User::with(['profile.bagian','profile.pembimbing.user'])->findOrFail(Auth::id());
        return view('user.profile_index', compact('user'));
    }

    // Tampilkan form edit
    public function edit()
    {
        $user = User::with(['profile.bagian','profile.pembimbing.user'])->findOrFail(Auth::id());
        return view('user.profile_edit', compact('user'));
    }

    // Update data pribadi + akademik
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'gender' => 'nullable|in:L,P',
            'tgl_lahir' => 'nullable|date',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            // Akademik
            'profile.pendidikan' => 'nullable|string|max:100',
            'profile.tingkatan' => 'nullable|string|max:50',
            'profile.kelas' => 'nullable|string|max:50',
            'profile.jurusan' => 'nullable|string|max:100',
        ]);

        // Foto
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('foto_users','public');
        }

        // Update data pribadi
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'tgl_lahir' => $request->tgl_lahir,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        // Update data akademik (UserProfile)
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'pendidikan' => $request->input('profile.pendidikan'),
                'tingkatan'  => $request->input('profile.tingkatan'),
                'kelas'      => $request->input('profile.kelas'),
                'jurusan'    => $request->input('profile.jurusan'),
            ]
        );

        return redirect()->route('user.profile.index')->with('success','Profil berhasil diperbarui');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required','min:6','confirmed'],
        ]);

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama salah',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success','Password berhasil diubah');
    }
}
