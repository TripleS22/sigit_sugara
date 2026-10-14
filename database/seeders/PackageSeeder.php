<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

/** Starting prices from PRD §4 (market-test prices; editable in the CMS). */
class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $hosting = 'Domain & hosting ditagihkan terpisah (biaya tahunan).';

        $business = [
            [
                'name' => 'Starter', 'slug' => 'starter', 'price_from' => 1000000, 'max_pages' => '5',
                'tagline' => 'Kehadiran online pertama yang rapi dan mudah dikelola.',
                'target_audience' => 'UMKM dan usaha yang baru memulai website',
                'delivery_estimate' => '7–10 hari kerja',
                'description' => 'Website hingga 5 halaman dengan CMS, SEO dasar, dan formulir kontak. Cocok untuk memperkenalkan usaha dan menerima pertanyaan calon pelanggan.',
                'features' => [['CMS', null, true], ['SEO dasar', null, true], ['Portofolio', 'Dasar', true], ['Formulir lead', 'Kontak', true], ['Statistik', 'Dasar', true], ['Chatbot AI', null, false], ['Integrasi API', null, false], ['Multi-bahasa', 'Add-on', false]],
            ],
            [
                'name' => 'Business', 'slug' => 'business', 'price_from' => 2000000, 'price_to' => 2500000, 'max_pages' => '10',
                'tagline' => 'Website bisnis lengkap untuk menghasilkan permintaan penawaran.',
                'target_audience' => 'bisnis yang ingin aktif mendapatkan leads',
                'delivery_estimate' => '10–14 hari kerja',
                'badge' => 'Paling populer', 'is_featured' => true,
                'description' => 'Hingga 10 halaman dengan portofolio lengkap, formulir permintaan penawaran, dan dashboard statistik. Chatbot AI dan integrasi API terbatas tersedia sebagai opsi.',
                'features' => [['CMS', null, true], ['SEO dasar', null, true], ['Portofolio', 'Lengkap', true], ['Formulir lead', 'Penawaran', true], ['Statistik', 'Dashboard', true], ['Chatbot AI', 'Opsional', true], ['Integrasi API', 'Terbatas', true], ['Multi-bahasa', 'Add-on', false]],
            ],
            [
                'name' => 'Premium AI', 'slug' => 'premium-ai', 'price_from' => 3500000, 'price_to' => 5000000, 'max_pages' => '15',
                'tagline' => 'Website dengan chatbot AI dan lead generation lanjutan.',
                'target_audience' => 'bisnis yang menerima banyak pertanyaan pelanggan',
                'delivery_estimate' => '14–21 hari kerja',
                'description' => 'Hingga 15 halaman dengan chatbot AI, formulir lead generation, statistik lanjutan, dan integrasi API sesuai ruang lingkup.',
                'features' => [['CMS', null, true], ['SEO dasar', null, true], ['Portofolio', 'Lengkap', true], ['Formulir lead', 'Lead generation', true], ['Statistik', 'Lanjutan', true], ['Chatbot AI', 'Ya', true], ['Integrasi API', 'Sesuai scope', true], ['Multi-bahasa', 'Add-on', false]],
            ],
            [
                'name' => 'Enterprise', 'slug' => 'enterprise', 'price_from' => 7500000, 'price_prefix' => 'Mulai', 'max_pages' => '25+',
                'tagline' => 'Solusi kustom untuk perusahaan dengan kebutuhan khusus.',
                'target_audience' => 'perusahaan dan organisasi skala menengah–besar',
                'delivery_estimate' => 'Sesuai ruang lingkup',
                'description' => 'Lebih dari 25 halaman dengan portofolio, formulir, statistik, chatbot, dan integrasi yang dirancang khusus. Multi-bahasa sesuai ruang lingkup.',
                'features' => [['CMS', null, true], ['SEO dasar', null, true], ['Portofolio', 'Kustom', true], ['Formulir lead', 'Kustom', true], ['Statistik', 'Kustom', true], ['Chatbot AI', 'Kustom', true], ['Integrasi API', 'Kustom', true], ['Multi-bahasa', 'Sesuai scope', true]],
            ],
        ];

        $student = [
            [
                'name' => 'Student Basic', 'slug' => 'student-basic', 'price_from' => 150000, 'price_to' => 250000, 'max_pages' => '3',
                'tagline' => 'Website statis untuk tugas dan praktikum.',
                'target_audience' => 'siswa SMK dan mahasiswa tahun awal',
                'delivery_estimate' => '2–4 hari',
                'description' => 'Website statis maksimal 3 halaman dengan HTML, CSS, dan JavaScript, responsif di ponsel.',
                'features' => [['Website statis', 'Maks. 3 halaman', true], ['Desain responsif', null, true], ['Source code', null, true], ['Penjelasan struktur kode', null, true], ['Database', null, false], ['Revisi', '1 kali', true]],
            ],
            [
                'name' => 'Student Dynamic', 'slug' => 'student-dynamic', 'price_from' => 350000, 'price_to' => 600000,
                'tagline' => 'Aplikasi Laravel dengan database dan CRUD sederhana.',
                'target_audience' => 'mahasiswa informatika dan sistem informasi',
                'delivery_estimate' => '4–7 hari', 'badge' => 'Banyak dipilih', 'is_featured' => true,
                'description' => 'Aplikasi Laravel dengan database MySQL dan CRUD sederhana untuk proyek praktikum.',
                'features' => [['Laravel + MySQL', null, true], ['CRUD sederhana', null, true], ['Source code', null, true], ['Panduan instalasi', null, true], ['Autentikasi & dashboard', null, false], ['Revisi', '2 kali', true]],
            ],
            [
                'name' => 'Student Project Pro', 'slug' => 'student-project-pro', 'price_from' => 750000, 'price_to' => 1200000,
                'tagline' => 'Prototipe sistem dengan autentikasi, beberapa modul, dan dashboard.',
                'target_audience' => 'proyek akhir mata kuliah dan demonstrasi teknis',
                'delivery_estimate' => '7–14 hari',
                'description' => 'Prototipe sistem dengan login, beberapa modul, dashboard, dan dokumentasi teknis. Deployment demo tersedia sebagai opsi.',
                'features' => [['Laravel + MySQL', null, true], ['Autentikasi & peran pengguna', null, true], ['Beberapa modul', 'Sesuai paket', true], ['Dashboard', null, true], ['Dokumentasi teknis', null, true], ['Deployment demo', 'Opsional', true], ['Revisi', '3 kali', true]],
            ],
        ];

        foreach (['business' => $business, 'student' => $student] as $segment => $packages) {
            foreach ($packages as $i => $data) {
                $features = $data['features'];
                unset($data['features']);

                $package = Package::updateOrCreate(['slug' => $data['slug']], $data + [
                    'segment' => $segment,
                    'hosting_note' => $hosting,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]);

                $package->features()->delete();
                foreach ($features as $j => [$label, $value, $included]) {
                    $package->features()->create(['label' => $label, 'value' => $value, 'is_included' => $included, 'sort_order' => $j]);
                }
            }
        }
    }
}
