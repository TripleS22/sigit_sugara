<x-admin.layout title="Redirect">
    <x-admin.header title="Redirect URL" description="Redirect 301 dibuat otomatis saat slug konten terbit berubah atau konten dihapus. Tambahkan manual untuk URL lama dari website sebelumnya.">
        <a href="{{ route('admin.redirects.create') }}" class="btn-primary">Tambah redirect</a>
    </x-admin.header>
    <form class="mb-6 flex gap-2" method="GET">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari path…" class="form-input max-w-xs">
        <button class="btn-secondary">Cari</button>
    </form>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Dari</th><th class="px-5 py-3 font-medium">Ke</th><th class="px-5 py-3 font-medium">Kode</th><th class="px-5 py-3 font-medium">Dipakai</th><th class="px-5 py-3"></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($redirects as $redirect)
                    <tr>
                        <td class="px-5 py-3 font-mono text-xs">{{ $redirect->from_path }}</td>
                        <td class="px-5 py-3 font-mono text-xs">{{ $redirect->to_path }}</td>
                        <td class="px-5 py-3">{{ $redirect->status_code }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $redirect->hits }}× @if ($redirect->last_hit_at)<span class="text-xs">({{ $redirect->last_hit_at->diffForHumans() }})</span>@endif</td>
                        <td class="px-5 py-3 text-right"><a href="{{ route('admin.redirects.edit', $redirect) }}" class="text-brand-700">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">Belum ada redirect.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $redirects->links() }}</div>
</x-admin.layout>
