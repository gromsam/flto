#!/bin/sh
set -e
if [ -n "${VITE_API_ORIGIN}" ]; then
  echo "VITE_API_ORIGIN=${VITE_API_ORIGIN}" > .env.local
fi
exec "$@"
