# Diseño de autenticación y autorización

## Arquitectura

La funcionalidad utiliza la autenticación de sesiones incluida en Laravel:

1. El usuario envía un formulario Blade.
2. Un Form Request valida los datos.
3. El controlador ejecuta la operación.
4. Laravel autentica mediante el guard `web`.
5. El middleware protege las rutas privadas.
6. El middleware `admin` comprueba el rol antes de permitir el acceso administrativo.

## Componentes principales

### Modelo

`App\Models\User` utiliza `Authenticatable` y contiene:

- `name`
- `email`
- `password`
- `role`
- `avatar_path`

El método `isAdmin()` devuelve `true` únicamente cuando `role` es `admin`.

### Controladores

- `RegisteredUserController`: registro de usuarios.
- `AuthenticatedSessionController`: login y logout.
- `ProfileController`: consulta y modificación del perfil.

### Form Requests

- `RegisterRequest`: valida el registro.
- `LoginRequest`: valida y autentica las credenciales.
- `ProfileUpdateRequest`: valida los cambios de perfil.

### Middleware

`EnsureUserIsAdmin` comprueba que el usuario autenticado tiene rol `admin`. Si no lo tiene, devuelve HTTP `403`.

El alias se registra en `bootstrap/app.php`:

```php
'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class
```

## Rutas

| Método | Ruta | Middleware | Función |
|---|---|---|---|
| GET | `/register` | `guest` | Formulario de registro |
| POST | `/register` | `guest` | Crear usuario |
| GET | `/login` | `guest` | Formulario de login |
| POST | `/login` | `guest` | Autenticar usuario |
| POST | `/logout` | `auth` | Cerrar sesión |
| GET | `/dashboard` | `auth` | Página privada |
| GET | `/profile` | `auth` | Mostrar perfil |
| PUT | `/profile` | `auth` | Actualizar perfil |
| GET | `/admin` | `auth`, `admin` | Panel administrativo |

## Seguridad

- El rol no se acepta desde el formulario de registro.
- El registro fuerza el rol `user`.
- El campo `role` no se incluye en el formulario de perfil.
- Las contraseñas se cifran mediante el cast `hashed` del modelo `User`.
- El logout invalida la sesión y regenera el token CSRF.
- Las rutas administrativas se protegen en el servidor.

## Factories y datos de demostración

Las factories de `database/factories/` permiten generar datos coherentes para pruebas:

- Usuarios.
- Títulos.
- Géneros.
- Personas.
- Créditos.
- Valoraciones.
- Reseñas.
- Listas.
- Títulos de listas.
- Comentarios.
- Relaciones entre títulos y géneros.

El `DatabaseSeeder` ejecuta los seeders en este orden:

1. `UsersSeeder`
2. `CatalogSeeder`
3. `DemoInteractionsSeeder`

`CatalogSeeder` crea géneros, personas, películas, series y créditos. `DemoInteractionsSeeder` crea valoraciones, reseñas, comentarios y una lista de favoritos.

Los seeders utilizan operaciones como `updateOrCreate`, `firstOrCreate` y `syncWithoutDetaching` para poder ejecutarse varias veces sin duplicar los datos principales.
