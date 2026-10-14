<x-admin.layout :title="$page->exists ? 'Edit halaman' : 'Halaman baru'">
    <x-admin.header :title="$page->exists ? 'Edit: '.$page->title : 'Halaman baru'">
        @if ($page->exists)
            <x-admin.delete :action="route('admin.pages.destroy', $page)" label="Hapus halaman" confirm="Hapus halaman ini? URL lama akan dialihkan (301) ke beranda." />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($page->exists) @method('PUT') @endif
        <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
            <div class="space-y-6">
                <x-admin.panel>
                    <x-admin.input name="title" label="Judul (H1)" :value="$page->title" required data-slug-source="#f-slug" />
                    <x-admin.input name="slug" label="Slug" :value="$page->slug" required help="URL: /slug. Mengubah slug halaman terbit otomatis membuat redirect 301." />
                    <x-admin.textarea name="excerpt" label="Ringkasan" :value="$page->excerpt" rows="2" help="Tampil di bawah judul dan dipakai sebagai meta description bila kosong." />
                    <x-admin.textarea name="body" label="Isi halaman" :value="$page->body" rows="18" mono />
                    <x-admin.markdown-help />
                </x-admin.panel>
                <x-admin.seo :model="$page" :preview-url="$page->exists ? $page->url() : null" />
            </div>
            <div class="space-y-6">
                <x-admin.panel title="Publikasi">
                    <x-admin.checkbox name="is_published" label="Terbitkan" :checked="$page->is_published" />
                    <x-admin.select name="template" label="Template" :options="\App\Models\Page::TEMPLATES" :value="$page->template" />
                </x-admin.panel>
                <x-admin.panel title="Gambar utama">
                    <x-admin.image name="hero_image_file" label="Gambar" :current="$page->hero_image" alt-name="hero_image_alt" :alt-value="$page->hero_image_alt" />
                </x-admin.panel>
            </div>
        </div>
        <x-admin.actions :cancel="route('admin.pages.index')" :view="$page->exists ? $page->url() : null" />
    </form>
</x-admin.layout>
