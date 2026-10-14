<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ConversionEvent;
use App\Models\Lead;
use App\Models\Package;
use App\Models\Portfolio;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $days = in_array((int) $request->query('hari'), [7, 30, 90], true) ? (int) $request->query('hari') : 30;
        $since = now()->subDays($days)->startOfDay();

        $events = ConversionEvent::query()->where('created_at', '>=', $since)
            ->selectRaw('event, count(*) as total')->groupBy('event')->pluck('total', 'event');

        $leads = Lead::query()->notSpam()->where('created_at', '>=', $since);

        // Funnel from PRD §9.3: clicks and forms are not sales; track quality and closed deals too.
        $funnel = [
            'Kunjungan halaman layanan' => (int) ($events['service_view'] ?? 0),
            'Klik WhatsApp' => (int) ($events['whatsapp_click'] ?? 0),
            'Klik CTA paket' => (int) ($events['package_cta_click'] ?? 0),
            'Formulir terkirim' => (int) (clone $leads)->count(),
            'Leads berkualitas' => (int) (clone $leads)->where('is_qualified', true)->count(),
            'Penawaran dikirim' => (int) (clone $leads)->whereIn('status', ['proposal', 'won'])->count(),
            'Proyek terjual' => (int) (clone $leads)->where('status', 'won')->count(),
        ];

        $revenue = (int) (clone $leads)->where('status', 'won')->sum('deal_value');

        $byChannel = (clone $leads)->get(['utm_source', 'referrer', 'status', 'deal_value'])
            ->groupBy(fn (Lead $l) => $l->channel())
            ->map(fn ($group, $channel) => [
                'channel' => $channel,
                'leads' => $group->count(),
                'won' => $group->where('status', 'won')->count(),
                'revenue' => $group->where('status', 'won')->sum('deal_value'),
            ])->sortByDesc('leads')->values();

        $topPages = ConversionEvent::query()->where('created_at', '>=', $since)
            ->whereIn('event', ['whatsapp_click', 'package_cta_click', 'lead_submit'])
            ->selectRaw('page_path, count(*) as total')->groupBy('page_path')
            ->orderByDesc('total')->limit(8)->get();

        $checklist = [
            'Nomor WhatsApp diatur' => filled(Setting::get('whatsapp_number')),
            'Email kontak diatur' => filled(Setting::get('contact_email')),
            'Google Analytics 4 terhubung' => filled(Setting::get('ga4_measurement_id')),
            'Verifikasi Google Search Console' => filled(Setting::get('google_site_verification')),
            'Situs boleh diindeks' => Setting::bool('allow_indexing'),
            'Minimal 1 portofolio terbit' => Portfolio::query()->published()->exists(),
            'Minimal 3 artikel terbit' => Article::query()->published()->count() >= 3,
            'Paket harga aktif' => Package::query()->active()->exists(),
        ];

        return view('admin.dashboard', [
            'days' => $days,
            'funnel' => $funnel,
            'revenue' => $revenue,
            'byChannel' => $byChannel,
            'topPages' => $topPages,
            'checklist' => $checklist,
            'newLeads' => Lead::query()->where('status', 'new')->latest()->limit(5)->get(),
            'newLeadCount' => Lead::query()->where('status', 'new')->count(),
            'draftCount' => Article::query()->where('status', 'draft')->count(),
        ]);
    }
}
