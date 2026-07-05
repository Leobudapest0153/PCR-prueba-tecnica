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

## Preguntas complementarias

### ¿Qué decisiones tomó para estructurar la solución?

La máquina donde desarrollé esta prueba no tiene PHP ni Composer instalados localmente, pero sí tiene Docker. En lugar de instalar todas las herramientas de forma nativa, decidí levantar todo el proyecto en contenedores: uno para la API, uno para la base de datos y uno para el frontend. Esto tiene una ventaja adicional más allá de resolver mi propia limitación: cualquier otra persona que quiera revisar o correr el proyecto puede hacerlo con Docker instalado, sin importar qué tenga (o no tenga) instalado en su computadora, y con un solo comando (`docker compose up`) levanta el proyecto completo, ya conectado entre sí.

A nivel de organización, separé el backend y el frontend en carpetas independientes (`backend/` y `frontend/`) dentro de un mismo repositorio, cada uno con su propia documentación. Dentro del backend, separé responsabilidades en capas (controladores, validación de entrada, lógica de negocio y acceso a datos) para que el código sea fácil de leer, de probar y de extender más adelante sin tener que reescribir todo.

### ¿Qué validaciones implementó y por qué?

Antes de guardar cualquier solicitud, el sistema revisa que la información básica venga completa: el cliente, el título y la descripción son obligatorios, porque sin esos datos la solicitud no tendría sentido ni se podría dar seguimiento. También se valida que la prioridad y el estado únicamente puedan tomar valores predefinidos (por ejemplo, prioridad solo puede ser baja, media o alta), para evitar que se guarde información inconsistente o inventada que después complique los reportes o los filtros.

Si algo no cumple con estas reglas, el sistema no lo guarda y le devuelve al usuario un mensaje claro indicando exactamente qué campo tiene el problema, en lugar de un error genérico. Esto mismo se replica en el frontend: los formularios avisan al usuario en el momento si falta algún dato, para que no tenga que esperar a enviar el formulario para darse cuenta del error.

### ¿Qué mejoras aplicaría si tuviera más tiempo?

- **Agregar autenticación**, para que solo usuarios identificados puedan crear o modificar solicitudes.
- **Manejar los clientes como un catálogo en lugar de texto libre**: hoy el cliente se escribe a mano al crear una solicitud. Si tuviera más tiempo, agregaría un módulo de clientes para que cada usuario registre y gestione sus propios clientes, y así evitar que se mezcle información entre distintos usuarios. Al lado del selector de clientes agregaría también un botón de "agregar rápido", para que el usuario pueda dar de alta un cliente nuevo con solo el nombre sin salir de la pantalla de solicitudes, y completar el resto de su información más adelante.
- **Convertir los estados y las prioridades en catálogos administrables** en vez de dejarlos fijos en el código. Esto facilita mucho su gestión a futuro: se les podría asignar un código interno para darles mejor seguimiento, y si la aplicación crece (por ejemplo, si se agregan más departamentos o más fases dentro del ciclo de vida de una solicitud), sería mucho más sencillo definir qué estados le corresponden a cada etapa o a cada área, sin tener que tocar código.
- **Definir permisos por usuario**, ligado a la autenticación: que cada usuario tenga un rol o privilegios específicos, de modo que al listar o gestionar solicitudes el sistema solo le muestre y le permita actuar sobre las que realmente le corresponden.
- **Permitir rechazar u observar una solicitud**, pidiendo siempre una justificación de por qué se tomó esa decisión. Así, la persona que creó la solicitud puede ver el motivo del rechazo, o si fue "observada", puede corregir la información necesaria y volver a enviarla.
- **Notificaciones y generación de PDF al aprobar una solicitud**: cuando una solicitud se apruebe, avisar por correo a la persona correspondiente, y permitir generar un PDF de la solicitud aprobada dentro del mismo sistema, por si el usuario final lo necesita como respaldo.

### ¿Cómo protegería esta funcionalidad si fuera parte de una plataforma corporativa real?

Lo primero sería que nadie pueda entrar ni hacer cambios sin antes iniciar sesión, y que cada usuario solo pueda ver y modificar lo que le corresponde según su rol dentro de la organización. Toda la comunicación entre el navegador y el servidor debería ir cifrada (HTTPS), y las contraseñas, llaves y credenciales de la base de datos nunca deberían quedar escritas directamente en el código, sino guardadas de forma segura y separadas por ambiente.

También agregaría un registro (bitácora) de quién hizo qué cambio y cuándo, especialmente para acciones sensibles como aprobar, rechazar o cambiar el estado de una solicitud, de manera que siempre se pueda saber qué pasó ante cualquier duda o reclamo. Por último, mantendría los ambientes de desarrollo, pruebas y producción completamente separados, con copias de respaldo periódicas de la base de datos y algún tipo de monitoreo que avise si algo empieza a fallar antes de que lo note un usuario.

### ¿Cómo manejaría el versionamiento y despliegue de esta funcionalidad en producción?

Trabajaría con ramas separadas para cada cambio o mejora (en vez de modificar directamente la rama principal), y cada cambio pasaría por una revisión antes de integrarse, tal como se hizo en este proyecto con commits pequeños y descriptivos que facilitan entender el historial y, si algo sale mal, revertir solo esa parte puntual sin afectar el resto.

Antes de que un cambio llegue a producción, me apoyaría en un proceso automático que corra las pruebas y valide que todo sigue funcionando correctamente, y pasaría primero por un ambiente de pruebas donde se pueda verificar con calma antes de exponerlo a los usuarios reales. Ya en producción, el despliegue se haría de forma controlada (idealmente sin tiempo de caída), con un respaldo de la base de datos previo a aplicar cualquier cambio, y con la posibilidad de regresar a la versión anterior rápidamente si algo no sale como se esperaba.
