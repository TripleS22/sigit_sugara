<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    use ManagesContent;

    public function edit(): View
    {
        $values = collect(Setting::DEFAULTS)->mapWithKeys(fn ($default, $key) => [$key => Setting::get($key)]);

        return view('admin.settings.edit', ['values' => $values]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->withBooleans($request, ['analytics_require_consent', 'allow_indexing']);

        $request->merge(['whatsapp_number' => preg_replace('/\D+/', '', (string) $request->input('whatsapp_number'))]);

        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:80'],
            'tagline' => ['required', 'string', 'max:150'],
            'hero_headline' => ['required', 'string', 'max:150'],
            'hero_subheadline' => ['required', 'string', 'max:300'],
            'hero_rotating_words' => ['nullable', 'string', 'max:500'],
            'home_value_props' => ['nullable', 'string', 'max:3000'],
            'process_steps' => ['nullable', 'string', 'max:3000'],
            'tech_stack' => ['nullable', 'string', 'max:500'],
            'default_meta_description' => ['required', 'string', 'max:170'],
            'whatsapp_number' => ['nullable', 'regex:/^62[0-9]{8,13}$/'],
            'whatsapp_default_message' => ['required', 'string', 'max:300'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'lead_notification_email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:300'],
            'service_area' => ['nullable', 'string', 'max:190'],
            'business_hours' => ['nullable', 'string', 'max:190'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'ga4_measurement_id' => ['nullable', 'regex:/^G-[A-Z0-9]{4,20}$/'],
            'analytics_require_consent' => ['boolean'],
            'google_site_verification' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'allow_indexing' => ['boolean'],
            'footer_note' => ['nullable', 'string', 'max:500'],
            'logo_file' => ['nullable', ...$this->imageRules()],
            'default_og_image_file' => ['nullable', ...$this->imageRules()],
        ], [
            'whatsapp_number.regex' => 'Gunakan format internasional tanpa tanda +, misalnya 6281234567890.',
            'ga4_measurement_id.regex' => 'Measurement ID GA4 berbentuk G-XXXXXXXXXX.',
            'google_site_verification.regex' => 'Tempel hanya nilai content dari meta tag verifikasi.',
        ]);

        $data['logo'] = $this->handleImage($request, 'logo_file', Setting::get('logo') ?: null, 'settings') ?? '';
        $data['default_og_image'] = $this->handleImage($request, 'default_og_image_file', Setting::get('default_og_image') ?: null, 'settings') ?? '';
        unset($data['logo_file'], $data['default_og_image_file']);

        Setting::put($data);

        return back()->with('status', 'Pengaturan disimpan.');
    }
}
