<x-admin.layout title="Portofolio">
    <x-admin.header title="Portofolio" description="Tampilkan hanya proyek nyata yang boleh dipublikasikan. Hasil proyek harus dapat dibuktikan.">
        <a href="{{ route('admin.portfolios.create') }}" class="btn-primary">Tambah portofolio</a>
    </x-admin.header>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Proyek</th><th class="px-5 py-3 font-medium">Jenis</th><th class="px-5 py-3 font-medium">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($portfolios as $portfolio)
                    <tr>
                        <td class="px-5 py-3 font-medium"><a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="hover:text-brand-700">{{ $portfolio->title }}</a></td>
                        <td class="px-5 py-3 text-slate-500">{{ $portfolio->project_type }}</td>
                        <td class="px-5 py-3"><x-admin.status :on="$portfolio->is_published" /></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-8 text-center text-slate-500">Belum ada portofolio.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $portfolios->links() }}</div>
</x-admin.layout>
