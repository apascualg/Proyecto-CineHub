# CineHub

## 1. Descripción del proyecto

**CineHub** será una aplicación web inspirada en Filmaffinity e IMDb para consultar películas y series, valorar contenidos y organizar una biblioteca personal.

La aplicación permitirá a los usuarios descubrir información sobre películas y series, consultar sus fichas, escribir reseñas, puntuar contenidos y crear listas personales. No tendrá funciones de recomendación ni ninguna otra función de inteligencia artificial para el usuario final.

La inteligencia artificial se utilizará únicamente como herramienta de apoyo durante el desarrollo del proyecto, por ejemplo, para ayudar a generar código, proponer tests, revisar errores y redactar documentación.

El proyecto no requiere despliegue en Internet. La aplicación se ejecutará y se demostrará en un entorno local durante la presentación.

## 2. Objetivos

### Objetivo general

Desarrollar una aplicación web funcional para gestionar y valorar un catálogo de películas y series utilizando Laravel como backend, una base de datos relacional y GitHub como plataforma de control de versiones.

### Objetivos específicos

- Implementar una aplicación con arquitectura organizada y mantenible.
- Crear un catálogo de películas y series.
- Permitir la búsqueda y filtrado de contenidos.
- Implementar autenticación de usuarios.
- Permitir valoraciones y reseñas.
- Permitir la creación de listas personales.
- Crear un panel de administración básico.
- Aplicar validaciones y permisos.
- Utilizar migraciones, seeders, factories y tests de Laravel.
- Mantener las credenciales fuera del repositorio.
- Documentar la instalación, configuración y uso del proyecto.

## 3. Requisitos tecnológicos

### Backend

- Laravel.
- PHP compatible con la versión elegida de Laravel.
- Laravel Eloquent ORM.
- Laravel Migrations.
- Laravel Seeders y Factories.
- Laravel Sanctum si se expone una API autenticada.

### Frontend

El frontend será libre. Se propone una de estas opciones:

- Blade + Livewire, para reducir la complejidad y mantener todo dentro de Laravel.
- Vue + Inertia.js, para una interfaz más dinámica.

La opción recomendada para este proyecto es **Blade + Livewire**, porque permite desarrollar una aplicación completa con Laravel sin tener que mantener un frontend y un backend completamente separados.

### Base de datos

Se utilizará una base de datos relacional. Las opciones válidas son:

- MySQL.
- PostgreSQL.
- SQLite.

Para el desarrollo local se recomienda **SQLite** por su sencillez. También se puede utilizar MySQL si se desea demostrar una configuración más cercana a un entorno profesional.

### Control de versiones

El proyecto utilizará obligatoriamente GitHub para:

- Alojar el código fuente.
- Gestionar ramas.
- Registrar cambios mediante commits.
- Crear issues.
- Organizar el trabajo.
- Revisar cambios mediante pull requests.
- Ejecutar comprobaciones automáticas con GitHub Actions.

## 4. Funcionalidades de la aplicación

### 4.1 Usuarios

- Registro de usuario.
- Inicio de sesión.
- Cierre de sesión.
- Recuperación de contraseña, si se desea incluir.
- Edición del perfil.
- Consulta de las reseñas y valoraciones realizadas.

### 4.2 Catálogo

Cada contenido podrá ser una película o una serie.

La ficha de un contenido podrá mostrar:

- Título.
- Título original.
- Tipo: película o serie.
- Sinopsis.
- Fecha de estreno.
- Duración o número de temporadas.
- Puntuación media de los usuarios.
- Póster.
- Imagen de portada.
- Tráiler, si existe.
- Géneros.
- Director o creador.
- Reparto principal.
- Reseñas de los usuarios.

### 4.3 Búsqueda y filtros

Los usuarios podrán buscar contenidos por título y aplicar filtros por:

- Tipo de contenido.
- Género.
- Año de estreno.
- Puntuación media.
- Estado de publicación.

Los resultados se mostrarán paginados para evitar cargar todo el catálogo en una única petición.

### 4.4 Valoraciones

Los usuarios autenticados podrán puntuar una película o serie con una puntuación de 1 a 10.

Reglas:

- Un usuario solo tendrá una valoración activa por contenido.
- Si vuelve a valorar el mismo contenido, se actualizará la valoración anterior.
- La puntuación media se calculará a partir de las valoraciones existentes.
- Las valoraciones deberán pertenecer a usuarios y contenidos válidos.

