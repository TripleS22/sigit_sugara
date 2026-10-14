<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SlugController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::get('/paket', [PackageController::class, 'index'])->name('packages.index');
Route::get('/paket/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/portofolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portofolio/{portfolio:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/blog', [ArticleController::class, 'index'])->name('blog.index');
Route::get('/blog/kategori/{category:slug}', [ArticleController::class, 'category'])->name('blog.category');
Route::get('/blog/{article:slug}', [ArticleController::class, 'show'])->name('blog.show');

Route::get('/faq', FaqController::class)->name('faq');

Route::get('/kontak', [LeadController::class, 'create'])->name('contact');
Route::post('/kontak', [LeadController::class, 'store'])->middleware('throttle:leads')->name('leads.store');
Route::get('/kontak/terima-kasih', [LeadController::class, 'thanks'])->name('leads.thanks');

Route::post('/track', TrackingController::class)->middleware('throttle:tracking')->name('track');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| CMS
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('pages', Admin\PageController::class)->except('show');
        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('packages', Admin\PackageController::class)->except('show');
        Route::resource('portfolios', Admin\PortfolioController::class)->except('show');
        Route::resource('articles', Admin\ArticleController::class)->except('show');
        Route::resource('categories', Admin\CategoryController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
        Route::resource('redirects', Admin\RedirectController::class)->except('show');

        Route::get('/leads/export', [Admin\LeadController::class, 'export'])->name('leads.export');
        Route::resource('leads', Admin\LeadController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::get('/media', [Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('/media', [Admin\MediaController::class, 'store'])->middleware('throttle:uploads')->name('media.store');
        Route::patch('/media/{media}', [Admin\MediaController::class, 'update'])->name('media.update');
        Route::delete('/media/{media}', [Admin\MediaController::class, 'destroy'])->name('media.destroy');

        Route::middleware('admin')->group(function () {
            Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
            Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
            Route::resource('users', Admin\UserController::class)->except('show');
        });
    });
});

/*
|--------------------------------------------------------------------------
| CMS-managed pages and service landing pages, e.g. /jasa-pembuatan-website.
| Keep this last so it never shadows a route above.
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', SlugController::class)->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('slug');
