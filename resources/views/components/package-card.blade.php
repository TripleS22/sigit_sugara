@props(['package', 'compact' => false])
<x-spotlight-card :highlight="$package->is_featured" class="flex flex-col">
    <div class="flex flex-col h-full">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-xl font-bold text-slate-900">
                <a href="{{ $package->url() }}" class="hover:text-brand-700">{{ $package->name }}</a>
            </h3>
            @if ($package->badge)
                <span class="shrink-0 rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white">{{ $package->badge }}</span>
            @endif
        </div>
        @if ($package->target_audience)
            <p class="mt-1 text-sm text-slate-500">Untuk {{ $package->target_audience }}</p>
        @endif

        <p class="mt-5">
            <span class="block text-xs font-medium tracking-wide text-slate-500 uppercase">{{ $package->price_prefix ?: 'Harga awal' }}</span>
            <span class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">{{ \App\Models\Package::rupiah($package->price_from) }}</span>
            @if ($package->price_to && $package->price_to > $package->price_from)
                <span class="text-base font-semibold text-slate-500">– {{ \App\Models\Package::rupiah($package->price_to) }}</span>
            @endif
        </p>
        @if ($package->tagline)
            <p class="mt-3 text-sm text-slate-600">{{ $package->tagline }}</p>
        @endif

        <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
            @if ($package->max_pages)
                <div class="rounded-2xl bg-slate-50 p-3">
                    <dt class="text-slate-500">Halaman</dt>
                    <dd class="font-semibold text-slate-900">Maks. {{ $package->max_pages }}</dd>
                </div>
            @endif
            @if ($package->delivery_estimate)
                <div class="rounded-2xl bg-slate-50 p-3">
                    <dt class="text-slate-500">Estimasi</dt>
                    <dd class="font-semibold text-slate-900">{{ $package->delivery_estimate }}</dd>
                </div>
            @endif
        </dl>

        <ul class="mt-6 space-y-2.5 text-sm" role="list">
            @foreach ($compact ? $package->features->take(6) : $package->features as $feature)
                <li class="flex gap-2.5 {{ $feature->is_included ? 'text-slate-700' : 'text-slate-400' }}">
                    @if ($feature->is_included)
                        <x-icon name="check" class="mt-0.5 size-4 shrink-0 text-brand-600" />
                    @else
                        <x-icon name="x" class="mt-0.5 size-4 shrink-0" />
                    @endif
                    <span>
                        <span class="sr-only">{{ $feature->is_included ? 'Termasuk:' : 'Tidak termasuk:' }}</span>
                        {{ $feature->label }}@if ($feature->value)<span class="text-slate-500">: {{ $feature->value }}</span>@endif
                    </span>
                </li>
            @endforeach
        </ul>

        @if ($package->hosting_note)
            <p class="mt-5 rounded-2xl bg-amber-50 px-3 py-2 text-xs text-amber-800">{{ $package->hosting_note }}</p>
        @endif

        <div class="mt-auto flex flex-col gap-2 pt-7">
            <x-wa-button :message="$package->whatsappMessage()" label="Konsultasi paket {{ $package->name }}"
                track-label="Paket {{ $package->name }}" :variant="$package->is_featured ? 'btn-wa' : 'btn-secondary'" class="w-full" />
            <a href="{{ $package->url() }}" class="btn text-brand-700 hover:bg-brand-50" data-track="package_cta_click" data-track-label="Detail {{ $package->name }}">
                Lihat detail paket <x-icon name="arrow-right" class="size-4" />
            </a>
        </div>
    </div>
</x-spotlight-card>
