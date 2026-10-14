<x-admin.layout title="Testimoni">
    <x-admin.header title="Testimoni" description="Hanya testimoni dari klien nyata yang sudah memberi izin. Testimoni dan rating palsu melanggar pedoman Google dan merusak kepercayaan.">
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">Tambah testimoni</a>
    </x-admin.header>
    <div class="divide-y divide-slate-100 rounded-3xl bg-white ring-1 ring-slate-200">
        @forelse ($testimonials as $testimonial)
            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 text-sm hover:bg-slate-50">
                <span><span class="font-medium text-slate-900">{{ $testimonial->name }}</span> <span class="text-slate-500">{{ $testimonial->company }}</span></span>
                <x-admin.status :on="$testimonial->is_published" />
            </a>
        @empty
            <p class="px-5 py-8 text-center text-sm text-slate-500">Belum ada testimoni. Bagian testimoni di beranda otomatis tersembunyi sampai ada testimoni yang diterbitkan.</p>
        @endforelse
    </div>
</x-admin.layout>
