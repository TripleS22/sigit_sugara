@props(['eyebrow' => null, 'title', 'lead' => null, 'center' => true, 'as' => 'h2'])
<div {{ $attributes->merge(['class' => ($center ? 'mx-auto text-center ' : '').'max-w-2xl']) }}>
    @if ($eyebrow)
        <p class="eyebrow">{{ $eyebrow }}</p>
    @endif
    <{{ $as }} class="section-title mt-3">{{ $title }}</{{ $as }}>
    @if ($lead)
        <p class="mt-4 text-lg text-slate-600">{{ $lead }}</p>
    @endif
</div>
