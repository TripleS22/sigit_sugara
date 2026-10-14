<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    use FlushesSiteCache;

    public const GROUPS = [
        'umum' => 'Umum',
        'harga' => 'Harga & pembayaran',
        'teknis' => 'Domain, hosting & teknis',
        'pelajar' => 'Pelajar & mahasiswa',
    ];

    protected $fillable = ['service_id', 'group', 'question', 'answer', 'is_published', 'show_on_home', 'sort_order'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'show_on_home' => 'boolean'];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort_order')->orderBy('id');
    }
}
