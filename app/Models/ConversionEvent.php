<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Anonymous marketing event (FR-07). No IP address, cookie id or personal data
 * is stored: only what was clicked, on which page, and the campaign source.
 */
class ConversionEvent extends Model
{
    public const UPDATED_AT = null;

    public const EVENTS = [
        'whatsapp_click' => 'Klik WhatsApp',
        'lead_submit' => 'Formulir terkirim',
        'package_cta_click' => 'Klik CTA paket',
        'service_view' => 'Kunjungan halaman layanan',
        'cta_click' => 'Klik CTA lainnya',
    ];

    protected $fillable = ['event', 'label', 'page_path', 'utm_source', 'utm_medium', 'utm_campaign'];
}
