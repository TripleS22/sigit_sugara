<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;

/**
 * Shared SEO columns (PRD §11) so every content table stores metadata the same way.
 */
class SeoColumns
{
    public const FIELDS = [
        'meta_title', 'meta_description', 'canonical_url',
        'og_title', 'og_description', 'og_image',
        'robots_index', 'robots_follow',
    ];

    public static function add(Blueprint $table): void
    {
        $table->string('meta_title', 70)->nullable();
        $table->string('meta_description', 170)->nullable();
        $table->string('canonical_url')->nullable();
        $table->string('og_title', 95)->nullable();
        $table->string('og_description', 200)->nullable();
        $table->string('og_image')->nullable();
        $table->boolean('robots_index')->default(true);
        $table->boolean('robots_follow')->default(true);
    }
}
