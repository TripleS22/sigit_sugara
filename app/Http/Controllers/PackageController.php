<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Package;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::query()->active()->ordered()->with('features')->get()->groupBy('segment');
        $business = $packages->get('business', collect());
        $student = $packages->get('student', collect());

        $seo = Seo::make('Paket & Harga Pembuatan Website')
            ->description('Bandingkan paket website Starter, Business, Premium AI, dan Enterprise serta paket pelajar. Harga awal, fitur, jumlah halaman, dan biaya yang tidak termasuk dijelaskan transparan.')
            ->canonical(route('packages.index'))
            ->breadcrumbs(['Paket Harga' => route('packages.index')]);

        $seo->schema($this->offerCatalog($business->concat($student)));

        return view('packages.index', [
            'business' => $business,
            'student' => $student,
            'comparison' => $this->comparison($business),
            'faqs' => Faq::query()->published()->where('group', 'harga')->get(),
            'seo' => $seo,
        ]);
    }

    public function show(Request $request, Package $package): View
    {
        abort_unless($package->is_active || $request->user(), 404);

        $package->load('features');

        $others = Package::query()->active()->where('segment', $package->segment)->whereKeyNot($package->id)->ordered()->get();

        $seo = $package->seo(
            'Paket '.$package->name.' — '.($package->segment === 'student' ? 'Website Pelajar' : 'Pembuatan Website'),
            $package->description ?: $package->tagline,
        )->breadcrumbs(['Paket Harga' => route('packages.index'), $package->name => $package->url()]);

        $seo->schema($this->offerCatalog(collect([$package])));

        if (! $package->is_active) {
            $seo->noindex();
        }

        return view('packages.show', [
            'package' => $package,
            'others' => $others,
            'faqs' => Faq::query()->published()->whereIn('group', $package->segment === 'student' ? ['pelajar'] : ['harga', 'teknis'])->limit(6)->get(),
            'seo' => $seo,
        ]);
    }

    /**
     * Rows for the comparison table: every feature label across the business
     * packages, with each package's value (or "—" when not listed).
     *
     * @return array<string, array<int, array{value: ?string, included: bool}|null>>
     */
    private function comparison(Collection $packages): array
    {
        $rows = [];

        foreach ($packages as $package) {
            foreach ($package->features as $feature) {
                $rows[$feature->label] ??= [];
                $rows[$feature->label][$package->id] = ['value' => $feature->value, 'included' => $feature->is_included];
            }
        }

        return $rows;
    }

    /** OfferCatalog with the real starting prices shown on the page. */
    private function offerCatalog(Collection $packages): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'OfferCatalog',
            'name' => 'Paket pembuatan website '.Setting::get('site_name'),
            'url' => route('packages.index'),
            'itemListElement' => $packages->values()->map(fn (Package $p) => array_filter([
                '@type' => 'Offer',
                'name' => 'Paket '.$p->name,
                'description' => $p->tagline,
                'url' => $p->url(),
                'priceCurrency' => 'IDR',
                'priceSpecification' => array_filter([
                    '@type' => 'PriceSpecification',
                    'priceCurrency' => 'IDR',
                    'minPrice' => $p->price_from,
                    'maxPrice' => $p->price_to,
                ]),
            ]))->all(),
        ];
    }
}
