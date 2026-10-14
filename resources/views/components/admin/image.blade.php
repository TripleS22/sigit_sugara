@props(['name', 'label', 'current' => null, 'altName' => null, 'altValue' => null, 'help' => 'JPG, PNG, atau WebP, maks. 2 MB. Gunakan lebar ±1200 px agar halaman tetap ringan.'])
<div class="space-y-3">
    <div>
        <label for="f-{{ $name }}" class="form-label">{{ $label }}</label>
        @if ($current)
            <div class="mb-3 flex items-center gap-4">
                <img src="{{ asset('storage/'.$current) }}" alt="" class="h-20 w-32 rounded-xl object-cover ring-1 ring-slate-200">
                <label class="flex items-center gap-2 text-sm text-red-700"><input type="checkbox" name="remove_{{ $name }}" value="1" class="rounded border-slate-300"> Hapus gambar</label>
            </div>
        @endif
        <input id="f-{{ $name }}" name="{{ $name }}" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700">
        <p class="mt-1 text-xs text-slate-500">{{ $help }}</p>
        @error($name)<p class="form-error">{{ $message }}</p>@enderror
    </div>
    @if ($altName)
        <x-admin.input :name="$altName" label="Teks alternatif (alt)" :value="$altValue" help="Jelaskan isi gambar untuk pembaca layar dan mesin pencari." />
    @endif
</div>
