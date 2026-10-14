<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} · CMS {{ \App\Models\Setting::get('site_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800">
    @php
        $nav = [
            ['Dashboard', 'admin.dashboard', 'admin.dashboard'],
            ['Leads', 'admin.leads.index', 'admin.leads.*'],
            ['Layanan', 'admin.services.index', 'admin.services.*'],
            ['Paket harga', 'admin.packages.index', 'admin.packages.*'],
            ['Portofolio', 'admin.portfolios.index', 'admin.portfolios.*'],
            ['Artikel', 'admin.articles.index', 'admin.articles.*'],
            ['Kategori', 'admin.categories.index', 'admin.categories.*'],
            ['Halaman', 'admin.pages.index', 'admin.pages.*'],
            ['FAQ', 'admin.faqs.index', 'admin.faqs.*'],
            ['Testimoni', 'admin.testimonials.index', 'admin.testimonials.*'],
            ['Media', 'admin.media.index', 'admin.media.*'],
            ['Redirect', 'admin.redirects.index', 'admin.redirects.*'],
        ];
        if (auth()->user()->isAdmin()) {
            $nav[] = ['Pengaturan', 'admin.settings.edit', 'admin.settings.*'];
            $nav[] = ['Pengguna', 'admin.users.index', 'admin.users.*'];
        }
    @endphp
    <div class="lg:grid lg:min-h-screen lg:grid-cols-[15rem_1fr]">
        <aside class="bg-ink text-white lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto">
            <div class="flex items-center justify-between px-5 py-4 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="font-bold">CMS {{ \App\Models\Setting::get('site_name') }}</a>
                <a href="{{ route('home') }}" target="_blank" class="text-xs text-white/60 hover:text-white lg:mt-1 lg:block">Lihat website ↗</a>
            </div>
            <nav class="flex gap-1 overflow-x-auto px-3 pb-3 text-sm lg:block lg:space-y-0.5 lg:overflow-visible" aria-label="Menu CMS">
                @foreach ($nav as [$label, $route, $pattern])
                    <a href="{{ route($route) }}" class="block shrink-0 rounded-xl px-3 py-2 whitespace-nowrap {{ request()->routeIs($pattern) ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <div class="hidden border-t border-white/10 px-5 py-4 text-xs text-white/60 lg:block">
                <p>{{ auth()->user()->name }} · {{ \App\Models\User::ROLES[auth()->user()->role] ?? auth()->user()->role }}</p>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">@csrf<button class="hover:text-white">Keluar</button></form>
            </div>
        </aside>

        <main class="min-w-0 px-4 py-8 sm:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-2xl bg-green-50 px-4 py-3 text-sm text-green-800 ring-1 ring-green-200" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-800 ring-1 ring-red-200" role="alert">
                    <p class="font-semibold">Periksa kembali isian berikut:</p>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
</body>
</html>
