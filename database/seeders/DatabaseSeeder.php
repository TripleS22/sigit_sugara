<?php

namespace Database\Seeders;

use App\Support\SiteCache;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Initial content: admin account, services, packages from the PRD,
     * FAQs, legal pages and starter articles. Portfolio and testimonials are
     * left empty on purpose: only real projects and real quotes belong there.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
            FaqSeeder::class,
            PageSeeder::class,
            ArticleSeeder::class,
        ]);

        SiteCache::flush();
    }
}
