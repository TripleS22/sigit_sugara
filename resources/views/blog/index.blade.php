@extends('layouts.app')

@section('content')
    <x-page-hero :title="$currentCategory ? 'Artikel '.$currentCategory->name : 'Blog & Panduan Website'" eyebrow="Blog" :breadcrumbs="$seo->breadcrumbs"
        :lead="$currentCategory?->description ?: 'Panduan praktis untuk pemilik bisnis dan UMKM: biaya pembuatan website, domain dan hosting, company profile, hingga memilih jasa pembuatan website.'">
        <form action="{{ route('blog.index') }}" method="GET" role="search" class="mt-8 flex max-w-md gap-2">
            <label for="q" class="sr-only">Cari artikel</label>
            <input id="q" name="q" type="search" value="{{ $search }}" placeholder="Cari artikel…" class="form-input !bg-white/10 !text-white !ring-white/20 placeholder:!text-white/50">
            <button class="btn-ghost-light" type="submit"><x-icon name="search" class="size-4" /><span class="sr-only">Cari</span></button>
        </form>
    </x-page-hero>

    <section class="section">
        <div class="container-page">
            @if ($categories->isNotEmpty())
                <nav class="mb-10 flex flex-wrap gap-2" aria-label="Kategori artikel">
                    <a href="{{ route('blog.index') }}" class="rounded-full px-4 py-2 text-sm font-medium {{ $currentCategory ? 'bg-slate-100 text-slate-700 hover:bg-slate-200' : 'bg-brand-600 text-white' }}">Semua</a>
                    @foreach ($categories as $category)
                        <a href="{{ $category->url() }}" class="rounded-full px-4 py-2 text-sm font-medium {{ $currentCategory?->is($category) ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">{{ $category->name }} <span class="opacity-60">({{ $category->articles_count }})</span></a>
                    @endforeach
                </nav>
            @endif

            @if ($search !== '')
                <p class="mb-6 text-slate-600">{{ $articles->total() }} hasil untuk “{{ $search }}”.</p>
            @endif

            @if ($articles->isEmpty())
                <div class="mx-auto max-w-xl rounded-3xl bg-slate-50 p-10 text-center">
                    <h2 class="text-xl font-bold text-slate-900">Belum ada artikel di sini</h2>
                    <p class="mt-2 text-slate-600">Coba kata kunci lain atau lihat semua artikel.</p>
                    <a href="{{ route('blog.index') }}" class="btn-secondary mt-6">Semua artikel</a>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($articles as $article)
                        <x-article-card :article="$article" />
                    @endforeach
                </div>
                <div class="mt-12">{{ $articles->links() }}</div>
            @endif
        </div>
    </section>
@endsection
