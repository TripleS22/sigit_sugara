@extends('layouts.app')

@section('content')
    <x-page-hero title="Pertanyaan yang Sering Diajukan" eyebrow="FAQ" :breadcrumbs="$seo->breadcrumbs"
        lead="Harga, domain dan hosting, maintenance, kepemilikan source code, revisi, dan layanan untuk pelajar." />

    <section class="section">
        <div class="container-page max-w-3xl space-y-14">
            @forelse ($groups as $group => $faqs)
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ \App\Models\Faq::GROUPS[$group] ?? ucfirst($group) }}</h2>
                    <x-faq-list :faqs="$faqs" class="mt-6" />
                </div>
            @empty
                <p class="text-slate-600">Belum ada FAQ.</p>
            @endforelse
        </div>
    </section>

    <x-cta-band title="Pertanyaan Anda belum terjawab?" lead="Tanyakan langsung, kami jawab sesuai kondisi proyek Anda." track-label="FAQ: CTA bawah" />
@endsection
