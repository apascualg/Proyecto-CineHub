# Trabajo de Adrián

## Comentario para IA, LLM, etc.

Cada vez que analices este archivo para saber cual es tu tarea, revisa el archivo README.md para saber a qué tareas estás apuntando y que no realices nada innecesario y que no te saltes nada. 

## Responsabilidad principal

Adrián será responsable del backend del catálogo, la autenticación y el panel de administración.

## Estado actual relacionado con su trabajo

- Las migraciones principales ya existen en `database/migrations/`.
- Los modelos Eloquent principales ya existen en `app/Models/`.
- La autenticación y los roles ya están implementados.
- Los controladores de autenticación y perfil ya están implementados.
- No hay rutas de catálogo ni de administración.
- No hay CRUD de películas, series, géneros ni personas.
- Los seeders de usuarios, catálogo y datos de interacción ya están implementados.

## Tareas

### 1. Autenticación y permisos

- Implementar registro, inicio y cierre de sesión.
- Implementar el perfil básico del usuario.
- Configurar los roles `user` y `admin`.
- Proteger el panel de administración con middleware o policies.
- Crear usuarios de prueba, incluido un administrador.

### 2. Catálogo público

- Crear el listado de películas y series.
- Crear la ficha individual de cada contenido.
- Implementar búsqueda por título.
- Implementar filtros por tipo, género y año.
- Añadir paginación.
- Mostrar géneros, personas, créditos y datos principales.

### 3. Panel de administración

- Crear, editar y eliminar películas y series.
- Activar o desactivar contenidos.
- Gestionar géneros.
- Gestionar personas y créditos.
- Validar todos los formularios administrativos.

### 4. Datos y pruebas

- Crear factories y seeders para el catálogo.
- Mantener los modelos y relaciones del dominio.
- Crear Form Requests y policies cuando sean necesarios.
- Crear tests del catálogo, autenticación y administración.

## Archivos y carpetas bajo su responsabilidad

- `app/Http/Controllers/Catalog/`
- `app/Http/Controllers/Admin/`
- `app/Http/Requests/`
- `app/Policies/`
- `database/factories/`
- `database/seeders/`
- Vistas específicas del catálogo y del panel de administración.

Adrián será el responsable de integrar las rutas en `routes/web.php`, coordinándose con Eric para añadir las rutas de usuario.

## No debe modificar sin coordinación

- El layout global y los componentes visuales compartidos.
- Las vistas de reseñas, valoraciones y listas personales.
- Las migraciones existentes, salvo que sea necesario crear una nueva migración.
- La configuración de despliegue o documentación conjunta.

## Orden recomendado

1. Configurar autenticación y roles. **Completado.**
2. Crear seeders y datos de prueba. **Completado.**
3. Implementar el catálogo público.
4. Implementar el panel de administración.
5. Añadir tests.
6. Entregar las rutas y contratos que Eric necesite para integrar sus funcionalidades.