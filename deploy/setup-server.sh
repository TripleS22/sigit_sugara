#!/usr/bin/env bash
#
# First-time provisioning of a fresh Ubuntu 22.04/24.04 (or Debian 12) VPS:
# Nginx + PHP 8.3-FPM + MariaDB + Node 22 + Composer, the app itself, HTTPS
# (when a domain is given), daily backups and a deploy user for CI.
#
# Run as root on the VPS:
#   curl -fsSL https://raw.githubusercontent.com/TripleS22/sigit_sugara/<branch>/deploy/setup-server.sh -o setup.sh
#   DOMAIN=example.com EMAIL=you@example.com bash setup.sh
#
# Without DOMAIN the site is served over plain HTTP on the server IP and kept
# out of search engines; re-run later with DOMAIN=... to add the domain + SSL.
# Safe to re-run: existing database, .env and content are kept.

set -euo pipefail

REPO_URL="${REPO_URL:-https://github.com/TripleS22/sigit_sugara.git}"
BRANCH="${BRANCH:-claude/blissful-knuth-ezhfsh}"
APP_USER="${APP_USER:-deploy}"
APP_DIR="${APP_DIR:-/var/www/sigit_sugara}"
DOMAIN="${DOMAIN:-}"
WWW="${WWW:-1}"                       # also serve www.DOMAIN
EMAIL="${EMAIL:-}"                    # Let's Encrypt contact
DB_NAME="${DB_NAME:-sigit_sugara}"
DB_USER="${DB_USER:-sigit_sugara}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@${DOMAIN:-example.com}}"
ENABLE_UFW="${ENABLE_UFW:-0}"         # opt-in: only if SSH runs on port 22
PHP_VERSION=8.3
CREDENTIALS_FILE=/root/sigit-credentials.txt

log() { printf '\n\033[1;35m==> %s\033[0m\n' "$*"; }
die() { printf '\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }
as_app() { sudo -u "$APP_USER" -H bash -c "cd '$APP_DIR' && $1"; }

# systemd on a real VPS; plain `service` inside containers used for testing.
svc() {
    if [ -d /run/systemd/system ]; then systemctl "$1" "$2"; else service "$2" "$1"; fi
}

# Reload a running service, start it otherwise (`service x reload` reports success even when x is stopped).
svc_reload() {
    if [ -d /run/systemd/system ]; then
        systemctl enable --quiet "$1" && systemctl reload-or-restart "$1"
    elif service "$1" status >/dev/null 2>&1; then
        service "$1" reload
    else
        service "$1" start
    fi
}

# One line per credential, replaced on re-runs instead of piling up.
save_cred() {
    touch "$CREDENTIALS_FILE"; chmod 600 "$CREDENTIALS_FILE"
    grep -v "^$1:" "$CREDENTIALS_FILE" > "$CREDENTIALS_FILE.tmp" || true
    printf '%s: %s\n' "$1" "$2" >> "$CREDENTIALS_FILE.tmp"
    mv "$CREDENTIALS_FILE.tmp" "$CREDENTIALS_FILE"
}

[ "$(id -u)" -eq 0 ] || die "Jalankan sebagai root (sudo -i)."
. /etc/os-release
case "$ID" in ubuntu|debian) ;; *) die "OS $PRETTY_NAME belum didukung skrip ini (butuh Ubuntu/Debian)." ;; esac

SERVER_IP="$(curl -fsS -4 --max-time 5 https://api.ipify.org 2>/dev/null || hostname -I | awk '{print $1}')"
if [ -n "$DOMAIN" ]; then
    APP_URL="https://$DOMAIN"
    SERVER_NAMES="$DOMAIN"; [ "$WWW" = 1 ] && SERVER_NAMES="$DOMAIN www.$DOMAIN"
else
    APP_URL="http://$SERVER_IP"
    SERVER_NAMES="_"
fi

log "Paket sistem (Nginx, PHP $PHP_VERSION, MariaDB, Git)"
export DEBIAN_FRONTEND=noninteractive
apt-get update -q
apt-get install -yq ca-certificates curl gnupg git unzip sudo cron openssh-client software-properties-common lsb-release
if ! apt-cache show "php${PHP_VERSION}-fpm" >/dev/null 2>&1; then
    if [ "$ID" = ubuntu ]; then
        add-apt-repository -y ppa:ondrej/php
    else
        curl -fsSL https://packages.sury.org/php/apt.gpg -o /usr/share/keyrings/sury-php.gpg
        echo "deb [signed-by=/usr/share/keyrings/sury-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/sury-php.list
    fi
    apt-get update -q
fi
apt-get install -yq nginx mariadb-server \
    "php${PHP_VERSION}-fpm" "php${PHP_VERSION}-cli" "php${PHP_VERSION}-mysql" "php${PHP_VERSION}-mbstring" \
    "php${PHP_VERSION}-xml" "php${PHP_VERSION}-curl" "php${PHP_VERSION}-zip" "php${PHP_VERSION}-gd" \
    "php${PHP_VERSION}-intl" "php${PHP_VERSION}-bcmath" "php${PHP_VERSION}-opcache"
