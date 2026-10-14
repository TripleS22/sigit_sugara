@extends('layouts.app')

@section('wa_message', $package->whatsappMessage())

@section('content')
    @unless ($package->is_active)
        <div class="fixed inset-x-0 top-16 z-30 bg-amber-400 py-2 text-center text-sm font-semibold text-amber-950">Pratinjau admin: paket ini nonaktif dan tidak tampil di website.</div>
    @endunless

    <x-page-hero :title="'Paket '.$package->name" :lead="$package->tagline" :breadcrumbs="$seo->breadcrumbs"
        :eyebrow="$package->segment === 'student' ? 'Paket pelajar & mahasiswa' : 'Paket website bisnis'">
        <p class="mt-6 text-3xl font-extrabold">{{ $package->priceLabel() }}</p>
    </x-page-hero>

    <section class="section">
        <div class="container-page grid gap-12 lg:grid-cols-[1fr_24rem]">
            <div>
                @if ($package->description)
                    <div class="prose-content">{{ \App\Support\Markdown::render($package->description) }}</div>
                @endif

                <dl class="mt-8 grid gap-4 sm:grid-cols-3">
                    @if ($package->target_audience)
                        <div class="rounded-3xl bg-slate-50 p-5"><dt class="text-sm text-slate-500">Cocok untuk</dt><dd class="mt-1 font-semibold text-slate-900">{{ ucfirst($package->target_audience) }}</dd></div>
                    @endif
                    @if ($package->max_pages)
                        <div class="rounded-3xl bg-slate-50 p-5"><dt class="text-sm text-slate-500">Jumlah halaman</dt><dd class="mt-1 font-semibold text-slate-900">Maksimal {{ $package->max_pages }}</dd></div>
                    @endif
                    @if ($package->delivery_estimate)
                        <div class="rounded-3xl bg-slate-50 p-5"><dt class="text-sm text-slate-500">Estimasi pengerjaan</dt><dd class="mt-1 font-semibold text-slate-900">{{ $package->delivery_estimate }}</dd></div>
                    @endif
                </dl>

                @php($included = $package->features->where('is_included', true))
                @php($excluded = $package->features->where('is_included', false))
                <div class="mt-10 grid gap-8 sm:grid-cols-2">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Termasuk dalam paket</h2>
                        <ul class="mt-4 space-y-3 text-sm text-slate-700" role="list">
                            @foreach ($included as $feature)
                                <li class="flex gap-2.5"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" /><span>{{ $feature->label }}@if ($feature->value)<span class="text-slate-500">: {{ $feature->value }}</span>@endif</span></li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($excluded->isNotEmpty() || $package->hosting_note)
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Tidak termasuk</h2>
                            <ul class="mt-4 space-y-3 text-sm text-slate-500" role="list">
                                @foreach ($excluded as $feature)
                                    <li class="flex gap-2.5"><x-icon name="x" class="mt-0.5 size-4 shrink-0" /><span>{{ $feature->label }}@if ($feature->value): {{ $feature->value }}@endif</span></li>
                                @endforeach
                                @if ($package->hosting_note)
                                    <li class="flex gap-2.5"><x-icon name="x" class="mt-0.5 size-4 shrink-0" /><span>{{ $package->hosting_note }}</span></li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="card">
                    <h2 class="text-lg font-bold text-slate-900">Minta penawaran paket {{ $package->name }}</h2>
                    <p class="mt-2 text-sm text-slate-600">Kirim kebutuhan Anda, kami balas dengan rincian ruang lingkup dan biaya.</p>
                    <x-lead-form :package="$package" :service-type="$package->segment === 'student' ? 'pelajar' : null" class="mt-6 space-y-4" />
                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <x-wa-button :message="$package->whatsappMessage()" label="Atau tanya via WhatsApp" :track-label="'Detail paket '.$package->name" class="w-full" />
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @if ($others->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading title="Bandingkan dengan paket lain" />
                <div class="mt-10 grid gap-6 md:grid-cols-2 {{ $others->count() >= 3 ? 'lg:grid-cols-3' : '' }}">
                    @foreach ($others as $other)
                        <a href="{{ $other->url() }}" class="card block transition hover:ring-brand-300" data-track="package_cta_click" data-track-label="Bandingkan: {{ $other->name }}">
                            <p class="font-bold text-slate-900">{{ $other->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $other->tagline }}</p>
                            <p class="mt-4 font-semibold text-brand-700">{{ $other->priceLabel() }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="container-page max-w-3xl">
                <x-section-heading title="Pertanyaan umum" />
                <x-faq-list :faqs="$faqs" class="mt-10" />
            </div>
        </section>
    @endif
@endsection
