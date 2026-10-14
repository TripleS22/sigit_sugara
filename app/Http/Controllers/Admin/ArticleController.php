<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ArticleController extends Controller
{
    use ManagesContent;

    public function index(Request $request): View
    {
        $articles = Article::query()
            ->with('category', 'author')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.articles.form', [
            'article' => new Article(['status' => 'draft', 'robots_index' => true, 'robots_follow' => true]),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $article = Article::create($this->validated($request) + ['user_id' => $request->user()->id]);

        return redirect()->route('admin.articles.edit', $article)->with('status', 'Artikel dibuat.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.form', [
            'article' => $article,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $article->update($this->validated($request, $article));

        return back()->with('status', 'Artikel disimpan.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $this->deleteFile($article->featured_image);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Artikel dihapus. URL lama dialihkan ke halaman blog.');
    }

    private function validated(Request $request, ?Article $article = null): array
    {
        $this->withBooleans($request, ['robots_index', 'robots_follow']);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => $this->slugRules('articles', $article),
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:200000', 'required_if:status,published'],
            'tags_text' => ['nullable', 'string', 'max:300'],
            'featured_image_file' => ['nullable', ...$this->imageRules()],
            'featured_image_alt' => ['nullable', 'string', 'max:190', 'required_with:featured_image_file'],
            'status' => ['required', Rule::in(array_keys(Article::STATUSES))],
            'published_at' => ['nullable', 'date'],
            ...$this->seoRules(),
        ], ['body.required_if' => 'Isi artikel wajib diisi sebelum dipublikasikan.']);

        $data['tags'] = $this->commaList($data['tags_text'] ?? '');
        $data['featured_image'] = $this->handleImage($request, 'featured_image_file', $article?->featured_image, 'articles');
        if ($data['status'] === 'published') {
            $data['published_at'] ??= $article?->published_at ?? now();
        }
        unset($data['tags_text'], $data['featured_image_file']);

        return $this->withSeoImage($request, $data, $article, 'articles');
    }
}
