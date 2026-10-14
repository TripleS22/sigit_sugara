<x-admin.layout :title="$package->exists ? 'Edit paket' : 'Paket baru'">
    <x-admin.header :title="$package->exists ? 'Edit paket: '.$package->name : 'Paket baru'">
        @if ($package->exists)
            <x-admin.delete :action="route('admin.packages.destroy', $package)" label="Hapus paket" confirm="Hapus paket ini? Untuk menyembunyikan sementara, cukup nonaktifkan." />
        @endif
    </x-admin.header>
    @php
        $features = old('features', $package->exists ? $package->features->map(fn ($f) => ['label' => $f->label, 'value' => $f->value, 'is_included' => $f->is_included])->all() : []);
        $features = array_pad($features, count($features) + 3, ['label' => '', 'value' => '', 'is_included' => true]);
    @endphp
    <form method="POST" action="{{ $package->exists ? route('admin.packages.update', $package) : route('admin.packages.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($package->exists) @method('PUT') @endif
        <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
            <div class="space-y-6">
                <x-admin.panel>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.input name="name" label="Nama paket" :value="$package->name" required data-slug-source="#f-slug" />
                        <x-admin.input name="slug" label="Slug" :value="$package->slug" required help="URL: /paket/slug" />
                    </div>
                    <x-admin.input name="tagline" label="Tagline" :value="$package->tagline" help="Satu kalimat manfaat utama paket." />
                    <x-admin.input name="target_audience" label="Target pengguna" :value="$package->target_audience" help="Mis. UMKM yang baru memulai website." />
                    <div class="grid gap-5 sm:grid-cols-3">
                        <x-admin.input name="price_from" label="Harga awal (Rp)" type="number" min="0" :value="$package->price_from" required />
                        <x-admin.input name="price_to" label="Harga hingga (Rp)" type="number" min="0" :value="$package->price_to" help="Kosongkan bila satu harga." />
                        <x-admin.input name="price_prefix" label="Awalan harga" :value="$package->price_prefix" help="Mis. Mulai" />
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-admin.input name="max_pages" label="Maks. halaman" :value="$package->max_pages" help="Mis. 5, 10, 25+" />
                        <x-admin.input name="delivery_estimate" label="Estimasi pengerjaan" :value="$package->delivery_estimate" />
                    </div>
                    <x-admin.input name="hosting_note" label="Catatan domain & hosting" :value="$package->hosting_note" help="Mis. Domain & hosting ditagihkan terpisah." />
                    <x-admin.textarea name="description" label="Deskripsi" :value="$package->description" rows="5" />
                </x-admin.panel>

                <x-admin.panel title="Fitur paket" description="Centang 'Termasuk' untuk fitur yang ada. Fitur tidak dicentang tampil sebagai 'Tidak termasuk'. Baris kosong diabaikan.">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" data-repeater>
                            <thead class="text-left text-slate-500"><tr><th class="pb-2 font-medium">Fitur</th><th class="pb-2 font-medium">Nilai (opsional)</th><th class="pb-2 font-medium">Termasuk</th></tr></thead>
                            <tbody>
                                @foreach ($features as $i => $feature)
                                    <tr>
                                        <td class="pr-2 pb-2"><input name="features[{{ $i }}][label]" value="{{ $feature['label'] }}" class="form-input" placeholder="mis. Statistik"></td>
                                        <td class="pr-2 pb-2"><input name="features[{{ $i }}][value]" value="{{ $feature['value'] }}" class="form-input" placeholder="mis. Dashboard"></td>
                                        <td class="pb-2 text-center"><input type="hidden" name="features[{{ $i }}][is_included]" value="0"><input type="checkbox" name="features[{{ $i }}][is_included]" value="1" class="size-4 rounded border-slate-300" @checked($feature['is_included'] ?? false)></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn-secondary !py-2" data-repeater-add>Tambah baris</button>
                </x-admin.panel>

                <x-admin.seo :model="$package" :preview-url="$package->exists ? $package->url() : null" />
            </div>
            <div class="space-y-6">
                <x-admin.panel title="Tampilan">
                    <x-admin.checkbox name="is_active" label="Aktif (tampil di website)" :checked="$package->is_active" />
                    <x-admin.checkbox name="is_featured" label="Sorot sebagai paket unggulan" :checked="$package->is_featured" />
                    <x-admin.input name="badge" label="Label" :value="$package->badge" help="Mis. Paling populer" />
                    <x-admin.select name="segment" label="Segmen" :options="\App\Models\Service::SEGMENTS" :value="$package->segment" />
                    <x-admin.input name="sort_order" label="Urutan" type="number" min="0" :value="$package->sort_order" />
                </x-admin.panel>
            </div>
        </div>
        <x-admin.actions :cancel="route('admin.packages.index')" :view="$package->exists ? $package->url() : null" />
    </form>
</x-admin.layout>
