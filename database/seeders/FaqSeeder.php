<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['harga', 'Apakah harga paket sudah final?', 'Harga yang tercantum adalah harga awal untuk ruang lingkup standar. Setelah konsultasi, Anda menerima penawaran tertulis berisi fitur, jumlah halaman, estimasi waktu, dan biaya final sebelum proyek dimulai.', true],
            ['harga', 'Bagaimana sistem pembayarannya?', 'Umumnya pembayaran dilakukan bertahap: uang muka sebelum pengerjaan dan pelunasan sebelum serah terima. Rincian termin dicantumkan dalam penawaran.', false],
            ['harga', 'Apakah ada biaya tambahan setelah website jadi?', 'Biaya berulang yang umum adalah perpanjangan domain dan hosting setiap tahun. Maintenance bulanan dan penambahan fitur bersifat opsional dan dihitung terpisah.', true],
            ['teknis', 'Apakah domain dan hosting sudah termasuk?', 'Belum, kecuali disebutkan lain dalam penawaran. Domain dan hosting adalah biaya tahunan yang dibayarkan terpisah. Kami bantu merekomendasikan dan menyiapkannya sesuai kebutuhan website Anda.', true],
            ['teknis', 'Apa perbedaan domain, hosting, dan maintenance?', 'Domain adalah alamat website (misalnya namabisnis.com). Hosting adalah server tempat file website disimpan. Maintenance adalah layanan perawatan: pembaruan, backup, pemantauan keamanan, dan perbaikan kecil.', false],
            ['teknis', 'Siapa pemilik source code website?', 'Setelah pelunasan, source code dan akses admin website diserahkan kepada Anda. Anda bebas memindahkan website ke penyedia hosting lain.', true],
            ['teknis', 'Bisakah saya mengubah konten sendiri?', 'Bisa. Setiap paket dilengkapi CMS untuk mengubah teks, gambar, artikel, portofolio, dan informasi kontak tanpa menyentuh kode. Kami juga memberikan panduan penggunaan.', true],
            ['umum', 'Berapa lama proses pembuatan website?', 'Tergantung paket dan kelengkapan materi. Paket Starter umumnya 7–10 hari kerja setelah materi lengkap, sedangkan proyek kustom mengikuti ruang lingkup yang disepakati.', false],
            ['umum', 'Apakah website dijamin muncul di halaman pertama Google?', 'Tidak ada pihak yang dapat menjamin posisi tertentu di Google. Kami menyiapkan fondasi SEO teknis, struktur konten, dan kecepatan halaman agar website siap bersaing; hasil peringkat juga dipengaruhi konten, persaingan, dan waktu.', true],
            ['umum', 'Berapa kali revisi yang didapat?', 'Kuota revisi tercantum di setiap paket dan penawaran. Revisi di luar ruang lingkup awal dihitung sebagai pekerjaan tambahan dengan penawaran baru.', false],
            ['pelajar', 'Apakah layanan pelajar termasuk mengerjakan ujian?', 'Tidak. Layanan ini berupa bantuan implementasi, prototipe, debugging, dan dokumentasi teknis. Kami tidak mengerjakan ujian, tidak mewakili presentasi, dan tidak menjanjikan nilai atau kelulusan.', false],
            ['pelajar', 'Apakah saya mendapatkan source code dan penjelasannya?', 'Ya. Anda menerima source code dan dokumentasi sesuai ruang lingkup paket, termasuk penjelasan struktur kode agar Anda memahami cara kerja proyek.', false],
            ['pelajar', 'Bagaimana jika ada tambahan fitur setelah proyek selesai?', 'Jumlah modul dan revisi dibatasi sesuai paket. Fitur tambahan di luar ruang lingkup memakai penawaran baru.', false],
            ['pelajar', 'Apakah bisa di-deploy agar bisa diakses online?', 'Bisa sebagai opsi. Biaya domain dan hosting untuk demo ditagihkan terpisah.', false],
        ];

        foreach ($faqs as $i => [$group, $question, $answer, $home]) {
            Faq::updateOrCreate(['question' => $question], [
                'group' => $group,
                'answer' => $answer,
                'show_on_home' => $home,
                'is_published' => true,
                'sort_order' => $i,
            ]);
        }
    }
}
