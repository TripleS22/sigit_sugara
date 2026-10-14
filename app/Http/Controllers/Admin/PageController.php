<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PageController extends Controller
{
    use ManagesContent;

    public function index(Request $request): View
    {
        $pages = Page::query()
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%'.$request->string('q').'%'))
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new Page(['template' => 'default', 'robots_index' => true, 'robots_follow' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $page = Page::create($this->validated($request));

        return redirect()->route('admin.pages.edit', $page)->with('status', 'Halaman dibuat.');
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validated($request, $page));

        return back()->with('status', 'Halaman disimpan.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $this->deleteFile($page->hero_image);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('status', 'Halaman dihapus. URL lama dialihkan ke beranda.');
    }

    private function validated(Request $request, ?Page $page = null): array
    {
        $this->withBooleans($request, ['is_published', 'robots_index', 'robots_follow']);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => $this->rootSlugRules('pages', $page),
            'template' => ['required', Rule::in(array_keys(Page::TEMPLATES))],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['nullable', 'string', 'max:100000'],
            'hero_image_file' => ['nullable', ...$this->imageRules()],
            'hero_image_alt' => ['nullable', 'string', 'max:190', 'required_with:hero_image_file'],
            'is_published' => ['boolean'],
            ...$this->seoRules(),
        ]);

        $data['hero_image'] = $this->handleImage($request, 'hero_image_file', $page?->hero_image, 'pages');
        unset($data['hero_image_file']);
        $data['published_at'] = $data['is_published'] ? ($page?->published_at ?? now()) : null;

        return $this->withSeoImage($request, $data, $page, 'pages');
    }
}
