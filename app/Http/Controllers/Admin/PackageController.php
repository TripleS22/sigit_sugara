<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PackageController extends Controller
{
    use ManagesContent;

    public function index(): View
    {
        $packages = Package::query()->ordered()->withCount('features')->get()->groupBy('segment');

        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View
    {
        return view('admin.packages.form', ['package' => new Package(['segment' => 'business', 'is_active' => true, 'robots_index' => true, 'robots_follow' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $package = DB::transaction(function () use ($request) {
            [$data, $features] = $this->validated($request);
            $package = Package::create($data);
            $this->syncFeatures($package, $features);

            return $package;
        });

        return redirect()->route('admin.packages.edit', $package)->with('status', 'Paket dibuat.');
    }

    public function edit(Package $package): View
    {
        $package->load('features');

        return view('admin.packages.form', compact('package'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        DB::transaction(function () use ($request, $package) {
            [$data, $features] = $this->validated($request, $package);
            $package->update($data);
            $this->syncFeatures($package, $features);
        });

        return back()->with('status', 'Paket disimpan. Harga dan fitur langsung tampil di website.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('status', 'Paket dihapus. URL lama dialihkan ke halaman paket.');
    }

    private function syncFeatures(Package $package, array $features): void
    {
        $package->features()->delete();

        foreach (array_values($features) as $i => $feature) {
            $package->features()->create([
                'label' => $feature['label'],
                'value' => $feature['value'] ?? null,
                'is_included' => (bool) ($feature['is_included'] ?? false),
                'sort_order' => $i,
            ]);
        }
    }

    /** @return array{0: array<string, mixed>, 1: array<int, array<string, mixed>>} */
    private function validated(Request $request, ?Package $package = null): array
    {
        $this->withBooleans($request, ['is_active', 'is_featured', 'robots_index', 'robots_follow']);

        // Drop empty repeater rows before validating.
        $request->merge(['features' => collect($request->input('features', []))
            ->filter(fn ($f) => filled($f['label'] ?? null))
            ->values()->all()]);

        $data = $request->validate([
            'segment' => ['required', Rule::in(array_keys(Service::SEGMENTS))],
            'name' => ['required', 'string', 'max:100'],
            'slug' => $this->slugRules('packages', $package),
            'tagline' => ['nullable', 'string', 'max:190'],
            'target_audience' => ['nullable', 'string', 'max:190'],
            'price_from' => ['required', 'integer', 'min:0', 'max:9999999999'],
            'price_to' => ['nullable', 'integer', 'gte:price_from', 'max:9999999999'],
            'price_prefix' => ['nullable', 'string', 'max:30'],
            'max_pages' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:2000'],
            'hosting_note' => ['nullable', 'string', 'max:190'],
            'delivery_estimate' => ['nullable', 'string', 'max:190'],
            'badge' => ['nullable', 'string', 'max:40'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'features' => ['array', 'max:60'],
            'features.*.label' => ['required', 'string', 'max:120'],
            'features.*.value' => ['nullable', 'string', 'max:120'],
            'features.*.is_included' => ['nullable', 'boolean'],
            ...$this->seoRules(),
        ]);

        $features = $data['features'] ?? [];
        unset($data['features']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return [$this->withSeoImage($request, $data, $package, 'packages'), $features];
    }
}
