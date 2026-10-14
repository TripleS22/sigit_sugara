<x-admin.layout title="Dashboard">
    <x-admin.header title="Dashboard" description="Ringkasan konversi dan pekerjaan yang perlu ditindaklanjuti.">
        @foreach ([7, 30, 90] as $option)
            <a href="{{ route('admin.dashboard', ['hari' => $option]) }}" class="rounded-full px-3 py-1.5 text-sm {{ $days === $option ? 'bg-ink text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200' }}">{{ $option }} hari</a>
        @endforeach
    </x-admin.header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" class="rounded-3xl bg-brand-600 p-6 text-white shadow-sm">
            <p class="text-sm text-white/80">Lead baru belum ditangani</p>
            <p class="mt-2 text-3xl font-bold">{{ $newLeadCount }}</p>
        </a>
        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Formulir terkirim ({{ $days }} hari)</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $funnel['Formulir terkirim'] }}</p>
        </div>
        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Klik WhatsApp ({{ $days }} hari)</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $funnel['Klik WhatsApp'] }}</p>
        </div>
        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200">
            <p class="text-sm text-slate-500">Pendapatan proyek terjual</p>
            <p class="mt-2 text-3xl font-bold text-slate-900">{{ \App\Models\Package::rupiah($revenue) }}</p>
        </div>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <x-admin.panel title="Funnel pemasaran" description="Klik WhatsApp bukan penjualan. Tandai lead berkualitas dan status proyek di menu Leads agar angka ini akurat.">
            @php($max = max(1, max($funnel)))
            <ul class="space-y-3" role="list">
                @foreach ($funnel as $label => $value)
                    <li>
                        <div class="flex justify-between text-sm"><span class="text-slate-600">{{ $label }}</span><span class="font-semibold text-slate-900">{{ number_format($value, 0, ',', '.') }}</span></div>
                        <div class="mt-1 h-2 rounded-full bg-slate-100"><div class="h-2 rounded-full bg-brand-600" style="width: {{ round($value / $max * 100, 1) }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </x-admin.panel>

        <x-admin.panel title="Leads per kanal" description="Sumber diambil dari parameter UTM atau referrer.">
            @if ($byChannel->isEmpty())
                <p class="text-sm text-slate-500">Belum ada lead pada periode ini.</p>
            @else
                <table class="w-full text-left text-sm">
                    <thead class="text-slate-500"><tr><th class="py-2 font-medium">Kanal</th><th class="py-2 font-medium">Leads</th><th class="py-2 font-medium">Terjual</th><th class="py-2 font-medium">Pendapatan</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($byChannel as $row)
                            <tr><td class="py-2">{{ $row['channel'] }}</td><td class="py-2">{{ $row['leads'] }}</td><td class="py-2">{{ $row['won'] }}</td><td class="py-2">{{ \App\Models\Package::rupiah((int) $row['revenue']) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </x-admin.panel>

        <x-admin.panel title="Halaman yang menghasilkan aksi">
            @if ($topPages->isEmpty())
                <p class="text-sm text-slate-500">Belum ada data klik.</p>
            @else
                <ul class="divide-y divide-slate-100 text-sm" role="list">
                    @foreach ($topPages as $row)
                        <li class="flex justify-between py-2"><span class="truncate text-slate-700">{{ $row->page_path ?: '(tidak diketahui)' }}</span><span class="font-semibold">{{ $row->total }}</span></li>
                    @endforeach
                </ul>
            @endif
        </x-admin.panel>

        <x-admin.panel title="Lead terbaru">
            @forelse ($newLeads as $lead)
                <a href="{{ route('admin.leads.show', $lead) }}" class="flex items-center justify-between gap-4 rounded-2xl p-3 hover:bg-slate-50">
                    <span>
                        <span class="block font-medium text-slate-900">{{ $lead->name }}</span>
                        <span class="block text-xs text-slate-500">{{ \App\Models\Lead::SERVICE_TYPES[$lead->service_type] ?? $lead->service_type }} · {{ $lead->created_at->diffForHumans() }}</span>
                    </span>
                    <span class="text-xs text-brand-700">Buka →</span>
                </a>
            @empty
                <p class="text-sm text-slate-500">Tidak ada lead baru.</p>
            @endforelse
        </x-admin.panel>

        <x-admin.panel title="Kesiapan peluncuran" description="Checklist dari acceptance criteria PRD.">
            <ul class="space-y-2 text-sm" role="list">
                @foreach ($checklist as $label => $done)
                    <li class="flex items-center gap-2">
                        <span class="grid size-5 place-items-center rounded-full text-xs {{ $done ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $done ? '✓' : '!' }}</span>
                        <span class="{{ $done ? 'text-slate-600' : 'font-medium text-slate-900' }}">{{ $label }}</span>
                    </li>
                @endforeach
            </ul>
            @if ($draftCount)
                <p class="text-sm text-slate-500">{{ $draftCount }} artikel masih draft. <a href="{{ route('admin.articles.index', ['status' => 'draft']) }}" class="text-brand-700 underline">Lihat</a></p>
            @endif
        </x-admin.panel>
    </div>
</x-admin.layout>
