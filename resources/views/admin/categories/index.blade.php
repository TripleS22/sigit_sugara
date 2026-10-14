<x-admin.layout title="Kategori">
    <x-admin.header title="Kategori artikel">
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">Tambah kategori</a>
    </x-admin.header>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Nama</th><th class="px-5 py-3 font-medium">URL</th><th class="px-5 py-3 font-medium">Artikel</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-5 py-3 font-medium"><a href="{{ route('admin.categories.edit', $category) }}" class="hover:text-brand-700">{{ $category->name }}</a></td>
                        <td class="px-5 py-3 text-slate-500">{{ $category->path() }}</td>
                        <td class="px-5 py-3">{{ $category->articles_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-8 text-center text-slate-500">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin.layout>
