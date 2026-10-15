# Deploy ke VPS

Target: VPS Ubuntu 22.04/24.04 atau Debian 12 dengan akses root. Stack yang dipasang:
Nginx, PHP 8.3-FPM, MariaDB, Node 22 (untuk build aset), Composer, Let's Encrypt.

## 1. Pemasangan pertama (sekali saja)

Masuk ke VPS lalu jalankan:

```bash
ssh root@IP_VPS
curl -fsSL https://raw.githubusercontent.com/TripleS22/sigit_sugara/claude/blissful-knuth-ezhfsh/deploy/setup-server.sh -o setup.sh

# Belum punya domain: situs jalan di http://IP_VPS dan tidak diindeks Google
bash setup.sh

# Sudah punya domain (A record domain & www sudah mengarah ke IP VPS):
DOMAIN=namadomain.com EMAIL=email@anda.com bash setup.sh
```

Variabel opsional: `BRANCH`, `WWW=0` (tanpa www), `ADMIN_EMAIL`, `ENABLE_UFW=1`
(aktifkan firewall; hanya jika SSH memakai port 22), `APP_DIR`, `DB_NAME`, `DB_USER`.

Di akhir, skrip menampilkan URL website, URL CMS, password admin, dan password database.
Semuanya juga tersimpan di `/root/sigit-credentials.txt` (hanya bisa dibaca root).
Segera login ke `/admin` lalu ganti password di menu Pengguna.

Skrip aman dijalankan ulang, misalnya untuk menambahkan domain setelah memasang via IP:
database, `.env`, dan konten CMS tidak ditimpa. Setelah domain terpasang, aktifkan kembali
**Izinkan mesin pencari mengindeks website** di CMS › Pengaturan.

## 2. Update berikutnya

Manual, dari VPS:

```bash
sudo -u deploy /var/www/sigit_sugara/deploy/deploy.sh
```

Otomatis setiap push (setelah workflow Tests hijau), lewat GitHub Actions
(`.github/workflows/deploy.yml`). Isi di GitHub › Settings › Secrets and variables › Actions:

| Secret | Isi |
|---|---|
| `VPS_HOST` | IP VPS, mis. `76.13.194.179` |
| `VPS_USER` | `deploy` |
| `VPS_SSH_KEY` | isi file `/home/deploy/.ssh/github_actions` (jalankan `cat` di VPS) |
| `VPS_PORT` | opsional, jika SSH bukan port 22 |
| `SITE_URL` | opsional, mis. `https://namadomain.com` untuk smoke test |

Deploy juga bisa dijalankan manual dari tab Actions › Deploy › Run workflow.

`deploy.sh` mengaktifkan mode maintenance, menarik commit terbaru, `composer install`,
build aset, migrasi database, cache konfigurasi, lalu reload PHP-FPM. Seeder **tidak**
dijalankan ulang, jadi isi CMS tidak tertimpa.

## 3. Backup & pemulihan

Backup otomatis setiap hari pukul 02.30 ke `/var/backups/sigit_sugara` (disimpan 14 hari):
dump database, arsip gambar upload, dan salinan `.env`. Log: `/var/log/sigit-backup.log`.
Jalankan manual: `sigit-backup`. Salin backup ke luar server secara berkala.

Pemulihan (uji minimal sekali sebelum peluncuran, PRD §12.14):

```bash
cd /var/www/sigit_sugara
sudo -u deploy php artisan down
zcat /var/backups/sigit_sugara/db-TANGGAL.sql.gz | mysql sigit_sugara
tar -xzf /var/backups/sigit_sugara/uploads-TANGGAL.tar.gz -C storage/app
chown -R deploy:www-data storage/app/public
sudo -u deploy php artisan optimize:clear && sudo -u deploy php artisan optimize
sudo -u deploy php artisan up
```

## 4. Email notifikasi lead (opsional)

Isi `MAIL_*` di `/var/www/sigit_sugara/.env` (SMTP penyedia email Anda), jalankan
`sudo -u deploy php artisan optimize`, lalu isi "Kirim notifikasi lead ke email" di CMS › Pengaturan.

## Lokasi penting

| Apa | Di mana |
|---|---|
| Aplikasi | `/var/www/sigit_sugara` (milik user `deploy`) |
| Konfigurasi | `/var/www/sigit_sugara/.env` |
| Nginx | `/etc/nginx/sites-available/sigit_sugara` |
| Log aplikasi | `/var/www/sigit_sugara/storage/logs/laravel.log` |
| Cron (backup, scheduler) | `/etc/cron.d/sigit_sugara` |
