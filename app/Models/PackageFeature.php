<?php

namespace App\Models;

use App\Models\Concerns\FlushesSiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageFeature extends Model
{
    use FlushesSiteCache;

    protected $fillable = ['package_id', 'label', 'value', 'is_included', 'sort_order'];

    protected function casts(): array
    {
        return ['is_included' => 'boolean'];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
