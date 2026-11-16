# Release Notes
## [v1.0]
- Añadida autenticación de usuarios.
- Añadidos roles `user` y `admin`.
- Protegidas las rutas administrativas.
- Añadidos tests de autenticación y autorización.
- Fallan 2 test al hacer `php artisan test tests/Feature/Auth tests/Feature/Profile`. Se arreglará en próximas actualizaciones.

## [v1.1]
- Archivo `app\Http\Controllers\Auth\ProfileController.php` modificado. Había una redirección sin cabecera que hacía que 2 de los 9 tests dieran error.