[ -n "$DOMAIN" ] && apt-get install -yq certbot python3-certbot-nginx

if ! command -v node >/dev/null || [ "$(node -p 'process.versions.node.split(".")[0]')" -lt 20 ]; then
    log "Node.js 22"
    curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
    apt-get install -yq nodejs
fi

if ! command -v composer >/dev/null; then
    log "Composer"
    EXPECTED="$(curl -fsSL https://composer.github.io/installer.sig)"
    php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
    [ "$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")" = "$EXPECTED" ] || die "Checksum installer Composer tidak cocok."
    php /tmp/composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
    rm -f /tmp/composer-setup.php
fi

svc start mariadb || svc start mysql
svc start "php${PHP_VERSION}-fpm"
svc start cron || true

log "User aplikasi '$APP_USER'"
id "$APP_USER" >/dev/null 2>&1 || useradd --create-home --shell /bin/bash "$APP_USER"
usermod -aG www-data "$APP_USER"
# CI deploys may reload PHP-FPM, nothing else.
echo "$APP_USER ALL=(root) NOPASSWD: /usr/bin/systemctl reload php${PHP_VERSION}-fpm, /usr/sbin/service php${PHP_VERSION}-fpm reload" > /etc/sudoers.d/sigit-deploy
chmod 440 /etc/sudoers.d/sigit-deploy

log "Database MariaDB"
DB_PASS="$(grep -oP '^DB_PASSWORD=\K.*' "$APP_DIR/.env" 2>/dev/null || true)"
if [ -z "$DB_PASS" ]; then
    DB_PASS="$(openssl rand -hex 20)"
    save_cred "Database" "$DB_NAME / user $DB_USER / password $DB_PASS"
