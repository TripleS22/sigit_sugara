<x-admin.layout :title="$faq->exists ? 'Edit FAQ' : 'FAQ baru'">
    <x-admin.header :title="$faq->exists ? 'Edit FAQ' : 'FAQ baru'">
        @if ($faq->exists)
            <x-admin.delete :action="route('admin.faqs.destroy', $faq)" />
        @endif
    </x-admin.header>
    <form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="max-w-3xl">
        @csrf
        @if ($faq->exists) @method('PUT') @endif
        <x-admin.panel>
            <x-admin.input name="question" label="Pertanyaan" :value="$faq->question" required />
            <x-admin.textarea name="answer" label="Jawaban" :value="$faq->answer" rows="6" required help="Jawab jujur dan spesifik. Teks biasa; baris baru dipertahankan." />
            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.select name="group" label="Kelompok" :options="\App\Models\Faq::GROUPS" :value="$faq->group" />
                <x-admin.select name="service_id" label="Khusus layanan" :options="$services->pluck('title', 'id')" :value="$faq->service_id" placeholder="— Umum —" />
            </div>
            <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$faq->sort_order" />
            <x-admin.checkbox name="is_published" label="Tampilkan" :checked="$faq->is_published" />
            <x-admin.checkbox name="show_on_home" label="Tampilkan di beranda" :checked="$faq->show_on_home" />
        </x-admin.panel>
        <x-admin.actions :cancel="route('admin.faqs.index')" />
    </form>
</x-admin.layout>
