<x-admin.layout :title="$portfolio->exists ? 'Edit portofolio' : 'Portofolio baru'">
    <x-admin.header :title="$portfolio->exists ? 'Edit: '.$portfolio->title : 'Portofolio baru'">
        @if ($portfolio->exists)
            <x-admin.delete :action="route('admin.portfolios.destroy', $portfolio)" label="Hapus" confirm="Hapus portofolio ini? URL lama dialihkan ke daftar portofolio." />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $portfolio->exists ? route('admin.portfolios.update', $portfolio) : route('admin.portfolios.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($portfolio->exists) @method('PUT') @endif
        <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
            <div class="space-y-6">
                <x-admin.panel>
                    <x-admin.input name="title" label="Nama proyek" :value="$portfolio->title" required data-slug-source="#f-slug" />
                    <x-admin.input name="slug" label="Slug" :value="$portfolio->slug" required help="URL: /portofolio/slug" />
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.input name="project_type" label="Jenis proyek" :value="$portfolio->project_type" help="Mis. Company Profile, Website UMKM, Sistem Laravel" />
                        <x-admin.input name="client_name" label="Nama klien" :value="$portfolio->client_name" help="Kosongkan bila klien tidak ingin disebut." />
                    </div>
                    <x-admin.textarea name="summary" label="Ringkasan" :value="$portfolio->summary" rows="2" />
                    <x-admin.textarea name="description" label="Deskripsi / studi kasus" :value="$portfolio->description" rows="14" mono help="Tantangan, solusi, dan proses pengerjaan." />
                    <x-admin.markdown-help />
                    <x-admin.input name="technologies_text" label="Teknologi" :value="implode(', ', $portfolio->technologies ?? [])" help="Pisahkan dengan koma: Laravel, MySQL, Tailwind CSS" />
                    <x-admin.textarea name="features_text" label="Fitur utama" :value="implode(PHP_EOL, $portfolio->features ?? [])" rows="5" help="Satu fitur per baris." />
                    <x-admin.textarea name="results" label="Hasil proyek yang dapat dibuktikan" :value="$portfolio->results" rows="4" help="Gunakan data nyata (mis. waktu muat, jumlah lead). Jangan menulis klaim yang tidak bisa dibuktikan." />
                </x-admin.panel>
                <x-admin.seo :model="$portfolio" :preview-url="$portfolio->exists ? $portfolio->url() : null" />
            </div>
            <div class="space-y-6">
                <x-admin.panel title="Publikasi">
                    <x-admin.checkbox name="is_published" label="Terbitkan" :checked="$portfolio->is_published" help="Pastikan klien sudah memberi izin." />
                    <x-admin.checkbox name="is_featured" label="Tampilkan di beranda" :checked="$portfolio->is_featured" />
                    <x-admin.input name="completed_at" label="Tanggal selesai" type="date" :value="$portfolio->completed_at?->toDateString()" />
                    <x-admin.input name="demo_url" label="URL demo" type="url" :value="$portfolio->demo_url" />
                    <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$portfolio->sort_order" />
                </x-admin.panel>
                <x-admin.panel title="Gambar">
                    <x-admin.image name="image_file" label="Gambar utama" :current="$portfolio->image" alt-name="image_alt" :alt-value="$portfolio->image_alt" />
                </x-admin.panel>
            </div>
        </div>
        <x-admin.actions :cancel="route('admin.portfolios.index')" :view="$portfolio->exists ? $portfolio->url() : null" />
    </form>
</x-admin.layout>
