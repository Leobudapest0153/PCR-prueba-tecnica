#!/bin/sh
set -e

# Instala dependencias de npm si el volumen nombrado de node_modules aún está
# vacío (primer arranque o cuando se recrea el volumen en un entorno nuevo).
if [ -z "$(ls -A node_modules 2>/dev/null)" ]; then
    echo "Instalando dependencias de npm..."
    npm install
fi

exec "$@"
