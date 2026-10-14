{{--
    Card rendered on the server, enhanced with the React Bits SpotlightCard
    (pointer-following light) once visible. The HTML inside stays crawlable.
--}}
@props(['class' => '', 'highlight' => false])
<div data-island="SpotlightCard" data-island-wrap
    data-props="{{ json_encode(['theme' => 'light', 'className' => trim('h-full '.$class.($highlight ? ' ring-2 ring-brand-600' : '')), 'spotlightColor' => '#5227FF', 'intensity' => 0.12]) }}"
    {{ $attributes->merge(['class' => trim('spotlight-fallback h-full '.$class.($highlight ? ' ring-2 ring-brand-600' : ''))]) }}>
    {{ $slot }}
</div>
