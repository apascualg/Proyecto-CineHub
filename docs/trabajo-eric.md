# Trabajo de Eric

## Responsabilidad principal

Eric será responsable de la interfaz compartida y de las funciones que los usuarios realizan sobre películas y series.

## Estado actual relacionado con su trabajo

- Las tablas de valoraciones, reseñas, comentarios y listas ya existen.
- Sus modelos Eloquent ya existen en `app/Models/`.
- Las factories de todos los modelos de dominio deben mantenerse disponibles para crear datos de prueba:
  - `UserFactory`
  - `TitleFactory`
  - `GenreFactory`
  - `PersonFactory`
  - `CreditFactory`
  - `RatingFactory`
  - `ReviewFactory`
  - `WatchlistFactory`
  - `WatchlistTitleFactory`
  - `CommentFactory`
  - `GenreTitleFactory`
- Ya existe `TitleInteractionController` para la ficha, las valoraciones y las reseñas.
- Ya existe la vista de detalle `resources/views/titles/show.blade.php` con sus formularios.
- Ya existen rutas protegidas para consultar títulos, guardar valoraciones y gestionar reseñas propias.
- Ya existen tests funcionales específicos de valoraciones y reseñas en `tests/Feature/User/TitleInteractionTest.php`.

## Punto alcanzado al cierre de la jornada

- El layout global, la navegación y el diseño responsive están terminados.
- El catálogo permite buscar por título y aplicar filtros de tipo, género, año, puntuación, director y actor.
- Las tarjetas del catálogo enlazan con la ficha individual de cada título.
- La ficha permite valorar del 1 al 10 y actualizar la valoración existente.
- La ficha permite crear, editar y eliminar reseñas propias.
- Las reseñas nuevas quedan pendientes de moderación y se muestran las aprobadas.
- Las valoraciones y reseñas tienen cobertura funcional de creación, actualización, eliminación, validación, permisos y moderación.
- Los usuarios pueden gestionar listas de favoritos, pendientes y vistos sin duplicar títulos.
- El perfil muestra las valoraciones, reseñas y listas personales del usuario.
- La compilación de Vite, las rutas, la sintaxis PHP y las vistas Blade se han validado correctamente.

### Cierre de la parte de Eric

- Se completó la prueba de integración de las funciones de usuario.
- La suite completa, las rutas, las vistas Blade y la compilación frontend están validadas.
- Queda pendiente únicamente la integración final del equipo y la entrega del proyecto.

## Tareas

### 1. Interfaz común

- Crear el layout principal.
- Crear la navegación.
- Crear componentes reutilizables.
- Añadir mensajes de validación, éxito y error.
- Preparar una interfaz responsive con Blade o Blade + Livewire.

### 2. Valoraciones

- Permitir puntuar contenidos del 1 al 10.
- Permitir actualizar la valoración existente.
- Mostrar la media y el número de valoraciones.
- Validar que un usuario no pueda crear duplicados.

### 3. Reseñas

- Crear reseñas con título opcional y texto.
- Permitir editar y eliminar las propias reseñas.
- Mostrar las reseñas aprobadas en la ficha del contenido.
- Mostrar el estado de moderación cuando corresponda.

### 4. Comentarios

- Crear comentarios sobre reseñas.
- Mostrar los comentarios.
- Permitir eliminar los propios comentarios.

### 5. Listas personales

- Implementar favoritos, pendientes y vistos. **Completado.**
- Permitir listas personalizadas si el tiempo lo permite.
- Evitar que un contenido aparezca duplicado en una misma lista. **Completado.**
- Permitir consultar y modificar las listas del usuario. **Completado.**

### 6. Perfil y pruebas

- Mostrar las reseñas y valoraciones realizadas por el usuario. **Completado.**
- Mostrar sus listas personales. **Completado.**
- Crear tests de valoraciones, reseñas, comentarios, listas y permisos. **Completado para las funcionalidades implementadas.**

## Archivos y carpetas bajo su responsabilidad

- `app/Http/Controllers/User/`
- Controladores de reseñas, valoraciones, comentarios y listas.
- `resources/views/layouts/`
- `resources/views/components/`
- `resources/views/user/`
- Tests de las funciones de usuario.

Eric debe entregar a Adrián las rutas necesarias para integrarlas en `routes/web.php`; no debe reorganizar las rutas del catálogo o administración.

## No debe modificar sin coordinación

- Las migraciones existentes.
- La estructura de autenticación y roles.
- Los controladores del catálogo y administración.
- El layout de administración.
- La lógica de creación y edición del catálogo.

## Orden recomendado

1. Crear el layout y la navegación.
2. Esperar a que esté disponible la autenticación.
3. Implementar valoraciones.
4. Implementar reseñas y comentarios.
5. Implementar listas personales.
6. Integrar todas las funcionalidades en la ficha del catálogo.
7. Añadir tests.