@extends('layouts.app')

@section('content')
    @unless ($article->isPublic())
        <div class="fixed inset-x-0 top-16 z-30 bg-amber-400 py-2 text-center text-sm font-semibold text-amber-950">Pratinjau admin: artikel ini belum terbit.</div>
    @endunless

    <x-page-hero :title="$article->title" :lead="$article->excerpt" :eyebrow="$article->category?->name" :breadcrumbs="$seo->breadcrumbs">
        <p class="mt-6 flex flex-wrap items-center gap-2 text-sm text-white/70">
            @if ($article->author)
                <span>Oleh {{ $article->author->name }}</span><span aria-hidden="true">·</span>
            @endif
            @if ($article->published_at)
                <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->translatedFormat('j F Y') }}</time><span aria-hidden="true">·</span>
            @endif
            <span>{{ $article->readingMinutes() }} menit baca</span>
        </p>
    </x-page-hero>

    <section class="section">
        <div class="container-page grid gap-12 lg:grid-cols-[1fr_20rem]">
            <article class="min-w-0">
                @if ($article->featured_image)
                    <img src="{{ asset('storage/'.$article->featured_image) }}" alt="{{ $article->featured_image_alt ?: $article->title }}" class="mb-10 w-full rounded-3xl" width="1200" height="675">
                @endif
                <div class="prose-content">{{ \App\Support\Markdown::render($article->body) }}</div>

                @if ($article->tags)
                    <ul class="mt-10 flex flex-wrap gap-2" role="list" aria-label="Tag">
                        @foreach ($article->tags as $tag)
                            <li class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">#{{ $tag }}</li>
                        @endforeach
                    </ul>
                @endif
            </article>

            <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-3xl bg-brand-600 p-6 text-white">
                    <p class="font-semibold">Butuh website untuk bisnis Anda?</p>
                    <p class="mt-2 text-sm text-white/80">Lihat paket dengan harga awal transparan atau konsultasikan kebutuhan Anda.</p>
                    <a href="{{ route('packages.index') }}" class="btn mt-4 w-full bg-white text-brand-700 hover:bg-brand-50" data-track="package_cta_click" data-track-label="Artikel: lihat paket">Lihat paket harga</a>
                    <x-wa-button class="mt-2 w-full" :message="'Halo, saya membaca artikel '.$article->title.' dan ingin berkonsultasi.'" :track-label="'Artikel '.$article->title" />
                </div>
            </aside>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading title="Artikel terkait" />
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($related as $item)
                        <x-article-card :article="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
