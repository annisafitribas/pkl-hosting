<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bagian;
use App\Models\UserProfile;
use App\Models\PembimbingProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

    class AkunController extends Controller
    {
        // Tampilkan daftar pengguna
    public function index(Request $request)
    {
        $role = $request->query('role', 'all');

        $query = User::with([
            'pembimbingProfile.bagian',
            'pembimbingProfile.usersDibimbing',
            'profile.bagian'   // <-- ubah dari userProfile.bagian
        ]);

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        $users = $query->get();

        return view('admin.pengguna_index', compact('users', 'role'));
    }

        // Halaman create
        public function create()
        {
            $bagians = Bagian::all();
            $pembimbings = PembimbingProfile::with('user')->get();
            return view('admin.pengguna_create', compact('bagians', 'pembimbings'));
        }

public function store(Request $request)
{

    /* ================= VALIDASI DASAR ================= */
    $rules = [
        'role' => 'required|in:admin,pembimbing,user',

        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',

        'gender' => 'nullable|in:L,P',
        'tgl_lahir' => 'nullable|date',
        'no_hp' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ];

    /* ================= ROLE PEMBIMBING ================= */
    if ($request->role === 'pembimbing') {
        $rules += [
            'pembimbing.nip' => 'required|string|max:50',
            'pembimbing.jabatan' => 'required|string|max:100',
            'pembimbing.bagian_id' => 'required|exists:bagians,id',
        ];
    }

    /* ================= ROLE USER ================= */
    if ($request->role === 'user') {
        $rules += [
            'user.nomor_induk' => 'nullable|string|max:50',
            'user.tingkatan' => 'nullable|string|max:50',
            'user.pendidikan' => 'nullable|string|max:100',
            'user.kelas' => 'nullable|string|max:50',
            'user.jurusan' => 'nullable|string|max:100',
            'user.bagian_id' => 'nullable|exists:bagians,id',
            'user.pembimbing_id' => 'nullable|exists:pembimbing_profiles,id',
            'user.tgl_masuk' => 'nullable|date',
            'user.tgl_keluar' => 'nullable|date|after_or_equal:user.tgl_masuk',
        ];
    }


    $validated = $request->validate($rules);

    /* ================= FOTO USER ================= */
    $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_users', 'public');
        }
    /* ================= SIMPAN USER ================= */
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'gender' => $validated['gender'] ?? null,
        'tgl_lahir' => $validated['tgl_lahir'] ?? null,
        'alamat' => $validated['alamat'] ?? null,
        'no_hp' => $validated['no_hp'] ?? null,
        'foto' => $fotoPath,
    ]);

    /* ================= USER PROFILE ================= */
    if ($user->role === 'user') {
        UserProfile::create([
            'user_id'        => $user->id,
            'nomor_induk'    => data_get($validated, 'user.nomor_induk'),
            'tingkatan'      => data_get($validated, 'user.tingkatan'),
            'pendidikan'     => data_get($validated, 'user.pendidikan'),
            'kelas'          => data_get($validated, 'user.kelas'),
            'jurusan'        => data_get($validated, 'user.jurusan'),
            'bagian_id'      => data_get($validated, 'user.bagian_id'),
            'pembimbing_id'  => data_get($validated, 'user.pembimbing_id'),
            'tgl_masuk'      => data_get($validated, 'user.tgl_masuk'),
            'tgl_keluar'     => data_get($validated, 'user.tgl_keluar'),
        ]);
    }

    return redirect()
        ->route('admin.pengguna.index')
        ->with('success', 'Pengguna berhasil ditambahkan');
}


    public function edit($id)
    {
        $user = User::with([
            'profile.bagian',
            'profile.pembimbing.user',
            'pembimbingProfile.bagian',
        ])->findOrFail($id);


        $bagians = Bagian::all();
        $pembimbings = PembimbingProfile::with('user')->get();

        return view('admin.pengguna_edit', compact(
            'user',
            'bagians',
            'pembimbings'
        ));
    }

        // Update data
        public function update(Request $request, User $pengguna)
        {
            // | VALIDASI USERS
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $pengguna->id,
                'password' => 'nullable|confirmed|min:6',
                'foto' => 'nullable|image|max:2048',

                // data pribadi (users)
                'gender' => 'nullable|in:L,P',
                'tgl_lahir' => 'nullable|date',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string|max:20',
            ]);

            // | FOTO
            if ($request->hasFile('foto')) {
                if ($pengguna->foto) {
                    Storage::disk('public')->delete($pengguna->foto);
                }
                $pengguna->foto = $request->file('foto')->store('foto_users', 'public');
            }

            // | UPDATE USERS
            $pengguna->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->filled('password')
                    ? Hash::make($request->password)
                    : $pengguna->password,

                'gender' => $request->gender,
                'tgl_lahir' => $request->tgl_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            // | USER PROFILE (MAGANG)
            if ($pengguna->role === 'user') {

                $request->validate([
                    'user.nomor_induk' => 'nullable|string|max:50',
                    'user.tingkatan' => 'nullable|string|max:50',
                    'user.pendidikan' => 'nullable|string|max:100',
                    'user.kelas' => 'nullable|string|max:50',
                    'user.jurusan' => 'nullable|string|max:100',
                    'user.bagian_id' => 'nullable|exists:bagians,id',
                    'user.pembimbing_id' => 'nullable|exists:pembimbing_profiles,id',
                    'user.tgl_masuk' => 'nullable|date',
                    'user.tgl_keluar' => 'nullable|date|after_or_equal:user.tgl_masuk',
                ]);

                UserProfile::updateOrCreate(
                    ['user_id' => $pengguna->id],
                    [
                        'nomor_induk' => $request->user['nomor_induk'] ?? null,
                        'tingkatan' => $request->user['tingkatan'] ?? null,
                        'pendidikan' => $request->user['pendidikan'] ?? null,
                        'kelas' => $request->user['kelas'] ?? null,
                        'jurusan' => $request->user['jurusan'] ?? null,
                        'bagian_id' => $request->user['bagian_id'] ?? null,
                        'pembimbing_id' => $request->user['pembimbing_id'] ?? null,
                        'tgl_masuk' => $request->user['tgl_masuk'] ?? null,
                        'tgl_keluar' => $request->user['tgl_keluar'] ?? null,
                    ]
                );
            }

            return redirect()
                ->route('admin.pengguna.index')
                ->with('success', 'Pengguna berhasil diupdate!');
    }

    public function destroy(User $pengguna)
    {
        // hapus foto user (jika ada)
        if ($pengguna->foto) {
            Storage::disk('public')->delete($pengguna->foto);
        }

        // hapus user (relasi cascade akan ikut terhapus)
        $pengguna->delete();

        return redirect()
            ->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }

        // Tampilkan detail user
    public function show($id)
    {
        // Ambil user berdasarkan ID
        $pengguna = User::with(['profile.bagian', 'profile.pembimbing.user', 'pembimbingProfile.bagian'])->findOrFail($id);

        // Kirim ke view
        return view('admin.pengguna_show', compact('pengguna'));
    }

    }
