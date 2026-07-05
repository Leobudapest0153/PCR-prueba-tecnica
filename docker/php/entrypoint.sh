#!/bin/sh
set -e

# Instala dependencias de Composer si el proyecto aún no las tiene (primer arranque
# o cuando el volumen del código se monta "limpio" en un entorno nuevo).
if [ ! -d "vendor" ]; then
    echo "Instalando dependencias de Composer..."
    composer install --no-interaction --prefer-dist
fi

# Genera la application key si todavía no existe una configurada.
if [ -f ".env" ] && ! grep -q "^APP_KEY=base64" .env; then
    echo "Generando APP_KEY..."
    php artisan key:generate --ansi
fi

exec "$@"
