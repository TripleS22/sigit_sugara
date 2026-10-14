<x-admin.layout :title="$user->exists ? 'Edit pengguna' : 'Pengguna baru'">
    <x-admin.header :title="$user->exists ? 'Edit: '.$user->name : 'Pengguna baru'" />
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="max-w-xl">
        @csrf
        @if ($user->exists) @method('PUT') @endif
        <x-admin.panel>
            <x-admin.input name="name" label="Nama" :value="$user->name" required />
            <x-admin.input name="email" label="Email" type="email" :value="$user->email" required autocomplete="off" />
            <x-admin.select name="role" label="Peran" :options="\App\Models\User::ROLES" :value="$user->role" />
            <x-admin.input name="password" label="Password" type="password" :required="! $user->exists" autocomplete="new-password"
                :help="$user->exists ? 'Kosongkan bila tidak diganti. Minimal 10 karakter, huruf dan angka.' : 'Minimal 10 karakter, huruf dan angka.'" />
            <x-admin.input name="password_confirmation" label="Ulangi password" type="password" autocomplete="new-password" />
        </x-admin.panel>
        <x-admin.actions :cancel="route('admin.users.index')" />
    </form>
</x-admin.layout>
