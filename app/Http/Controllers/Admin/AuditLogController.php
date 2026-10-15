<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    /**
     * Menampilkan daftar riwayat audit log aktivitas sistem (hanya-baca / append-only).
     */
    public function index(Request $request)
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

        // 3. Filter berdasarkan Event (created, updated, deleted, dll)
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

        // 5. Pencarian teks bebas (deskripsi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

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
        ];

        return view('admin.audit-log.index', compact('logs', 'users', 'logNames', 'events', 'stats'));
    }

    /**
     * Menampilkan detail spesifik dari satu record audit log (JSON / modal support).
     */
    public function show(Activity $activity)
    {
        $activity->load(['causer', 'subject']);

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
            'properties' => $activity->properties,
            'created_at' => $activity->created_at->format('d M Y, H:i:s'),
            'created_at_diff' => $activity->created_at->diffForHumans(),
        ]);
    }
}
