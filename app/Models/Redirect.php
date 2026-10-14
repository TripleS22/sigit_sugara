<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Redirect extends Model
{
    protected $fillable = ['from_path', 'to_path', 'status_code'];

    private const CACHE_KEY = 'redirects.map';

    protected static function booted(): void
    {
        static::saving(function (Redirect $redirect) {
            $redirect->from_path = self::normalize($redirect->from_path);
            $redirect->to_path = self::normalizeTarget($redirect->to_path);
        });
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    public static function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';

        return '/'.trim($path, '/');
    }

    public static function normalizeTarget(string $target): string
    {
        return str_starts_with($target, 'http') ? $target : self::normalize($target);
    }

    /** @return array<string, array{0: string, 1: int, 2: int}> from_path => [to, code, id] */
    public static function map(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => self::query()
            ->get(['id', 'from_path', 'to_path', 'status_code'])
            ->mapWithKeys(fn ($r) => [$r->from_path => [$r->to_path, $r->status_code, $r->id]])
            ->all());
    }

    /**
     * Record a permanent redirect when published content moves or disappears,
     * and re-point older redirects so visitors never hop through a chain.
     */
    public static function record(string $from, string $to): void
    {
        $from = self::normalize($from);
        $to = self::normalizeTarget($to);

        if ($from === $to) {
            return;
        }

        self::query()->where('from_path', $to)->delete();
        self::query()->where('to_path', $from)->get()->each(fn (Redirect $r) => $r->update(['to_path' => $to]));
        self::query()->updateOrCreate(['from_path' => $from], ['to_path' => $to, 'status_code' => 301]);
    }
}
