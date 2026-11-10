<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'deskripsi' => ['nullable', 'string'],
            'warna' => ['nullable', 'string', 'max:20'],
            'target' => ['required', 'in:semua,guru,siswa,orang_tua'],
            'is_libur' => ['boolean'],
            'is_aktif' => ['boolean'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
        ]);

        $isLibur = $request->boolean('is_libur', $validated['kategori'] === 'libur');
        $isAktif = $request->boolean('is_aktif', true);

        $agenda = KalenderAkademik::create([
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? TahunAjaran::aktif()->value('id'),
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kategori' => $validated['kategori'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? $validated['tanggal_mulai'],
            'warna' => $validated['warna'] ?? $this->defaultColorForCategory($validated['kategori']),
            'target' => $validated['target'],
            'is_libur' => $isLibur,
            'is_aktif' => $isAktif,
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
            'deskripsi' => ['nullable', 'string'],
            'warna' => ['nullable', 'string', 'max:20'],
            'target' => ['required', 'in:semua,guru,siswa,orang_tua'],
            'is_libur' => ['boolean'],
            'is_aktif' => ['boolean'],
            'tahun_ajaran_id' => ['nullable', 'exists:tahun_ajarans,id'],
        ]);

        $isLibur = $request->boolean('is_libur', $validated['kategori'] === 'libur');
        $isAktif = $request->boolean('is_aktif', true);

        $kalender->update([
            'tahun_ajaran_id' => $validated['tahun_ajaran_id'] ?? $kalender->tahun_ajaran_id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'kategori' => $validated['kategori'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? $validated['tanggal_mulai'],
            'warna' => $validated['warna'] ?? $this->defaultColorForCategory($validated['kategori']),
            'target' => $validated['target'],
            'is_libur' => $isLibur,
            'is_aktif' => $isAktif,
        ]);

        return back()->with('success', "Agenda '{$kalender->judul}' berhasil diperbarui.");
    }

    /**
     * Hapus agenda kalender.
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
                
                $matchingEvents = $events->filter(function ($ev) use ($dateStr) {
                    $start = $ev->tanggal_mulai->toDateString();
                    $end = ($ev->tanggal_selesai ?? $ev->tanggal_mulai)->toDateString();
                    return $dateStr >= $start && $dateStr <= $end;
                });

                $week[] = [
                    'date' => clone $current,
                    'dayNumber' => $current->day,
                    'isCurrentMonth' => $current->month === $bulan,
                    'isToday' => $current->isToday(),
                    'isSunday' => $current->isSunday(),
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
