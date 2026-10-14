{{--
    WhatsApp CTA with a message tailored to the page (FR-05). The link only opens
    WhatsApp with a draft; nothing is claimed as sent. Falls back to the contact
    form while no WhatsApp number is configured.
--}}
@props(['message' => null, 'label' => 'Konsultasi WhatsApp', 'trackLabel' => null, 'variant' => 'btn-wa'])
@php($href = \App\Models\Setting::whatsappUrl($message))
@if ($href)
    <a href="{{ $href }}" target="_blank" rel="noopener" data-track="whatsapp_click" data-track-label="{{ $trackLabel ?? $label }}"
        {{ $attributes->merge(['class' => $variant]) }}>
        <x-whatsapp-icon />
        <span>{{ $label }}</span>
    </a>
@else
    <a href="{{ route('contact') }}" data-track="cta_click" data-track-label="{{ $trackLabel ?? $label }} (form)"
        {{ $attributes->merge(['class' => $variant]) }}>
        <x-icon name="chat" />
        <span>{{ $label === 'Konsultasi WhatsApp' ? 'Konsultasi Gratis' : $label }}</span>
    </a>
@endif
