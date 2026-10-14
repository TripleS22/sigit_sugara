@extends('layouts.app')

@section('content')
    <x-page-hero title="Portofolio Proyek" eyebrow="Bukti hasil kerja" :breadcrumbs="$seo->breadcrumbs"
        lead="Studi kasus proyek website dan aplikasi: tujuan, teknologi, fitur utama, dan hasil yang dapat ditunjukkan." />

    <section class="section">
        <div class="container-page">
            @if ($types->count() > 1)
                <nav class="mb-10 flex flex-wrap gap-2" aria-label="Filter jenis proyek">
                    <a href="{{ route('portfolio.index') }}" class="rounded-full px-4 py-2 text-sm font-medium {{ $type ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-brand-600 text-white' }}">Semua</a>
                    @foreach ($types as $item)
                        <a href="{{ route('portfolio.index', ['jenis' => $item]) }}" class="rounded-full px-4 py-2 text-sm font-medium {{ $type === $item ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">{{ $item }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($portfolios->isEmpty())
                <div class="mx-auto max-w-xl rounded-3xl bg-slate-50 p-10 text-center">
                    <x-icon name="code" class="mx-auto size-10 text-brand-500" />
                    <h2 class="mt-4 text-xl font-bold text-slate-900">Studi kasus sedang kami siapkan</h2>
                    <p class="mt-2 text-slate-600">Kami hanya menampilkan proyek yang benar-benar dikerjakan dan sudah mendapat izin klien. Minta contoh hasil kerja yang relevan dengan kebutuhan Anda melalui konsultasi.</p>
                    <x-wa-button class="mt-6" label="Minta contoh hasil kerja" track-label="Portofolio kosong" message="Halo, saya ingin melihat contoh hasil kerja yang mirip dengan kebutuhan saya." />
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($portfolios as $portfolio)
                        <x-portfolio-card :portfolio="$portfolio" />
                    @endforeach
                </div>
                <div class="mt-12">{{ $portfolios->links() }}</div>
            @endif
        </div>
    </section>

    <x-cta-band track-label="Portofolio: CTA bawah" />
@endsection