### 4.5 Reseñas y comentarios

Los usuarios autenticados podrán escribir una reseña sobre una película o serie.

La reseña tendrá:

- Título opcional.
- Texto.
- Fecha de publicación.
- Fecha de modificación.
- Estado de moderación.

El usuario podrá editar o eliminar sus propias reseñas. El administrador podrá ocultar o eliminar reseñas que incumplan las normas de la aplicación.

### 4.6 Listas personales

Cada usuario podrá organizar contenidos mediante listas como:

- Favoritos.
- Pendientes de ver.
- Vistos.

Para evitar duplicados, un mismo contenido no podrá aparecer dos veces en la misma lista del mismo usuario.

También se podrá permitir que el usuario cree listas personalizadas, por ejemplo, “Películas para el verano” o “Series pendientes”.

### 4.7 Panel de administración

El administrador podrá:

- Crear películas y series.
- Editar contenidos.
- Eliminar o desactivar contenidos.
- Gestionar géneros.
- Gestionar personas del reparto.
- Consultar usuarios.
- Moderar reseñas.
- Revisar estadísticas básicas del catálogo.

El acceso al panel se protegerá mediante roles y middleware de autorización.

## 5. Funciones que no tendrá el proyecto

Para mantener claramente definido el alcance, CineHub no incluirá:

- Recomendaciones generadas por inteligencia artificial.
- Chatbot.
- Búsqueda semántica.
- Resúmenes generados automáticamente.
- Clasificación automática de películas.
- Análisis automático de sentimientos.
- Funciones de IA visibles para los usuarios.
- Despliegue obligatorio en Internet.

La inteligencia artificial solo se empleará durante el desarrollo como herramienta de asistencia.

## 6. Fuente de datos del catálogo

Se pueden utilizar dos estrategias:

### Opción A: catálogo propio

El administrador introducirá las películas, series, géneros y personas desde el panel de administración. Esta opción no necesita ninguna API externa y simplifica la gestión de credenciales.

### Opción B: importación desde TMDB

Se podrá utilizar la API de TMDB para importar datos cinematográficos. En ese caso:

- La clave se guardará en el archivo `.env`.
- El archivo `.env` se incluirá en `.gitignore`.
- El repositorio solo tendrá un archivo `.env.example`.
- Nunca se incluirá una clave real en commits, issues o documentación pública.

La integración con TMDB será opcional y no constituye una función de inteligencia artificial.

## 7. Diseño de la base de datos

Se propone utilizar una tabla común llamada `titles` para representar películas y series. El campo `type` indicará si el contenido es una película o una serie.

Este diseño evita duplicar tablas y permite que las valoraciones, reseñas, listas y géneros funcionen de la misma manera para ambos tipos de contenido.

### 7.1 Tabla `users`

Almacena las cuentas de los usuarios.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador del usuario |
| `name` | VARCHAR(255) | Nombre visible |
| `email` | VARCHAR(255), UNIQUE | Correo electrónico |
| `password` | VARCHAR(255) | Contraseña cifrada |
| `role` | VARCHAR(30) | `user` o `admin` |
| `avatar_path` | VARCHAR(255), NULL | Ruta del avatar |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

La contraseña nunca se almacenará en texto plano. Laravel la guardará mediante hashing.

### 7.2 Tabla `titles`

Almacena películas y series.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador del contenido |
| `type` | VARCHAR(20) | `movie` o `series` |
| `title` | VARCHAR(255) | Título mostrado |
| `original_title` | VARCHAR(255), NULL | Título original |
| `slug` | VARCHAR(255), UNIQUE | Identificador legible para URLs |
| `synopsis` | TEXT, NULL | Sinopsis |
| `release_date` | DATE, NULL | Fecha de estreno |
| `duration_minutes` | INTEGER, NULL | Duración de una película |
| `seasons_count` | INTEGER, NULL | Número de temporadas |
| `episodes_count` | INTEGER, NULL | Número de episodios |
| `poster_path` | VARCHAR(255), NULL | Ruta del póster |
| `backdrop_path` | VARCHAR(255), NULL | Ruta de la imagen de portada |
| `trailer_url` | VARCHAR(500), NULL | URL del tráiler |
| `status` | VARCHAR(30) | `draft`, `published` o `archived` |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

### 7.3 Tabla `genres`

Almacena los géneros disponibles.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador del género |
| `name` | VARCHAR(100), UNIQUE | Nombre del género |
| `slug` | VARCHAR(120), UNIQUE | Nombre para URLs |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

