# API de Administración de Solicitudes Técnicas

API REST construida en Laravel para el registro, consulta y seguimiento de solicitudes técnicas (soporte/tickets).

> Este README cubre el levantamiento del entorno. La documentación de los endpoints se agrega en cuanto la API queda disponible.

## Requisitos

- [Docker](https://www.docker.com/) y Docker Compose (no se necesita PHP ni Composer instalados en el host, todo corre en contenedores).

## Levantar el entorno

1. Copiar el archivo de variables de entorno:

   ```bash
   cp .env.example .env
   ```

2. Construir y levantar los contenedores (aplicación + PostgreSQL dedicado):

   ```bash
   docker compose up -d --build
   ```

   En el primer arranque el contenedor `app` instala las dependencias de Composer y genera el `APP_KEY` automáticamente.

3. Ejecutar las migraciones:

   ```bash
   docker compose exec app php artisan migrate
   ```

4. La API queda disponible en `http://localhost:8000`.

## Servicios

| Servicio | Descripción                              | Puerto host (por defecto) |
| -------- | ----------------------------------------- | -------------------------- |
| `app`    | Aplicación Laravel (PHP 8.3)              | `8000`                      |
| `db`     | PostgreSQL 16, dedicado a este proyecto   | `5433` (interno: `5432`)    |

El servicio `db` usa un volumen y una red propios del proyecto, por lo que no interfiere con otras instancias de PostgreSQL que puedan estar corriendo en el mismo equipo.

## Comandos útiles

```bash
# Ver logs de la aplicación
docker compose logs -f app

# Ejecutar artisan dentro del contenedor
docker compose exec app php artisan <comando>

# Detener el entorno
docker compose down
```
