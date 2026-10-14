<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Support\Markdown;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('jenis')->toString();

        $portfolios = Portfolio::query()->published()
            ->when($type, fn ($q) => $q->where('project_type', $type))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        $types = Portfolio::query()->published()->whereNotNull('project_type')->distinct()->orderBy('project_type')->pluck('project_type');

        $seo = Seo::make('Portofolio Proyek Website')
            ->description('Contoh hasil pengerjaan website company profile, website UMKM, dan aplikasi Laravel beserta teknologi, fitur, dan hasil proyeknya.')
            ->canonical(route('portfolio.index'))
            ->breadcrumbs(['Portofolio' => route('portfolio.index')]);

        // Filtered, paginated or still-empty listings are thin pages.
        if ($type || $portfolios->currentPage() > 1 || $portfolios->isEmpty()) {
            $seo->noindex();
        }

        return view('portfolio.index', compact('portfolios', 'types', 'type', 'seo'));
    }

    public function show(Request $request, Portfolio $portfolio): View
    {
        abort_unless($portfolio->is_published || $request->user(), 404);

        $portfolio->load(['testimonials' => fn ($q) => $q->where('is_published', true)]);

        $related = Portfolio::query()->published()->whereKeyNot($portfolio->id)->ordered()->limit(3)->get();

        $seo = $portfolio->seo(
            $portfolio->title.' — Portofolio '.($portfolio->project_type ?: 'Website'),
            $portfolio->summary ?: Markdown::plain($portfolio->description),
            $portfolio->image,
        )->breadcrumbs(['Portofolio' => route('portfolio.index'), $portfolio->title => $portfolio->url()]);

        $seo->schema(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $portfolio->title,
            'description' => $portfolio->summary,
            'url' => $portfolio->url(),
            'image' => $seo->image,
            'dateCreated' => $portfolio->completed_at?->toDateString(),
        ]));

        if (! $portfolio->is_published) {
            $seo->noindex();
        }

        return view('portfolio.show', compact('portfolio', 'related', 'seo'));
    }
}
