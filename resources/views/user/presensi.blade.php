<x-appuser-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <span class="font-semibold text-[#0D1B2A]">Buat Presensi</span>
    </x-slot>

    {{-- USER TOAST (frontend) --}}
    <x-user-toast />

{{-- DATA KANTOR --}}
<input type="hidden" id="kantorLat" value="{{ $kantor->kantor_lat }}">
<input type="hidden" id="kantorLng" value="{{ $kantor->kantor_lng }}">
<input type="hidden" id="radiusKantor" value="{{ $kantor->radius_absen }}">

{{-- STATUS --}}
<input type="hidden" id="sudahMasuk"
    value="{{ $presensiHariIni?->jam_masuk ? '1' : '0' }}">

<div class="space-y-4">

    {{-- INFO KANTOR --}}
    <div class="bg-white p-6 rounded-2xl shadow space-y-3">

        <p class="font-semibold text-gray-800 flex items-center gap-2">
            <x-heroicon-o-building-office-2 class="w-5 h-5 text-blue-600"/>
            {{ $kantor->nama_apk }}
        </p>

        <div class="flex items-center gap-2 text-gray-600 text-sm">
            <x-heroicon-o-clock class="w-5 h-5 text-blue-500"/>
            <span>
                {{ $kantor->jam_masuk }} - {{ $kantor->jam_keluar }}
            </span>
        </div>

        <div class="flex items-center gap-2 text-gray-600 text-sm">
            <x-heroicon-o-scale class="w-5 h-5 text-blue-500"/>
            <span>
                Radius Presensi {{ $kantor->radius_absen }} meter
            </span>
        </div>

    </div>

    {{-- STATUS LOKASI --}}
    <div class="bg-white p-6 rounded-2xl shadow space-y-3">

        <p id="statusLokasi"
            class="font-semibold text-gray-500 flex items-center gap-2">
            <x-heroicon-o-map-pin class="w-5 h-5"/>
            Lokasi belum diambil
        </p>

        <div class="flex items-center gap-2 text-gray-600 text-sm">
            <x-heroicon-o-globe-alt class="w-5 h-5 text-blue-500"/>
            <span id="latLngText">-</span>
        </div>

        <div class="flex items-center gap-2 text-gray-600 text-sm">
            <x-heroicon-o-arrows-right-left class="w-5 h-5 text-blue-500"/>
            <span id="jarakText">-</span>
        </div>

    </div>

    {{-- AMBIL LOKASI --}}
    <button type="button" id="ambilLokasi"
        class="w-full flex items-center justify-center gap-2
               bg-blue-600 hover:bg-blue-700 text-white
               py-3 rounded-xl font-semibold transition">
        <x-heroicon-o-map class="w-5 h-5"/>
        Ambil Lokasi
    </button>

    {{-- FORM PRESENSI --}}
    <form id="formPresensi" method="POST"
        action="{{ route('user.presensi.store') }}">
        @csrf

        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lng">

        <div class="flex gap-3 mt-3">


        {{-- MASUK --}}
        <button type="button" id="btnMasuk"
            data-allowed="{{ $bisaPresensiMasuk ? '1' : '0' }}"
            disabled
            class="flex-1 flex items-center justify-center gap-2
                bg-green-600 text-white py-3 rounded-xl
                font-semibold opacity-50">
            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5"/>
            Masuk
        </button>

        {{-- KELUAR --}}
        <button type="button" id="btnKeluar"
            disabled
            class="flex-1 flex items-center justify-center gap-2
                bg-blue-600 text-white py-3 rounded-xl
                font-semibold opacity-50">
            <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5"/>
            Keluar
        </button>

        {{-- TIDAK HADIR --}}
        <button type="button" id="btnTidakHadir"
            @if($presensiHariIni?->jam_masuk) disabled @endif
            class="flex-1 flex items-center justify-center gap-2
                bg-red-600 text-white py-3 rounded-xl
                font-semibold
                @if($presensiHariIni?->jam_masuk) opacity-50 cursor-not-allowed @endif">
            <x-heroicon-o-x-circle class="w-5 h-5"/>
            Tidak Hadir
        </button>

            </div>
        </form>

        {{-- INFO PRESENSI (MOBILE FRIENDLY) --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 text-sm text-blue-900 space-y-3">

            <p class="font-semibold flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600"/>
                Informasi Presensi
            </p>

            <ul class="space-y-2">

                <li class="flex items-start gap-2">
                    <span>
                        Tekan <b>Ambil Lokasi</b> dan pastikan status <b>valid</b>.
                    </span>
                </li>

                <li class="flex items-start gap-2">
                    <span>
                        <b>Masuk</b> hanya dapat dilakukan jika berada dalam radius kantor.
                    </span>
                </li>

                <li class="flex items-start gap-2">
                    <span>
                        <b>Keluar</b> setelah absen masuk dengan mengisi ringkasan pekerjaan.
                    </span>
                </li>

                <li class="flex items-start gap-2">
                    <span>
                        <b>Tidak Hadir</b> hanya sebelum absen masuk, tanpa lokasi
                        (pilih <b>Izin</b> atau <b>Sakit</b>).
                    </span>
                </li>

            </ul>
        </div>


    </div>

    {{-- MODAL KETERANGAN --}}
    <div id="modalKeterangan"
        class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl w-11/12 max-w-md space-y-4">

            <h3 id="judulModal" class="font-semibold text-lg">Keterangan</h3>

            {{-- IZIN / SAKIT --}}
            <div id="opsiStatus" class="flex gap-3">
                <button type="button" id="btnIzin"
                    class="flex-1 py-2 rounded-xl border border-yellow-400 text-yellow-600">
                    Izin
                </button>
                <button type="button" id="btnSakit"
                    class="flex-1 py-2 rounded-xl border border-red-400 text-red-600">
                    Sakit
                </button>
            </div>

            <textarea id="inputKeterangan"
                class="w-full border rounded-xl p-2"
                placeholder=""></textarea>

            <div class="flex justify-end gap-2">
                <button type="button" id="batalKeterangan"
                    class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl">
                    Batal
                </button>
                <button type="button" id="lanjutKeterangan"
                    class="px-4 py-2 bg-[#123B6E] text-white hover:bg-[#0F325C] text-white rounded-xl">
                    Lanjut
                </button>
            </div>
        </div>
    </div>

    {{-- CONFIRM --}}
    <x-user-confirm-modal
        id="confirm-submit"
        title="Konfirmasi Presensi"
        message="Apakah Anda yakin ingin mengirimkan presensi?"
    >
        <x-user-button
            type="button"
            @click="
                $dispatch('close-user-confirm', { id: 'confirm-submit' });
                $dispatch('user-toast', {
                    title: 'Diproses',
                    message: 'Presensi sedang diproses...'
                });
                document.getElementById('formPresensi').submit();
            "
        >
            Ya, Kirim
        </x-user-button>
    </x-user-confirm-modal>

<script>
let lokasiDiambil = false;
let lokasiValid   = false;
let presensiType  = '';
let statusTidakHadir = '';

const ambil   = document.getElementById('ambilLokasi');
const btnMasuk = document.getElementById('btnMasuk');
const btnKeluar = document.getElementById('btnKeluar');
const btnTidakHadir = document.getElementById('btnTidakHadir');

const modal = document.getElementById('modalKeterangan');
const inputKet = document.getElementById('inputKeterangan');
const opsiStatus = document.getElementById('opsiStatus');
const judulModal = document.getElementById('judulModal');

const btnIzin = document.getElementById('btnIzin');
const btnSakit = document.getElementById('btnSakit');

const sudahMasuk = document.getElementById('sudahMasuk').value === '1';
const bolehMasuk = btnMasuk.dataset.allowed === '1';

/* ================= AMBIL LOKASI ================= */
ambil.onclick = () => {

    const statusLokasiEl = document.getElementById('statusLokasi');

    const iconValid = `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
    `;

    const iconInvalid = `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600"
            fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
    `;

    // status awal
    statusLokasiEl.innerHTML = `
        <span class="flex items-center gap-2 text-gray-500 font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-spin"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v2m0 12v2m8-8h-2M6 12H4m12.364-5.364l-1.414 1.414M6.05 17.95l-1.414 1.414m12.728 0l-1.414-1.414M6.05 6.05L4.636 4.636" />
            </svg>
            Mengambil lokasi...
        </span>
    `;

    navigator.geolocation.getCurrentPosition(
        pos => {
            lokasiDiambil = true;

            const lat = parseFloat(pos.coords.latitude.toFixed(8));
            const lng = parseFloat(pos.coords.longitude.toFixed(8));

            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;


            const kantorLat = parseFloat(document.getElementById('kantorLat').value);
            const kantorLng = parseFloat(document.getElementById('kantorLng').value);
            const radius    = parseInt(document.getElementById('radiusKantor').value);

            const R = 6371000;
            const dLat = (lat - kantorLat) * Math.PI / 180;
            const dLng = (lng - kantorLng) * Math.PI / 180;

            const a = Math.sin(dLat / 2) ** 2 +
                Math.cos(kantorLat * Math.PI / 180) *
                Math.cos(lat * Math.PI / 180) *
                Math.sin(dLng / 2) ** 2;

            const jarak = R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));

            document.getElementById('latLngText').innerText =
                `Lat ${lat.toFixed(8)}, Lng ${lng.toFixed(8)}`;
            document.getElementById('jarakText').innerText =
                `Jarak ${jarak.toFixed(2)} meter`;

            if (jarak <= radius) {
                lokasiValid = true;

                statusLokasiEl.innerHTML = `
                    <span class="flex items-center gap-2 text-green-600 font-semibold">
                        ${iconValid}
                        Lokasi valid
                    </span>
                `;

                if (bolehMasuk && !sudahMasuk) {
                    btnMasuk.disabled = false;
                    btnMasuk.classList.remove('opacity-50');
                }

                if (sudahMasuk) {
                    btnKeluar.disabled = false;
                    btnKeluar.classList.remove('opacity-50');
                }

            } else {
                lokasiValid = false;

                statusLokasiEl.innerHTML = `
                    <span class="flex items-center gap-2 text-red-600 font-semibold">
                        ${iconInvalid}
                        Di luar radius kantor
                    </span>
                `;
            }
        },
        () => {
            // gagal ambil lokasi (tanpa toast & alert)
            lokasiDiambil = false;
            lokasiValid = false;

            statusLokasiEl.innerHTML = `
                <span class="flex items-center gap-2 text-red-600 font-semibold">
                    ${iconInvalid}
                    Gagal mengambil lokasi
                </span>
            `;
        }
    );
};

