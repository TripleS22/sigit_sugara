@extends('layouts.app')

@section('track_view', 'service_view')
@section('track_view_label', $service->title)
@section('wa_message', 'Halo, saya tertarik dengan layanan '.$service->title.'. Saya ingin berkonsultasi mengenai kebutuhan saya.')

@php($isStudent = $service->segment === 'student')

@section('content')
    @unless ($service->is_published)
        <div class="fixed inset-x-0 top-16 z-30 bg-amber-400 py-2 text-center text-sm font-semibold text-amber-950">Pratinjau admin: layanan ini belum dipublikasikan.</div>
    @endunless

    <x-page-hero :title="$service->headline ?: $service->title" :lead="$service->subheadline ?: $service->summary"
        :eyebrow="$service->headline ? $service->title : ($isStudent ? 'Layanan pelajar & mahasiswa' : 'Layanan')" :breadcrumbs="$seo->breadcrumbs">
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <x-wa-button :message="'Halo, saya tertarik dengan layanan '.$service->title.'. Saya ingin berkonsultasi mengenai kebutuhan saya.'"
                :label="$isStudent ? 'Konsultasi kebutuhan proyek' : 'Konsultasi Gratis'" :track-label="'Hero layanan: '.$service->title" />
            @if ($packages->isNotEmpty())
                <a href="#paket" class="btn-ghost-light" data-track="package_cta_click" data-track-label="Layanan {{ $service->title }}: lihat paket">Lihat paket & harga</a>
            @endif
        </div>
    </x-page-hero>

    @if ($service->highlights || $service->timeline)
        <section class="border-b border-slate-100 bg-white">
            <div class="container-page grid gap-6 py-10 lg:grid-cols-[2fr_1fr]">
                @if ($service->highlights)
                    <ul class="grid gap-3 sm:grid-cols-2" role="list">
                        @foreach ($service->highlights as $highlight)
                            <li class="flex gap-3 text-slate-700"><x-icon name="check" class="mt-0.5 size-5 shrink-0 text-brand-600" />{{ $highlight }}</li>
                        @endforeach
                    </ul>
                @endif
                @if ($service->timeline)
                    <div class="flex items-start gap-3 rounded-3xl bg-slate-50 p-5">
                        <x-icon name="clock" class="mt-0.5 size-5 text-brand-600" />
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Estimasi waktu pengerjaan</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $service->timeline }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if ($service->body)
        <section class="section">
            <div class="container-page grid gap-12 lg:grid-cols-[1fr_20rem]">
                <article class="prose-content">{{ \App\Support\Markdown::render($service->body) }}</article>
                <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                    @if ($service->image)
                        <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->image_alt }}" class="w-full rounded-3xl" loading="lazy" width="640" height="420">
                    @endif
                    @if ($children->isNotEmpty())
                        <nav class="rounded-3xl bg-slate-50 p-6" aria-label="Layanan terkait">
                            <p class="text-sm font-semibold text-slate-900">{{ $isStudent ? 'Pilihan layanan pelajar' : 'Jenis website' }}</p>
                            <ul class="mt-3 space-y-2 text-sm" role="list">
                                @foreach ($children as $child)
                                    <li><a href="{{ $child->url() }}" class="flex items-center justify-between gap-2 text-slate-700 hover:text-brand-700">{{ $child->label() }} <x-icon name="arrow-right" class="size-4" /></a></li>
                                @endforeach
                            </ul>
                        </nav>
                    @elseif ($service->parent)
                        <a href="{{ $service->parent->url() }}" class="block rounded-3xl bg-slate-50 p-6 text-sm text-slate-700 hover:text-brand-700">
                            <span class="block font-semibold text-slate-900">Layanan lainnya</span>
                            Lihat semua {{ mb_strtolower($service->parent->label()) }}
                        </a>
                    @endif
                    <div class="rounded-3xl bg-brand-600 p-6 text-white">
                        <p class="font-semibold">Belum yakin paket mana yang cocok?</p>
                        <p class="mt-2 text-sm text-white/80">Ceritakan kebutuhan Anda, kami sarankan ruang lingkup yang sesuai anggaran.</p>
                        <a href="{{ route('contact', ['layanan' => $isStudent ? 'pelajar' : null]) }}" class="btn mt-4 w-full bg-white text-brand-700 hover:bg-brand-50" data-track="cta_click" data-track-label="Sidebar layanan: formulir">Minta penawaran</a>
                    </div>
                </aside>
            </div>
        </section>
    @endif

    @if ($children->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading :title="$isStudent ? 'Pilih jenis bantuan proyek' : 'Jenis website yang kami kerjakan'" />
                <div class="mt-10 grid gap-6 sm:grid-cols-2 {{ $children->count() >= 4 ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }}">
                    @foreach ($children as $child)
                        <x-spotlight-card class="flex flex-col">
                            <div class="flex h-full flex-col">
                                <span class="grid size-11 place-items-center rounded-2xl bg-brand-600 text-white"><x-icon :name="$child->icon ?: 'briefcase'" /></span>
                                <h3 class="mt-5 text-lg font-bold text-slate-900"><a href="{{ $child->url() }}" class="hover:text-brand-700">{{ $child->label() }}</a></h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $child->summary }}</p>
                                <a href="{{ $child->url() }}" class="mt-auto inline-flex items-center gap-1 pt-5 text-sm font-semibold text-brand-700">Selengkapnya <x-icon name="arrow-right" class="size-4" /></a>
                            </div>
                        </x-spotlight-card>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($packages->isNotEmpty())
        <section class="section" id="paket">
            <div class="container-page">
                <x-section-heading eyebrow="Paket harga" :title="$isStudent ? 'Paket pelajar & mahasiswa' : 'Paket website untuk bisnis'"
                    :lead="$isStudent ? 'Domain dan hosting ditagihkan terpisah. Jumlah modul dan revisi dibatasi sesuai paket; pekerjaan tambahan memakai penawaran baru.' : 'Semua paket sudah termasuk CMS. Harga adalah harga awal dan disesuaikan setelah konsultasi kebutuhan.'" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 {{ $packages->count() >= 4 ? 'xl:grid-cols-4' : 'lg:grid-cols-3' }}">
                    @foreach ($packages as $package)
                        <x-package-card :package="$package" compact />
                    @endforeach
                </div>
                @if ($isStudent)
                    <p class="mx-auto mt-8 max-w-3xl rounded-2xl bg-amber-50 p-4 text-center text-sm text-amber-900">
                        Layanan ini berupa bantuan implementasi, prototipe, debugging, dan dokumentasi teknis. Kami tidak mengerjakan ujian atau mengambil alih tanggung jawab akademik Anda, dan tidak menjanjikan nilai atau kelulusan.
                    </p>
                @endif
            </div>
        </section>
    @endif

    @if (! $isStudent && $portfolios->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading eyebrow="Portofolio" title="Contoh hasil pengerjaan" />
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        <x-portfolio-card :portfolio="$portfolio" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="container-page max-w-3xl">
                <x-section-heading eyebrow="FAQ" :title="'Pertanyaan seputar '.mb_strtolower($service->label())" />
                <x-faq-list :faqs="$faqs" class="mt-10" />
            </div>
        </section>
    @endif

    @if ($articles->isNotEmpty() && ! $isStudent)
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading eyebrow="Bacaan terkait" title="Panduan sebelum memulai" />
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band :message="'Halo, saya tertarik dengan layanan '.$service->title.'. Saya ingin berkonsultasi mengenai kebutuhan saya.'"
        :title="$isStudent ? 'Diskusikan kebutuhan teknis proyek Anda' : 'Siap membahas website '.mb_strtolower($service->label()).'?'"
        :track-label="'Layanan '.$service->title.': CTA bawah'" />
@endsection
