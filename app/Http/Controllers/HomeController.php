<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Support\Seo;
use App\Support\SiteCache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $data = SiteCache::remember('home', fn () => [
            'services' => Service::query()->published()->where('segment', 'business')->whereNotNull('parent_id')->ordered()->get(),
            'studentService' => Service::query()->published()->where('segment', 'student')->whereNull('parent_id')->first(),
            'packages' => Package::query()->active()->where('segment', 'business')->ordered()->with('features')->get(),
            'studentPriceFrom' => Package::query()->active()->where('segment', 'student')->min('price_from'),
            'portfolios' => Portfolio::query()->published()->ordered()->orderByDesc('is_featured')->limit(3)->get(),
            'testimonials' => Testimonial::query()->published()->limit(6)->get(),
            'faqs' => Faq::query()->published()->where('show_on_home', true)->limit(6)->get(),
            'articles' => Article::query()->published()->latest('published_at')->limit(3)->get(),
        ]);

        $seo = Seo::make(Setting::get('site_name').' — '.Setting::get('tagline'))
            ->description(Setting::get('default_meta_description'))
            ->canonical(url('/'))
            ->image(Setting::get('default_og_image'));

        $seo->schema([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => Setting::get('site_name'),
            'url' => url('/'),
        ]);

        return view('home', $data + ['seo' => $seo]);
    }
}
