<x-admin.layout title="Pengguna">
    <x-admin.header title="Pengguna CMS" description="Admin mengelola pengaturan dan pengguna. Editor mengelola konten dan leads.">
        <a href="{{ route('admin.users.create') }}" class="btn-primary">Tambah pengguna</a>
    </x-admin.header>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Nama</th><th class="px-5 py-3 font-medium">Email</th><th class="px-5 py-3 font-medium">Peran</th><th class="px-5 py-3"></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-5 py-3 font-medium"><a href="{{ route('admin.users.edit', $user) }}" class="hover:text-brand-700">{{ $user->name }}</a></td>
                        <td class="px-5 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-5 py-3">{{ \App\Models\User::ROLES[$user->role] ?? $user->role }}</td>
                        <td class="px-5 py-3 text-right">
                            @unless ($user->is(auth()->user()))
                                <x-admin.delete :action="route('admin.users.destroy', $user)" confirm="Hapus pengguna ini?" />
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin.layout>
