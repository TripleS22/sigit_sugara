<?php

namespace App\Models\Concerns;

use App\Support\Seo;

/**
 * Builds page metadata from the shared SEO columns, falling back to the
 * content's own title, summary and image when a field is left empty.
 */
trait HasSeo
{
    public function seo(string $fallbackTitle, ?string $fallbackDescription = null, ?string $fallbackImage = null): Seo
    {
        $seo = Seo::make($this->meta_title ?: $fallbackTitle)
            ->description($this->meta_description ?: $fallbackDescription)
            ->canonical($this->canonical_url ?: $this->url())
            ->image($this->og_image ?: $fallbackImage);

        $seo->ogTitle = $this->og_title;
        $seo->ogDescription = $this->og_description;

        if (! $this->robots_index || ! $this->robots_follow) {
            $seo->index = (bool) $this->robots_index;
            $seo->follow = (bool) $this->robots_follow;
        }

        return $seo;
    }

    /** @return array<string, string> */
    protected function seoCasts(): array
    {
        return ['robots_index' => 'boolean', 'robots_follow' => 'boolean'];
    }
}
