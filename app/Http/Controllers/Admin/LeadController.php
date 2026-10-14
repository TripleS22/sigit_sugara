<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $leads = $this->filtered($request)->latest()->paginate(25)->withQueryString();

        $counts = Lead::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.leads.index', compact('leads', 'counts'));
    }

    public function show(Lead $lead): View
    {
        return view('admin.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $request->merge(['is_qualified' => $request->boolean('is_qualified')]);

        $lead->update($request->validate([
            'status' => ['required', Rule::in(array_keys(Lead::STATUSES))],
            'is_qualified' => ['boolean'],
            'deal_value' => ['nullable', 'integer', 'min:0', 'max:9999999999'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]));

        return back()->with('status', 'Lead diperbarui.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead dihapus.');
    }

    public function export(Request $request): StreamedResponse
    {
        $leads = $this->filtered($request)->latest()->get();

        return response()->streamDownload(function () use ($leads) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows Indonesian text correctly
            fputcsv($out, ['Tanggal', 'Nama', 'Bisnis/Institusi', 'Email', 'WhatsApp', 'Layanan', 'Anggaran', 'Paket', 'Status', 'Berkualitas', 'Nilai proyek', 'Sumber', 'Kampanye', 'Halaman', 'Kebutuhan']);
            foreach ($leads as $lead) {
                fputcsv($out, array_map([$this, 'csvSafe'], [
                    $lead->created_at->format('Y-m-d H:i'),
                    $lead->name,
                    $lead->organization,
                    $lead->email,
                    $lead->whatsapp,
                    Lead::SERVICE_TYPES[$lead->service_type] ?? $lead->service_type,
                    Lead::BUDGETS[$lead->budget] ?? $lead->budget,
                    $lead->package_slug,
                    Lead::STATUSES[$lead->status] ?? $lead->status,
                    $lead->is_qualified ? 'Ya' : 'Tidak',
                    $lead->deal_value,
                    $lead->channel(),
                    $lead->utm_campaign,
                    $lead->source_page,
                    $lead->message,
                ]));
            }
            fclose($out);
        }, 'leads-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Prevent spreadsheet formula injection from visitor-supplied text. */
    public function csvSafe(mixed $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;
    }

    private function filtered(Request $request)
    {
        return Lead::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')), fn ($q) => $q->notSpam())
            ->when($request->filled('service'), fn ($q) => $q->where('service_type', $request->string('service')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->string('q').'%';
                $q->where(fn ($q) => $q->where('name', 'like', $term)
                    ->orWhere('organization', 'like', $term)
                    ->orWhere('whatsapp', 'like', $term)
                    ->orWhere('email', 'like', $term));
            });
    }
}
