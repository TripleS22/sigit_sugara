@extends('layouts.app')

@php($seo = \App\Support\Seo::make('Halaman tidak ditemukan')->noindex())

@section('content')
    <x-page-hero title="Halaman tidak ditemukan" eyebrow="Error 404"
        lead="Halaman yang Anda cari mungkin sudah dipindahkan, diganti namanya, atau tidak pernah ada. Berikut beberapa halaman yang mungkin Anda cari." />

    <section class="section">
        <div class="container-page grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['Beranda', route('home'), 'Kembali ke halaman utama.'],
                ['Paket Harga', route('packages.index'), 'Bandingkan paket dan harga awal.'],
                ['Blog', route('blog.index'), 'Panduan biaya dan proses pembuatan website.'],
                ['Kontak', route('contact'), 'Tanyakan langsung kebutuhan Anda.'],
            ] as [$label, $href, $text])
                <a href="{{ $href }}" class="card block transition hover:ring-brand-300">
                    <span class="font-bold text-slate-900">{{ $label }}</span>
                    <span class="mt-2 block text-sm text-slate-600">{{ $text }}</span>
                </a>
            @endforeach
        </div>
        <form action="{{ route('blog.index') }}" method="GET" role="search" class="container-page mt-10 flex max-w-md gap-2">
            <label for="q404" class="sr-only">Cari artikel</label>
            <input id="q404" name="q" type="search" placeholder="Cari artikel…" class="form-input">
            <button class="btn-primary" type="submit">Cari</button>
        </form>
    </section>
@endsection
