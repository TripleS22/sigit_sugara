<x-admin.layout title="Halaman">
    <x-admin.header title="Halaman" description="Halaman statis seperti Tentang Kami, Kebijakan Privasi, dan Syarat & Ketentuan.">
        <a href="{{ route('admin.pages.create') }}" class="btn-primary">Tambah halaman</a>
    </x-admin.header>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Judul</th><th class="px-5 py-3 font-medium">URL</th><th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3"></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pages as $page)
                    <tr>
                        <td class="px-5 py-3 font-medium"><a href="{{ route('admin.pages.edit', $page) }}" class="hover:text-brand-700">{{ $page->title }}</a></td>
                        <td class="px-5 py-3 text-slate-500">{{ $page->path() }}</td>
                        <td class="px-5 py-3"><x-admin.status :on="$page->is_published" /></td>
                        <td class="px-5 py-3 text-right"><a href="{{ route('admin.pages.edit', $page) }}" class="text-brand-700">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-500">Belum ada halaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $pages->links() }}</div>
</x-admin.layout>