fi
mysql <<SQL
CREATE DATABASE IF NOT EXISTS \`$DB_NAME\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
ALTER USER '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON \`$DB_NAME\`.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
SQL

log "Kode aplikasi ($BRANCH)"
mkdir -p "$(dirname "$APP_DIR")"
if [ ! -d "$APP_DIR/.git" ]; then
    git clone --branch "$BRANCH" "$REPO_URL" "$APP_DIR"
fi
chown -R "$APP_USER":www-data "$APP_DIR"
as_app "git fetch origin '$BRANCH' && git checkout '$BRANCH' && git reset --hard 'origin/$BRANCH'"

if [ ! -f "$APP_DIR/.env" ]; then
    ADMIN_PASS="$(openssl rand -base64 18 | tr -dc 'A-Za-z0-9' | head -c 16)1a"
    cp "$APP_DIR/.env.example" "$APP_DIR/.env"
    save_cred "Admin CMS" "$APP_URL/admin  email $ADMIN_EMAIL  password $ADMIN_PASS"
fi

set_env() {
    local key="$1" value="$2"
    if grep -q "^${key}=" "$APP_DIR/.env"; then
        sed -i "s|^${key}=.*|${key}=${value}|" "$APP_DIR/.env"
    else
        echo "${key}=${value}" >> "$APP_DIR/.env"
    fi
}
set_env APP_ENV production
set_env APP_DEBUG false
set_env APP_URL "$APP_URL"
set_env LOG_LEVEL warning
set_env DB_CONNECTION mysql
set_env DB_HOST 127.0.0.1
set_env DB_PORT 3306
set_env DB_DATABASE "$DB_NAME"
set_env DB_USERNAME "$DB_USER"
set_env DB_PASSWORD "$DB_PASS"
set_env CACHE_STORE file
set_env SESSION_SECURE_COOKIE "$([ -n "$DOMAIN" ] && echo true || echo false)"
# Kept in .env only until the admin account exists (see seeding below).
if [ -n "${ADMIN_PASS:-}" ]; then
    set_env ADMIN_EMAIL "$ADMIN_EMAIL"
    set_env ADMIN_PASSWORD "$ADMIN_PASS"
fi
chown "$APP_USER":www-data "$APP_DIR/.env"; chmod 640 "$APP_DIR/.env"

log "Dependensi & build aset"
as_app "composer install --no-dev --optimize-autoloader --no-interaction --no-progress"
grep -q '^APP_KEY=base64:' "$APP_DIR/.env" || as_app "php artisan key:generate --force"
as_app "npm ci --no-audit --no-fund && npm run build"

log "Migrasi database"
as_app "php artisan migrate --force"
# Decide by database state, so a re-run after a failed first attempt still seeds.
if [ "$(mysql -N "$DB_NAME" -e 'SELECT COUNT(*) FROM users')" = 0 ]; then
    # Seeders fill the initial CMS content; run them once so later deploys never overwrite edits.
    as_app "php artisan db:seed --force"
    # The password is in the credentials file; do not keep it in .env.
    sed -i "s|^ADMIN_PASSWORD=.*|ADMIN_PASSWORD=|" "$APP_DIR/.env"
    if [ -z "$DOMAIN" ]; then
        # Bare IP: keep the site out of search engines until a real domain is attached.
        mysql "$DB_NAME" -e "INSERT INTO settings (\`key\`, value, created_at, updated_at) VALUES ('allow_indexing', '0', NOW(), NOW()) ON DUPLICATE KEY UPDATE value = '0';"
        as_app "php artisan cache:clear"
    fi
fi
as_app "php artisan storage:link --force >/dev/null 2>&1 || true"
as_app "php artisan optimize"

chown -R "$APP_USER":www-data "$APP_DIR"
find "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" -type d -exec chmod 2775 {} +
find "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" -type f -exec chmod 664 {} +

log "Nginx"
# Some VPS images have IPv6 disabled; nginx refuses to start if asked to bind it.
LISTEN_V6=""; [ -f /proc/net/if_inet6 ] && LISTEN_V6="listen [::]:80;"
cat > /etc/nginx/sites-available/sigit_sugara <<NGINX
server {
    listen 80;
    $LISTEN_V6
    server_name $SERVER_NAMES;
    root $APP_DIR/public;
    index index.php;
    charset utf-8;

    # Media uploads: up to 10 images of 2 MB in one request.
    client_max_body_size 25M;

    gzip on;
    gzip_types text/css application/javascript application/json image/svg+xml application/xml text/plain;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location /build/ {
        add_header Cache-Control "public, max-age=31536000, immutable";
        try_files \$uri =404;
    }

    location /storage/ {
        expires 30d;
        try_files \$uri =404;
    }

    location = /favicon.ico { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ ^/index\.php(/|\$) {
        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINX
ln -sf /etc/nginx/sites-available/sigit_sugara /etc/nginx/sites-enabled/sigit_sugara
# Only remove the stock placeholder site; other sites on this server stay untouched.
[ -L /etc/nginx/sites-enabled/default ] && rm -f /etc/nginx/sites-enabled/default
nginx -t
svc_reload nginx

if [ -n "$DOMAIN" ]; then
    log "HTTPS (Let's Encrypt) untuk $SERVER_NAMES"
    CERT_DOMAINS=(-d "$DOMAIN"); [ "$WWW" = 1 ] && CERT_DOMAINS+=(-d "www.$DOMAIN")
    if [ -n "$EMAIL" ]; then CERT_MAIL=(-m "$EMAIL"); else CERT_MAIL=(--register-unsafely-without-email); fi
    certbot --nginx --non-interactive --agree-tos --redirect "${CERT_MAIL[@]}" "${CERT_DOMAINS[@]}" \
        || echo "Sertifikat gagal dibuat. Pastikan DNS A record $DOMAIN mengarah ke $SERVER_IP, lalu jalankan ulang skrip ini."
fi

log "Backup harian (database + upload), disimpan 14 hari"
install -m 750 "$APP_DIR/deploy/backup.sh" /usr/local/bin/sigit-backup
cat > /etc/cron.d/sigit_sugara <<CRON
APP_DIR=$APP_DIR
30 2 * * * root /usr/local/bin/sigit-backup >> /var/log/sigit-backup.log 2>&1
* * * * * $APP_USER cd $APP_DIR && php artisan schedule:run >> /dev/null 2>&1
CRON
/usr/local/bin/sigit-backup

log "Kunci SSH untuk deploy otomatis (GitHub Actions)"
KEY=/home/$APP_USER/.ssh/github_actions
if [ ! -f "$KEY" ]; then
    sudo -u "$APP_USER" mkdir -p "/home/$APP_USER/.ssh"
    sudo -u "$APP_USER" ssh-keygen -q -t ed25519 -N '' -C "github-actions@sigit_sugara" -f "$KEY"
    cat "$KEY.pub" >> "/home/$APP_USER/.ssh/authorized_keys"
    chown "$APP_USER":"$APP_USER" "/home/$APP_USER/.ssh/authorized_keys"
    chmod 700 "/home/$APP_USER/.ssh"; chmod 600 "/home/$APP_USER/.ssh/authorized_keys"
fi

if [ "$ENABLE_UFW" = 1 ]; then
    log "Firewall (UFW)"
    apt-get install -yq ufw
    ufw allow OpenSSH && ufw allow 'Nginx Full' && ufw --force enable
fi

log "Selesai"
status="$(curl -s -o /dev/null -w '%{http_code}' -H "Host: ${DOMAIN:-$SERVER_IP}" http://127.0.0.1/ || true)"
echo "Cek lokal: HTTP $status"
echo "Website : $APP_URL"
echo "CMS     : $APP_URL/admin"
echo "Kredensial tersimpan di $CREDENTIALS_FILE (hanya root):"
cat "$CREDENTIALS_FILE"
echo
if [ -n "$DOMAIN" ]; then
    echo "Jika sebelumnya situs dipasang tanpa domain, aktifkan lagi 'Izinkan mesin pencari mengindeks website' di CMS > Pengaturan."
fi
echo "Deploy berikutnya: sudo -u $APP_USER $APP_DIR/deploy/deploy.sh"
echo "Deploy otomatis tiap push: isi GitHub Secrets VPS_HOST=$SERVER_IP, VPS_USER=$APP_USER,"
echo "dan VPS_SSH_KEY dengan isi:  cat $KEY   (lihat deploy/README.md)"
