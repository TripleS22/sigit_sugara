<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use App\Support\Markdown;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('q')->toString());

        $articles = Article::query()->published()
            ->with('category')
            ->when($search, fn ($q) => $q->where(fn ($q) => $q
                ->where('title', 'like', '%'.$search.'%')
                ->orWhere('excerpt', 'like', '%'.$search.'%')))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $seo = Seo::make('Blog: Panduan Website untuk Bisnis & UMKM')
            ->description('Artikel tentang biaya pembuatan website, company profile, domain dan hosting, Laravel, dan tips memilih jasa pembuatan website.')
            ->canonical(route('blog.index'))
            ->breadcrumbs(['Blog' => route('blog.index')]);

        // Internal search results are not indexed (PRD §8.4 point 10).
        if ($search !== '' || $articles->currentPage() > 1) {
            $seo->noindex();
        }

        return view('blog.index', [
            'articles' => $articles,
            'categories' => $this->categories(),
            'search' => $search,
            'currentCategory' => null,
            'seo' => $seo,
        ]);
    }

    public function category(Category $category): View
    {
        $articles = $category->articles()->published()->with('category')->latest('published_at')->paginate(9);

        $seo = Seo::make($category->meta_title ?: 'Artikel '.$category->name)
            ->description($category->meta_description ?: $category->description)
            ->canonical($category->url())
            ->breadcrumbs(['Blog' => route('blog.index'), $category->name => $category->url()]);

        if ($articles->isEmpty() || $articles->currentPage() > 1) {
            $seo->noindex();
        }

        return view('blog.index', [
            'articles' => $articles,
            'categories' => $this->categories(),
            'search' => '',
            'currentCategory' => $category,
            'seo' => $seo,
        ]);
    }

    public function show(Request $request, Article $article): View
    {
        abort_unless($article->isPublic() || $request->user(), 404);

        $article->load('category', 'author');

        $related = Article::query()->published()
            ->whereKeyNot($article->id)
            ->when($article->category_id, fn ($q) => $q->orderByRaw('category_id = ? desc', [$article->category_id]))
            ->latest('published_at')
            ->limit(3)
            ->get();

        $seo = $article->seo($article->title, $article->excerpt ?: Markdown::plain($article->body), $article->featured_image)
            ->type('article')
            ->breadcrumbs(array_filter([
                'Blog' => route('blog.index'),
                $article->category?->name ?? '' => $article->category?->url(),
                $article->title => $article->url(),
            ]));

        $seo->schema(array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $seo->description,
            'image' => $seo->image ? [$seo->image] : null,
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => $article->author
                ? ['@type' => 'Person', 'name' => $article->author->name]
                : ['@type' => 'Organization', 'name' => Setting::get('site_name')],
            'publisher' => ['@type' => 'Organization', 'name' => Setting::get('site_name'), 'url' => url('/')],
            'mainEntityOfPage' => $article->url(),
        ]));

        if (! $article->isPublic()) {
            $seo->noindex();
        }

        return view('blog.show', compact('article', 'related', 'seo'));
    }

    private function categories()
    {
        return Category::query()
            ->whereHas('articles', fn ($q) => $q->published())
            ->withCount(['articles' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();
    }
}
