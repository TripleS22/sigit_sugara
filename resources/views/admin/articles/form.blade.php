<x-admin.layout :title="$article->exists ? 'Edit artikel' : 'Artikel baru'">
    <x-admin.header :title="$article->exists ? 'Edit: '.$article->title : 'Artikel baru'">
        @if ($article->exists)
            <x-admin.delete :action="route('admin.articles.destroy', $article)" label="Hapus artikel" confirm="Hapus artikel ini? URL lama dialihkan (301) ke halaman blog." />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($article->exists) @method('PUT') @endif
        <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
            <div class="space-y-6">
                <x-admin.panel>
                    <x-admin.input name="title" label="Judul (H1)" :value="$article->title" required data-slug-source="#f-slug" />
                    <x-admin.input name="slug" label="Slug" :value="$article->slug" required help="URL: /blog/slug" />
                    <x-admin.textarea name="excerpt" label="Ringkasan" :value="$article->excerpt" rows="3" maxlength="500" help="Tampil di daftar artikel dan sebagai deskripsi bawaan." />
                    <x-admin.textarea name="body" label="Isi artikel" :value="$article->body" rows="26" mono help="Gunakan subjudul ## dan ### secara berurutan. H1 sudah otomatis dari judul." />
                    <x-admin.markdown-help />
                </x-admin.panel>
                <x-admin.seo :model="$article" :preview-url="$article->exists ? $article->url() : null" />
            </div>
            <div class="space-y-6">
                <x-admin.panel title="Publikasi">
                    <x-admin.select name="status" label="Status" :options="\App\Models\Article::STATUSES" :value="$article->status" />
                    <x-admin.input name="published_at" label="Tanggal terbit" type="datetime-local" :value="$article->published_at?->format('Y-m-d\TH:i')" help="Isi tanggal mendatang untuk menjadwalkan." />
                    <x-admin.select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')" :value="$article->category_id" placeholder="— Tanpa kategori —" />
                    <x-admin.input name="tags_text" label="Tag" :value="implode(', ', $article->tags ?? [])" help="Pisahkan dengan koma." />
                </x-admin.panel>
                <x-admin.panel title="Gambar utama">
                    <x-admin.image name="featured_image_file" label="Gambar" :current="$article->featured_image" alt-name="featured_image_alt" :alt-value="$article->featured_image_alt" />
                </x-admin.panel>
            </div>
        </div>
        <x-admin.actions :cancel="route('admin.articles.index')" :view="$article->exists ? $article->url() : null" />
    </form>
</x-admin.layout>
