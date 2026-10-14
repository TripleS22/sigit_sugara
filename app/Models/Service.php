<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicUrl;
use App\Models\Concerns\HasSeo;
use App\Support\SeoColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use FlushesSiteCache, HasPublicUrl, HasSeo;

    public const SEGMENTS = ['business' => 'Bisnis & UMKM', 'student' => 'Pelajar & Mahasiswa'];

    protected $fillable = [
        'parent_id', 'segment', 'title', 'slug', 'menu_label', 'headline', 'subheadline', 'summary',
        'icon', 'highlights', 'body', 'timeline', 'image', 'image_alt', 'is_published', 'show_in_menu',
        'sort_order', ...SeoColumns::FIELDS,
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
            ...$this->seoCasts(),
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Service::class, 'parent_id')->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function pathFor(string $slug): string
    {
        return '/'.$slug;
    }

    public function fallbackPath(): string
    {
        return $this->parent?->path() ?? '/';
    }

    public function isPublic(): bool
    {
        return (bool) $this->is_published;
    }

    public function label(): string
    {
        return $this->menu_label ?: $this->title;
    }
}
