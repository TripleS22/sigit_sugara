<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicUrl;
use App\Models\Concerns\HasSeo;
use App\Support\SeoColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use FlushesSiteCache, HasPublicUrl, HasSeo;

    public const TEMPLATES = [
        'default' => 'Standar',
        'about' => 'Tentang kami (dengan proses & CTA)',
        'legal' => 'Dokumen legal (kebijakan, syarat)',
    ];

    protected $fillable = [
        'title', 'slug', 'template', 'excerpt', 'body', 'hero_image', 'hero_image_alt',
        'is_published', 'published_at', ...SeoColumns::FIELDS,
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'published_at' => 'datetime', ...$this->seoCasts()];
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    public function pathFor(string $slug): string
    {
        return '/'.$slug;
    }

    public function fallbackPath(): string
    {
        return '/';
    }

    public function isPublic(): bool
    {
        return (bool) $this->is_published;
    }
}
