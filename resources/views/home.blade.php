@extends('layouts.app')

@php
    use App\Models\Package;
    use App\Models\Setting;
    $rotating = Setting::list('hero_rotating_words', "\n");
    $valueProps = Setting::lines('home_value_props');
    $steps = Setting::lines('process_steps');
    $tech = Setting::list('tech_stack');
    $lowestPrice = $packages->min('price_from');
@endphp

@section('content')
    {{-- Hero --}}
    <section class="relative isolate overflow-hidden bg-ink text-white">
        <div class="absolute inset-0 -z-10" aria-hidden="true" data-island="Aurora"
            data-props="{{ json_encode(['colorStops' => ['#5227FF', '#7c5cff', '#1fa8c9'], 'amplitude' => 1.1, 'blend' => 0.55, 'speed' => 0.8]) }}"></div>
        <div class="absolute inset-0 -z-20 bg-[radial-gradient(ellipse_at_top,rgba(82,39,255,0.45),transparent_65%)]" aria-hidden="true"></div>

        <div class="container-page pt-32 pb-20 sm:pt-40 sm:pb-28">
            <p class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-sm ring-1 ring-white/20 backdrop-blur">
                <x-icon name="sparkles" class="size-4 text-brand-200" />
                <span data-island="ShinyText" data-props="{{ json_encode(['text' => 'Setiap paket sudah termasuk CMS', 'color' => '#c9c2ff', 'shineColor' => '#ffffff', 'speed' => 3]) }}">Setiap paket sudah termasuk CMS</span>
            </p>

            <h1 class="mt-6 max-w-4xl text-4xl font-extrabold tracking-tight text-balance sm:text-6xl">
                <span data-island="BlurText" data-props="{{ json_encode(['text' => Setting::get('hero_headline'), 'delay' => 90, 'animateBy' => 'words', 'direction' => 'top']) }}">{{ Setting::get('hero_headline') }}</span>
            </h1>

            <p class="mt-6 max-w-2xl text-lg text-white/75 sm:text-xl">{{ Setting::get('hero_subheadline') }}</p>

            @if (count($rotating) > 1)
                <p class="mt-6 flex flex-wrap items-center gap-2 text-base text-white/80">
                    <span>Kami membangun</span>
                    <span class="inline-flex overflow-hidden rounded-xl bg-brand-600 px-3 py-1 font-semibold text-white">
                        <span data-island="RotatingText" data-props="{{ json_encode(['texts' => $rotating, 'rotationInterval' => 2400, 'staggerDuration' => 0.02, 'staggerFrom' => 'last', 'splitLevelClassName' => 'overflow-hidden', 'mainClassName' => 'overflow-hidden']) }}">{{ $rotating[0] }}</span>
                    </span>
                </p>
            @endif

            <div class="mt-10 flex flex-col gap-3 sm:flex-row">
                <x-wa-button label="Konsultasi Gratis" track-label="Hero" class="!px-7 !py-3.5 text-base" />
                <a href="{{ route('packages.index') }}" class="btn-ghost-light !px-7 !py-3.5 text-base" data-track="package_cta_click" data-track-label="Hero: lihat paket">
                    Lihat Paket Harga <x-icon name="arrow-right" class="size-4" />
                </a>
            </div>

            @if ($lowestPrice)
                <dl class="mt-14 grid max-w-2xl grid-cols-2 gap-x-4 gap-y-6 border-t border-white/10 pt-8 text-sm sm:grid-cols-3">
                    <div class="col-span-2 sm:col-span-1">
                        <dt class="text-white/60">Mulai dari</dt>
                        <dd class="mt-1 text-2xl font-bold sm:text-3xl">{{ Package::rupiah($lowestPrice) }}</dd>
                    </div>
                    <div>
                        <dt class="text-white/60">Paket bisnis</dt>
                        <dd class="mt-1 text-2xl font-bold sm:text-3xl"><span data-island="CountUp" data-props="{{ json_encode(['to' => $packages->count(), 'duration' => 1.5]) }}">{{ $packages->count() }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-white/60">CMS di paket</dt>
                        <dd class="mt-1 text-2xl font-bold sm:text-3xl"><span data-island="CountUp" data-props="{{ json_encode(['to' => 100, 'duration' => 1.8]) }}">100</span>%</dd>
                    </div>
                </dl>
            @endif
        </div>
    </section>

    @if ($tech)
        <section class="border-b border-slate-100 py-8" aria-label="Teknologi">
            <div class="container-page">
                <p class="text-center text-xs font-semibold tracking-[0.18em] text-slate-400 uppercase">Dibangun dengan teknologi yang teruji</p>
                <div class="mt-5" data-island="LogoLoop" data-props="{{ json_encode(['items' => $tech]) }}">
                    <ul class="flex flex-wrap justify-center gap-x-8 gap-y-2 text-lg font-semibold text-slate-500" role="list">
                        @foreach ($tech as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif

    {{-- Value proposition --}}
    @if ($valueProps)
        <section class="section">
            <div class="container-page">
                <x-section-heading eyebrow="Mengapa kami" title="Website yang jelas biayanya, mudah dikelola, dan siap ditemukan"
                    lead="Kami fokus pada hal yang membuat website benar-benar dipakai: informasi yang mudah dipahami, kecepatan, dan tombol konsultasi yang jelas." />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($valueProps as $prop)
                        <div class="card">
                            <span class="grid size-11 place-items-center rounded-2xl bg-brand-50 text-brand-700"><x-icon :name="['check', 'sparkles', 'search', 'shield'][$loop->index % 4]" /></span>
                            <h3 class="mt-5 font-bold text-slate-900">{{ $prop['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $prop['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Services --}}
    @if ($services->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading eyebrow="Layanan" title="Pilih layanan sesuai kebutuhan bisnis Anda" />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <x-spotlight-card class="flex flex-col">
                            <div class="flex h-full flex-col">
                                <span class="grid size-11 place-items-center rounded-2xl bg-brand-600 text-white"><x-icon :name="$service->icon ?: 'briefcase'" /></span>
                                <h3 class="mt-5 text-lg font-bold text-slate-900"><a href="{{ $service->url() }}" class="hover:text-brand-700">{{ $service->label() }}</a></h3>
                                <p class="mt-2 text-sm text-slate-600">{{ $service->summary }}</p>
                                <a href="{{ $service->url() }}" class="mt-auto inline-flex items-center gap-1 pt-5 text-sm font-semibold text-brand-700">
                                    Pelajari layanan <x-icon name="arrow-right" class="size-4" /><span class="sr-only">{{ $service->label() }}</span>
                                </a>
                            </div>
                        </x-spotlight-card>
                    @endforeach
                </div>

                @if ($studentService)
                    <a href="{{ $studentService->url() }}" class="mt-8 flex flex-col gap-3 rounded-3xl bg-white p-6 ring-1 ring-slate-200 transition hover:ring-brand-300 sm:flex-row sm:items-center sm:justify-between">
                        <span class="flex items-center gap-4">
                            <span class="grid size-11 shrink-0 place-items-center rounded-2xl bg-brand-50 text-brand-700"><x-icon name="academic" /></span>
                            <span>
                                <span class="block font-semibold text-slate-900">Pelajar atau mahasiswa?</span>
                                <span class="block text-sm text-slate-600">Bantuan implementasi proyek web dan prototipe Laravel{{ $studentPriceFrom ? ", mulai ".Package::rupiah($studentPriceFrom) : "" }}.</span>
                            </span>
                        </span>
                        <span class="inline-flex items-center gap-1 text-sm font-semibold text-brand-700">Lihat layanan pelajar <x-icon name="arrow-right" class="size-4" /></span>
                    </a>
                @endif
            </div>
        </section>
    @endif

    {{-- Packages --}}
    @if ($packages->isNotEmpty())
        <section class="section" id="paket">
            <div class="container-page">
                <x-section-heading eyebrow="Paket harga" title="Harga awal yang transparan"
                    lead="Semua paket bisnis sudah termasuk CMS. Pembedanya adalah jumlah halaman, kelengkapan fitur, integrasi, dan tingkat kustomisasi." />
                <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($packages as $package)
                        <x-package-card :package="$package" compact />
                    @endforeach
                </div>
                <p class="mt-8 text-center text-sm text-slate-500">
                    Harga adalah harga awal; spesifikasi final ditentukan setelah konsultasi.
                    <a href="{{ route('packages.index') }}" class="font-semibold text-brand-700 underline">Bandingkan semua fitur paket</a>
                </p>
            </div>
        </section>
    @endif

    {{-- Portfolio: only shown once real projects are published --}}
    @if ($portfolios->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <x-section-heading eyebrow="Portofolio" title="Hasil pengerjaan kami" :center="false" />
                    <a href="{{ route('portfolio.index') }}" class="btn-secondary">Semua portofolio</a>
                </div>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        <x-portfolio-card :portfolio="$portfolio" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Process --}}
    @if ($steps)
        <section class="section">
            <div class="container-page">
                <x-section-heading eyebrow="Proses pengerjaan" title="Dari konsultasi sampai website online" />
                <ol class="mt-12 grid gap-6 {{ [1 => 'md:grid-cols-1', 2 => 'md:grid-cols-2', 3 => 'md:grid-cols-3', 4 => 'md:grid-cols-2 lg:grid-cols-4', 5 => 'md:grid-cols-3 lg:grid-cols-5'][min(count($steps), 5)] }}" role="list">
                    @foreach ($steps as $step)
                        <li class="relative rounded-3xl bg-white p-6 ring-1 ring-slate-200">
                            <span class="text-sm font-bold text-brand-600">Langkah {{ $loop->iteration }}</span>
                            <h3 class="mt-2 font-bold text-slate-900">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $step['text'] }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- Testimonials: only real, published client quotes --}}
    @if ($testimonials->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <x-section-heading eyebrow="Testimoni" title="Kata klien kami" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="card">
                            <blockquote class="text-slate-700">“{{ $testimonial->quote }}”</blockquote>
                            <figcaption class="mt-5 text-sm">
                                <span class="font-semibold text-slate-900">{{ $testimonial->name }}</span>
                                @if ($testimonial->role || $testimonial->company)
                                    <span class="block text-slate-500">{{ collect([$testimonial->role, $testimonial->company])->filter()->join(', ') }}</span>
                                @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($faqs->isNotEmpty())
        <section class="section">
            <div class="container-page grid gap-10 lg:grid-cols-[1fr_1.5fr]">
                <div>
                    <x-section-heading eyebrow="FAQ" title="Pertanyaan yang sering diajukan" :center="false" />
                    <a href="{{ route('faq') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand-700">Lihat semua FAQ <x-icon name="arrow-right" class="size-4" /></a>
                </div>
                <x-faq-list :faqs="$faqs" />
            </div>
        </section>
    @endif

    @if ($articles->isNotEmpty())
        <section class="section bg-slate-50">
            <div class="container-page">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <x-section-heading eyebrow="Blog" title="Panduan sebelum membuat website" :center="false" />
                    <a href="{{ route('blog.index') }}" class="btn-secondary">Semua artikel</a>
                </div>
                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band track-label="Beranda: CTA bawah" />
@endsection
