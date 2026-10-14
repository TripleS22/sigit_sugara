<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Page;
use App\Models\Service;
use App\Support\SeoColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/** Validation and upload helpers shared by the CMS controllers. */
trait ManagesContent
{
    /** Top-level paths owned by routes, never usable as a page or service slug. */
    protected const RESERVED_SLUGS = [
        'admin', 'paket', 'portofolio', 'blog', 'faq', 'kontak', 'track', 'sitemap', 'robots',
        'up', 'storage', 'build', 'login', 'logout', 'api',
    ];

    protected function slugRules(string $table, ?Model $ignore = null): array
    {
        return [
            'required', 'string', 'max:190', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::unique($table, 'slug')->ignore($ignore?->getKey()),
        ];
    }

    /**
     * Pages and services share the root URL space (/{slug}), so a slug must be
     * free in both tables and must not collide with a fixed route.
     */
    protected function rootSlugRules(string $table, ?Model $ignore = null): array
    {
        return [
            ...$this->slugRules($table, $ignore),
            Rule::notIn(self::RESERVED_SLUGS),
            function (string $attribute, mixed $value, \Closure $fail) use ($table) {
                $other = $table === 'services' ? Page::class : Service::class;
                if ($other::query()->where('slug', $value)->exists()) {
                    $fail('Slug ini sudah dipakai oleh '.($other === Page::class ? 'halaman' : 'layanan').' lain.');
                }
            },
        ];
    }

    protected function seoRules(): array
    {
        return [
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:95'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image_file' => ['nullable', ...$this->imageRules()],
            'robots_index' => ['boolean'],
            'robots_follow' => ['boolean'],
        ];
    }

    protected function imageRules(): array
    {
        // SVG is excluded on purpose: it can carry scripts.
        return ['file', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000'];
    }

    /** Booleans from checkboxes: an unchecked box is simply missing from the request. */
    protected function withBooleans(Request $request, array $fields): void
    {
        $request->merge(collect($fields)->mapWithKeys(fn ($f) => [$f => $request->boolean($f)])->all());
    }

    /**
     * Store an uploaded image, replace the previous file, or remove it when the
     * "remove" checkbox is ticked. Returns the new stored path (or null).
     */
    protected function handleImage(Request $request, string $input, ?string $current, string $folder): ?string
    {
        if ($request->hasFile($input)) {
            $path = $request->file($input)->store('uploads/'.$folder, 'public');
            $this->deleteFile($current);

            return $path;
        }

        if ($request->boolean('remove_'.$input)) {
            $this->deleteFile($current);

            return null;
        }

        return $current;
    }

    protected function deleteFile(?string $path): void
    {
        if ($path && Str::startsWith($path, 'uploads/')) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function withSeoImage(Request $request, array $data, ?Model $model, string $folder): array
    {
        $data['og_image'] = $this->handleImage($request, 'og_image_file', $model?->og_image, $folder);
        unset($data['og_image_file']);

        return $data;
    }

    /** @return array<int, string> one entry per non-empty line */
    protected function lines(?string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', (string) $value))));
    }

    /** @return array<int, string> */
    protected function commaList(?string $value): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $value))));
    }

    protected function seoFields(): array
    {
        return SeoColumns::FIELDS;
    }
}
