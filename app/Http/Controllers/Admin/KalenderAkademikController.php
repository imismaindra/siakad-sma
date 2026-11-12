<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KalenderAkademikController extends Controller
{
    /**
     * Tampilkan kalender akademik untuk Admin (F5-04, F5-05).
     */
    public function index(Request $request)
    {
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $kategori = $request->get('kategori');
        $viewMode = $request->get('mode', 'grid'); // 'grid' atau 'list'

        $tahunAjaranAktif = TahunAjaran::aktif()->first();
        $tahunAjarans = TahunAjaran::orderByDesc('nama')->get();

        $query = KalenderAkademik::with(['tahunAjaran', 'pembuat']);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($viewMode === 'list') {
            $events = $query->orderBy('tanggal_mulai', 'asc')
                ->where('tanggal_mulai', '>=', now()->startOfMonth()->toDateString())
                ->paginate(15)
                ->withQueryString();
            $calendarData = null;
        } else {
            // Grid Bulanan
            $events = (clone $query)->bulan($bulan, $tahun)
                ->orderBy('tanggal_mulai', 'asc')
                ->get();

            $calendarData = $this->buildMonthlyCalendar($bulan, $tahun, $events);
        }

        // Summary Stats
        $stats = [
            'total_agenda' => KalenderAkademik::aktif()->count(),
            'total_libur' => KalenderAkademik::aktif()->where('kategori', 'libur')->count(),
            'total_ujian' => KalenderAkademik::aktif()->where('kategori', 'ujian')->count(),
            'total_rapor' => KalenderAkademik::aktif()->where('kategori', 'rapor')->count(),
        ];

        $currentDate = Carbon::create($tahun, $bulan, 1);
        $prevDate = (clone $currentDate)->subMonth();
        $nextDate = (clone $currentDate)->addMonth();

        return view('admin.kalender.index', compact(
            'events',
            'calendarData',
            'bulan',
            'tahun',
            'kategori',
            'viewMode',
            'currentDate',
            'prevDate',
            'nextDate',
            'stats',
            'tahunAjaranAktif',
            'tahunAjarans'
        ));
    }

    /**
     * Simpan agenda kalender baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:libur,ujian,rapor,kegiatan,lainnya'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'string', 'max:10'],
            'jam_selesai' => ['nullable', 'string', 'max:10'],
            'deskripsi' => ['nullable', 'string'],
            'warna' => ['nullable', 'string', 'max:20'],
            'target' => ['required', 'in:semua,guru,siswa,orang_tua'],
            'is_libur' => ['boolean'],
            'is_aktif' => ['boolean'],
            'lewati_akhir_pekan' => ['boolean'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,zip', 'max:10240'],
        ]);

        $isLibur = $request->boolean('is_libur', $validated['kategori'] === 'libur');
        $isAktif = $request->boolean('is_aktif', true);
        $lewatiAkhirPekan = $request->boolean('lewati_akhir_pekan', false);

        $dokumenPath = null;
        $dokumenNama = null;
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $dokumenPath = $file->store('kalender_dokumen', 'public');
        }

        $agenda = KalenderAkademik::create([
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? TahunAjaran::aktif()->value('id'),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'dokumen' => $dokumenPath,
            'dokumen_nama' => $dokumenNama,
            'kategori' => $validated['kategori'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? $validated['tanggal_mulai'],
            'jam_mulai' => ! empty($validated['jam_mulai']) ? $validated['jam_mulai'] : null,
            'jam_selesai' => ! empty($validated['jam_selesai']) ? $validated['jam_selesai'] : null,
            'warna' => $validated['warna'] ?? $this->defaultColorForCategory($validated['kategori']),
            'target' => $validated['target'],
            'is_libur' => $isLibur,
            'is_aktif' => $isAktif,
            'lewati_akhir_pekan' => $lewatiAkhirPekan,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', "Agenda '{$agenda->judul}' berhasil ditambahkan ke kalender.");
    }

    /**
     * Update agenda kalender.
     */
    public function update(Request $request, KalenderAkademik $kalender)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:libur,ujian,rapor,kegiatan,lainnya'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jam_mulai' => ['nullable', 'string', 'max:10'],
            'jam_selesai' => ['nullable', 'string', 'max:10'],
            'deskripsi' => ['nullable', 'string'],
            'warna' => ['nullable', 'string', 'max:20'],
            'target' => ['required', 'in:semua,guru,siswa,orang_tua'],
            'is_libur' => ['boolean'],
            'is_aktif' => ['boolean'],
            'lewati_akhir_pekan' => ['boolean'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
            'dokumen' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp,zip', 'max:10240'],
            'hapus_dokumen' => ['boolean'],
        ]);

        $isLibur = $request->boolean('is_libur', $validated['kategori'] === 'libur');
        $isAktif = $request->boolean('is_aktif', true);
        $lewatiAkhirPekan = $request->boolean('lewati_akhir_pekan', false);

        $dokumenPath = $kalender->dokumen;
        $dokumenNama = $kalender->dokumen_nama;

        if ($request->boolean('hapus_dokumen')) {
            if ($dokumenPath && Storage::disk('public')->exists($dokumenPath)) {
                Storage::disk('public')->delete($dokumenPath);
            }
            $dokumenPath = null;
            $dokumenNama = null;
        }

        if ($request->hasFile('dokumen')) {
            if ($kalender->dokumen && Storage::disk('public')->exists($kalender->dokumen)) {
                Storage::disk('public')->delete($kalender->dokumen);
            }
            $file = $request->file('dokumen');
            $dokumenNama = $file->getClientOriginalName();
            $dokumenPath = $file->store('kalender_dokumen', 'public');
        }

        $kalender->update([
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? $kalender->tahun_ajaran_id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'dokumen' => $dokumenPath,
            'dokumen_nama' => $dokumenNama,
            'kategori' => $validated['kategori'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? $validated['tanggal_mulai'],
            'jam_mulai' => ! empty($validated['jam_mulai']) ? $validated['jam_mulai'] : null,
            'jam_selesai' => ! empty($validated['jam_selesai']) ? $validated['jam_selesai'] : null,
            'warna' => $validated['warna'] ?? $this->defaultColorForCategory($validated['kategori']),
            'target' => $validated['target'],
            'is_libur' => $isLibur,
            'is_aktif' => $isAktif,
            'lewati_akhir_pekan' => $lewatiAkhirPekan,
        ]);

        return back()->with('success', "Agenda '{$kalender->judul}' berhasil diperbarui.");
    }

    /**
     * Hapus agenda kalender (Soft Delete).
     */
    public function destroy(KalenderAkademik $kalender)
    {
        $judul = $kalender->judul;
        $kalender->delete();

        return back()->with('success', "Agenda '{$judul}' berhasil dihapus dari kalender.");
    }

    /**
     * Struktur matriks kalender bulanan.
     */
    private function buildMonthlyCalendar(int $bulan, int $tahun, $events): array
    {
        $firstDay = Carbon::create($tahun, $bulan, 1);
        $startOfCalendar = (clone $firstDay)->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = (clone $firstDay)->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $weeks = [];
        $current = clone $startOfCalendar;

        while ($current <= $endOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $dateStr = $current->toDateString();
                
                $matchingEvents = $events->filter(function ($ev) use ($dateStr, $current) {
                    $start = Carbon::parse($ev->tanggal_mulai)->toDateString();
                    $end = Carbon::parse($ev->tanggal_selesai ?? $ev->tanggal_mulai)->toDateString();
                    
                    if ($dateStr < $start || $dateStr > $end) {
                        return false;
                    }

                    // Optimasi akhir pekan: jika lewati_akhir_pekan dicentang dan hari ini Sabtu/Minggu
                    if ($ev->lewati_akhir_pekan && ($current->isSaturday() || $current->isSunday())) {
                        return false;
                    }

                    return true;
                });

                $week[] = [
                    'date' => clone $current,
                    'dayNumber' => $current->day,
                    'isCurrentMonth' => $current->month === $bulan,
                    'isToday' => $current->isToday(),
                    'isSunday' => $current->isSunday(),
                    'isSaturday' => $current->isSaturday(),
                    'events' => $matchingEvents,
                ];

                $current->addDay();
            }
            $weeks[] = $week;
        }

        return $weeks;
    }

    private function defaultColorForCategory(string $category): string
    {
        return match ($category) {
            'libur' => 'rose',
            'ujian' => 'amber',
            'rapor' => 'purple',
            'kegiatan' => 'blue',
            default => 'slate',
        };
    }
}
