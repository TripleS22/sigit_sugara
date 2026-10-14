<x-admin.layout title="Pengaturan">
    <x-admin.header title="Pengaturan website" description="Informasi bisnis, konten beranda, kontak, analytics, dan SEO global." />
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid gap-6 xl:grid-cols-2">
            <x-admin.panel title="Identitas">
                <x-admin.input name="site_name" label="Nama bisnis" :value="$values['site_name']" required />
                <x-admin.input name="tagline" label="Tagline" :value="$values['tagline']" required />
                <x-admin.image name="logo_file" label="Logo" :current="$values['logo'] ?: null" help="PNG/WebP transparan, tinggi ±64 px." />
                <x-admin.textarea name="footer_note" label="Catatan footer" :value="$values['footer_note']" rows="2" />
            </x-admin.panel>

            <x-admin.panel title="Kontak" description="Isi hanya informasi yang benar. Bagian yang kosong tidak ditampilkan di website.">
                <x-admin.input name="whatsapp_number" label="Nomor WhatsApp" :value="$values['whatsapp_number']" placeholder="6281234567890" help="Format internasional tanpa +. Selama kosong, tombol WhatsApp diarahkan ke formulir kontak." />
                <x-admin.textarea name="whatsapp_default_message" label="Pesan awal WhatsApp bawaan" :value="$values['whatsapp_default_message']" rows="2" required help="Halaman layanan dan paket memakai pesan khusus masing-masing." />
                <x-admin.input name="contact_email" label="Email kontak" type="email" :value="$values['contact_email']" />
                <x-admin.input name="lead_notification_email" label="Kirim notifikasi lead ke email" type="email" :value="$values['lead_notification_email']" help="Butuh konfigurasi MAIL_* di file .env." />
                <x-admin.textarea name="address" label="Alamat" :value="$values['address']" rows="2" help="Isi hanya bila ada alamat usaha nyata. Jangan mencantumkan kantor fiktif." />
                <x-admin.input name="service_area" label="Area layanan" :value="$values['service_area']" />
                <x-admin.input name="business_hours" label="Jam kerja" :value="$values['business_hours']" />
                <x-admin.input name="instagram_url" label="Instagram" type="url" :value="$values['instagram_url']" />
                <x-admin.input name="linkedin_url" label="LinkedIn" type="url" :value="$values['linkedin_url']" />
                <x-admin.input name="facebook_url" label="Facebook" type="url" :value="$values['facebook_url']" />
            </x-admin.panel>

            <x-admin.panel title="Beranda" description="Hindari klaim yang tidak dapat dibuktikan, seperti 'pasti nomor satu di Google' atau 'dijamin meningkatkan omzet'.">
                <x-admin.input name="hero_headline" label="Headline" :value="$values['hero_headline']" required />
                <x-admin.textarea name="hero_subheadline" label="Subheadline" :value="$values['hero_subheadline']" rows="3" required />
                <x-admin.textarea name="hero_rotating_words" label="Teks berganti (animasi)" :value="$values['hero_rotating_words']" rows="4" help="Satu teks per baris." />
                <x-admin.textarea name="home_value_props" label="Keunggulan" :value="$values['home_value_props']" rows="5" mono help="Satu per baris: Judul | Deskripsi" />
                <x-admin.textarea name="process_steps" label="Proses pengerjaan" :value="$values['process_steps']" rows="6" mono help="Satu langkah per baris: Judul | Deskripsi" />
                <x-admin.input name="tech_stack" label="Teknologi" :value="$values['tech_stack']" help="Pisahkan dengan koma." />
            </x-admin.panel>

            <x-admin.panel title="SEO & analytics">
                <x-admin.textarea name="default_meta_description" label="Meta description beranda" :value="$values['default_meta_description']" rows="2" maxlength="170" counter required />
                <x-admin.image name="default_og_image_file" label="Gambar bagikan bawaan (1200×630)" :current="$values['default_og_image'] ?: null" />
                <x-admin.checkbox name="allow_indexing" label="Izinkan mesin pencari mengindeks website" :checked="$values['allow_indexing'] === '1'" help="Matikan saat staging atau sebelum peluncuran. robots.txt dan meta robots ikut menyesuaikan." />
                <x-admin.input name="google_site_verification" label="Kode verifikasi Google Search Console" :value="$values['google_site_verification']" help="Nilai content dari meta tag google-site-verification." />
                <x-admin.input name="ga4_measurement_id" label="Google Analytics 4 Measurement ID" :value="$values['ga4_measurement_id']" placeholder="G-XXXXXXXXXX" />
                <x-admin.checkbox name="analytics_require_consent" label="Minta persetujuan pengunjung sebelum memuat GA4" :checked="$values['analytics_require_consent'] === '1'" />
                <p class="rounded-2xl bg-slate-50 p-4 text-xs text-slate-600">Sitemap: <a href="{{ route('sitemap') }}" class="underline" target="_blank">{{ route('sitemap') }}</a> — kirimkan URL ini di Google Search Console.</p>
            </x-admin.panel>
        </div>
        <x-admin.actions :cancel="route('admin.dashboard')" />
    </form>
</x-admin.layout>
