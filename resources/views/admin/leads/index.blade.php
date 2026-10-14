<x-admin.layout title="Leads">
    <x-admin.header title="Leads" description="Permintaan konsultasi dari formulir website. Perbarui status agar laporan funnel di dashboard akurat.">
        <a href="{{ route('admin.leads.export', request()->query()) }}" class="btn-secondary">Ekspor CSV</a>
    </x-admin.header>
    <div class="mb-4 flex flex-wrap gap-2 text-sm">
        <a href="{{ route('admin.leads.index') }}" class="rounded-full px-3 py-1.5 {{ request('status') ? 'bg-white ring-1 ring-slate-200' : 'bg-ink text-white' }}">Semua (tanpa spam)</a>
        @foreach (\App\Models\Lead::STATUSES as $value => $label)
            <a href="{{ route('admin.leads.index', ['status' => $value]) }}" class="rounded-full px-3 py-1.5 {{ request('status') === $value ? 'bg-ink text-white' : 'bg-white ring-1 ring-slate-200' }}">{{ $label }} <span class="opacity-60">{{ $counts[$value] ?? 0 }}</span></a>
        @endforeach
    </div>
    <form class="mb-6 flex flex-wrap gap-2" method="GET">
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Nama, bisnis, WhatsApp, email…" class="form-input max-w-xs">
        <select name="service" class="form-input max-w-[16rem]">
            <option value="">Semua layanan</option>
            @foreach (\App\Models\Lead::SERVICE_TYPES as $value => $label)
                <option value="{{ $value }}" @selected(request('service') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-secondary">Filter</button>
    </form>
    <div class="overflow-x-auto rounded-3xl bg-white ring-1 ring-slate-200">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="px-5 py-3 font-medium">Tanggal</th><th class="px-5 py-3 font-medium">Nama</th><th class="px-5 py-3 font-medium">Layanan</th><th class="px-5 py-3 font-medium">Anggaran</th><th class="px-5 py-3 font-medium">Sumber</th><th class="px-5 py-3 font-medium">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($leads as $lead)
                    <tr class="{{ $lead->status === 'new' ? 'bg-brand-50/40' : '' }}">
                        <td class="px-5 py-3 whitespace-nowrap text-slate-500">{{ $lead->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-3"><a href="{{ route('admin.leads.show', $lead) }}" class="font-medium hover:text-brand-700">{{ $lead->name }}</a>@if ($lead->organization)<span class="block text-xs text-slate-500">{{ $lead->organization }}</span>@endif</td>
                        <td class="px-5 py-3">{{ \App\Models\Lead::SERVICE_TYPES[$lead->service_type] ?? $lead->service_type }}</td>
                        <td class="px-5 py-3">{{ \App\Models\Lead::BUDGETS[$lead->budget] ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $lead->channel() }}</td>
                        <td class="px-5 py-3">{{ \App\Models\Lead::STATUSES[$lead->status] ?? $lead->status }} @if ($lead->is_qualified)<span class="ml-1 text-xs text-green-700">✓ berkualitas</span>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada lead.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $leads->links() }}</div>
</x-admin.layout>