Ejemplos: acción, comedia, drama, ciencia ficción, terror, animación y documental.

### 7.4 Tabla `genre_title`

Tabla intermedia para relacionar contenidos y géneros.

| Campo | Tipo | Descripción |
|---|---|---|
| `title_id` | BIGINT, FK | Contenido relacionado |
| `genre_id` | BIGINT, FK | Género relacionado |

La clave primaria será compuesta por `title_id` y `genre_id`.

### 7.5 Tabla `people`

Almacena las personas relacionadas con los contenidos.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador de la persona |
| `name` | VARCHAR(255) | Nombre |
| `biography` | TEXT, NULL | Biografía |
| `birth_date` | DATE, NULL | Fecha de nacimiento |
| `photo_path` | VARCHAR(255), NULL | Fotografía |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

### 7.6 Tabla `credits`

Relaciona personas con películas o series.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador de la relación |
| `title_id` | BIGINT, FK | Contenido |
| `person_id` | BIGINT, FK | Persona |
| `role_type` | VARCHAR(30) | `actor`, `director`, `creator` o `writer` |
| `character_name` | VARCHAR(255), NULL | Personaje interpretado |
| `billing_order` | INTEGER, NULL | Orden de aparición |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

### 7.7 Tabla `ratings`

Almacena la puntuación que cada usuario da a un contenido.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador |
| `user_id` | BIGINT, FK | Usuario que puntúa |
| `title_id` | BIGINT, FK | Contenido puntuado |
| `score` | TINYINT o SMALLINT | Valor entre 1 y 10 |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

Debe existir una restricción única sobre `user_id` y `title_id`.

### 7.8 Tabla `reviews`

Almacena las reseñas escritas por los usuarios.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador |
| `user_id` | BIGINT, FK | Autor |
| `title_id` | BIGINT, FK | Contenido reseñado |
| `headline` | VARCHAR(255), NULL | Título de la reseña |
| `body` | TEXT | Texto |
| `status` | VARCHAR(30) | `pending`, `published` o `hidden` |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

### 7.9 Tabla `watchlists`

Representa las listas de un usuario.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador |
| `user_id` | BIGINT, FK | Propietario |
| `name` | VARCHAR(100) | Nombre de la lista |
| `type` | VARCHAR(30) | `favorites`, `watch_later`, `watched` o `custom` |
| `description` | TEXT, NULL | Descripción |
| `is_public` | BOOLEAN | Si otros usuarios pueden verla |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

### 7.10 Tabla `watchlist_title`

Relaciona contenidos con listas.

| Campo | Tipo | Descripción |
|---|---|---|
| `watchlist_id` | BIGINT, FK | Lista |
| `title_id` | BIGINT, FK | Contenido |
| `added_at` | TIMESTAMP | Fecha en que se añadió |

La clave primaria será compuesta por `watchlist_id` y `title_id`.

### 7.11 Tabla `comments`

Esta tabla es opcional. Se puede utilizar si se desea permitir comentarios sobre reseñas.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador |
| `user_id` | BIGINT, FK | Autor |
| `review_id` | BIGINT, FK | Reseña comentada |
| `body` | TEXT | Texto del comentario |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

### 7.12 Tabla `personal_access_tokens`

Laravel Sanctum puede crear esta tabla si se utiliza autenticación mediante tokens para una API.

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | BIGINT, PK | Identificador |
| `tokenable_type` | VARCHAR(255) | Tipo de modelo |
| `tokenable_id` | BIGINT | Identificador del modelo |
| `name` | VARCHAR(255) | Nombre del token |
| `token` | VARCHAR(64), UNIQUE | Token cifrado |
| `abilities` | TEXT, NULL | Permisos |
| `last_used_at` | TIMESTAMP, NULL | Último uso |
| `expires_at` | TIMESTAMP, NULL | Caducidad |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de modificación |

## 8. Relaciones principales

- Un usuario puede tener muchas valoraciones.
- Un usuario puede escribir muchas reseñas.
- Un usuario puede tener muchas listas.
- Una película o serie puede tener muchas valoraciones.
- Una película o serie puede tener muchas reseñas.
- Una película o serie puede pertenecer a muchos géneros.
- Un género puede estar asociado a muchas películas y series.
- Una película o serie puede tener muchas personas relacionadas.
- Una persona puede participar en muchos contenidos.
- Una lista puede contener muchos contenidos.
- Un contenido puede aparecer en muchas listas.

