# CineHub

## 1. Descripción del proyecto

**CineHub** será una aplicación web inspirada en Filmaffinity e IMDb para consultar películas y series, valorar contenidos y organizar una biblioteca personal.

La aplicación permitirá a los usuarios descubrir información sobre películas y series, consultar sus fichas, escribir reseñas, puntuar contenidos y crear listas personales. No tendrá funciones de recomendación ni ninguna otra función de inteligencia artificial para el usuario final.


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

## 3. Funcionalidades de la aplicación

### 3.1 Usuarios

- Registro de usuario.
- Inicio de sesión.
- Cierre de sesión.
- Recuperación de contraseña, si se desea incluir.
- Edición del perfil.
- Consulta de las reseñas y valoraciones realizadas.

### 3.2 Catálogo

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

### 3.3 Búsqueda y filtros

Los usuarios podrán buscar contenidos por título y aplicar filtros por:

- Tipo de contenido.
- Género.
- Año de estreno.
- Puntuación media.
- Estado de publicación.

Los resultados se mostrarán paginados para evitar cargar todo el catálogo en una única petición.

### 3.4 Valoraciones

Los usuarios autenticados podrán puntuar una película o serie con una puntuación de 1 a 10.

Reglas:

- Un usuario solo tendrá una valoración activa por contenido.
- Si vuelve a valorar el mismo contenido, se actualizará la valoración anterior.
- La puntuación media se calculará a partir de las valoraciones existentes.
- Las valoraciones deberán pertenecer a usuarios y contenidos válidos.

### 3.5 Reseñas y comentarios

Los usuarios autenticados podrán escribir una reseña sobre una película o serie.

La reseña tendrá:

- Título opcional.
- Texto.
- Fecha de publicación.
- Fecha de modificación.
- Estado de moderación.

El usuario podrá editar o eliminar sus propias reseñas. El administrador podrá ocultar o eliminar reseñas que incumplan las normas de la aplicación.

### 3.6 Listas personales

Cada usuario podrá organizar contenidos mediante listas como:

- Favoritos.
- Pendientes de ver.
- Vistos.

Para evitar duplicados, un mismo contenido no podrá aparecer dos veces en la misma lista del mismo usuario.

También se podrá permitir que el usuario cree listas personalizadas, por ejemplo, “Películas para el verano” o “Series pendientes”.

### 3.7 Panel de administración

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

## 4. Funciones que no tendrá el proyecto

Para mantener claramente definido el alcance, CineHub no incluirá:

- Recomendaciones generadas por inteligencia artificial.
- Chatbot.
- Búsqueda semántica.
- Resúmenes generados automáticamente.
- Clasificación automática de películas.
- Análisis automático de sentimientos.
- Funciones de IA visibles para los usuarios.
- Despliegue obligatorio en Internet.

## 
Fuente de datos del catálogo

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

## Datos de demostración

La aplicación incluye seeders para preparar una base de datos local completa. Después de configurar MySQL, ejecutar:

```bash
php artisan migrate --seed
```

Si las migraciones ya están ejecutadas:

```bash
php artisan db:seed
```

Se crearán usuarios, películas, series, géneros, personas, créditos, valoraciones, reseñas, comentarios y una lista de favoritos.

Las credenciales de demostración son:

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | `admin@cinehub.test` | `password` |
| Administrador root | `root@cinehub.test` | `root` |
| Usuario | `user@cinehub.test` | `password` |

Estas credenciales solo deben utilizarse en el entorno local.
