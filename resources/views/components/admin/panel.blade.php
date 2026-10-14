@props(['title' => null, 'description' => null])
<section {{ $attributes->merge(['class' => 'rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200']) }}>
    @if ($title)
        <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
        @if ($description)
            <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
        @endif
        <div class="mt-5 space-y-5">{{ $slot }}</div>
    @else
        <div class="space-y-5">{{ $slot }}</div>
    @endif
</section>
