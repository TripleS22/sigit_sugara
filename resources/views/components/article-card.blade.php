@props(['article'])
<article class="group flex flex-col overflow-hidden rounded-3xl bg-white ring-1 ring-slate-200 transition hover:shadow-lg">
    @if ($article->featured_image)
        <a href="{{ $article->url() }}" class="block aspect-[16/9] overflow-hidden" tabindex="-1" aria-hidden="true">
            <img src="{{ asset('storage/'.$article->featured_image) }}" alt="" loading="lazy" decoding="async" width="640" height="360"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        </a>
    @endif
    <div class="flex flex-1 flex-col p-6">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            @if ($article->relationLoaded('category') && $article->category)
                <span class="font-semibold text-brand-700">{{ $article->category->name }}</span>
                <span aria-hidden="true">·</span>
            @endif
            <time datetime="{{ $article->published_at?->toDateString() }}">{{ $article->published_at?->translatedFormat('j F Y') }}</time>
        </div>
        <h3 class="mt-3 text-lg font-bold text-slate-900">
            <a href="{{ $article->url() }}" class="hover:text-brand-700">{{ $article->title }}</a>
        </h3>
        @if ($article->excerpt)
            <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $article->excerpt }}</p>
        @endif
        <a href="{{ $article->url() }}" class="mt-auto inline-flex items-center gap-1 pt-4 text-sm font-semibold text-brand-700">
            Baca artikel <x-icon name="arrow-right" class="size-4" /><span class="sr-only">: {{ $article->title }}</span>
        </a>
    </div>
</article>
