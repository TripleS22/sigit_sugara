@props(['portfolio'])
<article class="group flex flex-col overflow-hidden rounded-3xl bg-white ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg">
    <a href="{{ $portfolio->url() }}" class="block aspect-[16/10] overflow-hidden bg-gradient-to-br from-brand-100 to-slate-100" tabindex="-1" aria-hidden="true">
        @if ($portfolio->image)
            <img src="{{ asset('storage/'.$portfolio->image) }}" alt="" loading="lazy" decoding="async" width="640" height="400"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-brand-400"><x-icon name="code" class="size-12" /></div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-6">
        @if ($portfolio->project_type)
            <p class="eyebrow">{{ $portfolio->project_type }}</p>
        @endif
        <h3 class="mt-2 text-lg font-bold text-slate-900">
            <a href="{{ $portfolio->url() }}" class="hover:text-brand-700">{{ $portfolio->title }}</a>
        </h3>
        @if ($portfolio->summary)
            <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $portfolio->summary }}</p>
        @endif
        @if ($portfolio->technologies)
            <ul class="mt-auto flex flex-wrap gap-1.5 pt-4" role="list">
                @foreach (array_slice($portfolio->technologies, 0, 4) as $tech)
                    <li class="rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-600">{{ $tech }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</article>
