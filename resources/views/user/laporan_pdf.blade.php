<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi</title>

    <style>
        @page {
            size: A4;
            margin: 135px 40px 120px 40px;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
            color: #000;
        }

        /* ================= HEADER ================= */
        .page-header {
            position: fixed;
            top: -110px;
            left: 0;
            right: 0;
            height: 75px;
            text-align: center;
            border-bottom: 2px solid #123B6E;
            padding-top: 6px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .page-header h2 {
            margin: 4px 0 2px;
            font-size: 13px;
            font-weight: bold;
            color: #123B6E;
        }

        .page-header p {
            margin: 0;
            font-size: 10px;
        }

        /* ================= INFO PESERTA ================= */
        table.info {
            width: 100%;
            border-collapse: collapse;
        }

        table.info td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .label { width: 16%; font-weight: bold; white-space: nowrap; }
        .colon { width: 2%; }
        .value { width: 32%; }

        /* ================= TABEL PRESENSI ================= */
        table.presensi {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        table.presensi thead {
            display: table-header-group;
        }

        table.presensi th,
        table.presensi td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        table.presensi th {
            background-color: #123B6E;
            color: #fff;
            text-align: center;
            font-weight: bold;
        }

        .clear { clear: both; }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<div class="page-header">
    <h1>LAPORAN PRESENSI PESERTA MAGANG</h1>
    <h2>{{ $kantor->nama_pt ?? '-' }}</h2>
    <p>{{ $kantor->alamat ?? '-' }}</p>
</div>

{{-- ================= IDENTITAS PESERTA ================= --}}
<table class="info">
    <tr>
        <td class="label">Nama Lengkap</td>
        <td class="colon">:</td>
        <td class="value">{{ $user->name }}</td>

        <td class="label">Bagian</td>
        <td class="colon">:</td>
        <td class="value">{{ optional($user->profile->bagian)->nama ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">NIM</td>
        <td class="colon">:</td>
        <td class="value">{{ $user->profile->nomor_induk ?? '-' }}</td>

        <td class="label">Pembimbing</td>
        <td class="colon">:</td>
        <td class="value">{{ optional($user->profile->pembimbing->user)->name ?? '-' }}</td>
    </tr>

    <tr>
        <td class="label">Asal Sekolah</td>
        <td class="colon">:</td>
        <td class="value">{{ $user->profile->pendidikan ?? '-' }}</td>

        <td class="label">Periode</td>
        <td class="colon">:</td>
        <td class="value">
            {{ optional($user->profile->tgl_masuk)->format('d-m-Y') ?? '-' }}
            s/d
            {{ optional($user->profile->tgl_keluar)->format('d-m-Y') ?? '-' }}
        </td>
    </tr>

    <tr>
        <td class="label">Jurusan</td>
        <td class="colon">:</td>
        <td class="value">{{ $user->profile->jurusan ?? '-' }}</td>

        <td class="label">Jam Kerja</td>
        <td class="colon">:</td>
        <td class="value">
            {{ $kantor->jam_masuk ?? '-' }} s/d {{ $kantor->jam_keluar ?? '-' }}
        </td>
    </tr>
</table>

@php
    $totalPresensi = $presensi->count();
@endphp

{{-- ================= PRESENSI ≤ 7 (TTD 1 KALI) ================= --}}
@if($totalPresensi <= 7)

<table class="presensi">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="15%">Jam Masuk</th>
            <th width="15%">Jam Keluar</th>
            <th width="50%">Catatan Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($presensi as $i => $p)
        <tr>
            <td align="center">{{ $i + 1 }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
            <td align="center">{{ $p->jam_masuk ?? '-' }}</td>
            <td align="center">{{ $p->jam_keluar ?? '-' }}</td>
            <td>
                @if($p->status === 'tidak_hadir' && empty($p->keterangan))
                    <em>Tanpa keterangan</em>
                @else
                    {{ $p->keterangan ?? '-' }}
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" align="center">Tidak ada data presensi</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- TTD --}}
<div style="margin-top:40px;">
    <div style="float:right; width:45%; text-align:center;">
        <p style="margin-bottom:60px; font-weight:bold;">Pembimbing Lapangan</p>
        <p style="margin:0; font-weight:bold; text-decoration:underline;">
            {{ optional($user->profile->pembimbing->user)->name ?? '-' }}
        </p>
        <p style="margin-top:4px; font-size:11px;">
            NIP. {{ optional($user->profile->pembimbing)->nip ?? '-' }}
        </p>
    </div>
    <div class="clear"></div>
</div>

@endif

{{-- ================= PRESENSI > 7 (TTD PER MINGGU) ================= --}}
@if($totalPresensi > 7)

@php
    $groupedPresensi = $presensi->groupBy(function ($p) {
        return \Carbon\Carbon::parse($p->tanggal)->format('o-W');
    });
@endphp

@foreach($groupedPresensi as $items)

<table class="presensi">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="15%">Jam Masuk</th>
            <th width="15%">Jam Keluar</th>
            <th width="50%">Catatan Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $i => $p)
        <tr>
            <td align="center">{{ $i + 1 }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
            <td align="center">{{ $p->jam_masuk ?? '-' }}</td>
            <td align="center">{{ $p->jam_keluar ?? '-' }}</td>
            <td>
                @if($p->status === 'tidak_hadir' && empty($p->keterangan))
                    <em>Tanpa keterangan</em>
                @else
                    {{ $p->keterangan ?? '-' }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- TTD TIAP MINGGU --}}
<div style="margin-top:40px; page-break-inside: avoid;">
    <div style="float:right; width:45%; text-align:center;">
        <p style="margin-bottom:60px; font-weight:bold;">Pembimbing Lapangan</p>
        <p style="margin:0; font-weight:bold; text-decoration:underline;">
            {{ optional($user->profile->pembimbing->user)->name ?? '-' }}
        </p>
        <p style="margin-top:4px; font-size:11px;">
            NIP. {{ optional($user->profile->pembimbing)->nip ?? '-' }}
        </p>
    </div>
    <div class="clear"></div>
</div>

@endforeach
@endif

</body>
</html>
