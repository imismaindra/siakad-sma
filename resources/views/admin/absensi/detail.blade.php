@extends('layouts.admin')

@section('title', 'Koreksi Absensi Kelas')

@section('breadcrumb-parent', 'Absensi')
@section('breadcrumb-current', 'Koreksi')

@section('admin-content')
<div class="space-y-6 animate-fade-in-up">
    
    {{-- Page Header --}}
    <div class="page-header flex-col sm:flex-row gap-4 items-start sm:items-center">
        <div>
            <h1 class="page-title font-display">Koreksi Absensi Sesi</h1>
            <p class="page-subtitle">Sesi Kelas: <span class="font-bold font-mono text-navy-500">{{ $absensi->kelas->nama_kelas }}</span> | Tanggal: <span class="font-bold text-slate-800">{{ $absensi->tanggal->locale('id')->isoFormat('D MMMM YYYY') }}</span></p>
        </div>
        <div>
            <a href="{{ route('admin.absensi.rekap') }}" class="btn-secondary">
                Kembali
            </a>
        </div>
    </div>

    {{-- Details Summary Card --}}
    <div class="card bg-slate-50 border-slate-200">
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6 text-sm">
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Mata Pelajaran</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->mataPelajaran->nama }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Guru Pengisi</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->guru->nama_lengkap }}</span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Jam Pelajaran</span>
                <span class="font-bold text-slate-800 block mt-1 font-mono">
                    {{ \Carbon\Carbon::parse($absensi->jadwalPelajaran->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($absensi->jadwalPelajaran->jam_selesai)->format('H:i') }} (Jam Ke-{{ $absensi->jadwalPelajaran->urutan_jam }})
                </span>
            </div>
            <div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Ruangan</span>
                <span class="font-bold text-slate-800 block mt-1">{{ $absensi->jadwalPelajaran->ruangan ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="text-sm font-bold font-display text-slate-800 uppercase tracking-wider">Daftar Kehadiran Siswa</h3>
        </div>
        <form action="{{ route('admin.absensi.koreksi', $absensi) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>NIS / NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th class="w-48">Status Kehadiran</th>
                            <th>Keterangan Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($absensi->detailAbsensis as $idx => $da)
                            <tr>
                                <td class="text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="font-mono text-slate-500 text-xs">
                                    {{ $da->siswa->nis }} / {{ $da->siswa->nisn ?? '—' }}
                                    <input type="hidden" name="detail[{{ $idx }}][id]" value="{{ $da->id }}">
                                </td>
                                <td class="font-bold text-slate-800">{{ $da->siswa->nama_lengkap }}</td>
                                <td>
                                    <span class="badge {{ $da->siswa->jenis_kelamin == 'L' ? 'badge-info' : 'badge-purple' }}">
                                        {{ $da->siswa->jenis_kelamin }}
                                    </span>
                                </td>
                                <td>
                                    <select name="detail[{{ $idx }}][status]" required class="form-select py-1.5 text-xs font-semibold">
                                        <option value="hadir" class="text-emerald-600 font-bold" {{ $da->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                        <option value="sakit" class="text-blue-500 font-bold" {{ $da->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                        <option value="izin" class="text-amber-500 font-bold" {{ $da->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                        <option value="alpa" class="text-rose-500 font-bold" {{ $da->status == 'alpa' ? 'selected' : '' }}>Alpa</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="detail[{{ $idx }}][keterangan]" value="{{ old("detail.{$idx}.keterangan", $da->keterangan) }}" 
                                        class="form-input py-1.5 text-xs" placeholder="Alasan tidak hadir, keterangan sakit, dll.">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.absensi.rekap') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    Simpan Koreksi Absensi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
