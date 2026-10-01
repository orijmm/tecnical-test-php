## Prueba Tecnica

Proyecto en Laravel 13 (version instalada: 13.34.0). Requiere PHP 8.3 o superior y Composer.

### Configuracion inicial

```bash
composer install
cp .env.example .env
php artisan key:generate
```

La aplicacion utiliza MySQL. Crea una base de datos vacia y revisa estas variables en `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=technical_test
DB_USERNAME=root
DB_PASSWORD=
```

Ajusta el nombre, usuario y contrasena segun tu instalacion de MySQL. Luego ejecuta las migraciones y los seeders:

```bash
php artisan migrate
php artisan db:seed
```

`db:seed` ejecuta `SettingsSeeder`, que crea los roles y usuarios de demostracion.

Para iniciar la aplicacion:

```bash
php artisan serve
```

### Uso de la API con Postman y cURL

- La URL base es `http://127.0.0.1:8000/api`. 

- En Postman, agrega el header `Accept: application/json` a todas las peticiones.
- Para las que envian JSON agrega tambien `Content-Type: application/json` y selecciona **Body > raw > JSON**.-

1. Solicita un token con `POST /sanctum/token`. En el body JSON usa un usuario creado por el seeder, por ejemplo `{"email":"admin@example.com","password":"password"}`. Copia el valor `token` de la respuesta.
2. Para las demas rutas, en **Authorization > Type > Bearer Token** pega el token. En cURL, reemplaza `TU_TOKEN` por ese valor en el ejemplo de abajo.

| Metodo | Ruta en Postman | Body JSON | Descripcion |
| --- | --- | --- | --- |
| GET | `/` | - | Comprueba que la API responde (sin token). |
| POST | `/sanctum/token` | `{"email":"admin@example.com","password":"password"}` | Obtiene el token (sin token previo). |
| GET | `/tickets` | - | Lista tickets; permite filtrar con `?status=open`. |
| POST | `/tickets` | `{"subject":"Error de acceso","description":"No puedo iniciar sesion","priority":"high"}` | Crea un ticket. Prioridades: `low`, `medium`, `high`, `urgent`. |
| GET | `/tickets/1` | - | Consulta el ticket 1. |
| PATCH | `/tickets/1/assign` | `{"agent_id":1}` | Asigna el ticket 1 a un agente; consulta `GET /users` para obtener su ID. |
| GET | `/users` | - | Lista usuarios y sus roles. |

En Postman, antepone la URL base a cada ruta de la tabla (por ejemplo, `GET http://127.0.0.1:8000/api/tickets`). En cURL, los mismos pasos son:

```bash
# Comprobar la API y obtener un token
curl -H 'Accept: application/json' http://127.0.0.1:8000/api
curl -X POST http://127.0.0.1:8000/api/sanctum/token \
	-H 'Accept: application/json' -H 'Content-Type: application/json' \
	-d '{"email":"cliente@example.com","password":"password"}'

# Copia el campo "token" de la respuesta anterior
TOKEN="TU_TOKEN"

curl http://127.0.0.1:8000/api/tickets \
	-H 'Accept: application/json' -H "Authorization: Bearer $TOKEN"
curl -X POST http://127.0.0.1:8000/api/tickets \
	-H 'Accept: application/json' -H 'Content-Type: application/json' \
	-H "Authorization: Bearer $TOKEN" \
	-d '{"subject":"Error de acceso","description":"No puedo iniciar sesion","priority":"high"}'
curl http://127.0.0.1:8000/api/tickets/1 \
	-H 'Accept: application/json' -H "Authorization: Bearer $TOKEN"
curl http://127.0.0.1:8000/api/users \
	-H 'Accept: application/json' -H "Authorization: Bearer $TOKEN"
curl -X PATCH http://127.0.0.1:8000/api/tickets/1/assign \
	-H 'Accept: application/json' -H 'Content-Type: application/json' \
	-H "Authorization: Bearer $TOKEN" -d '{"agent_id":2}'
```

Sustituye `1` por el ID del ticket creado y `2` por el ID real de un usuario con rol de agente obtenido en `/users`.