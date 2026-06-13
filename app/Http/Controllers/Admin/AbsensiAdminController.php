<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AbsensiAdminController extends Controller
{
    /**
     * Rekap absensi per kelas.
     */
    public function rekap(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran_id', TahunAjaran::aktif()->value('id'));
        $kelasId = $request->get('kelas_id');

        $absensis = Absensi::with(['kelas.jurusan', 'mataPelajaran', 'guru', 'detailAbsensis'])
            ->where(function ($q) use ($tahunAjaranId) {
                $q->whereHas('jadwalPelajaran', fn ($jq) => $jq->where('tahun_ajaran_id', $tahunAjaranId));
            })
            ->when($kelasId, fn ($q) => $q->where('kelas_id', $kelasId))
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();
        $kelasList = $tahunAjaranId
            ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->with('jurusan')->orderBy('tingkat')->get()
            : collect();

        return view('admin.absensi.rekap', compact('absensis', 'tahunAjarans', 'kelasList', 'tahunAjaranId', 'kelasId'));
    }

    /**
     * Detail absensi satu sesi, admin bisa koreksi.
     */
    public function detail(Absensi $absensi)
    {
        $absensi->load(['jadwalPelajaran', 'kelas.siswas', 'mataPelajaran', 'guru', 'detailAbsensis.siswa']);
        return view('admin.absensi.detail', compact('absensi'));
    }

    /**
     * Admin koreksi status absensi siswa tertentu.
     */
    public function koreksi(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'detail' => ['required', 'array'],
            'detail.*.id' => ['required', 'exists:detail_absensis,id'],
            'detail.*.status' => ['required', 'in:hadir,sakit,izin,alpa'],
            'detail.*.keterangan' => ['nullable', 'string'],
        ]);

        foreach ($validated['detail'] as $item) {
            $absensi->detailAbsensis()->where('id', $item['id'])->update([
                'status' => $item['status'],
                'keterangan' => $item['keterangan'] ?? null,
            ]);
        }

        return back()->with('success', 'Absensi berhasil dikoreksi.');
    }
}
