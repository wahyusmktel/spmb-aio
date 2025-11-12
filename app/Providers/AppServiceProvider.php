<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

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
        // --- TAMBAHKAN LOGIKA INI ---

        // Kita gunakan try-catch atau Schema::hasTable agar migrasi tidak error
        try {
            if (Schema::hasTable('settings')) {
                // Ambil logo dari Cache agar tidak query DB setiap saat
                $logoUrl = Cache::rememberForever('app_logo_url', function () {
                    $setting = Setting::where('key', 'logo_path')->first();
                    // Jika ada, kirim URL-nya
                    return $setting ? Storage::url($setting->value) : null;
                });

                // Kirim variabel $logoUrl ke SEMUA view
                View::share('logoUrl', $logoUrl);
            }
        } catch (\Exception $e) {
            // Tangani error jika DB belum siap
            View::share('logoUrl', null);
        }
        // --- END TAMBAHAN ---
    }
}
