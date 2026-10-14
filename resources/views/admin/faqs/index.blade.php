<x-admin.layout title="FAQ">
    <x-admin.header title="FAQ" description="Pertanyaan umum. FAQ yang terhubung ke layanan tampil di halaman layanan tersebut.">
        <a href="{{ route('admin.faqs.create') }}" class="btn-primary">Tambah FAQ</a>
    </x-admin.header>
    @forelse ($faqs as $group => $items)
        <h2 class="mt-8 mb-3 text-sm font-semibold text-slate-500 uppercase">{{ \App\Models\Faq::GROUPS[$group] ?? $group }}</h2>
        <div class="divide-y divide-slate-100 rounded-3xl bg-white ring-1 ring-slate-200">
            @foreach ($items as $faq)
                <a href="{{ route('admin.faqs.edit', $faq) }}" class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 text-sm hover:bg-slate-50">
                    <span class="font-medium text-slate-900">{{ $faq->question }}</span>
                    <span class="flex items-center gap-2">
                        @if ($faq->service)<span class="text-xs text-slate-500">{{ $faq->service->title }}</span>@endif
                        @if ($faq->show_on_home)<span class="rounded-full bg-brand-50 px-2 py-0.5 text-xs text-brand-700">Beranda</span>@endif
                        <x-admin.status :on="$faq->is_published" yes="Tampil" no="Disembunyikan" />
                    </span>
                </a>
            @endforeach
        </div>
    @empty
        <p class="text-slate-500">Belum ada FAQ.</p>
    @endforelse
</x-admin.layout>
