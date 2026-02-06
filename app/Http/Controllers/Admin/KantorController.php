<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class KantorController extends Controller
{
    public function index()
    {
        $kantors = Kantor::all();
        return view('admin.kantor_index', compact('kantors'));
    }

    public function create()
    {
        return view('admin.kantor_create');
    }

    public function store(Request $request)
    {
        // NORMALISASI LINK 
        $request->merge([
            'link_maps' => $request->link_maps ?: null,
            'wa_link'   => $request->wa_link ?: null,
            'ig_link'   => $request->ig_link ?: null,
        ]);

        // VALIDASI 
        $validated = $request->validate([
            'nama_apk'     => 'required|string|max:255',
            'nama_pt'      => 'nullable|string|max:255',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'alamat'       => 'required|string',
            'link_maps'    => 'nullable|string|max:255',
            'wa_link'      => 'nullable|string|max:255',
            'ig_link'      => 'nullable|string|max:255',
            'hari_kerja'   => 'nullable|array',
            'hari_kerja.*' => 'string',
            'jam_masuk'    => 'required',
            'jam_keluar'   => 'required',
            'kantor_lat'   => 'nullable|numeric',
            'kantor_lng'   => 'nullable|numeric',
            'radius_absen' => 'nullable|integer',
        ]);

        // NORMALISASI DATA 

        // jam → H:i:s
        $validated['jam_masuk'] = strlen($validated['jam_masuk']) === 5
            ? $validated['jam_masuk'] . ':00'
            : $validated['jam_masuk'];

        $validated['jam_keluar'] = strlen($validated['jam_keluar']) === 5
            ? $validated['jam_keluar'] . ':00'
            : $validated['jam_keluar'];

        // hari kerja → lowercase
        $validated['hari_kerja'] = collect($validated['hari_kerja'] ?? [])
            ->map(fn ($day) => strtolower($day))
            ->values()
            ->toArray();

        // default radius
        $validated['radius_absen'] = $validated['radius_absen'] ?? 100;

        // default koordinat (aman)
        $validated['kantor_lat'] = $validated['kantor_lat'] ?? -3.31686840;
        $validated['kantor_lng'] = $validated['kantor_lng'] ?? 114.59018350;

        // upload logo
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('kantor-logo', 'public');
        }

        Kantor::create($validated);

        Cache::forget('kantor_name');

        return redirect()
            ->route('admin.kantors.index')
            ->with('success', 'Kantor berhasil ditambahkan!');
    }

    public function edit(Kantor $kantor)
    {
        return view('admin.kantor_edit', compact('kantor'));
    }

    public function update(Request $request, Kantor $kantor)
    {
        // NORMALISASI LINK 
        $request->merge([
            'link_maps' => $request->link_maps ?: null,
            'wa_link'   => $request->wa_link ?: null,
            'ig_link'   => $request->ig_link ?: null,
        ]);

        // VALIDASI 
        $validated = $request->validate([
            'nama_apk'     => 'required|string|max:255',
            'nama_pt'      => 'nullable|string|max:255',
            'logo'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'alamat'       => 'required|string',
            'link_maps'    => 'nullable|string|max:255',
            'wa_link'      => 'nullable|string|max:255',
            'ig_link'      => 'nullable|string|max:255',
            'hari_kerja'   => 'nullable|array',
            'hari_kerja.*' => 'string',
            'jam_masuk'    => 'required',
            'jam_keluar'   => 'required',
            'kantor_lat'   => 'nullable|numeric',
            'kantor_lng'   => 'nullable|numeric',
            'radius_absen' => 'nullable|integer',
        ]);

        // NORMALISASI DATA 

        $validated['jam_masuk'] = strlen($validated['jam_masuk']) === 5
            ? $validated['jam_masuk'] . ':00'
            : $validated['jam_masuk'];

        $validated['jam_keluar'] = strlen($validated['jam_keluar']) === 5
            ? $validated['jam_keluar'] . ':00'
            : $validated['jam_keluar'];

        $validated['hari_kerja'] = collect($validated['hari_kerja'] ?? [])
            ->map(fn ($day) => strtolower($day))
            ->values()
            ->toArray();

        $validated['radius_absen'] = $validated['radius_absen'] ?? 100;

        // fallback koordinat
        $validated['kantor_lat'] = $validated['kantor_lat'] ?? $kantor->kantor_lat;
        $validated['kantor_lng'] = $validated['kantor_lng'] ?? $kantor->kantor_lng;

        // upload logo baru
        if ($request->hasFile('logo')) {
            if ($kantor->logo && Storage::disk('public')->exists($kantor->logo)) {
                Storage::disk('public')->delete($kantor->logo);
            }
            $validated['logo'] = $request->file('logo')->store('kantor-logo', 'public');
        }

        $kantor->update($validated);

        Cache::forget('kantor_name');

        return redirect()
            ->route('admin.kantors.index')
            ->with('success', 'Data kantor berhasil diperbarui!');
    }

    public function destroy(Kantor $kantor)
    {
        if ($kantor->logo && Storage::disk('public')->exists($kantor->logo)) {
            Storage::disk('public')->delete($kantor->logo);
        }

        $kantor->delete();

        Cache::forget('kantor_name');

        return redirect()
            ->route('admin.kantors.index')
            ->with('success', 'Kantor berhasil dihapus!');
    }
}
