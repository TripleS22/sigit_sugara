<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $business = $this->service([
            'segment' => 'business',
            'title' => 'Jasa Pembuatan Website',
            'slug' => 'jasa-pembuatan-website',
            'menu_label' => 'Jasa Pembuatan Website',
            'headline' => 'Jasa Pembuatan Website Profesional untuk Bisnis',
            'subheadline' => 'Website company profile, website UMKM, dan aplikasi web Laravel dengan CMS, struktur SEO yang benar, dan harga awal yang transparan.',
            'summary' => 'Website bisnis yang mudah dikelola, cepat dibuka di ponsel, dan dirancang untuk mendatangkan konsultasi.',
            'icon' => 'briefcase',
            'timeline' => '7–30 hari kerja tergantung paket dan kelengkapan materi',
            'highlights' => [
                'CMS di setiap paket: kelola teks, gambar, artikel, dan harga sendiri',
                'Desain mobile-first dan ringan dibuka',
                'Fondasi SEO: URL rapi, metadata, sitemap, dan schema',
                'Tombol WhatsApp dan formulir penawaran terhubung ke dashboard leads',
                'Source code dan akses admin diserahkan setelah pelunasan',
                'Harga awal dan ruang lingkup tertulis sebelum proyek dimulai',
            ],
            'body' => <<<'MD'
## Mengapa bisnis membutuhkan website?

Calon pelanggan hampir selalu mencari informasi sebelum menghubungi penyedia jasa atau membeli produk. Website memberi mereka tempat untuk memahami layanan Anda, melihat bukti hasil kerja, dan menghubungi Anda tanpa harus menunggu balasan di media sosial.

Website yang baik bukan sekadar tampilan menarik. Website perlu:

- **Menjelaskan layanan dengan jelas**: apa yang Anda tawarkan, untuk siapa, dan berapa kisaran biayanya.
- **Membangun kepercayaan**: portofolio, profil bisnis, dan informasi kontak yang konsisten.
- **Mudah ditemukan**: struktur halaman dan konten yang sesuai dengan apa yang dicari calon pelanggan di Google.
- **Mengarahkan ke tindakan**: tombol konsultasi WhatsApp dan formulir penawaran di tempat yang tepat.

## Jenis website yang kami kerjakan

| Jenis website | Cocok untuk |
|---|---|
| Company profile | Perusahaan, jasa profesional, organisasi |
| Website UMKM | Usaha kecil yang ingin tampil profesional dengan biaya terjangkau |
| Website Laravel / custom | Bisnis yang butuh sistem, dashboard, atau integrasi khusus |
| Landing page | Kampanye iklan atau peluncuran produk |

## Fitur yang tersedia

Fitur disesuaikan dengan paket: CMS, halaman layanan, portofolio, blog, formulir permintaan penawaran, integrasi WhatsApp, statistik pengunjung, chatbot AI (paket tertentu), multi-bahasa (add-on), dan integrasi API sesuai ruang lingkup.

## Domain, hosting, dan maintenance

Domain dan hosting adalah biaya tahunan terpisah dari biaya pembuatan website, kecuali disebutkan lain dalam penawaran. Kami bantu memilihkan layanan yang sesuai kebutuhan dan anggaran. Maintenance bulanan bersifat opsional.

## Kepemilikan source code

Setelah pelunasan, source code dan akses admin website menjadi milik Anda. Anda bebas memindahkan website ke penyedia hosting lain.
MD,
            'meta_title' => 'Jasa Pembuatan Website Profesional untuk Bisnis & UMKM',
            'meta_description' => 'Jasa pembuatan website company profile, UMKM, dan Laravel dengan CMS, SEO dasar, dan harga awal transparan. Konsultasikan kebutuhan Anda via WhatsApp.',
            'sort_order' => 1,
        ]);

        $children = [
            [
                'title' => 'Jasa Pembuatan Website Company Profile',
                'slug' => 'jasa-pembuatan-website-company-profile',
                'menu_label' => 'Website Company Profile',
                'headline' => 'Jasa Pembuatan Website Company Profile',
                'subheadline' => 'Tampilkan profil, layanan, dan portofolio perusahaan secara profesional. Pilih paket sesuai kebutuhan, fitur, dan anggaran.',
                'summary' => 'Profil perusahaan, layanan, portofolio, dan formulir penawaran dalam satu website yang mudah dikelola.',
                'icon' => 'building',
                'timeline' => '7–21 hari kerja setelah materi lengkap',
                'highlights' => ['Halaman profil, layanan, portofolio, dan kontak', 'CMS untuk memperbarui konten sendiri', 'Formulir permintaan penawaran', 'SEO dasar di setiap halaman'],
                'body' => "## Apa saja yang ada di website company profile?\n\nWebsite company profile umumnya berisi halaman beranda, tentang perusahaan, layanan atau produk, portofolio atau studi kasus, artikel, dan kontak. Jumlah halaman dan fitur menyesuaikan paket yang dipilih.\n\n## Berapa biaya website company profile?\n\nBiaya dipengaruhi jumlah halaman, kebutuhan fitur seperti multi-bahasa atau integrasi, dan tingkat kustomisasi desain. Lihat perbandingan [paket harga](/paket) untuk kisaran harga awal, lalu konsultasikan kebutuhan Anda untuk penawaran final.\n\n## Materi yang perlu disiapkan\n\n- Logo dan identitas visual (jika ada)\n- Profil singkat perusahaan, visi, dan layanan\n- Foto produk, tim, atau proyek\n- Daftar klien atau portofolio yang boleh ditampilkan\n- Kontak resmi yang akan dicantumkan\n\nBelum punya semua materi? Kami bantu menyusun struktur konten bersama Anda.",
                'meta_title' => 'Jasa Pembuatan Website Company Profile Profesional',
                'meta_description' => 'Bangun website company profile untuk bisnis Anda. Pilih paket website sesuai kebutuhan, fitur, dan anggaran. Konsultasikan proyek melalui WhatsApp.',
            ],
            [
                'title' => 'Jasa Pembuatan Website UMKM',
                'slug' => 'jasa-pembuatan-website-umkm',
                'menu_label' => 'Website UMKM',
                'headline' => 'Website UMKM yang Profesional dan Sesuai Anggaran',
                'subheadline' => 'Mulai dari paket Starter dengan CMS, halaman katalog atau layanan, dan tombol WhatsApp untuk menerima pesanan.',
                'summary' => 'Website hemat biaya untuk usaha kecil: katalog, informasi usaha, dan tombol pesan via WhatsApp.',
                'icon' => 'store',
                'timeline' => '7–14 hari kerja setelah materi lengkap',
                'highlights' => ['Harga awal terjangkau untuk usaha kecil', 'Katalog produk atau layanan', 'Tombol WhatsApp dengan pesan otomatis', 'Bisa dikembangkan bertahap'],
                'body' => "## Website gratis atau website sendiri?\n\nPlatform gratis cocok untuk mencoba, tetapi biasanya terbatas dalam desain, domain, dan kepemilikan data. Website sendiri memberi Anda domain bisnis, tampilan yang konsisten dengan merek, dan kebebasan mengembangkan fitur.\n\n## Fitur yang paling sering dibutuhkan UMKM\n\n- Halaman profil usaha dan lokasi\n- Katalog produk atau daftar layanan beserta harga\n- Tombol WhatsApp untuk pemesanan\n- Integrasi Google Maps\n- Artikel atau promo yang bisa diperbarui sendiri lewat CMS\n\n## Mulai dari kebutuhan yang paling penting\n\nAnda tidak harus membangun semuanya sekaligus. Mulailah dari paket Starter, lalu tambahkan fitur saat usaha berkembang.",
                'meta_title' => 'Jasa Pembuatan Website UMKM Terjangkau dengan CMS',
                'meta_description' => 'Website untuk UMKM dengan CMS, katalog produk, dan tombol WhatsApp. Harga awal transparan dan bisa dikembangkan bertahap.',
            ],
            [
                'title' => 'Jasa Pembuatan Website Laravel',
                'slug' => 'jasa-pembuatan-website-laravel',
                'menu_label' => 'Website & Aplikasi Laravel',
                'headline' => 'Jasa Pembuatan Website dan Aplikasi Web Laravel',
                'subheadline' => 'Sistem informasi, dashboard, dan aplikasi web custom berbasis Laravel dengan ruang lingkup dan biaya yang jelas.',
                'summary' => 'Aplikasi web custom: dashboard, sistem informasi, integrasi API, dan otomatisasi proses bisnis.',
                'icon' => 'code',
                'timeline' => 'Sesuai ruang lingkup; estimasi diberikan setelah analisis kebutuhan',
                'highlights' => ['Analisis kebutuhan dan ruang lingkup tertulis', 'Autentikasi, peran pengguna, dan dashboard', 'Integrasi API pihak ketiga', 'Dokumentasi teknis dan serah terima source code'],
                'body' => "## Kapan bisnis membutuhkan Laravel?\n\nLaravel cocok saat website tidak hanya menampilkan informasi, tetapi juga memproses data: pendaftaran, pemesanan, inventaris, laporan, atau integrasi dengan sistem lain.\n\n## Contoh sistem yang dapat dibangun\n\n- Dashboard internal dan laporan\n- Sistem pemesanan atau reservasi\n- Portal pelanggan atau anggota\n- Integrasi pembayaran, ekspedisi, atau API lain sesuai kebutuhan\n\n## Cara kami bekerja\n\nProyek dimulai dengan analisis kebutuhan dan dokumen ruang lingkup. Pengembangan dilakukan bertahap dengan demo berkala, lalu diakhiri pengujian, dokumentasi, dan serah terima.",
                'meta_title' => 'Jasa Pembuatan Website Laravel & Aplikasi Web Custom',
                'meta_description' => 'Jasa programmer Laravel untuk sistem informasi, dashboard, dan aplikasi web custom. Ruang lingkup jelas, dokumentasi, dan serah terima source code.',
            ],
            [
                'title' => 'Jasa Pembuatan Website Perusahaan',
                'slug' => 'jasa-pembuatan-website-perusahaan',
                'menu_label' => 'Website Perusahaan',
                'headline' => 'Website Perusahaan dengan Fitur Lengkap dan Skala Besar',
                'subheadline' => 'Untuk perusahaan yang membutuhkan banyak halaman, multi-bahasa, integrasi, dan pengelolaan konten oleh tim.',
                'summary' => 'Website korporat 15–25+ halaman, multi-bahasa, integrasi, dan peran admin untuk tim.',
                'icon' => 'rocket',
                'timeline' => '3–8 minggu tergantung ruang lingkup',
                'highlights' => ['Struktur konten untuk banyak divisi atau layanan', 'Multi-bahasa sesuai kebutuhan', 'Peran pengguna CMS untuk tim', 'Integrasi API dan analytics lanjutan'],
                'body' => "## Kebutuhan website perusahaan\n\nWebsite perusahaan biasanya dikelola oleh beberapa orang, melayani beberapa segmen pelanggan, dan perlu terhubung dengan sistem lain. Karena itu, struktur informasi dan hak akses CMS perlu direncanakan sejak awal.\n\n## Yang kami siapkan\n\n- Arsitektur informasi dan peta halaman\n- Desain yang konsisten dengan identitas perusahaan\n- CMS dengan peran admin dan editor\n- Fondasi SEO teknis dan pengukuran konversi\n- Dokumentasi dan pelatihan penggunaan CMS",
                'meta_title' => 'Jasa Pembuatan Website Perusahaan Profesional',
                'meta_description' => 'Website perusahaan dengan banyak halaman, multi-bahasa, integrasi, dan CMS untuk tim. Lihat paket Enterprise atau konsultasikan kebutuhan Anda.',
            ],
        ];

        foreach ($children as $i => $child) {
            $this->service($child + ['segment' => 'business', 'parent_id' => $business->id, 'sort_order' => $i + 1]);
        }

        $student = $this->service([
            'segment' => 'student',
            'title' => 'Jasa Website Pelajar & Mahasiswa',
            'slug' => 'jasa-website-pelajar',
            'menu_label' => 'Layanan Pelajar & Mahasiswa',
            'headline' => 'Butuh Website untuk Proyek Kuliah atau Praktikum?',
            'subheadline' => 'Temukan layanan pengembangan website statis, aplikasi Laravel, database MySQL, dan prototipe sistem dengan ruang lingkup serta biaya yang transparan.',
            'summary' => 'Bantuan implementasi, prototipe, debugging, dan dokumentasi proyek web untuk pelajar dan mahasiswa.',
            'icon' => 'academic',
            'timeline' => '3–14 hari tergantung paket dan jumlah modul',
            'highlights' => [
                'Website statis, Laravel, dan MySQL',
                'Source code dan dokumentasi sesuai ruang lingkup',
                'Pilihan deployment demo',
                'Penjelasan kode agar Anda memahami proyeknya',
            ],
            'body' => <<<'MD'
## Layanan yang tersedia

Kami membantu implementasi teknis proyek web Anda: membangun prototipe, menyusun database, memperbaiki bug, dan menyiapkan dokumentasi. Tujuannya agar proyek berjalan dan Anda memahami cara kerjanya.

## Pilihan teknologi

- HTML, CSS, JavaScript, dan Tailwind CSS untuk website statis
- PHP Laravel dan MySQL untuk aplikasi dinamis
- Autentikasi, CRUD, dashboard, dan beberapa modul sesuai paket

## Cakupan dan batasan

- Jumlah modul dan revisi dibatasi sesuai paket.
- Pekerjaan tambahan memakai penawaran baru.
- Domain dan hosting untuk demo ditagihkan terpisah.
- Dokumentasi disesuaikan dengan ruang lingkup proyek.

## Etika akademik

Layanan ini adalah bantuan implementasi, pembelajaran, dan prototipe. Kami **tidak** mengerjakan ujian, tidak mewakili Anda dalam presentasi atau penilaian, dan tidak menjanjikan nilai atau kelulusan. Pastikan penggunaan bantuan ini sesuai aturan institusi Anda.
MD,
            'meta_title' => 'Jasa Website Pelajar & Mahasiswa: Laravel, MySQL, Prototipe',
            'meta_description' => 'Bantuan implementasi proyek web untuk pelajar dan mahasiswa: website statis, Laravel, MySQL, debugging, dan dokumentasi dengan biaya transparan.',
            'sort_order' => 2,
        ]);

        $studentChildren = [
            [
                'title' => 'Jasa Website Tugas Kuliah',
                'slug' => 'jasa-website-tugas-kuliah',
                'menu_label' => 'Website Tugas & Praktikum',
                'summary' => 'Website statis atau dinamis sederhana untuk tugas mata kuliah dan praktikum.',
                'icon' => 'academic',
                'body' => "## Untuk tugas dan praktikum\n\nCocok untuk tugas pemrograman web dasar: halaman statis, form, dan tampilan responsif. Anda menerima source code dan penjelasan struktur file.\n\n## Yang perlu Anda siapkan\n\n- Ketentuan tugas dari dosen atau guru\n- Teknologi yang diwajibkan\n- Tenggat waktu\n\nKami akan menjelaskan apakah kebutuhan Anda masuk paket Student Basic atau Student Dynamic.",
                'meta_title' => 'Jasa Website Tugas Kuliah & Praktikum Web',
                'meta_description' => 'Bantuan pembuatan website untuk tugas kuliah dan praktikum: HTML, CSS, JavaScript, hingga Laravel sederhana. Ruang lingkup dan biaya jelas.',
            ],
            [
                'title' => 'Jasa Website Laravel Mahasiswa',
                'slug' => 'jasa-website-laravel-mahasiswa',
                'menu_label' => 'Laravel untuk Mahasiswa',
                'summary' => 'Aplikasi Laravel dengan database, CRUD, autentikasi, dan dashboard untuk proyek mahasiswa.',
                'icon' => 'code',
                'body' => "## Aplikasi Laravel untuk proyek\n\nUntuk proyek yang membutuhkan database dan proses data: CRUD, login, peran pengguna, dan laporan sederhana.\n\n## Termasuk\n\n- Rancangan tabel database\n- Implementasi modul sesuai paket\n- Panduan instalasi dan penjelasan alur kode\n\n## Tidak termasuk\n\n- Mengerjakan ujian atau mewakili presentasi\n- Penulisan laporan akademik atas nama Anda",
                'meta_title' => 'Jasa Website Laravel untuk Mahasiswa',
                'meta_description' => 'Bantuan implementasi aplikasi Laravel untuk mahasiswa: database MySQL, CRUD, autentikasi, dan dashboard dengan dokumentasi.',
            ],
            [
                'title' => 'Jasa Proyek Web Mahasiswa',
                'slug' => 'jasa-proyek-web-mahasiswa',
                'menu_label' => 'Prototipe & Debugging',
                'summary' => 'Prototipe sistem untuk demonstrasi, perbaikan bug, dan pendampingan teknis proyek web.',
                'icon' => 'rocket',
                'body' => "## Prototipe dan pendampingan\n\nSudah mulai membangun proyek tetapi terhambat error? Kami bantu debugging, menjelaskan penyebab masalah, dan menyarankan perbaikan.\n\n## Prototipe untuk demonstrasi\n\nUntuk kebutuhan demo teknis, kami bangun prototipe dengan fitur inti dan opsi deployment agar dapat diakses secara online.",
                'meta_title' => 'Jasa Proyek Web Mahasiswa: Prototipe & Debugging',
                'meta_description' => 'Bantuan prototipe sistem, debugging, dan pendampingan teknis proyek web mahasiswa dengan ruang lingkup dan biaya transparan.',
            ],
        ];

        foreach ($studentChildren as $i => $child) {
            $this->service($child + ['segment' => 'student', 'parent_id' => $student->id, 'sort_order' => $i + 1]);
        }
    }

    private function service(array $data): Service
    {
        return Service::updateOrCreate(['slug' => $data['slug']], $data + ['is_published' => true, 'show_in_menu' => true]);
    }
}