/* ================= MASUK ================= */
btnMasuk.onclick = () => {
    if (!lokasiDiambil || !lokasiValid) {
        return window.dispatchEvent(new CustomEvent('user-toast', {
            detail: { title: 'Perhatian', message: 'Ambil lokasi terlebih dahulu' }
        }));
    }

    presensiType = 'masuk';
    injectHidden('type', 'masuk');
    openConfirm();
};

/* ================= KELUAR ================= */
btnKeluar.onclick = () => {
    presensiType = 'keluar';

    judulModal.innerText = 'Keterangan Pekerjaan Hari Ini';
    opsiStatus.style.display = 'none';
    inputKet.placeholder = 'Tuliskan ringkasan pekerjaan hari ini...';

    modal.classList.remove('hidden');
};

/* ================= TIDAK HADIR ================= */
btnTidakHadir.onclick = () => {
    if (sudahMasuk) return;

    presensiType = 'tidak_hadir';
    statusTidakHadir = '';

    judulModal.innerText = 'Keterangan Tidak Hadir';
    opsiStatus.style.display = 'flex';
    inputKet.placeholder = 'Tuliskan keterangan izin / sakit...';

    resetStatusBtn();
    modal.classList.remove('hidden');
};

/* ===== IZIN / SAKIT UI ===== */
btnIzin.onclick = () => {
    statusTidakHadir = 'izin';
    resetStatusBtn();
    btnIzin.classList.add('bg-yellow-500', 'text-white');
};

