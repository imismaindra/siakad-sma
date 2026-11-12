<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KalenderAkademikSharedController extends Controller
{
    /**
     * Tampilan kalender akademik bagi Guru, Siswa, dan Orang Tua (F5-05).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', now()->year);
        $kategori = $request->get('kategori');
        $viewMode = $request->get('mode', 'grid');

        $query = KalenderAkademik::forUser($user)->with('tahunAjaran');

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
            $events = (clone $query)->bulan($bulan, $tahun)
                ->orderBy('tanggal_mulai', 'asc')
                ->get();

            $calendarData = $this->buildMonthlyCalendar($bulan, $tahun, $events);
        }

        $currentDate = Carbon::create($tahun, $bulan, 1);
        $prevDate = (clone $currentDate)->subMonth();
        $nextDate = (clone $currentDate)->addMonth();

        $tahunAktif = TahunAjaran::aktif()->first();

        // Tentukan layout berdasarkan peran aktif
        $roleLayout = match (true) {
            $user->isGuru() => 'guru',
            $user->isSiswa() => 'siswa',
            $user->isOrangTua() => 'orang-tua',
            default => 'admin',
        };

        // Jika Orang Tua, ambil data anak aktif untuk header context
        $activeSiswa = null;
        $siswas = collect();
        if ($user->isOrangTua() && $user->orangTua) {
            $siswas = $user->orangTua->siswas()->with('kelas')->get();
            $activeId = session('active_anak_id');
            $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();
        }

        $viewName = match ($roleLayout) {
            'guru' => 'guru.kalender',
            'siswa' => 'siswa.kalender',
            'orang-tua' => 'orang-tua.kalender',
            default => 'admin.kalender.index',
        };

        return view($viewName, compact(
            'events',
            'calendarData',
            'bulan',
            'tahun',
            'kategori',
            'viewMode',
            'currentDate',
            'prevDate',
            'nextDate',
            'tahunAktif',
            'roleLayout',
            'activeSiswa',
            'siswas'
        ));
    }

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
}
