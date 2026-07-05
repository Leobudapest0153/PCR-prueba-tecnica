# API de Administración de Solicitudes Técnicas

API REST construida en Laravel para el registro, consulta y seguimiento de solicitudes técnicas (soporte/tickets).

Este backend es parte del monorepo raíz de este repositorio. Para levantar el proyecto completo (API + base de datos + frontend) con un solo comando, ver el [README de la raíz](../README.md). Este documento cubre el detalle propio de la API: arquitectura, endpoints y ejemplos de uso.

## Requisitos

- [Docker](https://www.docker.com/) y Docker Compose (no se necesita PHP ni Composer instalados en el host, todo corre en contenedores).

## Levantar solo el backend

Todos los comandos de Docker se ejecutan desde la **raíz del repositorio** (ahí vive `docker-compose.yml`).

1. Copiar los archivos de variables de entorno:

   ```bash
   cp .env.example .env                       # variables de orquestación (raíz)
   cp backend/.env.example backend/.env       # variables propias de Laravel
   ```

2. Construir y levantar los contenedores del backend (aplicación + PostgreSQL dedicado):

   ```bash
   docker compose up -d --build app db
   ```

   En el primer arranque el contenedor `app` instala las dependencias de Composer y genera el `APP_KEY` automáticamente.

3. Ejecutar las migraciones (opcionalmente con datos de prueba):

   ```bash
   docker compose exec app php artisan migrate
   # o, para poblar la tabla con 20 solicitudes de ejemplo:
   docker compose exec app php artisan migrate --seed
   ```

4. La API queda disponible en `http://localhost:8000`.

## Servicios

| Servicio | Descripción                              | Puerto host (por defecto) |
| -------- | ----------------------------------------- | -------------------------- |
| `app`    | Aplicación Laravel (PHP 8.4)              | `8000`                      |
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

## Arquitectura

El código sigue una separación clara de responsabilidades:

```
routes/api.php                    → definición de endpoints (prefijo /api/v1)
app/Http/Controllers/Api/V1/      → controladores: reciben la petición y devuelven la respuesta
app/Http/Requests/                → Form Requests: validación de entradas
app/Http/Resources/               → formato de salida de la API
app/Services/                     → lógica de negocio
app/Models/                       → Eloquent (acceso a datos)
app/Enums/                        → valores permitidos (Prioridad, EstadoSolicitud)
database/migrations/              → esquema de la base de datos
database/factories/ y seeders/    → datos de prueba
```

## Endpoints

Base URL: `http://localhost:8000/api/v1`

| Método | Endpoint                                   | Descripción                                |
| ------ | ------------------------------------------- | ------------------------------------------- |
| POST   | `/solicitudes-tecnicas`                     | Crea una nueva solicitud técnica            |
| GET    | `/solicitudes-tecnicas`                     | Lista solicitudes (con filtros y paginación) |
| GET    | `/solicitudes-tecnicas/{id}`                | Consulta una solicitud por id               |
| PATCH  | `/solicitudes-tecnicas/{id}/estado`         | Actualiza el estado de una solicitud        |

### Formato de respuesta

Todas las respuestas siguen el mismo formato:

```jsonc
// Éxito
{
  "success": true,
  "message": "Solicitud tecnica creada correctamente.",
  "data": { "id": 1, "cliente": "...", "...": "..." }
}

// Error
{
  "success": false,
  "message": "Los datos enviados no son validos.",
  "errors": { "cliente": ["El cliente es obligatorio."] }
}
```

El listado (`GET /solicitudes-tecnicas`) envía además la metadata de paginación dentro de `data`:

```jsonc
{
  "success": true,
  "message": "Solicitudes obtenidas correctamente.",
  "data": {
    "items": [{ "id": 1, "cliente": "...", "...": "..." }],
    "meta": { "current_page": 1, "last_page": 3, "per_page": 15, "total": 42 }
  }
}
```

### Valores permitidos

- `prioridad`: `baja`, `media`, `alta`
- `estado`: `pendiente` (por defecto al crear), `en_proceso`, `resuelto`

### Crear una solicitud

```bash
curl -X POST http://localhost:8000/api/v1/solicitudes-tecnicas \
  -H "Content-Type: application/json" \
  -d '{
        "cliente": "Alcaldía de San Salvador",
        "titulo": "Falla en el sistema de planillas",
        "descripcion": "El modulo de planillas no genera el reporte mensual.",
        "prioridad": "alta"
      }'
```

### Listar solicitudes (con filtros y paginación)

```bash
curl "http://localhost:8000/api/v1/solicitudes-tecnicas?estado=pendiente&prioridad=alta&per_page=10"
```

### Ver una solicitud

```bash
curl http://localhost:8000/api/v1/solicitudes-tecnicas/1
```

### Actualizar el estado de una solicitud

```bash
curl -X PATCH http://localhost:8000/api/v1/solicitudes-tecnicas/1/estado \
  -H "Content-Type: application/json" \
  -d '{"estado": "en_proceso"}'
```

## Manejo de errores

La API valida los campos obligatorios y los valores permitidos de `prioridad`/`estado`, respondiendo `422` con el detalle por campo. Un `id` inexistente responde `404`, y cualquier otro error no controlado responde `500`, siempre con el mismo formato `{ success, message, errors? }`.
