<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    use ManagesContent;

    public function index(): View
    {
        $portfolios = Portfolio::query()->ordered()->paginate(20);

        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create(): View
    {
        return view('admin.portfolios.form', ['portfolio' => new Portfolio(['robots_index' => true, 'robots_follow' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $portfolio = Portfolio::create($this->validated($request));

        return redirect()->route('admin.portfolios.edit', $portfolio)->with('status', 'Portofolio dibuat.');
    }

    public function edit(Portfolio $portfolio): View
    {
        return view('admin.portfolios.form', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio): RedirectResponse
    {
        $portfolio->update($this->validated($request, $portfolio));

        return back()->with('status', 'Portofolio disimpan.');
    }

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        $this->deleteFile($portfolio->image);
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')->with('status', 'Portofolio dihapus. URL lama dialihkan ke daftar portofolio.');
    }

    private function validated(Request $request, ?Portfolio $portfolio = null): array
    {
        $this->withBooleans($request, ['is_published', 'is_featured', 'robots_index', 'robots_follow']);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:190'],
            'slug' => $this->slugRules('portfolios', $portfolio),
            'project_type' => ['nullable', 'string', 'max:100'],
            'client_name' => ['nullable', 'string', 'max:150'],
            'summary' => ['nullable', 'string', 'max:300'],
            'description' => ['nullable', 'string', 'max:100000'],
            'technologies_text' => ['nullable', 'string', 'max:500'],
            'features_text' => ['nullable', 'string', 'max:3000'],
            'results' => ['nullable', 'string', 'max:3000'],
            'image_file' => ['nullable', ...$this->imageRules()],
            'image_alt' => ['nullable', 'string', 'max:190', 'required_with:image_file'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'completed_at' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            ...$this->seoRules(),
        ]);

        $data['technologies'] = $this->commaList($data['technologies_text'] ?? '');
        $data['features'] = $this->lines($data['features_text'] ?? '');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['image'] = $this->handleImage($request, 'image_file', $portfolio?->image, 'portfolios');
        unset($data['technologies_text'], $data['features_text'], $data['image_file']);

        return $this->withSeoImage($request, $data, $portfolio, 'portfolios');
    }
}
