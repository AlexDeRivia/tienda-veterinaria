#!/bin/sh
set -eu

: "${APP_KEY:?APP_KEY is required}"
: "${APP_URL:?APP_URL is required}"
: "${DB_HOST:?DB_HOST is required}"
: "${DB_DATABASE:?DB_DATABASE is required}"
: "${DB_USERNAME:?DB_USERNAME is required}"
: "${DB_PASSWORD:?DB_PASSWORD is required}"
: "${AIVEN_CA_BASE64:?AIVEN_CA_BASE64 is required}"
: "${BREVO_API_KEY:?BREVO_API_KEY is required}"
: "${BREVO_FROM_EMAIL:?BREVO_FROM_EMAIL is required}"

case "$APP_URL" in
  https://*) ;;
  *) echo 'APP_URL must start with https://' >&2; exit 1 ;;
esac

printf '%s' "$AIVEN_CA_BASE64" | base64 -d > /tmp/aiven-ca.pem
chmod 600 /tmp/aiven-ca.pem

php artisan migrate --force
php artisan db:seed --force
php artisan app:provision-admin

exec apache2-foreground
