<?php

namespace App\Http\Controllers;

use App\Models\ConversionEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/** Receives sendBeacon events from resources/js/tracking.js. */
class TrackingController extends Controller
{
    /** lead_submit is recorded server-side only, so it cannot be faked from the browser. */
    private const CLIENT_EVENTS = ['whatsapp_click', 'package_cta_click', 'service_view', 'cta_click'];

    public function __invoke(Request $request): Response
    {
        $data = $request->validate([
            'event' => ['required', Rule::in(self::CLIENT_EVENTS)],
            'label' => ['nullable', 'string', 'max:500'],
            'path' => ['nullable', 'string', 'max:500'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
        ]);

        ConversionEvent::create([
            'event' => $data['event'],
            'label' => Str::limit((string) ($data['label'] ?? ''), 185),
            'page_path' => Str::limit((string) ($data['path'] ?? ''), 250, ''),
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
        ]);

        return response()->noContent();
    }
}
