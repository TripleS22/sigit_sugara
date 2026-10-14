<?php

namespace App\Models\Concerns;

use App\Models\Redirect;

/**
 * Content with a public URL. When a published item's slug changes, or the item
 * is deleted, a 301 redirect is recorded so internal and external links keep
 * working (FR-01: deleted content must not leave broken links unhandled).
 */
trait HasPublicUrl
{
    abstract public function pathFor(string $slug): string;

    /** Where visitors go after this item is deleted, e.g. the listing page. */
    abstract public function fallbackPath(): string;

    abstract public function isPublic(): bool;

    protected static function bootHasPublicUrl(): void
    {
        static::updated(function ($model) {
            if ($model->wasChanged('slug') && $model->wasPublicBeforeUpdate()) {
                Redirect::record($model->pathFor($model->getOriginal('slug')), $model->pathFor($model->slug));
            }
        });

        static::deleted(function ($model) {
            if ($model->isPublic()) {
                Redirect::record($model->pathFor($model->slug), $model->fallbackPath());
            }
        });
    }

    public function path(): string
    {
        return $this->pathFor($this->slug);
    }

    public function url(): string
    {
        return url($this->path());
    }

    protected function wasPublicBeforeUpdate(): bool
    {
        $current = $this->getAttributes();
        $this->setRawAttributes($this->getOriginal());
        $public = $this->isPublic();
        $this->setRawAttributes($current);

        return $public;
    }
}