## 9. Rutas principales

### Rutas públicas

```text
GET  /
GET  /titles
GET  /titles/{title}
GET  /genres/{genre}
GET  /search
```

### Rutas autenticadas

```text
GET    /profile
PUT    /profile
POST   /titles/{title}/ratings
PUT    /titles/{title}/ratings
POST   /titles/{title}/reviews
PUT    /reviews/{review}
DELETE /reviews/{review}
POST   /watchlists
POST   /watchlists/{watchlist}/titles
DELETE /watchlists/{watchlist}/titles/{title}
```

### Rutas de administración

```text
GET    /admin
GET    /admin/titles
POST   /admin/titles
PUT    /admin/titles/{title}
DELETE /admin/titles/{title}
GET    /admin/users
GET    /admin/reviews
PUT    /admin/reviews/{review}/status
```

## 10. Seguridad y gestión de secretos

El repositorio no deberá contener contraseñas, claves API ni tokens reales.

Medidas obligatorias:

- Incluir `.env` en `.gitignore`.
- Añadir un archivo `.env.example` sin valores secretos.
- Utilizar el sistema de hashing de Laravel para las contraseñas.
- Validar todos los formularios mediante Form Requests o reglas de validación.
- Utilizar autorización mediante middleware, policies o gates.
- Escapar correctamente el contenido mostrado en las vistas.
- Proteger las rutas administrativas.
- No guardar claves en el código fuente.
- Revisar el historial de Git antes de publicar el repositorio.

Ejemplo de `.env.example`:

```env
APP_NAME=CineHub
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

TMDB_API_KEY=
```

El valor real de `APP_KEY`, las contraseñas y cualquier clave externa se crearán únicamente en el entorno local.

## 11. Organización recomendada de GitHub

El repositorio puede organizarse con:

- Rama `main` para versiones estables.
- Rama `develop` para integración.
- Ramas de funcionalidad, por ejemplo `feature/catalogo` o `feature/valoraciones`.
- Issues para cada funcionalidad.
- Pull requests para integrar cambios.
- Etiquetas como `backend`, `frontend`, `database`, `testing` y `documentation`.
- Un README con instrucciones claras.
- GitHub Actions para ejecutar tests y comprobaciones básicas.

Ejemplos de issues:

1. Crear proyecto Laravel.
2. Diseñar migraciones de la base de datos.
3. Implementar autenticación.
4. Crear catálogo de películas y series.
5. Añadir búsqueda y filtros.
6. Implementar valoraciones.
7. Implementar reseñas.
8. Crear listas personales.
9. Crear panel de administración.
10. Añadir tests y documentación.

## 12. Tests recomendados

Se crearán tests para comprobar:

- Registro e inicio de sesión.
- Acceso restringido a usuarios autenticados.
- Acceso restringido al panel de administración.
- Creación y edición de contenidos.
- Búsqueda y filtros.
- Valoraciones entre 1 y 10.
- Impedimento de valoraciones duplicadas.
- Creación de reseñas.
- Edición únicamente de reseñas propias.
- Añadir y eliminar contenidos de listas.
- Relaciones entre modelos.

## 13. Demostración final

La demostración se realizará localmente y podrá seguir este orden:

1. Mostrar el repositorio de GitHub.
2. Explicar la estructura de Laravel.
3. Ejecutar la aplicación en local.
4. Registrar o iniciar sesión con un usuario.
5. Consultar el catálogo.
6. Utilizar la búsqueda y los filtros.
7. Abrir la ficha de una película o serie.
8. Puntuar el contenido.
9. Escribir una reseña.
10. Añadir el contenido a favoritos o a una lista.
11. Acceder al panel de administración.
12. Mostrar las migraciones, seeders y tests.
13. Enseñar que las credenciales no están publicadas en GitHub.

## 14. Resultado esperado

El resultado será una aplicación Laravel funcional, documentada y versionada en GitHub que permita consultar y valorar películas y series, gestionar reseñas y organizar listas personales.

El proyecto cumplirá los requisitos principales:

- Backend desarrollado con Laravel.
- Frontend libre.
- Uso de una base de datos compatible.
- Sin contraseñas ni claves API en el repositorio.
- Uso obligatorio de GitHub.
- Aplicación ejecutable y demostrable localmente.
- Sin funcionalidades de inteligencia artificial para los usuarios.
- Sin necesidad de despliegue.