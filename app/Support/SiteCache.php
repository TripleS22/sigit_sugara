<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Facades\Cache;

/**
 * Cache for public-site queries (navigation, home sections, sitemap).
 *
 * Keys are namespaced by a version number, so flush() invalidates everything
 * at once on any cache store, including the file store used on shared hosting.
 */
class SiteCache
{
    private const VERSION_KEY = 'site-cache-version';

    public static function remember(string $key, Closure $callback, int $seconds = 3600): mixed
    {
        return Cache::remember(self::key($key), $seconds, $callback);
    }

    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
    }

    private static function key(string $key): string
    {
        return 'site:v'.self::version().':'.$key;
    }

    private static function version(): int
    {
        return (int) Cache::rememberForever(self::VERSION_KEY, fn () => 1);
    }
}
