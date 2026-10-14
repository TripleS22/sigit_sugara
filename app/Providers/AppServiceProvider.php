<?php

namespace App\Providers;

use App\Models\Service;
use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        $this->configureRateLimiting();

        View::composer('layouts.app', function ($view) {
            $view->with('navServices', SiteCache::remember('nav.services', fn () => Service::query()
                ->published()
                ->whereNull('parent_id')
                ->where('show_in_menu', true)
                ->ordered()
                ->with(['children' => fn ($q) => $q->published()->where('show_in_menu', true)])
                ->get()));
        });

        View::share('whatsappUrl', fn (?string $message = null) => Setting::whatsappUrl($message));
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', fn (Request $request) => [
            Limit::perMinute(5)->by(strtolower((string) $request->input('email')).'|'.$request->ip()),
            Limit::perMinute(20)->by($request->ip()),
        ]);

        RateLimiter::for('leads', fn (Request $request) => [
            Limit::perMinute(3)->by($request->ip()),
            Limit::perDay(20)->by($request->ip()),
        ]);

        RateLimiter::for('tracking', fn (Request $request) => Limit::perMinute(60)->by($request->ip()));

        RateLimiter::for('uploads', fn (Request $request) => Limit::perMinute(30)->by($request->user()?->id ?: $request->ip()));
    }
}
