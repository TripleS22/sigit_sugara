<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::query()->with('service')->orderBy('group')->orderBy('sort_order')->get()->groupBy('group');

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.form', ['faq' => new Faq(['group' => 'umum', 'is_published' => true]), 'services' => Service::query()->ordered()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ dibuat.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', ['faq' => $faq, 'services' => Service::query()->ordered()->get()]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validated($request));

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ disimpan.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('status', 'FAQ dihapus.');
    }

    private function validated(Request $request): array
    {
        $request->merge(['is_published' => $request->boolean('is_published'), 'show_on_home' => $request->boolean('show_on_home')]);

        $data = $request->validate([
            'question' => ['required', 'string', 'max:190'],
            'answer' => ['required', 'string', 'max:3000'],
            'group' => ['required', Rule::in(array_keys(Faq::GROUPS))],
            'service_id' => ['nullable', Rule::exists('services', 'id')],
            'is_published' => ['boolean'],
            'show_on_home' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
