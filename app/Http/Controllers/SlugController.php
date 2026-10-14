<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Support\Markdown;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Resolves top-level URLs to a service landing page or a CMS page. */
class SlugController extends Controller
{
    public function __invoke(Request $request, string $slug): View
    {
        $preview = $request->user() !== null;

        $service = Service::query()->where('slug', $slug)->with('parent')->first();
        if ($service && ($service->is_published || $preview)) {
            return $this->service($service);
        }

        $page = Page::query()->where('slug', $slug)->first();
        if ($page && ($page->is_published || $preview)) {
            return $this->page($page);
        }

        abort(404);
    }

    private function service(Service $service): View
    {
        $children = $service->children()->published()->get();
        $segment = $service->segment;

        $packages = Package::query()->active()->where('segment', $segment)->ordered()->with('features')->get();

        $faqs = $service->faqs()->where('is_published', true)->get();
        if ($faqs->count() < 4) {
            $groups = $segment === 'student' ? ['pelajar'] : ['harga', 'teknis'];
            $faqs = $faqs->merge(
                Faq::query()->published()->whereNull('service_id')->whereIn('group', $groups)->limit(6 - $faqs->count())->get()
            );
        }

        $portfolios = Portfolio::query()->published()->ordered()->limit(3)->get();
        $articles = Article::query()->published()->latest('published_at')->limit(3)->get();

        $trail = [];
        if ($service->parent) {
            $trail[$service->parent->label()] = $service->parent->url();
        }
        $trail[$service->label()] = $service->url();

        $seo = $service->seo($service->title, $service->summary ?: Markdown::plain($service->body), $service->image)
            ->breadcrumbs($trail)
            ->schema(array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'name' => $service->title,
                'description' => $service->summary,
                'url' => $service->url(),
                'areaServed' => 'ID',
                'provider' => ['@type' => 'Organization', 'name' => Setting::get('site_name'), 'url' => url('/')],
            ]));

        if (! $service->is_published) {
            $seo->noindex();
        }

        return view('services.show', compact('service', 'children', 'packages', 'faqs', 'portfolios', 'articles', 'seo'));
    }

    private function page(Page $page): View
    {
        $seo = $page->seo($page->title, $page->excerpt ?: Markdown::plain($page->body), $page->hero_image)
            ->breadcrumbs([$page->title => $page->url()]);

        if (! $page->is_published) {
            $seo->noindex();
        }

        return view('pages.show', compact('page', 'seo'));
    }
}
