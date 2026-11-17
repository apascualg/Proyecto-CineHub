# Pruebas de autenticación y autorización

## Comando utilizado

```bash
php artisan test tests/Feature/Auth tests/Feature/Profile tests/Feature/Database
```

## Tests implementados

### Autenticación

- Registro correcto de un usuario.
- Asignación automática del rol `user`.
- Autenticación después del registro.
- Inicio de sesión correcto.
- Cierre de sesión correcto.
- Rechazo de credenciales incorrectas.
- Redirección de visitantes desde páginas protegidas.

### Autorización

- Un administrador puede acceder a `/admin`.
- Un usuario normal recibe HTTP `403`.
- Un usuario no puede cambiar su rol desde el perfil.

### Perfil

- Un usuario puede actualizar su nombre y email.
- No se permiten emails duplicados.
- El perfil muestra las valoraciones, reseñas y listas del usuario.

### Listas personales

- Un usuario puede añadir títulos a favoritos, pendientes y vistos.
- Un título no se duplica dentro de la misma lista.
- Un usuario puede consultar y quitar títulos de sus listas.
- Un usuario no puede ver las listas de otro usuario.

### Seeders y datos de demostración

- Se crean los usuarios de demostración.
- Se crean géneros, personas y títulos.
- Se crean créditos de directores, creadores y actores.
- Se crean valoraciones, reseñas, comentarios y listas.
- Un usuario puede crear y eliminar comentarios propios sobre reseñas visibles.
- Un usuario no puede comentar reseñas pendientes de otra persona ni borrar comentarios ajenos.
- Un administrador puede consultar, aprobar, ocultar y eliminar reseñas.
- Las relaciones entre títulos, géneros y personas son correctas.
- El seeder puede ejecutarse dos veces sin duplicar datos.

## Resultado actual

Última ejecución de la suite completa:

```text
33 tests passed
132 assertions
```

La ejecución incluye las pruebas de autenticación, perfil, autorización, catálogo, seeders, valoraciones, reseñas, comentarios y listas personales. También se verificó que el seeder puede ejecutarse dos veces sin duplicar registros.

## Validaciones adicionales

También se comprobaron:

- Sintaxis PHP de los archivos modificados.
- Registro de rutas con `php artisan route:list`.
- Compilación de vistas Blade con `php artisan view:cache`.
- Formato de código con Laravel Pint.
