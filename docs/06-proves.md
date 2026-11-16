# Pruebas de autenticación y autorización

## Comando utilizado

```bash
php artisan test tests/Feature/Auth tests/Feature/Profile
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

## Resultado actual

Última ejecución:

```text
9 tests passed
33 assertions
```

La ejecución se realizó correctamente después de corregir la redirección del controlador de perfil para utilizar explícitamente la ruta `profile.edit`.

## Validaciones adicionales

También se comprobaron:

- Sintaxis PHP de los archivos modificados.
- Registro de rutas con `php artisan route:list`.
- Compilación de vistas Blade con `php artisan view:cache`.
- Formato de código con Laravel Pint.
