<?php

namespace App\Providers;

use App\Models\Siswa;
use App\Policies\AnakPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // F4: Daftarkan Policy otoritas pemantauan data anak oleh Orang Tua
        Gate::policy(Siswa::class, AnakPolicy::class);

        // F3 Audit Log: Rekam otomatis IP Address dan User-Agent pada setiap aktivitas sistem
        Activity::creating(function (Activity $activity) {
            $props = $activity->properties ? $activity->properties->toArray() : [];

            $hasHttpRequest = app()->bound('request') && request() && (request()->server('REMOTE_ADDR') || request()->header('User-Agent'));

            if (! isset($props['ip'])) {
                if ($hasHttpRequest) {
                    $rawIp = request()->header('CF-Connecting-IP')
                        ?? request()->header('X-Forwarded-For')
                        ?? request()->server('REMOTE_ADDR')
                        ?? request()->ip()
                        ?? '127.0.0.1';
                    $props['ip'] = trim(explode(',', $rawIp)[0]);
                } elseif (! app()->runningInConsole()) {
                    $props['ip'] = request()?->ip() ?? '127.0.0.1';
                } else {
                    $props['ip'] = '127.0.0.1 (CLI)';
                }
            }

            if (! isset($props['user_agent'])) {
                if ($hasHttpRequest) {
                    $props['user_agent'] = request()->userAgent() ?? request()->header('User-Agent') ?? '-';
                } elseif (! app()->runningInConsole()) {
                    $props['user_agent'] = request()?->userAgent() ?? '-';
                } else {
                    $props['user_agent'] = 'Artisan CLI';
                }
            }

            $activity->properties = collect($props);
        });
    }
}
