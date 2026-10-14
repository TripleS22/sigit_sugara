{{-- Save bar + delete form for edit screens. The delete form lives outside the main form. --}}
@props(['cancel', 'view' => null])
<div class="sticky bottom-0 z-10 -mx-4 mt-8 flex flex-wrap items-center gap-3 border-t border-slate-200 bg-slate-100/95 px-4 py-4 backdrop-blur sm:-mx-8 sm:px-8">
    <button type="submit" class="btn-primary">Simpan</button>
    <a href="{{ $cancel }}" class="btn-secondary">Kembali</a>
    @if ($view)
        <a href="{{ $view }}" target="_blank" class="btn text-brand-700 hover:bg-white">Lihat di website ↗</a>
    @endif
</div>
