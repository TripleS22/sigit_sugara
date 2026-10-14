<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceController extends Controller
{
    use ManagesContent;

    public function index(): View
    {
        $services = Service::query()->whereNull('parent_id')->ordered()
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')])
            ->get();

        $orphans = Service::query()->whereNotNull('parent_id')->whereDoesntHave('parent')->get();

        return view('admin.services.index', compact('services', 'orphans'));
    }

    public function create(): View
    {
        return view('admin.services.form', [
            'service' => new Service(['segment' => 'business', 'show_in_menu' => true, 'robots_index' => true, 'robots_follow' => true]),
            'parents' => $this->parents(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $service = Service::create($this->validated($request));

        return redirect()->route('admin.services.edit', $service)->with('status', 'Layanan dibuat.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service, 'parents' => $this->parents($service)]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request, $service));

        return back()->with('status', 'Layanan disimpan.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->load('parent');
        $this->deleteFile($service->image);
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Layanan dihapus. URL lama dialihkan ke halaman induk.');
    }

    private function parents(?Service $except = null)
    {
        return Service::query()->whereNull('parent_id')
            ->when($except, fn ($q) => $q->whereKeyNot($except->id))
            ->ordered()->get();
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        $this->withBooleans($request, ['is_published', 'show_in_menu', 'robots_index', 'robots_follow']);

        $data = $request->validate([
            'parent_id' => ['nullable', Rule::exists('services', 'id')->whereNull('parent_id'), Rule::notIn([$service?->id])],
            'segment' => ['required', Rule::in(array_keys(Service::SEGMENTS))],
            'title' => ['required', 'string', 'max:190'],
            'slug' => $this->rootSlugRules('services', $service),
            'menu_label' => ['nullable', 'string', 'max:60'],
            'headline' => ['nullable', 'string', 'max:190'],
            'subheadline' => ['nullable', 'string', 'max:500'],
            'summary' => ['nullable', 'string', 'max:300'],
            'icon' => ['nullable', 'string', 'max:40'],
            'highlights_text' => ['nullable', 'string', 'max:3000'],
            'body' => ['nullable', 'string', 'max:100000'],
            'timeline' => ['nullable', 'string', 'max:190'],
            'image_file' => ['nullable', ...$this->imageRules()],
            'image_alt' => ['nullable', 'string', 'max:190', 'required_with:image_file'],
            'is_published' => ['boolean'],
            'show_in_menu' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            ...$this->seoRules(),
        ]);

        $data['highlights'] = $this->lines($data['highlights_text'] ?? '');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['image'] = $this->handleImage($request, 'image_file', $service?->image, 'services');
        unset($data['highlights_text'], $data['image_file']);

        return $this->withSeoImage($request, $data, $service, 'services');
    }
}
