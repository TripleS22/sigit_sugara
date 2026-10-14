@extends('layouts.app')

@section('wa_message', 'Halo, saya melihat portofolio '.$portfolio->title.' dan ingin membuat website serupa.')

@section('content')
    @unless ($portfolio->is_published)
        <div class="fixed inset-x-0 top-16 z-30 bg-amber-400 py-2 text-center text-sm font-semibold text-amber-950">Pratinjau admin: portofolio ini belum dipublikasikan.</div>
    @endunless

    <x-page-hero :title="$portfolio->title" :lead="$portfolio->summary" :eyebrow="$portfolio->project_type" :breadcrumbs="$seo->breadcrumbs">
        @if ($portfolio->demo_url)
            <a href="{{ $portfolio->demo_url }}" target="_blank" rel="noopener nofollow" class="btn-ghost-light mt-8">Lihat demo <x-icon name="external" class="size-4" /></a>
        @endif
    </x-page-hero>

    <section class="section">
        <div class="container-page grid gap-12 lg:grid-cols-[1fr_20rem]">
            <article>
                @if ($portfolio->image)
                    <img src="{{ asset('storage/'.$portfolio->image) }}" alt="{{ $portfolio->image_alt ?: $portfolio->title }}" class="mb-10 w-full rounded-3xl ring-1 ring-slate-200" width="1200" height="750">
                @endif
                <div class="prose-content">{{ \App\Support\Markdown::render($portfolio->description) }}</div>

                @if ($portfolio->features)
                    <h2 class="mt-12 text-2xl font-bold text-slate-900">Fitur utama</h2>
                    <ul class="mt-5 grid gap-3 sm:grid-cols-2" role="list">
                        @foreach ($portfolio->features as $feature)
                            <li class="flex gap-3 text-slate-700"><x-icon name="check" class="mt-0.5 size-5 shrink-0 text-brand-600" />{{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($portfolio->results)
                    <h2 class="mt-12 text-2xl font-bold text-slate-900">Hasil proyek</h2>
                    <div class="prose-content mt-4">{{ \App\Support\Markdown::render($portfolio->results) }}</div>
                @endif

                @foreach ($portfolio->testimonials as $testimonial)
                    <figure class="mt-12 rounded-3xl bg-brand-50 p-8">
                        <blockquote class="text-lg text-slate-800">“{{ $testimonial->quote }}”</blockquote>
                        <figcaption class="mt-4 text-sm text-slate-600"><strong>{{ $testimonial->name }}</strong>{{ $testimonial->role ? ', '.$testimonial->role : '' }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</figcaption>
                    </figure>
                @endforeach
            </article>

            <aside class="space-y-4 lg:sticky lg:top-24 lg:self-start">
                <dl class="space-y-4 rounded-3xl bg-slate-50 p-6 text-sm">
                    @if ($portfolio->client_name)
                        <div><dt class="text-slate-500">Klien</dt><dd class="font-semibold text-slate-900">{{ $portfolio->client_name }}</dd></div>
                    @endif
                    @if ($portfolio->project_type)
                        <div><dt class="text-slate-500">Jenis proyek</dt><dd class="font-semibold text-slate-900">{{ $portfolio->project_type }}</dd></div>
                    @endif
                    @if ($portfolio->completed_at)
                        <div><dt class="text-slate-500">Selesai</dt><dd class="font-semibold text-slate-900">{{ $portfolio->completed_at->translatedFormat('F Y') }}</dd></div>
                    @endif
                    @if ($portfolio->technologies)
                        <div>
                            <dt class="text-slate-500">Teknologi</dt>
                            <dd class="mt-2 flex flex-wrap gap-1.5">
                                @foreach ($portfolio->technologies as $tech)
                                    <span class="rounded-full bg-white px-2.5 py-1 text-xs text-slate-700 ring-1 ring-slate-200">{{ $tech }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                </dl>
                <div class="rounded-3xl bg-brand-600 p-6 text-white">
                    <p class="font-semibold">Ingin website seperti ini?</p>
                    <x-wa-button class="mt-4 w-full" :message="'Halo, saya melihat portofolio '.$portfolio->title.' dan ingin membuat website serupa.'" label="Diskusikan proyek Anda" :track-label="'Portofolio '.$portfolio->title" />
                </div>
            </aside>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading title="Proyek lainnya" />
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $item)
                        <x-portfolio-card :portfolio="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
