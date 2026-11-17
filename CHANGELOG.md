# Release Notes
## [v1.0]
- Añadida autenticación de usuarios.
- Añadidos roles `user` y `admin`.
- Protegidas las rutas administrativas.
- Añadidos tests de autenticación y autorización.
- Fallan 2 test al hacer `php artisan test tests/Feature/Auth tests/Feature/Profile`. Se arreglará en próximas actualizaciones.

## [v1.1]
- Archivo `app\Http\Controllers\Auth\ProfileController.php` modificado. Había una redirección sin cabecera que hacía que 2 de los 9 tests dieran error.

## [v1.2]
- Añadidas factories para todos los modelos del dominio.
- Añadidos seeders de usuarios, catálogo e interacciones de demostración.
- Añadidas películas, series, géneros, personas, créditos, valoraciones, reseñas, comentarios y listas de prueba.
- Verificada la ejecución repetida de los seeders sin duplicar datos.
- Añadidas pruebas específicas para los seeders.
## [v1.2.1]
- Errores de filtraje parcialmente arreglados.
## [v1.2.2]
- Errores de filtraje corregidos.
- Añadidos comentarios sobre reseñas y moderación administrativa.
- Añadidas listas personales de favoritos, pendientes y vistos.
- Ampliado el perfil con valoraciones, reseñas y listas.
- Añadido el usuario administrador local `root@cinehub.test`.
- Añadidas listas personalizadas, estadísticas personales y el botón «Sorpréndeme».
- Añadidos filtros de estado para la moderación de reseñas.
- La suite completa queda en 40 tests superados.
