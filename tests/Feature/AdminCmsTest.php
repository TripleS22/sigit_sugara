<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Lead;
use App\Models\Package;
use App\Models\Redirect;
use App\Models\Service;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    public function test_guests_are_sent_to_login_and_admin_is_never_indexed(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/login')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_login_and_logout(): void
    {
        $user = $this->admin();

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        $user = $this->admin();

        foreach (range(1, 5) as $i) {
            $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertTooManyRequests();
    }

    public function test_every_cms_screen_loads(): void
    {
        $this->seedSite();
        $admin = $this->admin();

        $screens = ['dashboard', 'leads.index', 'services.index', 'services.create', 'packages.index', 'packages.create',
            'portfolios.index', 'portfolios.create', 'articles.index', 'articles.create', 'categories.index', 'pages.index',
            'pages.create', 'faqs.index', 'faqs.create', 'testimonials.index', 'testimonials.create', 'media.index',
            'redirects.index', 'redirects.create', 'settings.edit', 'users.index', 'users.create'];

        foreach ($screens as $screen) {
            $this->actingAs($admin)->get(route('admin.'.$screen))->assertOk();
        }

        $this->actingAs($admin)->get(route('admin.services.edit', Service::first()))->assertOk();
        $this->actingAs($admin)->get(route('admin.packages.edit', Package::first()))->assertOk();
        $this->actingAs($admin)->get(route('admin.articles.edit', Article::first()))->assertOk();
    }

    public function test_editors_cannot_manage_settings_or_users(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->get(route('admin.settings.edit'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.articles.index'))->assertOk();
    }

    public function test_article_can_be_written_and_published(): void
    {
        $this->actingAs($this->editor())->post(route('admin.articles.store'), [
            'title' => 'Checklist Sebelum Meluncurkan Website',
            'slug' => 'checklist-peluncuran',
            'body' => "## Domain\n\nPastikan domain aktif.",
            'status' => 'published',
            'meta_title' => 'Checklist Peluncuran Website Bisnis',
            'robots_index' => '1',
            'robots_follow' => '1',
        ])->assertRedirect();

        $this->get('/blog/checklist-peluncuran')
            ->assertOk()
            ->assertSee('<title>Checklist Peluncuran Website Bisnis | Sugara Digital</title>', false)
            ->assertSee('<h2>Domain</h2>', false);
    }

    public function test_markdown_cannot_inject_scripts(): void
    {
        Article::create([
            'title' => 'XSS', 'slug' => 'xss', 'status' => 'published', 'published_at' => now(),
            'body' => "<script>alert(1)</script>\n\n[klik](javascript:alert(1))",
        ]);

        $html = $this->get('/blog/xss')->assertOk()->getContent();
        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringNotContainsString('href="javascript:', $html);
    }

    public function test_slug_must_be_unique_and_not_reserved(): void
    {
        $this->seedSite();
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.pages.store'), ['title' => 'Blog', 'slug' => 'blog', 'template' => 'default'])
            ->assertSessionHasErrors('slug');
        // Pages and services share the root URL space.
        $this->actingAs($admin)->post(route('admin.pages.store'), ['title' => 'X', 'slug' => 'jasa-pembuatan-website', 'template' => 'default'])
            ->assertSessionHasErrors('slug');
    }

    public function test_changing_a_published_slug_creates_a_permanent_redirect(): void
    {
        $this->seedSite();
        $article = Article::where('slug', 'perbedaan-domain-hosting-maintenance')->first();

        $article->update(['slug' => 'domain-hosting-maintenance']);

        $this->assertDatabaseHas('redirects', ['from_path' => '/blog/perbedaan-domain-hosting-maintenance', 'to_path' => '/blog/domain-hosting-maintenance']);
        $this->get('/blog/perbedaan-domain-hosting-maintenance?utm_source=x')
            ->assertStatus(301)
            ->assertRedirect(url('/blog/domain-hosting-maintenance?utm_source=x'));
    }

    public function test_redirect_chains_are_collapsed(): void
    {
        $this->seedSite();
        $article = Article::where('slug', 'perbedaan-domain-hosting-maintenance')->first();

        $article->update(['slug' => 'versi-dua']);
        $article->update(['slug' => 'versi-tiga']);

        $this->assertSame('/blog/versi-tiga', Redirect::where('from_path', '/blog/perbedaan-domain-hosting-maintenance')->value('to_path'));
    }

    public function test_deleting_published_content_redirects_to_its_listing(): void
    {
        $this->seedSite();
        $package = Package::where('slug', 'premium-ai')->first();

        $this->actingAs($this->admin())->delete(route('admin.packages.destroy', $package))->assertRedirect();

        $this->get('/paket/premium-ai')->assertStatus(301)->assertRedirect(url('/paket'));
    }

    public function test_package_features_are_managed_from_the_cms(): void
    {
        $this->seedSite();
        $package = Package::where('slug', 'starter')->first();

        $this->actingAs($this->admin())->put(route('admin.packages.update', $package), [
            'segment' => 'business', 'name' => 'Starter', 'slug' => 'starter', 'price_from' => 1100000,
            'is_active' => '1', 'robots_index' => '1', 'robots_follow' => '1',
            'features' => [
                ['label' => 'CMS', 'value' => '', 'is_included' => '1'],
                ['label' => 'Chatbot AI', 'value' => '', 'is_included' => '0'],
                ['label' => '', 'value' => '', 'is_included' => '1'],
            ],
        ])->assertRedirect();

        $this->assertSame(['CMS', 'Chatbot AI'], $package->features()->pluck('label')->all());
        $this->get('/paket/starter')->assertSee('Rp1.100.000')->assertSee('Tidak termasuk');
    }

    public function test_image_upload_requires_alt_text_and_rejects_svg(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.media.store'), ['files' => [UploadedFile::fake()->image('a.png')]])
            ->assertSessionHasErrors('alt');
        $this->actingAs($admin)->post(route('admin.media.store'), ['files' => [UploadedFile::fake()->create('x.svg', 1, 'image/svg+xml')], 'alt' => 'x'])
            ->assertSessionHasErrors('files.0');
        $this->actingAs($admin)->post(route('admin.media.store'), ['files' => [UploadedFile::fake()->image('hero.webp', 800, 400)], 'alt' => 'Tampilan website'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('media', ['alt' => 'Tampilan website', 'width' => 800]);
    }

    public function test_lead_status_is_tracked_and_exported_safely(): void
    {
        $lead = Lead::create([
            'name' => '=HYPERLINK("http://evil")', 'whatsapp' => '081234567890', 'service_type' => 'umkm',
            'message' => 'Butuh website', 'consent_at' => now(),
        ]);
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.leads.update', $lead), ['status' => 'won', 'is_qualified' => '1', 'deal_value' => 2000000])
            ->assertRedirect();
        $this->assertTrue($lead->fresh()->is_qualified);

        $csv = $this->actingAs($admin)->get(route('admin.leads.export'))->assertOk()->streamedContent();
        $this->assertStringContainsString("'=HYPERLINK", $csv);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertSee('Rp2.000.000');
    }

    public function test_testimonial_needs_confirmation_before_publishing(): void
    {
        $this->actingAs($this->admin())->post(route('admin.testimonials.store'), [
            'name' => 'Klien', 'quote' => 'Bagus', 'is_published' => '1',
        ])->assertSessionHasErrors('confirm_real');
    }

    public function test_last_admin_cannot_be_demoted_or_deleted(): void
    {
        $admin = $this->admin();
        $other = $this->editor();

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('user');
        $this->actingAs($admin)->put(route('admin.users.update', $admin), ['name' => 'A', 'email' => $admin->email, 'role' => 'editor'])
            ->assertSessionHasErrors('role');
        $this->actingAs($admin)->delete(route('admin.users.destroy', $other))->assertRedirect();
    }
}
