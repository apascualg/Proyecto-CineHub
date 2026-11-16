# Trabajo de Eric

## Responsabilidad principal

Eric será responsable de la interfaz compartida y de las funciones que los usuarios realizan sobre películas y series.

## Estado actual relacionado con su trabajo

- Las tablas de valoraciones, reseñas, comentarios y listas ya existen.
- Sus modelos Eloquent ya existen en `app/Models/`.
- No hay controladores para estas funcionalidades.
- No hay vistas de usuario ni formularios.
- No hay rutas protegidas para usuarios autenticados.
- No hay tests funcionales de estas operaciones.

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

- Implementar favoritos, pendientes y vistos.
- Permitir listas personalizadas si el tiempo lo permite.
- Evitar que un contenido aparezca duplicado en una misma lista.
- Permitir consultar y modificar las listas del usuario.

### 6. Perfil y pruebas

- Mostrar las reseñas y valoraciones realizadas por el usuario.
- Mostrar sus listas personales.
- Crear tests de valoraciones, reseñas, comentarios, listas y permisos.

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