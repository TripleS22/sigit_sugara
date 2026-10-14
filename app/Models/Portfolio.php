<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicUrl;
use App\Models\Concerns\HasSeo;
use App\Support\SeoColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    use FlushesSiteCache, HasPublicUrl, HasSeo;

    protected $fillable = [
        'title', 'slug', 'project_type', 'client_name', 'summary', 'description', 'technologies',
        'features', 'results', 'image', 'image_alt', 'demo_url', 'completed_at', 'is_featured',
        'is_published', 'sort_order', ...SeoColumns::FIELDS,
    ];

    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'features' => 'array',
            'completed_at' => 'date',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            ...$this->seoCasts(),
        ];
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderByDesc('completed_at')->orderByDesc('id');
    }

    public function pathFor(string $slug): string
    {
        return '/portofolio/'.$slug;
    }

    public function fallbackPath(): string
    {
        return '/portofolio';
    }

    public function isPublic(): bool
    {
        return (bool) $this->is_published;
    }
}
