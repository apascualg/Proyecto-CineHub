# Instalación y configuración

## Requisitos

- PHP 8.3 o superior.
- Composer.
- Node.js y npm.
- MySQL.
- Extensión PHP `pdo_mysql`.

SQLite no es necesario para ejecutar la aplicación con MySQL. Si PHPUnit mantiene la configuración actual de SQLite en memoria, también será necesario habilitar `pdo_sqlite` para ejecutar los tests.

## Instalación

Desde la raíz del proyecto:

```bash
composer install
npm install
```

Crear el archivo de entorno:

```bash
copy .env.example .env
php artisan key:generate
```

## Configuración de MySQL

Crear una base de datos para CineHub y configurar el `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinehub
DB_USERNAME=root
DB_PASSWORD=
```

La contraseña real debe mantenerse fuera de Git.

## Base de datos y usuarios de prueba

Ejecutar:

```bash
php artisan migrate --seed
```

El seeding ejecuta únicamente `TmdbCatalogSeeder`. Importa películas y series desde TMDB si existen credenciales válidas; no crea usuarios ni datos de demostración.

La carga automática del catálogo continúa en segundo plano hasta 2040 títulos publicados (20 páginas de 102). Mantén un worker activo con `php artisan queue:work`.

Si las migraciones ya están ejecutadas, se puede ejecutar solo el seeder:

```bash
php artisan db:seed
```

Para reiniciar completamente la base de datos local:

```bash
php artisan migrate:fresh --seed
```

> Este comando elimina todas las tablas y datos existentes. Solo debe utilizarse en desarrollo.

Las cuentas de demostración han sido eliminadas. Crea usuarios mediante el registro de la aplicación.

## Ejecutar la aplicación

```bash
php artisan serve
npm run dev
```

La aplicación estará disponible normalmente en `http://127.0.0.1:8000`.

## Ejecutar los tests

```bash
php artisan test tests/Feature/Auth tests/Feature/Profile tests/Feature/Database
```

Los tests actuales utilizan SQLite en memoria según `phpunit.xml`. Para ejecutarlos hay que habilitar `pdo_sqlite` o cambiar PHPUnit a una base de datos MySQL exclusiva para pruebas.
