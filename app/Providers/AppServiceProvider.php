<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kantor;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema; // <-- import Schema
use BladeUI\Heroicons\BladeHeroicons;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Blade::componentNamespace('BladeUI\Heroicons\BladeHeroicons', 'heroicon');

        if (Schema::hasTable('kantors')) {

            $kantor = Kantor::query()
                ->whereNotNull('nama_apk')
                ->where('nama_apk', '!=', '')
                ->orderBy('id')
                ->first();

            view()->share([
                'appName' => $kantor?->nama_apk ?? config('app.name'),
                'appLogo' => $kantor?->logo,
                'appPt' => $kantor?->nama_pt,
            ]);

        } else {
            view()->share([
                'appName' => config('app.name'),
                'appLogo' => null,
                'appPt' => null,
            ]);
        }
    }

    public const HOME = '/dashboard';

}
