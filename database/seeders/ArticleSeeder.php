<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Content calendar from PRD §8.5. Two articles are published as examples; the
 * rest are drafts with an outline for the team to finish. Never publish an
 * article just to fill the schedule.
 */
class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Biaya & Paket', 'slug' => 'biaya-paket', 'description' => 'Rincian biaya pembuatan website, domain, hosting, dan maintenance.'],
            ['name' => 'Panduan Website', 'slug' => 'panduan-website', 'description' => 'Panduan merencanakan, membangun, dan meluncurkan website bisnis.'],
            ['name' => 'Teknologi', 'slug' => 'teknologi', 'description' => 'Penjelasan teknologi web untuk pemilik bisnis.'],
        ])->mapWithKeys(fn ($c) => [$c['slug'] => Category::updateOrCreate(['slug' => $c['slug']], $c)]);

        $author = User::query()->where('role', 'admin')->first();

        $published = [
            [
                'category' => 'biaya-paket',
                'title' => 'Berapa Biaya Pembuatan Website Company Profile?',
                'slug' => 'biaya-pembuatan-website-company-profile',
                'excerpt' => 'Biaya website company profile dipengaruhi jumlah halaman, fitur, desain, serta domain dan hosting. Berikut komponen biaya yang perlu Anda ketahui sebelum meminta penawaran.',
                'tags' => ['company profile', 'biaya website'],
                'body' => <<<'MD'
Pertanyaan pertama hampir setiap calon klien adalah: *berapa biayanya?* Jawabannya bergantung pada kebutuhan. Artikel ini menjelaskan komponen biaya agar Anda dapat membandingkan penawaran dengan adil.

## Komponen biaya pembuatan website

### 1. Jumlah halaman

Website 5 halaman (beranda, tentang, layanan, portofolio, kontak) tentu lebih sederhana daripada website 20 halaman dengan halaman per layanan. Jumlah halaman memengaruhi waktu desain, penulisan struktur konten, dan pengujian.

### 2. Fitur

Fitur standar seperti CMS, formulir kontak, dan tombol WhatsApp relatif ringan. Fitur seperti multi-bahasa, chatbot, integrasi API, atau sistem pemesanan membutuhkan waktu pengembangan lebih banyak.

### 3. Tingkat kustomisasi desain

Desain berbasis komponen yang sudah teruji lebih hemat dibanding desain yang sepenuhnya dibuat khusus dari nol.

### 4. Domain dan hosting

Ini adalah biaya **tahunan** yang terpisah dari biaya pembuatan. Pastikan penawaran menyebutkan apakah domain dan hosting sudah termasuk atau belum.

### 5. Maintenance

Pembaruan keamanan, backup, dan perubahan kecil setelah website online. Biasanya opsional dan ditawarkan per bulan atau per tahun.

## Kisaran harga awal

Sebagai gambaran, paket kami dimulai dari paket Starter untuk website hingga 5 halaman, hingga paket Enterprise untuk website perusahaan dengan kebutuhan khusus. Lihat [perbandingan paket](/paket) untuk rinciannya.

## Pertanyaan yang perlu diajukan ke penyedia jasa

- Apakah CMS sudah termasuk?
- Berapa kali revisi yang didapat?
- Apakah domain dan hosting termasuk, dan berapa biaya perpanjangannya?
- Siapa pemilik source code setelah pelunasan?
- Apa saja yang **tidak** termasuk dalam penawaran?

Penawaran yang baik menjawab semua pertanyaan di atas secara tertulis.
MD,
            ],
            [
                'category' => 'biaya-paket',
                'title' => 'Apa Perbedaan Domain, Hosting, dan Maintenance?',
                'slug' => 'perbedaan-domain-hosting-maintenance',
                'excerpt' => 'Tiga istilah yang sering muncul di penawaran website. Kenali fungsinya agar Anda memahami biaya berulang setelah website online.',
                'tags' => ['domain', 'hosting', 'maintenance'],
                'body' => <<<'MD'
Setelah website selesai dibuat, ada biaya yang berulang setiap tahun. Agar tidak terkejut, pahami tiga komponen berikut.

## Domain: alamat website

Domain adalah nama yang diketik pengunjung, misalnya `namabisnis.com` atau `namabisnis.co.id`. Domain disewa per tahun dan harus diperpanjang agar tidak hilang.

**Tips:** daftarkan domain atas nama bisnis Anda sendiri dan simpan akses akunnya.

## Hosting: tempat website disimpan

Hosting adalah server yang menyimpan file dan database website sehingga dapat diakses 24 jam. Untuk website company profile berbasis Laravel, shared hosting yang mendukung versi PHP terbaru umumnya sudah cukup.

## Maintenance: perawatan website

Maintenance mencakup:

- Pembaruan framework dan dependensi demi keamanan
- Backup berkala dan uji pemulihan
- Pemantauan website tetap bisa diakses
- Perubahan kecil pada konten atau tampilan

Maintenance bersifat opsional, tetapi sangat disarankan untuk website yang menjadi sumber leads.

## Ringkasan

| Komponen | Fungsi | Sifat biaya |
|---|---|---|
| Domain | Alamat website | Tahunan |
| Hosting | Server penyimpanan | Tahunan / bulanan |
| Maintenance | Perawatan & keamanan | Opsional, bulanan / tahunan |

Ingin tahu kebutuhan hosting untuk website Anda? [Konsultasikan di sini](/kontak).
MD,
            ],
        ];

        foreach ($published as $i => $data) {
            $this->article($data, $categories, $author, 'published', now()->subDays(10 - $i * 3));
        }

        $drafts = [
            ['panduan-website', 'Apa Saja yang Harus Ada dalam Website Company Profile?', 'isi-website-company-profile', 'Edukasi calon pelanggan'],
            ['panduan-website', 'Website Gratis vs Website Custom untuk UMKM', 'website-gratis-vs-custom-umkm', 'Membantu perbandingan'],
            ['panduan-website', 'Cara Memilih Jasa Pembuatan Website untuk Bisnis', 'cara-memilih-jasa-pembuatan-website', 'Mendukung keputusan pembelian'],
            ['teknologi', 'Laravel untuk Website Bisnis: Kapan Dibutuhkan?', 'laravel-untuk-website-bisnis', 'Menjangkau calon pelanggan teknis'],
            ['panduan-website', 'Contoh Struktur Website Company Profile yang Efektif', 'struktur-website-company-profile', 'Mendukung portofolio dan desain'],
            ['panduan-website', 'Checklist Sebelum Meluncurkan Website Bisnis', 'checklist-peluncuran-website-bisnis', 'Menarik pencarian informasional'],
        ];

        foreach ($drafts as [$category, $title, $slug, $goal]) {
            $this->article([
                'category' => $category,
                'title' => $title,
                'slug' => $slug,
                'excerpt' => null,
                'tags' => [],
                'body' => "> DRAFT — Tujuan artikel: {$goal}.\n> Tulis berdasarkan pertanyaan nyata calon pelanggan. Jangan terbitkan sebelum kualitasnya memadai.\n\n## Pendahuluan\n\n## Poin utama 1\n\n## Poin utama 2\n\n## Poin utama 3\n\n## Kesimpulan dan ajakan konsultasi\n",
            ], $categories, $author, 'draft', null);
        }
    }

    private function article(array $data, $categories, ?User $author, string $status, $publishedAt): void
    {
        $category = $categories[$data['category']];
        unset($data['category']);

        Article::updateOrCreate(['slug' => $data['slug']], $data + [
            'category_id' => $category->id,
            'user_id' => $author?->id,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);
    }
}
