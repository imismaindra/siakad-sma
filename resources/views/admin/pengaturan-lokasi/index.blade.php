@extends('layouts.admin')

@section('title', 'Pengaturan Lokasi Sekolah & Geofencing')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map-preview {
        height: 380px;
        width: 100%;
        border-radius: 1rem;
        z-index: 10;
    }
</style>
@endpush

@section('admin-content')
<div class="space-y-6 animate-fade-in-up" x-data="pengaturanLokasiApp()">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <h1 class="text-xl font-bold text-slate-800">Pengaturan Lokasi & Geofencing (F2)</h1>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                Atur titik koordinat sekolah, radius toleransi absensi, dan parameter deteksi <em>Fake Location / Mock GPS</em>.
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if($settings->is_geofencing_active)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Geofencing Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                    Geofencing Nonaktif
                </span>
            @endif
        </div>
    </div>

    {{-- Feedback Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs text-emerald-800 flex items-center gap-2 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 space-y-1 shadow-xs">
            <p class="font-bold">Terjadi kesalahan validasi data:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pengaturan-lokasi.update') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf
        @method('PUT')

        {{-- Left Form Panel --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- Data Identitas Sekolah --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Identitas Lokasi Sekolah
                </h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Sekolah</label>
                    <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $settings->nama_sekolah) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-medium">{{ old('alamat', $settings->alamat) }}</textarea>
                </div>
            </div>

            {{-- Titik Koordinat & Geofencing --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Koordinat & Radius Presensi
                    </h3>
                    <button type="button" @click="getCurrentLocation()" class="text-[11px] text-indigo-600 hover:text-indigo-800 font-semibold cursor-pointer underline flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Ambil GPS Saya
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Latitude</label>
                        <input type="number" step="any" name="latitude" x-model.number="lat" @input="updateMapFromInputs()" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Longitude</label>
                        <input type="number" step="any" name="longitude" x-model.number="lng" @input="updateMapFromInputs()" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Radius Toleransi (Meter)</label>
                        <div class="relative">
                            <input type="number" min="10" max="5000" name="radius_meter" x-model.number="radius" @input="updateMapFromInputs()" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-mono pr-8">
                            <span class="absolute right-3 top-2 text-xs text-slate-400">m</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Rekomendasi area sekolah: 50 - 200m</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Maks. Akurasi GPS (Meter)</label>
                        <div class="relative">
                            <input type="number" min="10" max="500" name="max_accuracy_meter" value="{{ old('max_accuracy_meter', $settings->max_accuracy_meter) }}" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 font-mono pr-8">
                            <span class="absolute right-3 top-2 text-xs text-slate-400">m</span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Tolak jika sinyal GPS > ambang ini</p>
                    </div>
                </div>

                {{-- Toggles --}}
                <div class="pt-3 border-t border-slate-100 space-y-3">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="is_geofencing_active" value="1" {{ old('is_geofencing_active', $settings->is_geofencing_active) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-semibold text-slate-800 block">Wajibkan Geofencing Siswa (F2-02)</span>
                            <span class="text-[11px] text-slate-500">Jika dicentang, absensi QR hanya berhasil bila siswa berada di dalam radius sekolah.</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="enforce_anti_mock" value="1" {{ old('enforce_anti_mock', $settings->enforce_anti_mock) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-semibold text-slate-800 block">Aktifkan Proteksi Anti-Fake Location (F2-03)</span>
                            <span class="text-[11px] text-slate-500">Mendeteksi anomali akurasi 0m khas Mock Provider & manipulasi waktu jam perangkat.</span>
                        </div>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition">
                        Simpan Pengaturan Geofencing
                    </button>
                </div>
            </div>

        </div>

        {{-- Right Panel: Interactive Map Preview --}}
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Peta Interaktif Area Sekolah
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Klik pada peta atau geser pin merah untuk mengubah titik koordinat sekolah secara langsung.</p>
                </div>
                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold text-xs rounded-lg border border-indigo-200">
                    Radius: <span x-text="radius"></span> meter
                </span>
            </div>

            {{-- Leaflet Map Container --}}
            <div id="map-preview" class="border border-slate-200 shadow-inner"></div>

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-500 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Lingkaran biru merepresentasikan radius batas toleransi. Siswa di luar lingkaran ini otomatis ditolak oleh sistem.</span>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function pengaturanLokasiApp() {
    return {
        lat: {{ $settings->latitude }},
        lng: {{ $settings->longitude }},
        radius: {{ $settings->radius_meter }},
        map: null,
        marker: null,
        circle: null,
        init() {
            this.$nextTick(() => {
                this.initMap();
            });
        },
        initMap() {
            if (this.map) return;

            // Inisialisasi peta Leaflet
            this.map = L.map('map-preview').setView([this.lat, this.lng], 16);

            // Layer OpenStreetMap gratis
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            // Marker Sekolah (Draggable)
            this.marker = L.marker([this.lat, this.lng], {
                draggable: true,
                title: "Titik Pusat Sekolah"
            }).addTo(this.map);

            this.marker.bindPopup("<strong>Lokasi Sekolah</strong><br>Geser untuk memindahkan titik.").openPopup();

            // Lingkaran Radius Geofence
            this.circle = L.circle([this.lat, this.lng], {
                color: '#4f46e5',
                fillColor: '#818cf8',
                fillOpacity: 0.25,
                radius: this.radius
            }).addTo(this.map);

            // Event saat marker digeser
            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                this.lat = parseFloat(pos.lat.toFixed(7));
                this.lng = parseFloat(pos.lng.toFixed(7));
                this.updateCircle();
            });

            // Event klik pada peta
            this.map.on('click', (e) => {
                this.lat = parseFloat(e.latlng.lat.toFixed(7));
                this.lng = parseFloat(e.latlng.lng.toFixed(7));
                this.marker.setLatLng([this.lat, this.lng]);
                this.updateCircle();
            });
        },
        updateCircle() {
            if (this.circle) {
                this.circle.setLatLng([this.lat, this.lng]);
                this.circle.setRadius(this.radius);
            }
        },
        updateMapFromInputs() {
            if (this.map && this.marker && !isNaN(this.lat) && !isNaN(this.lng)) {
                this.marker.setLatLng([this.lat, this.lng]);
                this.updateCircle();
                this.map.panTo([this.lat, this.lng]);
            }
        },
        getCurrentLocation() {
            if (!("geolocation" in navigator)) {
                alert("Browser tidak mendukung geolokasi.");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.lat = parseFloat(pos.coords.latitude.toFixed(7));
                    this.lng = parseFloat(pos.coords.longitude.toFixed(7));
                    this.updateMapFromInputs();
                    alert(`Titik berhasil diperbarui ke lokasi Anda saat ini (Akurasi: ±${Math.round(pos.coords.accuracy)}m)`);
                },
                (err) => {
                    alert("Gagal membaca GPS: " + err.message);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        }
    };
}
</script>
@endpush
