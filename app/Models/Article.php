<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicUrl;
use App\Models\Concerns\HasSeo;
use App\Support\SeoColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use FlushesSiteCache, HasPublicUrl, HasSeo;

    public const STATUSES = ['draft' => 'Draft', 'published' => 'Published'];

    protected $fillable = [
        'category_id', 'user_id', 'title', 'slug', 'excerpt', 'body', 'tags',
        'featured_image', 'featured_image_alt', 'status', 'published_at', ...SeoColumns::FIELDS,
    ];

    protected function casts(): array
    {
        return ['tags' => 'array', 'published_at' => 'datetime', ...$this->seoCasts()];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Published and not scheduled for the future. */
    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function pathFor(string $slug): string
    {
        return '/blog/'.$slug;
    }

    public function fallbackPath(): string
    {
        return '/blog';
    }

    public function isPublic(): bool
    {
        return $this->status === 'published' && $this->published_at && $this->published_at->lte(now());
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 200));
    }
}
