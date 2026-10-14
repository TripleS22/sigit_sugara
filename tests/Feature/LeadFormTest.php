<?php

namespace Tests\Feature;

use App\Mail\NewLeadMail;
use App\Models\ConversionEvent;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LeadFormTest extends TestCase
{
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'organization' => 'Katering Sehat',
            'email' => 'budi@example.com',
            'whatsapp' => '0812-3456-7890',
            'service_type' => 'umkm',
            'budget' => '1-2.5jt',
            'message' => 'Saya butuh website 5 halaman untuk usaha katering dengan galeri menu.',
            'consent' => '1',
            'utm_source' => 'google',
            'utm_campaign' => 'website-umkm',
            'form_started' => Crypt::encryptString((string) now()->subMinute()->timestamp),
        ], $overrides);
    }

    public function test_valid_lead_is_stored_and_counted_as_conversion(): void
    {
        $this->post('/kontak', $this->payload())->assertRedirect(route('leads.thanks'));

        $lead = Lead::sole();
        $this->assertSame('08123456789'.'0', $lead->whatsapp);
        $this->assertSame('new', $lead->status);
        $this->assertNotNull($lead->consent_at);
        $this->assertSame('google', $lead->channel());
        $this->assertDatabaseHas('conversion_events', ['event' => 'lead_submit', 'utm_campaign' => 'website-umkm']);
    }

    public function test_thank_you_page_does_not_claim_whatsapp_was_sent(): void
    {
        $this->followingRedirects()->post('/kontak', $this->payload())
            ->assertOk()
            ->assertSee('Terima kasih, Budi Santoso!')
            ->assertSee('Pesan baru terkirim setelah Anda menekan tombol kirim di WhatsApp');
    }

    public function test_required_fields_and_consent_are_validated(): void
    {
        $this->post('/kontak', $this->payload(['name' => '', 'whatsapp' => '12345', 'consent' => null]))
            ->assertSessionHasErrors(['name', 'whatsapp', 'consent']);

        $this->assertSame(0, Lead::count());
    }

    public function test_honeypot_submissions_are_silently_dropped(): void
    {
        $this->post('/kontak', $this->payload(['website' => 'http://spam.example']))->assertRedirect(route('leads.thanks'));

        $this->assertSame(0, Lead::count());
        $this->assertSame(0, ConversionEvent::count());
    }

    public function test_instant_submissions_are_treated_as_bots(): void
    {
        $this->post('/kontak', $this->payload(['form_started' => Crypt::encryptString((string) now()->timestamp)]));
        $this->post('/kontak', $this->payload(['form_started' => 'tampered']));

        $this->assertSame(0, Lead::count());
    }

    public function test_lead_form_is_rate_limited(): void
    {
        foreach (range(1, 3) as $i) {
            $this->post('/kontak', $this->payload())->assertRedirect();
        }

        $this->post('/kontak', $this->payload())->assertTooManyRequests();
    }

    public function test_notification_email_is_sent_when_configured(): void
    {
        Mail::fake();
        Setting::put(['lead_notification_email' => 'owner@example.com']);

        $this->post('/kontak', $this->payload());

        Mail::assertSent(NewLeadMail::class, fn ($mail) => $mail->hasTo('owner@example.com'));
    }
}
