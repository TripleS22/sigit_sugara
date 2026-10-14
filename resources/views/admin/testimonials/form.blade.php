<x-admin.layout :title="$testimonial->exists ? 'Edit testimoni' : 'Testimoni baru'">
    <x-admin.header :title="$testimonial->exists ? 'Edit testimoni' : 'Testimoni baru'">
        @if ($testimonial->exists)
            <x-admin.delete :action="route('admin.testimonials.destroy', $testimonial)" />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}" class="max-w-3xl">
        @csrf
        @if ($testimonial->exists) @method('PUT') @endif
        <x-admin.panel>
            <div class="grid gap-5 sm:grid-cols-3">
                <x-admin.input name="name" label="Nama" :value="$testimonial->name" required />
                <x-admin.input name="role" label="Jabatan" :value="$testimonial->role" />
                <x-admin.input name="company" label="Perusahaan" :value="$testimonial->company" />
            </div>
            <x-admin.textarea name="quote" label="Testimoni" :value="$testimonial->quote" rows="5" required help="Tulis sesuai ucapan klien, tanpa dilebih-lebihkan." />
            <x-admin.select name="portfolio_id" label="Proyek terkait" :options="$portfolios->pluck('title', 'id')" :value="$testimonial->portfolio_id" placeholder="— Tidak ada —" />
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$testimonial->sort_order" />
            <x-admin.checkbox name="is_published" label="Terbitkan" :checked="$testimonial->is_published" />
            <x-admin.checkbox name="confirm_real" label="Saya konfirmasi testimoni ini berasal dari klien nyata dan klien mengizinkan publikasi." :checked="$testimonial->is_published" />
        </x-admin.panel>
        <x-admin.actions :cancel="route('admin.testimonials.index')" />
    </form>
</x-admin.layout>
