@extends('layouts.app')

@section('content')
    <x-page-hero title="Konsultasi & Permintaan Penawaran" eyebrow="Kontak" :breadcrumbs="$seo->breadcrumbs"
        lead="Ceritakan kebutuhan Anda. Kami bantu menentukan ruang lingkup, paket, dan estimasi biaya. Konsultasi tidak dipungut biaya." />

    <section class="section">
        <div class="container-page grid gap-12 lg:grid-cols-[1.4fr_1fr]">
            <div class="card">
                <h2 class="text-xl font-bold text-slate-900">Formulir permintaan penawaran</h2>
                <p class="mt-1 text-sm text-slate-500">Kolom bertanda * wajib diisi.</p>
                <x-lead-form :package="$package" :service-type="$serviceType" class="mt-8 space-y-5" />
            </div>

            <aside class="space-y-6">
                <div class="rounded-3xl bg-slate-50 p-6">
                    <h2 class="font-bold text-slate-900">Lebih suka chat langsung?</h2>
                    <p class="mt-2 text-sm text-slate-600">Tombol di bawah membuka WhatsApp dengan pesan awal yang bisa Anda ubah sebelum dikirim.</p>
                    <x-wa-button class="mt-4 w-full" :message="$package?->whatsappMessage()" track-label="Halaman kontak" />
                </div>
                <dl class="space-y-4 rounded-3xl bg-slate-50 p-6 text-sm">
                    @if (\App\Models\Setting::get('contact_email'))
                        <div><dt class="text-slate-500">Email</dt><dd><a href="mailto:{{ \App\Models\Setting::get('contact_email') }}" class="font-semibold text-brand-700">{{ \App\Models\Setting::get('contact_email') }}</a></dd></div>
                    @endif
                    @if (\App\Models\Setting::get('address'))
                        <div><dt class="text-slate-500">Alamat</dt><dd class="font-semibold text-slate-900">{{ \App\Models\Setting::get('address') }}</dd></div>
                    @endif
                    <div><dt class="text-slate-500">Jam kerja</dt><dd class="font-semibold text-slate-900">{{ \App\Models\Setting::get('business_hours') }}</dd></div>
                    <div><dt class="text-slate-500">Area layanan</dt><dd class="font-semibold text-slate-900">{{ \App\Models\Setting::get('service_area') }}</dd></div>
                </dl>
                <div class="rounded-3xl p-6 ring-1 ring-slate-200">
                    <h2 class="font-bold text-slate-900">Apa yang terjadi setelah ini?</h2>
                    <ol class="mt-3 list-decimal space-y-2 pl-5 text-sm text-slate-600">
                        <li>Kami membaca kebutuhan Anda dan menghubungi Anda pada jam kerja.</li>
                        <li>Kami diskusikan tujuan, fitur, dan anggaran.</li>
                        <li>Anda menerima penawaran tertulis berisi ruang lingkup, waktu, dan biaya.</li>
                    </ol>
                </div>
            </aside>
        </div>
    </section>
@endsection
