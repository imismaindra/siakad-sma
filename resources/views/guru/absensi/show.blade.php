@extends('layouts.guru')

@section('title', 'Rincian Absensi Pelajaran')

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Rincian Sesi')

@section('guru-content')
<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up" x-data="{
    overrideModal: false,
    selectedSiswa: null,
    overrideUrl: '',
    overrideStatus: 'hadir',
    alasanOverride: '',
    openOverride(siswaId, nama, currentStatus) {
        this.selectedSiswa = { id: siswaId, nama: nama, status: currentStatus };
        this.overrideStatus = currentStatus === 'hadir' ? 'izin' : 'hadir';
        this.alasanOverride = '';
        this.overrideUrl = `{{ url('guru/absensi/' . $absensi->id . '/override') }}/${siswaId}`;
        this.overrideModal = true;
    }
}">
    
    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-title font-display">Rincian Absensi Sesi</h1>
            <p class="page-subtitle">Sesi Kelas: <span class="font-bold font-mono text-navy-500">{{ $absensi->kelas->nama_kelas }}</span> | Tanggal: <span class="font-bold text-slate-800">{{ $absensi->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</span></p>
        </div>
        <div>
            <a href="{{ route('guru.absensi.index') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Details Summary Card --}}
    <div class="card bg-slate-50 border-slate-200">
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6 text-sm border-b border-slate-100">
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Mata Pelajaran</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->mataPelajaran->nama }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jam Pelajaran</span>
                <span class="font-bold text-slate-800 block mt-1 font-mono">
                    {{ \Carbon\Carbon::parse($absensi->jadwalPelajaran->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($absensi->jadwalPelajaran->jam_selesai)->format('H:i') }}
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Ruangan</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->jadwalPelajaran->ruangan ?? '—' }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Total Siswa</span>
                <span class="font-bold text-slate-800 block mt-1 font-mono">{{ $absensi->detailAbsensis->count() }} Orang</span>
            </div>
        </div>
        <div class="p-6 text-sm space-y-3">
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Materi Pembahasan</span>
                <p class="font-medium text-slate-700 mt-1 leading-relaxed">{{ $absensi->materi ?? '—' }}</p>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Catatan Guru</span>
                <p class="font-medium text-slate-700 mt-1 leading-relaxed italic">{{ $absensi->catatan ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Attendance list Card --}}
    <div class="card">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Daftar Kehadiran Siswa</h3>
            <span class="text-xs text-slate-400">Guru berhak melakukan override jika ada siswa berkendala GPS/kamera</span>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>L/P</th>
                        <th>Status</th>
                        <th>Metode</th>
                        <th>Keterangan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensi->detailAbsensis as $idx => $da)
                        <tr>
                            <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="font-mono text-slate-500 text-xs">{{ $da->siswa->nis }}</td>
                            <td class="font-bold text-slate-800">{{ $da->siswa->nama_lengkap }}</td>
                            <td>
                                <span class="badge {{ $da->siswa->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
                                    {{ $da->siswa->jenis_kelamin }}
                                </span>
                            </td>
                            <td>
                                <span class="badge absensi-status-{{ $da->status }}">
                                    {{ ucfirst($da->status) }}
                                </span>
                            </td>
                            <td>
                                @if($da->metode === 'qr')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        QR ({{ $da->waktu_absen ? $da->waktu_absen->format('H:i') : '' }})
                                    </span>
                                @elseif($da->metode === 'override')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200" title="{{ $da->alasan_override }}">
                                        Override Guru
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">
                                        Manual
                                    </span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">{{ $da->keterangan ?? '—' }}</td>
                            <td class="text-right">
                                <button type="button" @click="openOverride({{ $da->siswa->id }}, '{{ addslashes($da->siswa->nama_lengkap) }}', '{{ $da->status }}')" class="px-2.5 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-600 rounded-lg text-xs font-semibold transition cursor-pointer">
                                    Override
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Override Modal (F2-05) --}}
    <div x-show="overrideModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60" @click="overrideModal = false"></div>

            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl border border-slate-100"
                 @click.away="overrideModal = false">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Override Kehadiran Siswa</h3>
                        <p class="text-xs text-slate-500" x-text="selectedSiswa?.nama"></p>
                    </div>
                    <button type="button" @click="overrideModal = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="overrideUrl" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Kehadiran Baru</label>
                        <div class="grid grid-cols-4 gap-2">
                            <label class="flex flex-col items-center p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-center hover:bg-slate-50"
                                   :class="overrideStatus === 'hadir' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : ''">
                                <input type="radio" name="status" value="hadir" x-model="overrideStatus" class="sr-only">
                                <span>Hadir</span>
                            </label>
                            <label class="flex flex-col items-center p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-center hover:bg-slate-50"
                                   :class="overrideStatus === 'sakit' ? 'border-amber-500 bg-amber-50 text-amber-800' : ''">
                                <input type="radio" name="status" value="sakit" x-model="overrideStatus" class="sr-only">
                                <span>Sakit</span>
                            </label>
                            <label class="flex flex-col items-center p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-center hover:bg-slate-50"
                                   :class="overrideStatus === 'izin' ? 'border-blue-500 bg-blue-50 text-blue-800' : ''">
                                <input type="radio" name="status" value="izin" x-model="overrideStatus" class="sr-only">
                                <span>Izin</span>
                            </label>
                            <label class="flex flex-col items-center p-2 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-center hover:bg-slate-50"
                                   :class="overrideStatus === 'alpa' ? 'border-rose-500 bg-rose-50 text-rose-800' : ''">
                                <input type="radio" name="status" value="alpa" x-model="overrideStatus" class="sr-only">
                                <span>Alpa</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Alasan Override <span class="text-rose-500">* (Wajib diisi & tercatat di audit log)</span>
                        </label>
                        <textarea name="alasan_override" x-model="alasanOverride" rows="3" required placeholder="Contoh: Kendala GPS/kamera ponsel di ruang kelas, atau surat izin fisik diterima." class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="overrideModal = false" class="btn-secondary text-xs">
                            Batal
                        </button>
                        <button type="submit" :disabled="!alasanOverride.trim()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                            Simpan Override
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
