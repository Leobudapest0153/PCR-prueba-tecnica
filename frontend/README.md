# Frontend — Administración de Solicitudes Técnicas

SPA construida con Vue 3 (Composition API) y Vuetify, que consume la API del backend para listar, crear, consultar y actualizar el estado de solicitudes técnicas.

Este frontend es parte del monorepo raíz de este repositorio. Para levantar el proyecto completo (API + base de datos + frontend) con un solo comando, ver el [README de la raíz](../README.md). Este documento cubre el detalle propio de la SPA: estructura, variables de entorno y funcionalidades.

## Requisitos

- [Docker](https://www.docker.com/) y Docker Compose (no se necesita Node ni npm instalados en el host, todo corre en contenedores).

## Levantar solo el frontend

Todos los comandos de Docker se ejecutan desde la **raíz del repositorio** (ahí vive `docker-compose.yml`).

1. Copiar el archivo de variables de entorno de orquestación (si no existe aún):

   ```bash
   cp .env.example .env
   ```

2. Levantar el backend y el frontend (la SPA necesita la API disponible para funcionar):

   ```bash
   docker compose up -d --build app db frontend
   ```

   En el primer arranque el contenedor `frontend` instala las dependencias de npm automáticamente.

3. La SPA queda disponible en `http://localhost:5173`.

## Variables de entorno

El frontend usa una única variable, `VITE_API_BASE_URL`, que apunta a la base de la API (por defecto `http://localhost:8000/api/v1`).

En Docker, esta variable se inyecta desde el **`.env` de la raíz** del monorepo (ver `docker-compose.yml`), que tiene prioridad sobre `frontend/.env`. El archivo `frontend/.env.example` documenta el valor esperado por si se quiere correr Vite directamente en el host (sin Docker).

## Estructura

El código sigue una separación por capas, buscando que cada carpeta tenga una única responsabilidad:

```
src/
├── enums/          → valores permitidos (Prioridad, EstadoSolicitud), espejo de los enums del backend
├── services/        → capa de acceso a la API (axios): un cliente HTTP + un servicio por recurso
├── stores/          → estado global (Pinia): datos de solicitudes técnicas y notificaciones (snackbar)
├── components/       → piezas reutilizables: diálogos de crear / ver detalle / cambiar estado, snackbar
├── views/            → pantallas completas, componen componentes + store
├── router/           → definición de rutas (Vue Router)
└── plugins/          → configuración de Vuetify
```

## Funcionalidades

- **Listado con paginación server-side**: la tabla (`v-data-table-server`) pide una página a la vez a la API; el tamaño y número de página vienen del `meta` que devuelve el backend.
- **Filtros** por `estado` y `prioridad`, combinables, que reinician el listado a la página 1.
- **Crear solicitud**: formulario con validación en el cliente (campos obligatorios, longitud máxima) y manejo de errores de validación `422` del backend, mostrados por campo.
- **Ver detalle**: consulta el detalle completo de una solicitud contra la API en el momento de abrir el diálogo.
- **Cambiar estado**: actualiza el estado de una solicitud y refleja el cambio en la fila correspondiente sin recargar todo el listado.
- **Feedback global**: un snackbar único (`AppSnackbar`, respaldado por el store `notificaciones`) muestra el resultado de las acciones de crear/actualizar estado, tanto en éxito como en error inesperado.

## Comandos útiles

```bash
# Ver logs del servidor de desarrollo
docker compose logs -f frontend

# Instalar una nueva dependencia (el contenedor usa un volumen propio para node_modules)
docker compose exec frontend npm install <paquete>
docker compose restart frontend

# Detener el entorno
docker compose down
```
