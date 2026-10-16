<?php

namespace App\Services;

use App\Models\PengaturanSekolah;

class GeofencingService
{
    /**
     * Radius bumi dalam satuan meter (WGS-84 mean radius).
     */
    const EARTH_RADIUS_METERS = 6371000;

    /**
     * Menghitung jarak antara dua titik koordinat (latitude/longitude) menggunakan Rumus Haversine.
     * Hasil perhitungan dalam satuan meter.
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $angle * self::EARTH_RADIUS_METERS;
    }

    /**
     * Memvalidasi lokasi siswa terhadap titik koordinat sekolah & mendeteksi anomali Fake Location / Mock GPS (F2-02, F2-03).
     *
     * @param float|null $latitude  Latitude dari browser siswa
     * @param float|null $longitude Longitude dari browser siswa
     * @param float|null $accuracy  Akurasi GPS dalam meter dari browser siswa
     * @param int|null   $clientTimestamp Waktu epoch millisecond dari perangkat siswa (opsional)
     * @return array [
     *    'valid' => bool,
     *    'distance' => float|null,
     *    'accuracy' => float|null,
     *    'message' => string|null,
     *    'is_mock_suspected' => bool
     * ]
     */
    public static function validateLocation(?float $latitude, ?float $longitude, ?float $accuracy = null, ?int $clientTimestamp = null): array
    {
        $settings = PengaturanSekolah::getSettings();

        // 1. Jika geofencing dinonaktifkan oleh Admin, loloskan validasi
        if (! $settings->is_geofencing_active) {
            $dist = ($latitude !== null && $longitude !== null)
                ? round(self::calculateDistance($latitude, $longitude, $settings->latitude, $settings->longitude), 1)
                : null;

            return [
                'valid' => true,
                'distance' => $dist,
                'accuracy' => $accuracy,
                'message' => null,
                'is_mock_suspected' => false,
            ];
        }

        // 2. Cek kelengkapan koordinat (F2-04)
        if ($latitude === null || $longitude === null) {
            return [
                'valid' => false,
                'distance' => null,
                'accuracy' => null,
                'message' => 'Koordinat lokasi (GPS) tidak ditemukan. Pastikan izin lokasi telah diaktifkan di browser ponsel Anda.',
                'is_mock_suspected' => false,
            ];
        }

        // 3. Validasi batas wajar koordinat dunia
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return [
                'valid' => false,
                'distance' => null,
                'accuracy' => $accuracy,
                'message' => 'Nilai koordinat GPS di luar rentang geografis yang valid.',
                'is_mock_suspected' => true,
            ];
        }

        // 4. Deteksi anomali Fake Location / Mock GPS (F2-03)
        if ($settings->enforce_anti_mock) {
            // Anomali 1: Nilai akurasi <= 0 (Hardware GPS nyata selalu memiliki nilai akurasi positif > 0)
            if ($accuracy !== null && $accuracy <= 0) {
                return [
                    'valid' => false,
                    'distance' => null,
                    'accuracy' => $accuracy,
                    'message' => 'Terdeteksi anomali sinyal GPS (akurasi 0m). Sistem mendeteksi potensi penggunaan aplikasi Fake GPS / Mock Location.',
                    'is_mock_suspected' => true,
                ];
            }

            // Anomali 2: Drift waktu perangkat vs server > 5 menit jika timestamp client dikirim
            if ($clientTimestamp !== null) {
                $serverSec = now()->timestamp;
                $clientSec = (int) round($clientTimestamp / 1000);
                if (abs($serverSec - $clientSec) > 300) {
                    return [
                        'valid' => false,
                        'distance' => null,
                        'accuracy' => $accuracy,
                        'message' => 'Waktu jam perangkat Anda berbeda lebih dari 5 menit dengan server. Mohon atur tanggal & jam ke Otomatis.',
                        'is_mock_suspected' => true,
                    ];
                }
            }
        }

        // 5. Cek ambang batas akurasi sinyal (F2-03)
        $maxAccuracy = $settings->max_accuracy_meter ?? 80;
        if ($accuracy !== null && $accuracy > $maxAccuracy) {
            return [
                'valid' => false,
                'distance' => null,
                'accuracy' => $accuracy,
                'message' => "Sinyal GPS perangkat Anda kurang akurat (±" . round($accuracy) . " meter, maksimal {$maxAccuracy}m). Silakan dekati jendela/pindah ke ruang terbuka dan coba kembali.",
                'is_mock_suspected' => false,
            ];
        }

        // 6. Hitung jarak ke titik koordinat sekolah (F2-02 Haversine)
        $distance = self::calculateDistance($latitude, $longitude, $settings->latitude, $settings->longitude);
        $radius = $settings->radius_meter ?? 100;

        if ($distance > $radius) {
            $selisih = round($distance - $radius);
            return [
                'valid' => false,
                'distance' => round($distance, 1),
                'accuracy' => $accuracy,
                'message' => "Anda berada di luar area sekolah! Jarak Anda terdeteksi " . round($distance) . " meter dari sekolah (melebihi radius toleransi {$radius} meter sebanyak {$selisih}m).",
                'is_mock_suspected' => false,
            ];
        }

        // 7. Lolos seluruh validasi geofence & anti-mock
        return [
            'valid' => true,
            'distance' => round($distance, 1),
            'accuracy' => $accuracy ? round($accuracy, 1) : null,
            'message' => null,
            'is_mock_suspected' => false,
        ];
    }
}
