<?php

namespace App\Models;

use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'settings.all';

    /**
     * Every setting the CMS knows about, with its default value. Contact details
     * default to empty: the site never shows a number or address that is not real.
     */
    public const DEFAULTS = [
        'site_name' => 'Sugara Digital',
        'tagline' => 'Jasa pembuatan website & solusi digital',
        'hero_headline' => 'Website Profesional untuk Bisnis yang Ingin Tumbuh di Era Digital.',
        'hero_subheadline' => 'Bangun kehadiran digital bisnis melalui website company profile, website UMKM, dan solusi web custom yang dirancang sesuai kebutuhan.',
        'hero_rotating_words' => "Company Profile\nWebsite UMKM\nAplikasi Laravel\nLanding Page",
        'default_meta_description' => 'Jasa pembuatan website company profile, website UMKM, dan aplikasi Laravel dengan paket harga transparan dan CMS untuk setiap paket.',
        'default_og_image' => '',
        'logo' => '',
        'whatsapp_number' => '',
        'whatsapp_default_message' => 'Halo, saya ingin berkonsultasi mengenai kebutuhan website.',
        'contact_email' => '',
        'address' => '',
        'service_area' => 'Melayani klien secara online di seluruh Indonesia',
        'business_hours' => 'Senin–Jumat, 09.00–17.00 WIB',
        'instagram_url' => '',
        'linkedin_url' => '',
        'facebook_url' => '',
        'ga4_measurement_id' => '',
        'analytics_require_consent' => '1',
        'google_site_verification' => '',
        'allow_indexing' => '1',
        'home_value_props' => "Harga transparan | Setiap paket mencantumkan cakupan, batas halaman, dan biaya yang tidak termasuk.\nCMS di setiap paket | Ubah teks, gambar, artikel, dan harga sendiri tanpa menyentuh kode.\nSEO sejak awal | Struktur URL, metadata, sitemap, dan kecepatan halaman disiapkan dari fondasi.\nSource code milik Anda | Setelah pelunasan, source code dan akses admin diserahkan kepada Anda.",
        'process_steps' => "Konsultasi kebutuhan | Kami pelajari tujuan bisnis, target pengunjung, dan fitur yang benar-benar dibutuhkan.\nPenawaran & ruang lingkup | Anda menerima rincian fitur, jumlah halaman, estimasi waktu, dan biaya tertulis.\nDesain & pengembangan | Website dibangun bertahap dan dapat Anda tinjau sebelum diluncurkan.\nRevisi & pengujian | Revisi sesuai kuota paket, lalu uji tampilan mobile, formulir, dan kecepatan.\nPeluncuran & serah terima | Website online, akses CMS diserahkan, dan Anda mendapat panduan penggunaan.",
        'tech_stack' => 'Laravel, PHP, MySQL, Tailwind CSS, React, Alpine.js, Google Analytics 4, Google Search Console',
        'lead_notification_email' => '',
        'footer_note' => 'Harga yang tercantum adalah harga awal. Spesifikasi dan biaya final ditentukan setelah konsultasi kebutuhan.',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = self::cached()[$key] ?? null;

        if ($value === null || $value === '') {
            return $default ?? (self::DEFAULTS[$key] ?? null);
        }

        return $value;
    }

    public static function bool(string $key): bool
    {
        return self::get($key) === '1';
    }

    /** @param  array<string, mixed>  $values */
    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            self::query()->updateOrCreate(['key' => $key], ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]);
        }

        Cache::forget(self::CACHE_KEY);
        SiteCache::flush();
    }

    /** @return array<string, string|null> */
    private static function cached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            if (! Schema::hasTable('settings')) {
                return [];
            }

            return self::query()->pluck('value', 'key')->all();
        });
    }

    /**
     * Settings stored one item per line as "Title | Description".
     *
     * @return array<int, array{title: string, text: string}>
     */
    public static function lines(string $key): array
    {
        return collect(preg_split('/\r?\n/', (string) self::get($key)))
            ->map(fn ($line) => array_map('trim', explode('|', $line, 2)))
            ->filter(fn ($parts) => $parts[0] !== '')
            ->map(fn ($parts) => ['title' => $parts[0], 'text' => $parts[1] ?? ''])
            ->values()
            ->all();
    }

    /** @return array<int, string> */
    public static function list(string $key, string $separator = ','): array
    {
        return array_values(array_filter(array_map('trim', explode($separator, (string) self::get($key)))));
    }

    public static function whatsappUrl(?string $message = null): ?string
    {
        $number = preg_replace('/\D+/', '', (string) self::get('whatsapp_number'));

        if ($number === '') {
            return null;
        }

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message ?: self::get('whatsapp_default_message'));
    }
}
