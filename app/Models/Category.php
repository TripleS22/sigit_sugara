<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use FlushesSiteCache, HasPublicUrl;

    protected $fillable = ['name', 'slug', 'description', 'meta_title', 'meta_description'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function pathFor(string $slug): string
    {
        return '/blog/kategori/'.$slug;
    }

    public function fallbackPath(): string
    {
        return '/blog';
    }

    public function isPublic(): bool
    {
        return true;
    }
}
