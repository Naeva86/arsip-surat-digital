<?php
// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use App\Models\SuratMasuk;
use App\Models\Disposisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $user = Auth::user();
            $badgeSuratBaru = 0;
            $badgeDisposisi = 0;

            if ($user) {
                if (in_array($user->role, ['staff', 'admin'])) {
                    $badgeSuratBaru = SuratMasuk::where('status', 'baru')->count();
                }

                if (in_array($user->role, ['direktur', 'kabag', 'kasubbag', 'admin'])) {
                    $badgeDisposisi = Disposisi::where('kepada_user_id', $user->id)
                        ->whereIn('status', ['menunggu', 'dibaca'])
                        ->whereHas('suratMasuk')
                        ->count();
                }
            }

            $view->with(compact('badgeSuratBaru', 'badgeDisposisi'));
        });
    }

    public function register(): void {}
}