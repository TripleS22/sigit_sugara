<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesContent;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    use ManagesContent;

    public function index(): View
    {
        return view('admin.media.index', ['media' => Media::query()->latest()->paginate(24)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'max:10'],
            'files.*' => $this->imageRules(),
            'alt' => ['required', 'string', 'max:190'],
        ], ['alt.required' => 'Isi teks alternatif (alt) agar gambar dapat dipahami pembaca layar dan mesin pencari.']);

        foreach ($request->file('files') as $file) {
            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

            Media::create([
                'path' => $file->store('uploads/media', 'public'),
                'original_name' => mb_substr($file->getClientOriginalName(), 0, 190),
                'alt' => $request->string('alt'),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'user_id' => $request->user()->id,
            ]);
        }

        return back()->with('status', 'Gambar diunggah. Salin kode Markdown untuk dipakai di artikel atau halaman.');
    }

    public function update(Request $request, Media $media): RedirectResponse
    {
        $media->update($request->validate(['alt' => ['required', 'string', 'max:190']]));

        return back()->with('status', 'Teks alternatif disimpan.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        $media->delete();

        return back()->with('status', 'Gambar dihapus. Pastikan gambar ini tidak lagi dipakai di konten.');
    }
}
