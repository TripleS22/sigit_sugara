@extends('layouts.app')

@section('content')
    @unless ($page->is_published)
        <div class="fixed inset-x-0 top-16 z-30 bg-amber-400 py-2 text-center text-sm font-semibold text-amber-950">Pratinjau admin: halaman ini belum dipublikasikan.</div>
    @endunless

    <x-page-hero :title="$page->title" :lead="$page->excerpt" :breadcrumbs="$seo->breadcrumbs" />

    <section class="section">
        <div class="container-page {{ $page->template === 'legal' ? 'max-w-3xl' : 'grid gap-12 lg:grid-cols-[1fr_20rem]' }}">
            <article class="prose-content">
                @if ($page->hero_image)
                    <img src="{{ asset('storage/'.$page->hero_image) }}" alt="{{ $page->hero_image_alt }}" class="mb-8 w-full rounded-3xl" width="1200" height="630">
                @endif
                {{ \App\Support\Markdown::render($page->body) }}
                @if ($page->template === 'legal')
                    <p class="text-sm text-slate-500">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('j F Y') }}</p>
                @endif
            </article>

            @if ($page->template !== 'legal')
                <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <p class="font-semibold text-slate-900">Ingin berdiskusi?</p>
                        <p class="mt-2 text-sm text-slate-600">Konsultasi kebutuhan website tidak dipungut biaya.</p>
                        <x-wa-button class="mt-4 w-full" track-label="Halaman {{ $page->title }}" />
                    </div>
                </aside>
            @endif
        </div>
    </section>

    @if ($page->template === 'about')
        @php($steps = \App\Models\Setting::lines('process_steps'))
        @if ($steps)
            <section class="section bg-slate-50">
                <div class="container-page">
                    <x-section-heading eyebrow="Cara kami bekerja" title="Proses yang jelas di setiap proyek" />
                    <ol class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3" role="list">
                        @foreach ($steps as $step)
                            <li class="rounded-3xl bg-white p-6 ring-1 ring-slate-200">
                                <span class="text-sm font-bold text-brand-600">Langkah {{ $loop->iteration }}</span>
                                <h3 class="mt-2 font-bold text-slate-900">{{ $step['title'] }}</h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $step['text'] }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </section>
        @endif
        <x-cta-band track-label="Tentang kami: CTA" />
    @endif
@endsection
