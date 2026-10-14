<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Masuk CMS · {{ \App\Models\Setting::get('site_name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-ink px-4 font-sans">
    <main class="w-full max-w-sm rounded-3xl bg-white p-8 shadow-xl">
        <h1 class="text-xl font-bold text-slate-900">Masuk ke CMS</h1>
        <p class="mt-1 text-sm text-slate-500">{{ \App\Models\Setting::get('site_name') }}</p>
        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-input">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="form-input">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300"> Ingat saya</label>
            <button type="submit" class="btn-primary w-full">Masuk</button>
        </form>
    </main>
</body>
</html>
