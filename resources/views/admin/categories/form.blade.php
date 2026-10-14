<x-admin.layout :title="$category->exists ? 'Edit kategori' : 'Kategori baru'">
    <x-admin.header :title="$category->exists ? 'Edit: '.$category->name : 'Kategori baru'">
        @if ($category->exists)
            <x-admin.delete :action="route('admin.categories.destroy', $category)" confirm="Hapus kategori? Artikel di dalamnya tetap ada tanpa kategori." />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="max-w-2xl">
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <x-admin.panel>
            <x-admin.input name="name" label="Nama" :value="$category->name" required data-slug-source="#f-slug" />
            <x-admin.input name="slug" label="Slug" :value="$category->slug" required help="URL: /blog/kategori/slug" />
            <x-admin.textarea name="description" label="Deskripsi" :value="$category->description" rows="3" />
            <x-admin.input name="meta_title" label="Meta title" :value="$category->meta_title" maxlength="70" counter />
            <x-admin.textarea name="meta_description" label="Meta description" :value="$category->meta_description" rows="2" maxlength="170" counter />
        </x-admin.panel>
        <x-admin.actions :cancel="route('admin.categories.index')" />
    </form>
</x-admin.layout>
