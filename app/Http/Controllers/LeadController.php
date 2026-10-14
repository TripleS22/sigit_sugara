<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Mail\NewLeadMail;
use App\Models\ConversionEvent;
use App\Models\Lead;
use App\Models\Package;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class LeadController extends Controller
{
    /** Bots submit instantly; people need a few seconds to fill the form. */
    private const MIN_FILL_SECONDS = 3;

    public function create(Request $request): View
    {
        $package = $request->filled('paket')
            ? Package::query()->active()->where('slug', $request->string('paket'))->first()
            : null;

        $seo = Seo::make('Kontak & Konsultasi Gratis')
            ->description('Ceritakan kebutuhan website Anda melalui formulir atau WhatsApp. Kami bantu menentukan paket, fitur, dan estimasi biaya yang sesuai.')
            ->canonical(route('contact'))
            ->breadcrumbs(['Kontak' => route('contact')]);

        if ($request->query()) {
            $seo->noindex();
        }

        return view('contact', [
            'package' => $package,
            'serviceType' => $request->string('layanan')->toString() ?: ($package?->segment === 'student' ? 'pelajar' : null),
            'seo' => $seo,
        ]);
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        if ($this->looksLikeSpam($request)) {
            // Answer like a success so bots learn nothing, but store nothing.
            return redirect()->route('leads.thanks');
        }

        $data = $request->validated();

        $lead = Lead::create([
            ...collect($data)->except('consent')->all(),
            'source_page' => Str::limit((string) ($data['source_page'] ?? ''), 250, ''),
            'referrer' => Str::limit((string) $request->headers->get('referer'), 250, ''),
            'consent_at' => now(),
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 250, ''),
        ]);

        ConversionEvent::create([
            'event' => 'lead_submit',
            'label' => Lead::SERVICE_TYPES[$lead->service_type] ?? $lead->service_type,
            'page_path' => $lead->source_page ?: '/kontak',
            'utm_source' => $lead->utm_source,
            'utm_medium' => $lead->utm_medium,
            'utm_campaign' => $lead->utm_campaign,
        ]);

        $this->notify($lead);

        return redirect()->route('leads.thanks')->with('lead_name', $lead->name)->with('lead_package', $lead->package_slug);
    }

    public function thanks(Request $request): View
    {
        $package = $request->session()->get('lead_package');
        $message = $package
            ? "Halo, saya baru mengirim formulir konsultasi untuk paket {$package}."
            : 'Halo, saya baru mengirim formulir konsultasi di website.';

        return view('thanks', [
            'name' => $request->session()->get('lead_name'),
            'whatsappMessage' => $message,
            'seo' => Seo::make('Terima Kasih')->noindex(false),
        ]);
    }

    private function looksLikeSpam(Request $request): bool
    {
        if ($request->filled('website')) {
            return true;
        }

        try {
            $startedAt = (int) Crypt::decryptString((string) $request->input('form_started'));
        } catch (DecryptException) {
            return true;
        }

        return now()->timestamp - $startedAt < self::MIN_FILL_SECONDS;
    }

    private function notify(Lead $lead): void
    {
        $to = Setting::get('lead_notification_email');

        if (! $to) {
            return;
        }

        try {
            Mail::to($to)->send(new NewLeadMail($lead));
        } catch (Throwable $e) {
            // The lead is saved; a mail outage must not lose it or show an error.
            report($e);
        }
    }
}
