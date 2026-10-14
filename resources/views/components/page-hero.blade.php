{{-- Dark header for inner pages, with a subtle React Bits Aurora behind the title. --}}
@props(['title', 'lead' => null, 'eyebrow' => null, 'breadcrumbs' => []])
<section class="relative isolate overflow-hidden bg-ink text-white">
    <div class="absolute inset-0 -z-10 opacity-60" aria-hidden="true"
        data-island="Aurora"
        data-props="{{ json_encode(['colorStops' => ['#5227FF', '#7c5cff', '#1fa8c9'], 'amplitude' => 0.8, 'blend' => 0.6, 'speed' => 0.6, 'mouseInteraction' => false]) }}"></div>
    <div class="absolute inset-0 -z-20 bg-[radial-gradient(ellipse_at_top,rgba(82,39,255,0.35),transparent_60%)]" aria-hidden="true"></div>
    <div class="container-page pt-28 pb-14 sm:pt-32 sm:pb-20">
        <x-breadcrumbs :items="$breadcrumbs" dark class="mb-6" />
        @if ($eyebrow)
            <p class="text-xs font-semibold tracking-[0.18em] text-brand-200 uppercase">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-3 max-w-3xl text-4xl font-bold tracking-tight text-balance sm:text-5xl">{{ $title }}</h1>
        @if ($lead)
            <p class="mt-5 max-w-2xl text-lg text-white/75">{{ $lead }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
