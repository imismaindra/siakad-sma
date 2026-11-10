<div class="max-w-3xl mx-auto space-y-6 animate-fade-in-up">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="page-title font-display !text-2xl">Pengaturan Notifikasi.</h1>
            <p class="page-subtitle">Atur channel penerimaan pesan (Email, Telegram, WhatsApp) dan jenis informasi yang ingin Anda terima.</p>
        </div>
        <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Kembali ke Notifikasi
        </a>
    </div>

    {{-- Settings Form --}}
    <form action="{{ route('notifications.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Island 1: Channel Delivery --}}
        <div class="rounded-[2rem] bg-white p-6 sm:p-8 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5 space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold font-display text-navy-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    Saluran Notifikasi (Delivery Channels)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan media perpesanan yang aktif untuk menerima pemberitahuan.</p>
            </div>

            <div class="space-y-4">
                {{-- In-App (Selalu Aktif) --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-navy-900">Notifikasi Dalam Aplikasi (In-App)</p>
                            <p class="text-[11px] text-slate-500">Muncul pada lonceng notifikasi di sudut kanan atas dan kotak masuk portal.</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        Aktif
                    </span>
                    <input type="hidden" name="channel_in_app" value="1">
                </div>

                {{-- Email --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-navy-900">Email Sekolah</p>
                                <p class="text-[11px] text-slate-500">Kirim salinan pengumuman resmi dan e-Rapor ke: <strong>{{ auth()->user()->email }}</strong></p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="channel_mail" value="1" {{ old('channel_mail', $setting->channel_mail) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                        </label>
                    </div>
                </div>

                {{-- Telegram Bot --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-navy-900">Telegram Bot Channel</p>
                                <p class="text-[11px] text-slate-500">Notifikasi instan bebas pulsa melalui bot Telegram resmi sekolah.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="channel_telegram" value="1" {{ old('channel_telegram', $setting->channel_telegram) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                        </label>
                    </div>

                    <div class="pt-2">
                        <label for="telegram_chat_id" class="block text-[11px] font-semibold text-slate-700 mb-1">Telegram Chat ID Anda</label>
                        <input type="text" id="telegram_chat_id" name="telegram_chat_id" value="{{ old('telegram_chat_id', $setting->telegram_chat_id) }}" placeholder="Contoh: 123456789"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2 text-xs font-mono text-navy-900 focus:border-gold-500 focus:ring-gold-500" />
                        <p class="text-[10px] text-slate-400 mt-1">
                            💡 Cara cek: Cari bot <code>@userinfobot</code> di aplikasi Telegram Anda, lalu salin angka Id yang tertera ke kolom di atas.
                        </p>
                    </div>
                </div>

                {{-- WhatsApp Gateway --}}
                <div class="rounded-2xl border border-slate-100 bg-white p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-navy-900">WhatsApp Gateway (Adapter Modular)</p>
                                <p class="text-[11px] text-slate-500">Pesan otomatis ke WhatsApp nomor wali murid / siswa.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="channel_whatsapp" value="1" {{ old('channel_whatsapp', $setting->channel_whatsapp) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                        </label>
                    </div>

                    <div class="pt-2">
                        <label for="whatsapp_number" class="block text-[11px] font-semibold text-slate-700 mb-1">Nomor WhatsApp Aktif</label>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $setting->whatsapp_number ?? auth()->user()->routeNotificationForWhatsApp()) }}" placeholder="Contoh: 081234567890 atau 6281234567890"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-navy-900 focus:border-gold-500 focus:ring-gold-500" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Island 2: Topic Preferences --}}
        <div class="rounded-[2rem] bg-white p-6 sm:p-8 shadow-[0_24px_60px_-30px_rgba(15,37,87,0.3)] ring-1 ring-black/5 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold font-display text-navy-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gold-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                    Kategori Pemberitahuan (Event Topics)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Pilih peristiwa dan jenis pengumuman yang ingin Anda pantau.</p>
            </div>

            <div class="divide-y divide-slate-100">
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-navy-900">Ketidakhadiran & Presensi Alpa</p>
                        <p class="text-[11px] text-slate-500">Pemberitahuan darurat saat siswa tidak hadir di kelas tanpa keterangan.</p>
                    </div>
                    <input type="checkbox" name="notify_alpa" value="1" {{ old('notify_alpa', $setting->notify_alpa) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                </div>

                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-navy-900">Penerbitan Nilai & e-Rapor</p>
                        <p class="text-[11px] text-slate-500">Pemberitahuan saat rapor semester resmi telah difinalisasi dan siap diunduh.</p>
                    </div>
                    <input type="checkbox" name="notify_nilai" value="1" {{ old('notify_nilai', $setting->notify_nilai) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                </div>

                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-navy-900">Pengumuman & Surat Edaran Sekolah</p>
                        <p class="text-[11px] text-slate-500">Informasi resmi dari pihak sekolah, kepala sekolah, atau wali kelas.</p>
                    </div>
                    <input type="checkbox" name="notify_pengumuman" value="1" {{ old('notify_pengumuman', $setting->notify_pengumuman) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                </div>

                <div class="py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-navy-900">Sesi Presensi QR Dibuka</p>
                        <p class="text-[11px] text-slate-500">Pemberitahuan saat guru pelajaran membuka sesi pemindaian QR di kelas.</p>
                    </div>
                    <input type="checkbox" name="notify_qr_sesi" value="1" {{ old('notify_qr_sesi', $setting->notify_qr_sesi) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-navy-900 focus:ring-gold-500">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
            <button type="submit" name="kirim_test" value="1" class="rounded-full bg-white px-5 py-2.5 text-xs font-semibold text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50 transition-colors cursor-pointer shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                Kirim Notifikasi Uji Coba
            </button>

            <button type="submit" class="group inline-flex items-center gap-2 rounded-full bg-navy-800 px-6 py-2.5 text-xs font-semibold text-white hover:bg-navy-900 transition-colors shadow-sm cursor-pointer">
                <span>Simpan Pengaturan</span>
                <svg class="h-4 w-4 text-gold-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </button>
        </div>
    </form>

</div>
