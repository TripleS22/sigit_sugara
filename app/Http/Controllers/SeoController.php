<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Package;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Support\SiteCache;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $xml = SiteCache::remember('sitemap', fn () => view('seo.sitemap', ['urls' => $this->urls()])->render());

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): Response
    {
        $lines = ['User-agent: *'];

        if (Setting::bool('allow_indexing')) {
            $lines[] = 'Disallow: /admin';
            $lines[] = 'Disallow: /track';
            $lines[] = 'Disallow: /kontak/terima-kasih';
            $lines[] = 'Disallow: /blog?q=';
            $lines[] = '';
            $lines[] = 'Sitemap: '.route('sitemap');
        } else {
            // Staging or pre-launch: keep the whole site out of search results.
            $lines[] = 'Disallow: /';
        }

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * Indexable public URLs only: unpublished items, noindex items and items
     * with a canonical pointing elsewhere are left out.
     *
     * @return array<int, array{loc: string, lastmod: ?string}>
     */
    private function urls(): array
    {
        $urls = [
            ['loc' => url('/'), 'lastmod' => null],
            ['loc' => route('packages.index'), 'lastmod' => Package::query()->active()->max('updated_at')],
            ['loc' => route('blog.index'), 'lastmod' => Article::query()->published()->max('updated_at')],
            ['loc' => route('faq'), 'lastmod' => null],
            ['loc' => route('contact'), 'lastmod' => null],
        ];

        if (Portfolio::query()->published()->exists()) {
            $urls[] = ['loc' => route('portfolio.index'), 'lastmod' => Portfolio::query()->published()->max('updated_at')];
        }

        $indexable = fn ($item) => $item->robots_index && (! $item->canonical_url || $item->canonical_url === $item->url());

        $collections = [
            Service::query()->published()->ordered()->get(),
            Page::query()->published()->get(),
            Package::query()->active()->ordered()->get(),
            Portfolio::query()->published()->ordered()->get(),
            Article::query()->published()->latest('published_at')->get(),
        ];

        foreach ($collections as $items) {
            foreach ($items->filter($indexable) as $item) {
                $urls[] = ['loc' => $item->url(), 'lastmod' => $item->updated_at];
            }
        }

        Category::query()->whereHas('articles', fn ($q) => $q->published())->get()
            ->each(function (Category $category) use (&$urls) {
                $urls[] = ['loc' => $category->url(), 'lastmod' => $category->updated_at];
            });

        return collect($urls)->map(fn ($u) => [
            'loc' => $u['loc'],
            'lastmod' => $u['lastmod'] ? Carbon::parse($u['lastmod'])->toAtomString() : null,
        ])->unique('loc')->values()->all();
    }
}
