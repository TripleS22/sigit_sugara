<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['path', 'original_name', 'alt', 'mime_type', 'size', 'width', 'height', 'user_id'];

    protected static function booted(): void
    {
        static::deleted(fn (Media $media) => Storage::disk('public')->delete($media->path));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function url(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function markdown(): string
    {
        return '!['.($this->alt ?: '').']('.$this->url().')';
    }
}
