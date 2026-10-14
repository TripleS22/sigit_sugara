@extends('layouts.app')

@section('content')
    <x-page-hero title="Terima kasih{{ $name ? ', '.$name : '' }}!"
        lead="Permintaan konsultasi Anda sudah kami terima. Kami akan menghubungi Anda melalui WhatsApp atau email pada jam kerja." />

    <section class="section">
        <div class="container-page max-w-2xl text-center">
            <p class="text-slate-600">Ingin mempercepat? Anda juga dapat membuka WhatsApp dan mengirim pesan kepada kami. Pesan baru terkirim setelah Anda menekan tombol kirim di WhatsApp.</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <x-wa-button :message="$whatsappMessage" label="Lanjutkan di WhatsApp" track-label="Halaman terima kasih" />
                <a href="{{ route('blog.index') }}" class="btn-secondary">Baca panduan website</a>
            </div>
        </div>
    </section>
@endsection
