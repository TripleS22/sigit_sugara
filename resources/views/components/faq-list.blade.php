@props(['faqs'])
<div {{ $attributes->merge(['class' => 'divide-y divide-slate-200 rounded-3xl bg-white ring-1 ring-slate-200']) }}>
    @foreach ($faqs as $faq)
        <details class="group p-6 [&_summary::-webkit-details-marker]:hidden" @if ($loop->first) open @endif>
            <summary class="flex cursor-pointer items-start justify-between gap-4 font-semibold text-slate-900">
                <span>{{ $faq->question }}</span>
                <x-icon name="chevron-down" class="mt-0.5 size-5 shrink-0 text-slate-400 transition group-open:rotate-180" />
            </summary>
            <div class="mt-3 text-slate-600 leading-relaxed">{!! nl2br(e($faq->answer)) !!}</div>
        </details>
    @endforeach
</div>
