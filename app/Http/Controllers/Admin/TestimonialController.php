<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::query()->with('portfolio')->orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial, 'portfolios' => Portfolio::query()->ordered()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        Testimonial::create($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni dibuat.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', ['testimonial' => $testimonial, 'portfolios' => Portfolio::query()->ordered()->get()]);
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni disimpan.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimoni dihapus.');
    }

    private function validated(Request $request): array
    {
        $request->merge(['is_published' => $request->boolean('is_published')]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:150'],
            'quote' => ['required', 'string', 'max:1500'],
            'portfolio_id' => ['nullable', Rule::exists('portfolios', 'id')],
            'is_published' => ['boolean'],
            'confirm_real' => $request->boolean('is_published') ? ['accepted'] : ['nullable'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], ['confirm_real.*' => 'Konfirmasi bahwa testimoni ini berasal dari klien nyata dan sudah mendapat izin sebelum dipublikasikan.']);

        unset($data['confirm_real']);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
