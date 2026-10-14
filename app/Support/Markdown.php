<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Renders CMS content. Raw HTML typed by editors is escaped and unsafe links
 * (javascript:, data:) are dropped, so stored content cannot inject scripts.
 */
class Markdown
{
    public static function render(?string $markdown): HtmlString
    {
        if (blank($markdown)) {
            return new HtmlString('');
        }

        $html = Str::markdown($markdown, [
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);

        $html = str_replace('<img ', '<img loading="lazy" decoding="async" ', $html);

        return new HtmlString($html);
    }

    /** Plain text for meta descriptions and excerpts. */
    public static function plain(?string $markdown, int $limit = 160): string
    {
        $text = strip_tags(Str::markdown((string) $markdown, ['html_input' => 'strip']));

        return Str::limit(trim(preg_replace('/\s+/', ' ', $text)), $limit);
    }
}
