#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --force
fi

echo "Waiting for database..."
for i in 1 2 3 4 5 6 7 8 9 10; do
  if php artisan migrate --force 2>/dev/null; then
    break
  fi
  [ "$i" = 10 ] && exit 1
  sleep 2
done

php artisan db:seed --force

exec "$@"
