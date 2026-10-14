<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'template' => 'about',
                'excerpt' => 'Kami membangun website yang membantu calon pelanggan memahami layanan Anda, mempercayai bisnis Anda, dan menghubungi Anda.',
                'body' => "## Siapa kami\n\nKami adalah penyedia jasa pembuatan website dan solusi digital untuk bisnis, UMKM, dan organisasi, dengan pengembangan berbasis Laravel.\n\n> Ubah bagian ini di CMS dengan cerita, pengalaman, dan tim Anda yang sebenarnya.\n\n## Prinsip kerja\n\n- **Transparan**: harga awal, ruang lingkup, dan batasan dijelaskan sebelum proyek dimulai.\n- **Mudah dikelola**: setiap website dilengkapi CMS.\n- **Terukur**: website disiapkan dengan analytics dan pelacakan konversi.\n- **Jujur**: kami tidak menjanjikan peringkat Google atau kenaikan omzet tertentu.",
                'meta_title' => 'Tentang Kami',
                'meta_description' => 'Kenali cara kami bekerja: harga transparan, CMS di setiap paket, dan website yang dirancang untuk mendatangkan konsultasi.',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'template' => 'legal',
                'excerpt' => 'Bagaimana kami mengumpulkan, menggunakan, dan melindungi data yang Anda kirimkan melalui website ini.',
                'body' => "> Templat ini perlu ditinjau dan disesuaikan dengan kondisi bisnis Anda, termasuk ketentuan UU No. 27 Tahun 2022 tentang Pelindungan Data Pribadi.\n\n## Data yang kami kumpulkan\n\n- **Formulir konsultasi**: nama, nama bisnis/institusi, email (opsional), nomor WhatsApp, jenis layanan, estimasi anggaran, dan deskripsi kebutuhan.\n- **Data teknis formulir**: alamat IP, jenis browser, halaman asal, dan sumber kampanye (UTM) untuk mencegah spam dan memahami kanal pemasaran.\n- **Statistik penggunaan anonim**: klik tombol WhatsApp, klik tombol paket, dan kunjungan halaman layanan, tanpa identitas pribadi.\n- **Google Analytics 4**: hanya dimuat setelah Anda memberi persetujuan (bila diaktifkan).\n\n## Penggunaan data\n\nData digunakan untuk menghubungi Anda terkait permintaan konsultasi, menyusun penawaran, dan meningkatkan layanan. Kami tidak menjual data Anda kepada pihak lain.\n\n## Penyimpanan dan keamanan\n\nData disimpan di server dengan akses terbatas untuk pengelola website. Kami menyimpan data selama diperlukan untuk tujuan di atas atau sesuai kewajiban hukum.\n\n## Hak Anda\n\nAnda dapat meminta akses, perbaikan, atau penghapusan data Anda dengan menghubungi kami melalui kontak yang tercantum di website.\n\n## Perubahan kebijakan\n\nKebijakan ini dapat diperbarui. Tanggal pembaruan terakhir tercantum di bawah halaman ini.",
                'meta_title' => 'Kebijakan Privasi',
                'meta_description' => 'Kebijakan privasi: data yang dikumpulkan melalui formulir dan analytics, cara penggunaan, penyimpanan, dan hak Anda.',
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'template' => 'legal',
                'excerpt' => 'Ketentuan umum layanan pembuatan website.',
                'body' => "> Templat ini perlu ditinjau dan disesuaikan sebelum dipakai sebagai dasar perjanjian.\n\n## Penawaran dan ruang lingkup\n\nHarga di website adalah harga awal. Ruang lingkup, biaya, jadwal, dan termin pembayaran final mengikuti penawaran tertulis yang disetujui kedua pihak.\n\n## Materi dari klien\n\nKlien bertanggung jawab atas materi yang diberikan (teks, logo, foto) dan memastikan memiliki hak untuk menggunakannya.\n\n## Revisi dan pekerjaan tambahan\n\nRevisi mengikuti kuota pada paket atau penawaran. Pekerjaan di luar ruang lingkup memakai penawaran baru.\n\n## Domain, hosting, dan layanan pihak ketiga\n\nBiaya domain, hosting, dan layanan pihak ketiga ditagihkan terpisah kecuali disebutkan lain, dan tunduk pada ketentuan penyedianya.\n\n## Serah terima dan kepemilikan\n\nSource code dan akses admin diserahkan setelah pelunasan.\n\n## Layanan pelajar dan mahasiswa\n\nLayanan berupa bantuan implementasi dan prototipe. Pengguna bertanggung jawab memastikan penggunaannya sesuai aturan institusi pendidikan masing-masing. Kami tidak mengerjakan ujian atau mewakili pengguna dalam penilaian akademik.\n\n## Batasan tanggung jawab\n\nKami tidak menjamin peringkat mesin pencari, jumlah pengunjung, atau peningkatan penjualan tertentu.",
                'meta_title' => 'Syarat & Ketentuan Layanan',
                'meta_description' => 'Syarat dan ketentuan layanan pembuatan website: penawaran, revisi, domain dan hosting, serah terima, dan layanan pelajar.',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page + ['is_published' => true, 'published_at' => now()]);
        }
    }
}
