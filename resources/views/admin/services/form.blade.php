<x-admin.layout :title="$service->exists ? 'Edit layanan' : 'Layanan baru'">
    <x-admin.header :title="$service->exists ? 'Edit: '.$service->title : 'Layanan baru'"
        description="Setiap layanan harus punya maksud pencarian dan isi yang berbeda. Hindari membuat halaman hampir sama hanya untuk variasi kata kunci.">
        @if ($service->exists)
            <x-admin.delete :action="route('admin.services.destroy', $service)" label="Hapus layanan" confirm="Hapus layanan ini? URL lama akan dialihkan (301) ke layanan induk." />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($service->exists) @method('PUT') @endif
        <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
            <div class="space-y-6">
                <x-admin.panel>
                    <x-admin.input name="title" label="Nama layanan" :value="$service->title" required data-slug-source="#f-slug" />
                    <x-admin.input name="slug" label="Slug" :value="$service->slug" required help="URL: /slug, mis. jasa-pembuatan-website-umkm." />
                    <x-admin.input name="headline" label="Headline (H1)" :value="$service->headline" help="Judul utama halaman. Kosongkan untuk memakai nama layanan." />
                    <x-admin.textarea name="subheadline" label="Subheadline" :value="$service->subheadline" rows="2" />
                    <x-admin.textarea name="summary" label="Ringkasan kartu" :value="$service->summary" rows="2" help="Tampil di kartu layanan dan sebagai deskripsi bawaan." />
                    <x-admin.textarea name="highlights_text" label="Poin keunggulan" :value="implode(PHP_EOL, $service->highlights ?? [])" rows="5" help="Satu poin per baris." />
                    <x-admin.input name="timeline" label="Estimasi waktu" :value="$service->timeline" help="Mis. 7–14 hari kerja setelah materi lengkap." />
                    <x-admin.textarea name="body" label="Isi halaman" :value="$service->body" rows="22" mono />
                    <x-admin.markdown-help />
                </x-admin.panel>
                <x-admin.seo :model="$service" :preview-url="$service->exists ? $service->url() : null" />
            </div>
            <div class="space-y-6">
                <x-admin.panel title="Publikasi">
                    <x-admin.checkbox name="is_published" label="Terbitkan" :checked="$service->is_published" />
                    <x-admin.checkbox name="show_in_menu" label="Tampilkan di menu" :checked="$service->show_in_menu" />
                    <x-admin.select name="segment" label="Segmen" :options="\App\Models\Service::SEGMENTS" :value="$service->segment" help="Menentukan paket yang tampil di halaman ini." />
                    <x-admin.select name="parent_id" label="Layanan induk" :options="$parents->pluck('title', 'id')" :value="$service->parent_id" placeholder="— Tidak ada (layanan utama) —" />
                    <x-admin.input name="menu_label" label="Label menu" :value="$service->menu_label" />
                    <x-admin.select name="icon" label="Ikon" :options="['briefcase' => 'Koper', 'building' => 'Gedung', 'store' => 'Toko', 'code' => 'Kode', 'academic' => 'Akademik', 'rocket' => 'Roket', 'sparkles' => 'Kilau']" :value="$service->icon" placeholder="Default" />
                    <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$service->sort_order" />
                </x-admin.panel>
                <x-admin.panel title="Gambar">
                    <x-admin.image name="image_file" label="Gambar sidebar" :current="$service->image" alt-name="image_alt" :alt-value="$service->image_alt" />
                </x-admin.panel>
            </div>
        </div>
        <x-admin.actions :cancel="route('admin.services.index')" :view="$service->exists ? $service->url() : null" />
    </form>
</x-admin.layout>
