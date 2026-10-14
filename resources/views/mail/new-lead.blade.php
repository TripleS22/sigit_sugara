Lead baru masuk dari website.

Nama: {!! $lead->name !!}
Bisnis/institusi: {!! $lead->organization ?: '-' !!}
WhatsApp: {!! $lead->whatsapp !!}
Email: {!! $lead->email ?: '-' !!}
Layanan: {!! \App\Models\Lead::SERVICE_TYPES[$lead->service_type] ?? $lead->service_type !!}
Anggaran: {!! \App\Models\Lead::BUDGETS[$lead->budget] ?? '-' !!}
Paket: {!! $lead->package_slug ?: '-' !!}
Sumber: {!! $lead->channel() }} {{ $lead->utm_campaign ? '('.$lead->utm_campaign.')' : '' !!}

Kebutuhan:
{!! $lead->message !!}

Kelola lead: {!! route('admin.leads.show', $lead) !!}
