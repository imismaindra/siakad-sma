<?php

namespace App\Http\Controllers;

use App\Models\UserNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan Pusat Notifikasi Pengguna (F6-02).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all'); // 'all', 'unread', or type
        $type = $request->get('type');

        $query = $user->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }

        if ($type) {
            $query->where('data->type', $type);
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $unreadCount = $user->unreadNotifications()->count();

        $roleLayout = match (true) {
            $user->isGuru() => 'guru',
            $user->isSiswa() => 'siswa',
            $user->isOrangTua() => 'orang-tua',
            default => 'admin',
        };

        // Header context untuk orang tua
        $activeSiswa = null;
        $siswas = collect();
        if ($user->isOrangTua() && $user->orangTua) {
            $siswas = $user->orangTua->siswas()->with('kelas')->get();
            $activeId = session('active_anak_id');
            $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();
        }

        return view('notifications.index', compact(
            'notifications',
            'unreadCount',
            'filter',
            'type',
            'roleLayout',
            'activeSiswa',
            'siswas'
        ));
    }

    /**
     * Tandai notifikasi sebagai dibaca dan arahkan ke tautan target.
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $targetUrl = $notification->data['url'] ?? null;

        if ($targetUrl && filter_var($targetUrl, FILTER_VALIDATE_URL)) {
            return redirect($targetUrl);
        }

        return back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Tandai seluruh notifikasi sebagai sudah dibaca.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai telah dibaca.');
    }

    /**
     * Hapus satu entri notifikasi.
     */
    public function destroy(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * API JSON unread count untuk live indicator di topbar.
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
        $latest = $user ? $user->notifications()->latest()->take(5)->get()->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Pemberitahuan',
                'message' => $n->data['message'] ?? '',
                'url' => route('notifications.read', $n->id),
                'read' => $n->read_at !== null,
                'time_ago' => $n->created_at->locale('id')->diffForHumans(),
                'color' => $n->data['color'] ?? 'blue',
            ];
        }) : [];

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $latest,
        ]);
    }

    /**
     * Halaman pengaturan kontak & preferensi notifikasi (F6-05).
     */
    public function settings()
    {
        $user = Auth::user();
        $setting = $user->getOrCreateNotificationSetting();

        $roleLayout = match (true) {
            $user->isGuru() => 'guru',
            $user->isSiswa() => 'siswa',
            $user->isOrangTua() => 'orang-tua',
            default => 'admin',
        };

        $activeSiswa = null;
        $siswas = collect();
        if ($user->isOrangTua() && $user->orangTua) {
            $siswas = $user->orangTua->siswas()->with('kelas')->get();
            $activeId = session('active_anak_id');
            $activeSiswa = $siswas->firstWhere('id', $activeId) ?? $siswas->first();
        }

        return view('notifications.settings', compact('setting', 'roleLayout', 'activeSiswa', 'siswas'));
    }

    /**
     * Simpan pengaturan kontak & preferensi notifikasi.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'telegram_chat_id' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'channel_in_app' => ['boolean'],
            'channel_mail' => ['boolean'],
            'channel_telegram' => ['boolean'],
            'channel_whatsapp' => ['boolean'],
            'notify_alpa' => ['boolean'],
            'notify_nilai' => ['boolean'],
            'notify_pengumuman' => ['boolean'],
            'notify_qr_sesi' => ['boolean'],
        ]);

        $setting = $user->getOrCreateNotificationSetting();
        $setting->update([
            'telegram_chat_id' => $validated['telegram_chat_id'] ?? null,
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'channel_in_app' => $request->boolean('channel_in_app', true),
            'channel_mail' => $request->boolean('channel_mail', true),
            'channel_telegram' => $request->boolean('channel_telegram', false),
            'channel_whatsapp' => $request->boolean('channel_whatsapp', false),
            'notify_alpa' => $request->boolean('notify_alpa', true),
            'notify_nilai' => $request->boolean('notify_nilai', true),
            'notify_pengumuman' => $request->boolean('notify_pengumuman', true),
            'notify_qr_sesi' => $request->boolean('notify_qr_sesi', true),
        ]);

        // Jika tombol kirim uji coba ditekan
        if ($request->has('kirim_test')) {
            $user->notify(new \App\Notifications\TestNotification());
            return back()->with('success', 'Preferensi disimpan dan notifikasi uji coba berhasil dikirim!');
        }

        return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui.');
    }
}
