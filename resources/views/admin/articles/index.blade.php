<x-admin.layout title="Artikel">
    <x-admin.header title="Artikel blog" description="Prioritaskan artikel yang menjawab pertanyaan nyata calon pelanggan.">
        <a href="{{ route('admin.articles.create') }}" class="btn-primary">Tulis artikel</a>
    </x-admin.header>
    <form class="mb-6 flex flex-wrap gap-2" method="GET">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul…" class="form-input max-w-xs">
        <select name="status" class="form-input max-w-[10rem]">
            <option value="">Semua status</option>
            @foreach (\App\Models\Article::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-secondary">Filter</button>
    </form>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Judul</th><th class="px-5 py-3 font-medium">Kategori</th><th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Terbit</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($articles as $article)
                    <tr>
                        <td class="px-5 py-3 font-medium"><a href="{{ route('admin.articles.edit', $article) }}" class="hover:text-brand-700">{{ $article->title }}</a></td>
                        <td class="px-5 py-3 text-slate-500">{{ $article->category?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if ($article->status === 'published' && $article->published_at?->isFuture())
                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">Terjadwal</span>
                            @else
                                <x-admin.status :on="$article->status === 'published'" />
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-500">{{ $article->published_at?->format('d M Y') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-slate-500">Belum ada artikel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
</x-admin.layout>
