#!/usr/bin/env bash
# Daily backup of the database and uploaded files (PRD §6.2, §12.14).
# Restore: see deploy/README.md.
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/sigit_sugara}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/sigit_sugara}"
KEEP_DAYS="${KEEP_DAYS:-14}"
STAMP="$(date +%Y%m%d-%H%M%S)"

env_value() { grep -oP "^$1=\K.*" "$APP_DIR/.env" | tr -d '"'; }

mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"

MYSQL_PWD="$(env_value DB_PASSWORD)" mysqldump --single-transaction --quick --no-tablespaces \
    -h "$(env_value DB_HOST)" -u "$(env_value DB_USERNAME)" "$(env_value DB_DATABASE)" \
    | gzip > "$BACKUP_DIR/db-$STAMP.sql.gz"

tar -czf "$BACKUP_DIR/uploads-$STAMP.tar.gz" -C "$APP_DIR/storage/app" public
cp "$APP_DIR/.env" "$BACKUP_DIR/env-$STAMP" && chmod 600 "$BACKUP_DIR/env-$STAMP"

# Refuse to keep a broken dump.
gzip -t "$BACKUP_DIR/db-$STAMP.sql.gz"
zcat "$BACKUP_DIR/db-$STAMP.sql.gz" | tail -1 | grep -q "Dump completed" || { echo "Dump tidak lengkap"; exit 1; }

find "$BACKUP_DIR" -type f -mtime +"$KEEP_DAYS" -delete
echo "$(date '+%F %T') backup OK: $BACKUP_DIR/*-$STAMP*"
