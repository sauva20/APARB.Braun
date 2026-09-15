<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            $notifications = collect();
            
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $targetDate = \Carbon\Carbon::today()->addDays(30);

                $query = \App\Models\Apar::with(['lokasi.gedung'])
                                ->whereDate('tgl_kedaluwarsa', '<=', $targetDate);

                if ($user->role === 'EHSS') {
                    $notifications = $query->orderBy('updated_at', 'desc')->get();
                } elseif ($user->role === 'Staff') {
                    $assignedGedungIds = $user->gedungs()->pluck('gedung.id')->toArray();
                    
                    if (empty($assignedGedungIds)) {
                        // Sometimes pluck('gedung.id') doesn't work if table names are weird, let's use the safer route:
                        $assignedGedungIds = $user->gedungs->pluck('id')->toArray();
                    }
                    
                    if (!empty($assignedGedungIds)) {
                        $notifications = $query->whereHas('lokasi', function ($q) use ($assignedGedungIds) {
                            $q->whereIn('gedung_id', $assignedGedungIds);
                        })->orderBy('updated_at', 'desc')->get();
                    }
                }
            }
            
            $view->with('importantNotifications', $notifications);
        });
    }
}
