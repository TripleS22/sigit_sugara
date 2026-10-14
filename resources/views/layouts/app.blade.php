@php
    use App\Models\Setting;
    $siteName = Setting::get('site_name');
    // Inline @section values arrive HTML-escaped; decode before building the WhatsApp URL.
    $waMessage = html_entity_decode(trim($__env->yieldContent('wa_message')), ENT_QUOTES) ?: null;
    $studentNav = $navServices->firstWhere('segment', 'student');
    $businessNav = $navServices->where('segment', 'business');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b0b1a">
    <x-seo :seo="$seo" />
    @if (Setting::get('google_site_verification'))
        <meta name="google-site-verification" content="{{ Setting::get('google_site_verification') }}">
    @endif
    @if (Setting::get('ga4_measurement_id'))
        <meta name="ga4-id" content="{{ Setting::get('ga4_measurement_id') }}">
        <meta name="ga4-consent" content="{{ Setting::bool('analytics_require_consent') ? '1' : '0' }}">
        <link rel="preconnect" href="https://www.googletagmanager.com">
    @endif
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-sans"
    @hasSection('track_view') data-track-view="@yield('track_view')" data-track-view-label="@yield('track_view_label')" @endif>
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900">Lewati ke konten</a>

    <header class="fixed inset-x-0 top-0 z-40 border-b border-white/10 bg-ink/75 text-white backdrop-blur-md">
        <div class="container-page flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight">
                @if (Setting::get('logo'))
                    <img src="{{ asset('storage/'.Setting::get('logo')) }}" alt="{{ $siteName }}" class="h-8 w-auto" width="120" height="32">
                @else
                    <span class="grid size-8 place-items-center rounded-xl bg-brand-600 text-sm">{{ mb_substr($siteName, 0, 1) }}</span>
                    <span>{{ $siteName }}</span>
                @endif
            </a>

            <nav aria-label="Navigasi utama" class="hidden lg:block">
                <ul class="flex items-center gap-1 text-sm font-medium text-white/80" role="list">
                    <li><a href="{{ route('home') }}" class="rounded-full px-3 py-2 hover:text-white @if (request()->routeIs('home')) text-white @endif">Beranda</a></li>
                    <li class="group relative">
                        <button type="button" class="flex items-center gap-1 rounded-full px-3 py-2 hover:text-white" aria-haspopup="true">
                            Layanan <x-icon name="chevron-down" class="size-4" />
                        </button>
                        <div class="invisible absolute left-0 top-full w-80 translate-y-1 rounded-3xl bg-white p-3 text-slate-700 opacity-0 shadow-xl ring-1 ring-slate-200 transition group-focus-within:visible group-focus-within:translate-y-0 group-focus-within:opacity-100 group-hover:visible group-hover:translate-y-0 group-hover:opacity-100">
                            @foreach ($businessNav as $item)
                                <a href="{{ $item->url() }}" class="block rounded-2xl px-3 py-2 font-semibold text-slate-900 hover:bg-slate-50">{{ $item->label() }}</a>
                                @foreach ($item->children as $child)
                                    <a href="{{ $child->url() }}" class="block rounded-2xl px-3 py-1.5 pl-6 text-sm hover:bg-slate-50 hover:text-brand-700">{{ $child->label() }}</a>
                                @endforeach
                            @endforeach
                            @if ($studentNav)
                                <div class="mt-2 border-t border-slate-100 pt-2">
                                    <a href="{{ $studentNav->url() }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 text-sm hover:bg-slate-50 hover:text-brand-700">
                                        <x-icon name="academic" class="size-4 text-brand-600" /> {{ $studentNav->label() }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>
                    <li><a href="{{ route('packages.index') }}" class="rounded-full px-3 py-2 hover:text-white @if (request()->routeIs('packages.*')) text-white @endif">Paket Harga</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="rounded-full px-3 py-2 hover:text-white @if (request()->routeIs('portfolio.*')) text-white @endif">Portofolio</a></li>
                    <li><a href="{{ route('blog.index') }}" class="rounded-full px-3 py-2 hover:text-white @if (request()->routeIs('blog.*')) text-white @endif">Blog</a></li>
                    <li><a href="{{ url('/tentang-kami') }}" class="rounded-full px-3 py-2 hover:text-white">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="rounded-full px-3 py-2 hover:text-white @if (request()->routeIs('contact')) text-white @endif">Kontak</a></li>
                </ul>
            </nav>

            <div class="flex items-center gap-2">
                <x-wa-button :message="$waMessage" label="Konsultasi WhatsApp" track-label="Header" class="hidden !px-4 !py-2 sm:inline-flex" />
                <button type="button" class="rounded-full p-2 text-white hover:bg-white/10 lg:hidden" data-nav-toggle aria-expanded="false" aria-controls="mobile-menu">
                    <span class="sr-only">Buka menu</span>
                    <x-icon name="menu" class="size-6" />
                </button>
            </div>
        </div>

        <nav id="mobile-menu" data-nav-menu class="hidden max-h-[calc(100dvh-4rem)] overflow-y-auto border-t border-white/10 lg:hidden" aria-label="Navigasi mobile">
            <ul class="container-page space-y-1 py-4 text-base" role="list">
                <li><a href="{{ route('home') }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">Beranda</a></li>
                @foreach ($navServices as $item)
                    <li>
                        <a href="{{ $item->url() }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">{{ $item->label() }}</a>
                        @if ($item->children->isNotEmpty())
                            <ul class="ml-3 border-l border-white/10 pl-3 text-sm text-white/75" role="list">
                                @foreach ($item->children as $child)
                                    <li><a href="{{ $child->url() }}" class="block rounded-xl px-3 py-1.5 hover:bg-white/10">{{ $child->label() }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                <li><a href="{{ route('packages.index') }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">Paket Harga</a></li>
                <li><a href="{{ route('portfolio.index') }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">Portofolio</a></li>
                <li><a href="{{ route('blog.index') }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">Blog</a></li>
                <li><a href="{{ url('/tentang-kami') }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">Tentang</a></li>
                <li><a href="{{ route('contact') }}" class="block rounded-xl px-3 py-2 hover:bg-white/10">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <main id="konten">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="container-page grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <p class="text-lg font-bold text-slate-900">{{ $siteName }}</p>
                <p class="mt-2 max-w-md text-sm text-slate-600">{{ Setting::get('tagline') }}. {{ Setting::get('service_area') }}.</p>
                <ul class="mt-5 space-y-2 text-sm text-slate-600" role="list">
                    @if (Setting::get('contact_email'))
                        <li class="flex items-center gap-2"><x-icon name="mail" class="size-4 text-brand-600" /><a href="mailto:{{ Setting::get('contact_email') }}" class="hover:text-brand-700">{{ Setting::get('contact_email') }}</a></li>
                    @endif
                    @if (Setting::get('address'))
                        <li class="flex items-start gap-2"><x-icon name="map" class="mt-0.5 size-4 text-brand-600" /><span>{{ Setting::get('address') }}</span></li>
                    @endif
                    <li class="flex items-center gap-2"><x-icon name="clock" class="size-4 text-brand-600" /><span>{{ Setting::get('business_hours') }}</span></li>
                </ul>
                <div class="mt-6"><x-wa-button :message="$waMessage" track-label="Footer" /></div>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">Layanan</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-600" role="list">
                    @foreach ($navServices as $item)
                        <li><a href="{{ $item->url() }}" class="hover:text-brand-700">{{ $item->label() }}</a></li>
                        @foreach ($item->children->take(3) as $child)
                            <li><a href="{{ $child->url() }}" class="hover:text-brand-700">{{ $child->label() }}</a></li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">Informasi</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-600" role="list">
                    <li><a href="{{ route('packages.index') }}" class="hover:text-brand-700">Paket Harga</a></li>
                    <li><a href="{{ route('portfolio.index') }}" class="hover:text-brand-700">Portofolio</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-brand-700">Blog</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-brand-700">FAQ</a></li>
                    <li><a href="{{ url('/tentang-kami') }}" class="hover:text-brand-700">Tentang Kami</a></li>
                    <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-brand-700">Kebijakan Privasi</a></li>
                    <li><a href="{{ url('/syarat-ketentuan') }}" class="hover:text-brand-700">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-200">
            <div class="container-page flex flex-col gap-2 py-6 text-xs text-slate-500 sm:flex-row sm:justify-between">
                <p>&copy; {{ now()->year }} {{ $siteName }}. Hak cipta dilindungi.</p>
                @if (Setting::get('footer_note'))
                    <p class="max-w-xl sm:text-right">{{ Setting::get('footer_note') }}</p>
                @endif
            </div>
        </div>
    </footer>

    {{-- Floating WhatsApp shortcut for mobile; small and never covers the content it sits on. --}}
    @if (Setting::whatsappUrl())
        <a href="{{ Setting::whatsappUrl($waMessage) }}" target="_blank" rel="noopener" data-track="whatsapp_click" data-track-label="Tombol mengambang"
            class="fixed right-4 bottom-4 z-30 grid size-14 place-items-center rounded-full bg-wa text-white shadow-lg shadow-green-900/30 transition hover:scale-105 sm:hidden">
            <x-whatsapp-icon class="size-7" />
            <span class="sr-only">Konsultasi melalui WhatsApp</span>
        </a>
    @endif

    @if (Setting::get('ga4_measurement_id') && Setting::bool('analytics_require_consent'))
        <div data-consent-bar class="fixed inset-x-4 bottom-4 z-40 mx-auto hidden max-w-xl rounded-2xl bg-white p-4 text-sm text-slate-700 shadow-xl ring-1 ring-slate-200 sm:bottom-6" role="region" aria-label="Persetujuan analitik">
            <p>Kami ingin memakai Google Analytics untuk memahami halaman mana yang membantu pengunjung. Tidak ada iklan atau penjualan data. <a href="{{ url('/kebijakan-privasi') }}" class="font-medium text-brand-700 underline">Pelajari</a></p>
            <div class="mt-3 flex gap-2">
                <button type="button" data-consent="granted" class="btn-primary !py-2">Izinkan</button>
                <button type="button" data-consent="denied" class="btn-secondary !py-2">Tolak</button>
            </div>
        </div>
    @endif
</body>
</html>
