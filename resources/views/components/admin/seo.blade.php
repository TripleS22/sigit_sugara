{{-- Shared SEO fieldset (PRD §11): every content type stores metadata the same way. --}}
@props(['model', 'previewUrl' => null])
<x-admin.panel title="SEO" description="Kosongkan untuk memakai judul dan ringkasan konten. Judul ideal ±60 karakter, deskripsi ±155 karakter.">
    @if ($previewUrl)
        <div class="rounded-2xl bg-slate-50 p-4 text-sm">
            <p class="truncate text-xs text-green-700">{{ $previewUrl }}</p>
            <p class="truncate text-base text-blue-800">{{ $model->meta_title ?: ($model->title ?? $model->name) }}</p>
            <p class="line-clamp-2 text-slate-600">{{ $model->meta_description ?: 'Deskripsi diambil dari ringkasan konten.' }}</p>
        </div>
    @endif
    <x-admin.input name="meta_title" label="Meta title" :value="$model->meta_title" maxlength="70" counter />
    <x-admin.textarea name="meta_description" label="Meta description" :value="$model->meta_description" rows="2" maxlength="170" counter
        help="Google tidak selalu menampilkan teks ini; tulis ringkasan yang akurat." />
    <x-admin.input name="canonical_url" label="Canonical URL" type="url" :value="$model->canonical_url" help="Isi hanya jika versi utama konten ini ada di URL lain." />
    <details class="rounded-2xl bg-slate-50 p-4">
        <summary class="cursor-pointer text-sm font-medium text-slate-700">Open Graph & robots</summary>
        <div class="mt-4 space-y-4">
            <x-admin.input name="og_title" label="Judul saat dibagikan (og:title)" :value="$model->og_title" maxlength="95" />
            <x-admin.textarea name="og_description" label="Deskripsi saat dibagikan" :value="$model->og_description" rows="2" maxlength="200" />
            <x-admin.image name="og_image_file" label="Gambar saat dibagikan (1200×630)" :current="$model->og_image" />
            <x-admin.checkbox name="robots_index" label="Izinkan diindeks mesin pencari" :checked="$model->robots_index ?? true" />
            <x-admin.checkbox name="robots_follow" label="Izinkan mengikuti tautan (follow)" :checked="$model->robots_follow ?? true" />
        </div>
    </details>
</x-admin.panel>
