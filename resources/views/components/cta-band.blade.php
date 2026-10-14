@props(['title' => 'Siap membahas kebutuhan website Anda?', 'lead' => 'Ceritakan tujuan dan anggaran Anda. Kami bantu memilih paket dan fitur yang benar-benar dibutuhkan, tanpa biaya konsultasi.', 'message' => null, 'trackLabel' => 'CTA band'])
<section class="section">
    <div class="container-page">
        <div class="relative isolate overflow-hidden rounded-[2rem] bg-ink px-6 py-14 text-center text-white sm:px-16">
            <div class="absolute inset-0 -z-10 opacity-70" aria-hidden="true" data-island="Aurora"
                data-props="{{ json_encode(['colorStops' => ['#1fa855', '#5227FF', '#7c5cff'], 'amplitude' => 0.7, 'speed' => 0.5, 'mouseInteraction' => false, 'direction' => 'bottom']) }}"></div>
            <h2 class="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-balance sm:text-4xl">{{ $title }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-white/75">{{ $lead }}</p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <x-wa-button :message="$message" :track-label="$trackLabel" />
                <a href="{{ route('contact') }}" class="btn-ghost-light" data-track="cta_click" data-track-label="{{ $trackLabel }}: formulir">Kirim formulir penawaran</a>
            </div>
        </div>
    </div>
</section>
