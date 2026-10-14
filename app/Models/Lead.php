<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public const SERVICE_TYPES = [
        'company-profile' => 'Website company profile',
        'umkm' => 'Website UMKM',
        'laravel' => 'Aplikasi / sistem Laravel',
        'landing-page' => 'Landing page',
        'pelajar' => 'Proyek pelajar / mahasiswa',
        'maintenance' => 'Maintenance / pengembangan website',
        'lainnya' => 'Lainnya',
    ];

    public const BUDGETS = [
        '<1jt' => 'Di bawah Rp1 juta',
        '1-2.5jt' => 'Rp1 – 2,5 juta',
        '2.5-5jt' => 'Rp2,5 – 5 juta',
        '5-10jt' => 'Rp5 – 10 juta',
        '>10jt' => 'Di atas Rp10 juta',
        'belum-tahu' => 'Belum tahu / perlu saran',
    ];

    /** Sales pipeline from PRD §9.1: consultation → offer → project. */
    public const STATUSES = [
        'new' => 'Baru',
        'contacted' => 'Sudah dihubungi',
        'qualified' => 'Berkualitas',
        'proposal' => 'Penawaran dikirim',
        'won' => 'Proyek berjalan',
        'lost' => 'Tidak lanjut',
        'spam' => 'Spam',
    ];

    protected $fillable = [
        'name', 'organization', 'email', 'whatsapp', 'service_type', 'budget', 'message',
        'package_slug', 'source_page', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign',
        'status', 'is_qualified', 'deal_value', 'notes', 'consent_at', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return ['consent_at' => 'datetime', 'is_qualified' => 'boolean', 'deal_value' => 'integer'];
    }

    public function scopeNotSpam(Builder $query): void
    {
        $query->where('status', '!=', 'spam');
    }

    public function whatsappLink(): string
    {
        $number = preg_replace('/\D+/', '', $this->whatsapp);
        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        return 'https://wa.me/'.$number;
    }

    public function channel(): string
    {
        return $this->utm_source ?: ($this->referrer ? (parse_url($this->referrer, PHP_URL_HOST) ?: 'referral') : 'langsung');
    }
}
