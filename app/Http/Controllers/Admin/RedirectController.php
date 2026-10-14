<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function index(Request $request): View
    {
        $redirects = Redirect::query()
            ->when($request->filled('q'), fn ($q) => $q->where('from_path', 'like', '%'.$request->string('q').'%')->orWhere('to_path', 'like', '%'.$request->string('q').'%'))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create(): View
    {
        return view('admin.redirects.form', ['redirect' => new Redirect(['status_code' => 301])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Redirect::create($this->validated($request));

        return redirect()->route('admin.redirects.index')->with('status', 'Redirect dibuat.');
    }

    public function edit(Redirect $redirect): View
    {
        return view('admin.redirects.form', compact('redirect'));
    }

    public function update(Request $request, Redirect $redirect): RedirectResponse
    {
        $redirect->update($this->validated($request, $redirect));

        return redirect()->route('admin.redirects.index')->with('status', 'Redirect disimpan.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return redirect()->route('admin.redirects.index')->with('status', 'Redirect dihapus.');
    }

    private function validated(Request $request, ?Redirect $redirect = null): array
    {
        $request->merge(['from_path' => Redirect::normalize((string) $request->input('from_path', ''))]);

        return $request->validate([
            'from_path' => ['required', 'string', 'max:255', 'not_in:/', 'not_regex:#^/admin#', Rule::unique('redirects', 'from_path')->ignore($redirect?->id)],
            'to_path' => ['required', 'string', 'max:255', 'different:from_path', 'regex:#^(/|https?://)#'],
            'status_code' => ['required', Rule::in([301, 302])],
        ], ['to_path.regex' => 'Tujuan harus diawali "/" atau URL lengkap https://.']);
    }
}
