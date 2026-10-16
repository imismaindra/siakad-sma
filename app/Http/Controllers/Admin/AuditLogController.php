<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    /**
     * Membangun query builder terfilter untuk Audit Log (digunakan bersama oleh index dan export).
     */
    protected function buildFilteredQuery(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])->latest();

        // 1. Filter berdasarkan Pelaku (causer_id)
        if ($request->filled('causer_id')) {
            $query->where('causer_type', User::class)
                  ->where('causer_id', $request->causer_id);
        }

        // 2. Filter berdasarkan Jenis Data / Log Name
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // 3. Filter berdasarkan Event (created, updated, deleted, override, anomali_lokasi, dll)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // 4. Filter berdasarkan Rentang Tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // 5. Pencarian teks bebas (deskripsi, properties, IP)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Menampilkan daftar riwayat audit log aktivitas sistem (hanya-baca / append-only).
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        $logs = $query->paginate(20)->withQueryString();

        // Data untuk dropdown filter
        $users = User::select('id', 'name', 'role')->orderBy('name')->get();
        $logNames = Activity::distinct()->pluck('log_name')->filter()->values();
        $events = Activity::distinct()->pluck('event')->filter()->values();

        // Statistik ringkas
        $stats = [
            'total' => Activity::count(),
            'today' => Activity::whereDate('created_at', today())->count(),
            'users' => Activity::whereNotNull('causer_id')->distinct('causer_id')->count('causer_id'),
            'overrides' => Activity::where('event', 'override')->count(),
        ];

        return view('admin.audit-log.index', compact('logs', 'users', 'logNames', 'events', 'stats'));
    }

    /**
     * Menampilkan detail spesifik dari satu record audit log (JSON / modal support).
     */
    public function show(Activity $activity)
    {
        $activity->load(['causer', 'subject']);
        $props = $activity->properties ? $activity->properties->toArray() : [];

        return response()->json([
            'id' => $activity->id,
            'log_name' => $activity->log_name,
            'description' => $activity->description,
            'event' => $activity->event,
            'causer' => $activity->causer ? [
                'name' => $activity->causer->name ?? 'User',
                'role' => $activity->causer->role ?? '-',
                'email' => $activity->causer->email ?? '-',
            ] : null,
            'subject_type' => class_basename($activity->subject_type ?? ''),
            'subject_id' => $activity->subject_id,
            'ip' => $props['ip'] ?? $props['ip_address'] ?? '127.0.0.1',
            'user_agent' => $props['user_agent'] ?? '-',
            'properties' => $activity->properties,
            'created_at' => $activity->created_at->format('d M Y, H:i:s'),
            'created_at_diff' => $activity->created_at->diffForHumans(),
        ]);
    }

    /**
     * Mengekspor data audit log ke file CSV (berdasarkan filter aktif) untuk keperluan audit resmi.
     */
    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $filename = 'audit-log-siakad-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar kompatibel dengan Excel pada Windows
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Waktu',
                'Pelaku',
                'Role Pelaku',
                'IP Address',
                'User Agent',
                'Modul',
                'Aksi / Event',
                'Subjek Model',
                'Subjek ID',
                'Deskripsi',
                'Properti Mutasi (JSON)',
            ]);

            $query->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    $props = $log->properties ? $log->properties->toArray() : [];
                    $ip = $props['ip'] ?? $props['ip_address'] ?? '-';
                    $ua = $props['user_agent'] ?? '-';

                    fputcsv($handle, [
                        $log->id,
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->causer?->name ?? 'Sistem / Otomatis',
                        $log->causer?->role ?? '-',
                        $ip,
                        $ua,
                        $log->log_name ?? '-',
                        $log->event ?? '-',
                        class_basename($log->subject_type ?? ''),
                        $log->subject_id ?? '-',
                        $log->description ?? '-',
                        json_encode($log->properties, JSON_UNESCAPED_UNICODE),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}

