#!/usr/bin/env bash
# Update the running site to the latest commit of the deploy branch.
# Run on the VPS as the app user:  sudo -u deploy /var/www/sigit_sugara/deploy/deploy.sh
# GitHub Actions runs the same script over SSH (.github/workflows/deploy.yml).
set -euo pipefail

# Everything runs inside main() so bash has parsed the whole file before
# `git reset` replaces this script on disk.
main() {

APP_DIR="${APP_DIR:-/var/www/sigit_sugara}"
cd "$APP_DIR"
BRANCH="${BRANCH:-$(git rev-parse --abbrev-ref HEAD)}"
PHP_VERSION=8.3

echo "==> Deploy $BRANCH"
git fetch --quiet origin "$BRANCH"
if [ "$(git rev-parse HEAD)" = "$(git rev-parse "origin/$BRANCH")" ] && [ "${FORCE:-0}" != 1 ]; then
    echo "Sudah versi terbaru ($(git rev-parse --short HEAD))."
    exit 0
fi

php artisan down --retry=15 || true
trap 'php artisan up' EXIT

git reset --hard "origin/$BRANCH"
composer install --no-dev --optimize-autoloader --no-interaction --no-progress
npm ci --no-audit --no-fund
npm run build
php artisan migrate --force
php artisan optimize:clear >/dev/null
php artisan optimize

# Reload PHP-FPM so OPcache picks up the new code (allowed via /etc/sudoers.d/sigit-deploy).
sudo -n systemctl reload "php${PHP_VERSION}-fpm" 2>/dev/null || sudo -n service "php${PHP_VERSION}-fpm" reload 2>/dev/null || true

echo "==> Selesai: $(git log -1 --format='%h %s')"
}

main "$@"
