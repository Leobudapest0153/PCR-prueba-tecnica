# PCR Prueba Técnica — Administración de Solicitudes Técnicas

Monorepo con el backend (API) y el frontend de la administración de solicitudes técnicas.

```
.
├── backend/    API REST en Laravel + PostgreSQL — ver backend/README.md
└── frontend/   SPA en Vue 3 + Vuetify 3 — ver frontend/README.md
```

## Requisitos

- [Docker](https://www.docker.com/) y Docker Compose (no se necesita PHP, Composer, Node ni npm instalados en el host, todo corre en contenedores).

## Levantar todo el proyecto con un solo comando

1. Copiar los archivos de variables de entorno:

   ```bash
   cp .env.example .env                       # variables de orquestación (puertos, credenciales, URLs)
   cp backend/.env.example backend/.env       # variables propias de Laravel
   ```

2. Construir y levantar todos los servicios:

   ```bash
   docker compose up -d --build
   ```

   En el primer arranque cada contenedor instala sus propias dependencias (Composer en `app`, npm en `frontend`) y Laravel genera su `APP_KEY` automáticamente.

3. Ejecutar las migraciones de la base de datos (con datos de prueba):

   ```bash
   docker compose exec app php artisan migrate --seed
   ```

4. Listo:

   - Frontend: <http://localhost:5173>
   - API: <http://localhost:8000/api/v1>

## Servicios

| Servicio   | Descripción                          | Puerto host (por defecto) |
| ---------- | ------------------------------------- | -------------------------- |
| `app`      | API Laravel (PHP 8.4)                 | `8000`                      |
| `db`       | PostgreSQL 16, dedicado a este proyecto | `5433` (interno: `5432`)  |
| `frontend` | SPA Vue 3 + Vuetify 3 (Vite, con hot-reload) | `5173`               |

## Comandos útiles

```bash
# Ver logs de un servicio
docker compose logs -f app
docker compose logs -f frontend

# Ejecutar artisan dentro del contenedor del backend
docker compose exec app php artisan <comando>

# Detener el entorno
docker compose down
```

## Documentación detallada

- [`backend/README.md`](backend/README.md): arquitectura, endpoints, formato de respuesta y ejemplos de la API.
- [`frontend/README.md`](frontend/README.md): estructura de la SPA y variables de entorno.