btnSakit.onclick = () => {
    statusTidakHadir = 'sakit';
    resetStatusBtn();
    btnSakit.classList.add('bg-red-500', 'text-white');
};

function resetStatusBtn() {
    btnIzin.className = 'flex-1 py-2 rounded-xl border border-yellow-400 text-yellow-600';
    btnSakit.className = 'flex-1 py-2 rounded-xl border border-red-400 text-red-600';
}

/* ================= MODAL ACTION ================= */
document.getElementById('batalKeterangan').onclick =
    () => modal.classList.add('hidden');

document.getElementById('lanjutKeterangan').onclick = () => {

    if (presensiType === 'tidak_hadir' && !statusTidakHadir) {
        return window.dispatchEvent(new CustomEvent('user-toast', {
            detail: { title: 'Perhatian', message: 'Pilih izin atau sakit' }
        }));
    }

    injectHidden('type', presensiType);

    if (presensiType === 'tidak_hadir') {
        injectHidden('status', statusTidakHadir);
    }

    injectTextarea('keterangan', inputKet.value);

    modal.classList.add('hidden');
    openConfirm();
};

/* ================= UTIL ================= */
function injectHidden(name, value) {
    document.querySelectorAll(`[name="${name}"]`).forEach(el => el.remove());
    const i = document.createElement('input');
    i.type = 'hidden';
    i.name = name;
    i.value = value;
    document.getElementById('formPresensi').appendChild(i);
}

function injectTextarea(name, value) {
    document.querySelectorAll(`[name="${name}"]`).forEach(el => el.remove());
    const t = document.createElement('textarea');
    t.name = name;
    t.value = value;
    t.classList.add('hidden');
    document.getElementById('formPresensi').appendChild(t);
}

function openConfirm() {
    window.dispatchEvent(new CustomEvent('open-user-confirm', {
        detail: { id: 'confirm-submit' }
    }));
}
</script>

</x-appuser-layout>
