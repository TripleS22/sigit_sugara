<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Package;
use App\Models\Setting;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seedSite();
    }

    public static function publicPages(): array
    {
        return [
            ['/'], ['/jasa-pembuatan-website'], ['/jasa-pembuatan-website-company-profile'],
            ['/jasa-pembuatan-website-umkm'], ['/jasa-pembuatan-website-laravel'], ['/jasa-pembuatan-website-perusahaan'],
            ['/jasa-website-pelajar'], ['/jasa-website-tugas-kuliah'], ['/jasa-website-laravel-mahasiswa'], ['/jasa-proyek-web-mahasiswa'],
            ['/paket'], ['/paket/starter'], ['/paket/business'], ['/paket/premium-ai'], ['/paket/enterprise'],
            ['/portofolio'], ['/blog'], ['/blog/biaya-pembuatan-website-company-profile'], ['/blog/kategori/biaya-paket'],
            ['/faq'], ['/kontak'], ['/tentang-kami'], ['/kebijakan-privasi'], ['/syarat-ketentuan'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_page_renders_with_one_h1_and_canonical(string $path): void
    {
        $response = $this->get($path)->assertOk();
        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), "Expected exactly one H1 on {$path}");
        $this->assertStringContainsString('<link rel="canonical"', $html);
        $this->assertStringContainsString('<meta name="description"', $html);
        $this->assertStringContainsString('application/ld+json', $html);
    }

    public function test_service_page_uses_meta_from_cms(): void
    {
        $this->get('/jasa-pembuatan-website-company-profile')
            ->assertSee('<title>Jasa Pembuatan Website Company Profile Profesional | Sugara Digital</title>', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('data-track-view="service_view"', false);
    }

    public function test_inactive_package_is_hidden_from_public(): void
    {
        Package::where('slug', 'enterprise')->update(['is_active' => false]);

        $this->get('/paket/enterprise')->assertNotFound();
        $this->get('/paket')->assertDontSee('/paket/enterprise');
    }

    public function test_draft_and_scheduled_articles_are_not_public(): void
    {
        $this->get('/blog/isi-website-company-profile')->assertNotFound();

        $article = Article::where('slug', 'biaya-pembuatan-website-company-profile')->first();
        $article->update(['published_at' => now()->addDay()]);
        $this->get('/blog/biaya-pembuatan-website-company-profile')->assertNotFound();
    }

    public function test_admin_can_preview_unpublished_content_with_noindex(): void
    {
        $this->actingAs($this->admin())
            ->get('/blog/isi-website-company-profile')
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex,follow">', false);
    }

    public function test_internal_search_results_are_noindex(): void
    {
        $this->get('/blog?q=domain')->assertOk()->assertSee('content="noindex,follow"', false);
    }

    public function test_sitemap_lists_only_published_indexable_urls(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8')->getContent();

        $this->assertStringContainsString('/jasa-pembuatan-website-company-profile</loc>', $xml);
        $this->assertStringContainsString('/paket/business</loc>', $xml);
        $this->assertStringContainsString('/blog/biaya-pembuatan-website-company-profile</loc>', $xml);
        $this->assertStringNotContainsString('isi-website-company-profile', $xml);
        $this->assertStringNotContainsString('/admin', $xml);
        // No portfolio is published yet, so the empty listing is left out.
        $this->assertStringNotContainsString('/portofolio</loc>', $xml);
    }

    public function test_robots_blocks_admin_and_points_to_sitemap(): void
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin')->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    public function test_indexing_switch_blocks_the_whole_site(): void
    {
        Setting::put(['allow_indexing' => false]);

        $this->get('/robots.txt')->assertSee("Disallow: /\n", false);
        $this->get('/')->assertSee('<meta name="robots" content="noindex,nofollow">', false);
    }

    public function test_unknown_url_returns_helpful_404(): void
    {
        $this->get('/halaman-yang-tidak-ada')->assertNotFound()->assertSee('Halaman tidak ditemukan')->assertSee('Paket Harga');
    }

    public function test_whatsapp_button_falls_back_to_contact_form_without_a_number(): void
    {
        $this->get('/paket')->assertDontSee('wa.me', false)->assertSee(route('contact'), false);
    }

    public function test_whatsapp_button_uses_package_specific_message(): void
    {
        Setting::put(['whatsapp_number' => '6281234567890']);

        $message = rawurlencode('Halo, saya tertarik dengan paket Business. Saya ingin berkonsultasi mengenai kebutuhan website.');

        $this->get('/paket/business')->assertSee('https://wa.me/6281234567890?text='.$message, false);
    }

    public function test_package_price_change_in_cms_shows_on_site(): void
    {
        Package::where('slug', 'starter')->first()->update(['price_from' => 1250000]);

        $this->get('/paket')->assertSee('Rp1.250.000');
    }

    public function test_home_hides_portfolio_and_testimonial_sections_until_real_content_exists(): void
    {
        $this->get('/')->assertDontSee('Hasil pengerjaan kami')->assertDontSee('Kata klien kami');
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }
}
