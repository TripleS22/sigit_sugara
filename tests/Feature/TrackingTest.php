<?php

namespace Tests\Feature;

use App\Models\ConversionEvent;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    public function test_whatsapp_click_is_recorded_without_personal_data(): void
    {
        $this->post('/track', ['event' => 'whatsapp_click', 'label' => 'Paket Business', 'path' => '/paket', 'utm_source' => 'google'])
            ->assertNoContent();

        $event = ConversionEvent::sole();
        $this->assertSame('whatsapp_click', $event->event);
        $this->assertSame('/paket', $event->page_path);
        $this->assertArrayNotHasKey('ip_address', $event->getAttributes());
    }

    public function test_lead_submit_cannot_be_faked_from_the_browser(): void
    {
        $this->post('/track', ['event' => 'lead_submit'])->assertSessionHasErrors('event');
        $this->assertSame(0, ConversionEvent::count());
    }
}
