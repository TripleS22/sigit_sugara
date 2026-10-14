@extends('layouts.app')

@section('content')
    <x-page-hero title="Paket & Harga Pembuatan Website" eyebrow="Harga transparan" :breadcrumbs="$seo->breadcrumbs"
        lead="Semua paket bisnis sudah termasuk CMS. Pembeda utamanya adalah jumlah halaman, kelengkapan fitur, integrasi, dan tingkat kustomisasi. Harga di bawah adalah harga awal; spesifikasi final ditentukan setelah konsultasi." />

    @if ($business->isNotEmpty())
        <section class="section" id="bisnis">
            <div class="container-page">
                <x-section-heading eyebrow="Bisnis & UMKM" title="Paket website bisnis" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($business as $package)
                        <x-package-card :package="$package" compact />
                    @endforeach
                </div>
            </div>
        </section>

        @if ($comparison)
            <section class="section bg-slate-50" aria-labelledby="perbandingan">
                <div class="container-page">
                    <x-section-heading id="perbandingan" title="Perbandingan fitur paket bisnis" lead="Geser tabel ke samping pada layar kecil." />
                    <div class="mt-10 overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
                        <table class="w-full min-w-[44rem] text-left text-sm">
                            <caption class="sr-only">Perbandingan fitur paket website bisnis</caption>
                            <thead class="bg-slate-50 text-slate-900">
                                <tr>
                                    <th scope="col" class="px-5 py-4 font-semibold">Fitur</th>
                                    @foreach ($business as $package)
                                        <th scope="col" class="px-5 py-4 font-semibold {{ $package->is_featured ? 'text-brand-700' : '' }}">{{ $package->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <th scope="row" class="px-5 py-3 font-medium text-slate-700">Harga awal</th>
                                    @foreach ($business as $package)
                                        <td class="px-5 py-3 font-semibold text-slate-900">{{ $package->priceLabel() }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th scope="row" class="px-5 py-3 font-medium text-slate-700">Maks. halaman</th>
                                    @foreach ($business as $package)
                                        <td class="px-5 py-3">{{ $package->max_pages ?: '—' }}</td>
                                    @endforeach
                                </tr>
                                @foreach ($comparison as $label => $values)
                                    <tr>
                                        <th scope="row" class="px-5 py-3 font-medium text-slate-700">{{ $label }}</th>
                                        @foreach ($business as $package)
                                            @php($cell = $values[$package->id] ?? null)
                                            <td class="px-5 py-3">
                                                @if (! $cell)
                                                    <span class="text-slate-400">—</span>
                                                @elseif ($cell['value'])
                                                    <span class="{{ $cell['included'] ? 'text-slate-800' : 'text-slate-400' }}">{{ $cell['value'] }}</span>
                                                @elseif ($cell['included'])
                                                    <x-icon name="check" class="size-5 text-brand-600" /><span class="sr-only">Ya</span>
                                                @else
                                                    <x-icon name="x" class="size-5 text-slate-300" /><span class="sr-only">Tidak</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
    @endif

    @if ($student->isNotEmpty())
        <section class="section" id="pelajar">
            <div class="container-page">
                <x-section-heading eyebrow="Pelajar & mahasiswa" title="Paket proyek web pelajar"
                    lead="Untuk prototipe, praktikum, dan demonstrasi teknis. Domain dan hosting ditagihkan terpisah; jumlah modul dan revisi dibatasi." />
                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ($student as $package)
                        <x-package-card :package="$package" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section bg-slate-50">
        <div class="container-page grid gap-10 lg:grid-cols-2">
            <div class="card">
                <h2 class="text-xl font-bold text-slate-900">Yang perlu Anda ketahui tentang biaya</h2>
                <ul class="mt-5 space-y-3 text-sm text-slate-600" role="list">
                    <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" />Harga paket adalah harga awal untuk ruang lingkup standar. Penawaran final tertulis setelah konsultasi.</li>
                    <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" />Domain dan hosting adalah biaya tahunan yang dibayarkan terpisah, kecuali disebutkan lain di penawaran.</li>
                    <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" />Maintenance dan pengembangan lanjutan bersifat opsional dan dapat dipilih setelah website online.</li>
                    <li class="flex gap-3"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" />Fitur di luar paket, seperti multi-bahasa atau integrasi khusus, dihitung sebagai add-on.</li>
                </ul>
            </div>
            @if ($faqs->isNotEmpty())
                <x-faq-list :faqs="$faqs" />
            @endif
        </div>
    </section>

    <x-cta-band title="Butuh bantuan memilih paket?" track-label="Paket: CTA bawah" />
@endsection
