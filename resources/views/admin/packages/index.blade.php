<x-admin.layout title="Paket harga">
    <x-admin.header title="Paket harga" description="Perubahan harga dan fitur langsung tampil di website tanpa mengubah kode. Paket nonaktif disembunyikan dari publik.">
        <a href="{{ route('admin.packages.create') }}" class="btn-primary">Tambah paket</a>
    </x-admin.header>
    @foreach (\App\Models\Service::SEGMENTS as $segment => $label)
        <h2 class="mt-8 mb-3 text-sm font-semibold text-slate-500 uppercase">{{ $label }}</h2>
        <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Paket</th><th class="px-5 py-3 font-medium">Harga</th><th class="px-5 py-3 font-medium">Halaman</th><th class="px-5 py-3 font-medium">Fitur</th><th class="px-5 py-3 font-medium">Status</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($packages->get($segment, collect()) as $package)
                        <tr>
                            <td class="px-5 py-3 font-medium"><a href="{{ route('admin.packages.edit', $package) }}" class="hover:text-brand-700">{{ $package->name }}</a> @if ($package->badge)<span class="ml-1 rounded-full bg-brand-50 px-2 py-0.5 text-xs text-brand-700">{{ $package->badge }}</span>@endif</td>
                            <td class="px-5 py-3">{{ $package->priceLabel() }}</td>
                            <td class="px-5 py-3">{{ $package->max_pages ?: '—' }}</td>
                            <td class="px-5 py-3">{{ $package->features_count }}</td>
                            <td class="px-5 py-3"><x-admin.status :on="$package->is_active" yes="Aktif" no="Nonaktif" /></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-6 text-center text-slate-500">Belum ada paket.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</x-admin.layout>
