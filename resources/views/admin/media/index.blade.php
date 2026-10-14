<x-admin.layout title="Media">
    <x-admin.header title="Media" description="Unggah gambar untuk artikel dan halaman, lalu salin kode Markdown-nya. Kompres gambar sebelum diunggah agar halaman tetap cepat." />
    <x-admin.panel title="Unggah gambar" class="mb-8">
        <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="grid gap-5 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
            @csrf
            <div>
                <label for="files" class="form-label">File (maks. 10, masing-masing 2 MB)</label>
                <input id="files" name="files[]" type="file" multiple required accept="image/jpeg,image/png,image/webp" class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700">
            </div>
            <x-admin.input name="alt" label="Teks alternatif (alt)" required />
            <button class="btn-primary">Unggah</button>
        </form>
    </x-admin.panel>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($media as $item)
            <div class="overflow-hidden rounded-3xl bg-white ring-1 ring-slate-200">
                <img src="{{ $item->url() }}" alt="{{ $item->alt }}" class="aspect-video w-full bg-slate-100 object-cover" loading="lazy">
                <div class="space-y-3 p-4 text-xs">
                    <p class="truncate text-slate-500">{{ $item->original_name }} · {{ $item->width }}×{{ $item->height }} · {{ number_format($item->size / 1024, 0) }} KB</p>
                    <form method="POST" action="{{ route('admin.media.update', $item) }}" class="flex gap-2">
                        @csrf
                        @method('PATCH')
                        <input name="alt" value="{{ $item->alt }}" class="form-input !py-1.5 text-xs" aria-label="Teks alternatif">
                        <button class="btn-secondary !px-3 !py-1.5 text-xs">Simpan</button>
                    </form>
                    <div class="flex items-center justify-between">
                        <button type="button" class="font-semibold text-brand-700" data-copy="{{ $item->markdown() }}">Salin Markdown</button>
                        <x-admin.delete :action="route('admin.media.destroy', $item)" confirm="Hapus gambar ini? Konten yang memakainya akan menampilkan gambar rusak." />
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-500">Belum ada media.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $media->links() }}</div>
</x-admin.layout>
