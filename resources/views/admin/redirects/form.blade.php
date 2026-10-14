<x-admin.layout :title="$redirect->exists ? 'Edit redirect' : 'Redirect baru'">
    <x-admin.header :title="$redirect->exists ? 'Edit redirect' : 'Redirect baru'">
        @if ($redirect->exists)
            <x-admin.delete :action="route('admin.redirects.destroy', $redirect)" />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $redirect->exists ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}" class="max-w-2xl">
        @csrf
        @if ($redirect->exists) @method('PUT') @endif
        <x-admin.panel>
            <x-admin.input name="from_path" label="Path lama" :value="$redirect->from_path" required placeholder="/halaman-lama" />
            <x-admin.input name="to_path" label="Tujuan" :value="$redirect->to_path" required placeholder="/halaman-baru" help="Path di website ini (/...) atau URL lengkap." />
            <x-admin.select name="status_code" label="Jenis" :options="[301 => '301 — permanen (disarankan)', 302 => '302 — sementara']" :value="$redirect->status_code" />
        </x-admin.panel>
        <x-admin.actions :cancel="route('admin.redirects.index')" />
    </form>
</x-admin.layout>
