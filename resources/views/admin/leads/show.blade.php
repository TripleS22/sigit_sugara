<x-admin.layout :title="'Lead '.$lead->name">
    <x-admin.header :title="$lead->name" :description="'Masuk '.$lead->created_at->translatedFormat('j F Y H:i').' · '.$lead->created_at->diffForHumans()">
        <x-admin.delete :action="route('admin.leads.destroy', $lead)" label="Hapus lead" confirm="Hapus lead ini secara permanen?" />
    </x-admin.header>
    <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
        <x-admin.panel title="Data permintaan">
            <dl class="grid gap-4 text-sm sm:grid-cols-2">
                <div><dt class="text-slate-500">WhatsApp</dt><dd class="font-medium"><a href="{{ $lead->whatsappLink() }}" target="_blank" rel="noopener" class="text-brand-700 underline">{{ $lead->whatsapp }}</a></dd></div>
                <div><dt class="text-slate-500">Email</dt><dd class="font-medium">@if ($lead->email)<a href="mailto:{{ $lead->email }}" class="text-brand-700 underline">{{ $lead->email }}</a>@else — @endif</dd></div>
                <div><dt class="text-slate-500">Bisnis / institusi</dt><dd class="font-medium">{{ $lead->organization ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Layanan</dt><dd class="font-medium">{{ \App\Models\Lead::SERVICE_TYPES[$lead->service_type] ?? $lead->service_type }}</dd></div>
                <div><dt class="text-slate-500">Anggaran</dt><dd class="font-medium">{{ \App\Models\Lead::BUDGETS[$lead->budget] ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Paket</dt><dd class="font-medium">{{ $lead->package_slug ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Halaman asal</dt><dd class="font-medium break-all">{{ $lead->source_page ?: '—' }}</dd></div>
                <div><dt class="text-slate-500">Sumber / kampanye</dt><dd class="font-medium">{{ $lead->channel() }}{{ $lead->utm_medium ? ' / '.$lead->utm_medium : '' }}{{ $lead->utm_campaign ? ' / '.$lead->utm_campaign : '' }}</dd></div>
                <div><dt class="text-slate-500">Persetujuan privasi</dt><dd class="font-medium">{{ $lead->consent_at->format('d M Y H:i') }}</dd></div>
            </dl>
            <div>
                <p class="text-sm text-slate-500">Kebutuhan</p>
                <div class="mt-2 rounded-2xl bg-slate-50 p-4 text-sm whitespace-pre-line">{{ $lead->message }}</div>
            </div>
        </x-admin.panel>
        <form method="POST" action="{{ route('admin.leads.update', $lead) }}">
            @csrf
            @method('PUT')
            <x-admin.panel title="Tindak lanjut">
                <x-admin.select name="status" label="Status" :options="\App\Models\Lead::STATUSES" :value="$lead->status" />
                <x-admin.checkbox name="is_qualified" label="Lead berkualitas" :checked="$lead->is_qualified" help="Kebutuhan dan anggaran sesuai layanan." />
                <x-admin.input name="deal_value" label="Nilai proyek (Rp)" type="number" min="0" :value="$lead->deal_value" help="Isi saat proyek terjual untuk laporan pendapatan per kanal." />
                <x-admin.textarea name="notes" label="Catatan internal" :value="$lead->notes" rows="5" />
                <button class="btn-primary w-full">Simpan</button>
            </x-admin.panel>
        </form>
    </div>
    <a href="{{ route('admin.leads.index') }}" class="mt-6 inline-block text-sm text-brand-700">← Kembali ke daftar lead</a>
</x-admin.layout>
