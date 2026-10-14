<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use App\Models\Concerns\HasPublicUrl;
use App\Models\Concerns\HasSeo;
use App\Support\SeoColumns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use FlushesSiteCache, HasPublicUrl, HasSeo;

    protected $fillable = [
        'segment', 'name', 'slug', 'tagline', 'target_audience', 'price_from', 'price_to', 'price_prefix',
        'max_pages', 'description', 'hosting_note', 'delivery_estimate', 'badge', 'is_featured',
        'is_active', 'sort_order', ...SeoColumns::FIELDS,
    ];

    protected function casts(): array
    {
        return [
            'price_from' => 'integer',
            'price_to' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            ...$this->seoCasts(),
        ];
    }

    public function features(): HasMany
    {
        return $this->hasMany(PackageFeature::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('price_from');
    }

    public function pathFor(string $slug): string
    {
        return '/paket/'.$slug;
    }

    public function fallbackPath(): string
    {
        return '/paket';
    }

    public function isPublic(): bool
    {
        return (bool) $this->is_active;
    }

    public static function rupiah(int $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }

    public function priceLabel(): string
    {
        $label = self::rupiah($this->price_from);

        if ($this->price_to && $this->price_to > $this->price_from) {
            $label .= ' – '.self::rupiah($this->price_to);
        }

        return trim(($this->price_prefix ? $this->price_prefix.' ' : '').$label);
    }

    public function whatsappMessage(): string
    {
        return "Halo, saya tertarik dengan paket {$this->name}. Saya ingin berkonsultasi mengenai kebutuhan website.";
    }
}
