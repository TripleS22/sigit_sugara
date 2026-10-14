<x-admin.layout title="Layanan">
    <x-admin.header title="Layanan" description="Landing page layanan. Layanan induk (mis. Jasa Pembuatan Website) memiliki sub-layanan yang tampil di menu.">
        <a href="{{ route('admin.services.create') }}" class="btn-primary">Tambah layanan</a>
    </x-admin.header>
    <div class="space-y-6">
        @foreach ($services as $parent)
            <div class="overflow-hidden rounded-3xl bg-white ring-1 ring-slate-200">
                <div class="flex flex-wrap items-center justify-between gap-3 bg-slate-50 px-5 py-4">
                    <div>
                        <a href="{{ route('admin.services.edit', $parent) }}" class="font-semibold text-slate-900 hover:text-brand-700">{{ $parent->title }}</a>
                        <span class="ml-2 text-xs text-slate-500">{{ $parent->path() }} · {{ \App\Models\Service::SEGMENTS[$parent->segment] }}</span>
                    </div>
                    <x-admin.status :on="$parent->is_published" />
                </div>
                <ul class="divide-y divide-slate-100 text-sm" role="list">
                    @foreach ($parent->children as $child)
                        <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 pl-10">
                            <span><a href="{{ route('admin.services.edit', $child) }}" class="font-medium hover:text-brand-700">{{ $child->title }}</a> <span class="text-xs text-slate-500">{{ $child->path() }}</span></span>
                            <x-admin.status :on="$child->is_published" />
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</x-admin.layout>
