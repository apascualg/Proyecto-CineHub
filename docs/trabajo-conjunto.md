# Trabajo conjunto

## Resumen del estado actual

CineHub está en una fase inicial de desarrollo:

- Laravel está configurado.
- Las migraciones y los modelos principales ya existen.
- Las relaciones básicas del dominio están creadas.
- Solo existe la ruta inicial y la vista de bienvenida de Laravel.
- No hay autenticación, catálogo funcional, panel de administración ni funcionalidades de usuario.
- Solo existen tests de ejemplo.
- El seeder solo crea un usuario de prueba.

La información funcional completa está en `README.md` y la propuesta inicial en `docs/01-proposta.md`.

## Decisiones que deben tomar juntos

1. Confirmar si se utilizará SQLite o MySQL.
2. Elegir Blade puro o Blade + Livewire.
3. Confirmar si será obligatorio desplegar la aplicación mediante una URL, ya que `docs/directius-projecte.md` lo exige aunque el README lo presenta como opcional.
4. Definir los nombres definitivos de las rutas.
5. Confirmar el alcance mínimo para la presentación.
6. Decidir si la importación desde TMDB queda fuera del MVP.

La opción recomendada es Blade + Livewire y un catálogo propio, porque reduce dependencias y permite terminar una demo funcional con mayor seguridad.

## Reglas para no pisarse

- Cada programador trabajará en su propia rama.
- Nadie trabajará directamente sobre `main`.
- Adrián será responsable de integrar `routes/web.php`.
- Eric entregará sus rutas y controladores en un pull request separado.
- No se modificarán migraciones ya compartidas. Los cambios de base de datos se harán mediante nuevas migraciones.
- El layout global será responsabilidad de Eric.
- El catálogo y el panel de administración serán responsabilidad de Adrián.
- Los cambios de documentación general se harán en esta carpeta y se coordinarán antes de integrarlos.
- Cada pull request tendrá una única finalidad y deberá incluir sus tests.

## Orden de ejecución

### Fase 1: base común

1. Confirmar decisiones técnicas.
2. Configurar autenticación.
3. Ejecutar y verificar las migraciones.
4. Crear un usuario administrador y usuarios de prueba.
5. Definir rutas, permisos y datos que compartirán ambos.

### Fase 2: trabajo paralelo

**Adrián**

- Autenticación y roles.
- Catálogo público.
- Panel de administración.
- Factories y seeders del catálogo.

**Eric**

- Layout y componentes.
- Valoraciones.
- Reseñas y comentarios.
- Listas personales.
- Perfil del usuario.

### Fase 3: integración

1. Integrar las rutas.
2. Mostrar valoraciones y reseñas en la ficha del catálogo.
3. Añadir botones para las listas personales.
4. Verificar permisos de usuario y administrador.
5. Ejecutar todos los tests.

### Fase 4: entrega

- Completar `docs/02-requisits.md` a `docs/06-proves.md`.
- Actualizar `README.md` con instalación, uso, autores, herramientas de IA, capturas y URL.
- Completar `docs/registre-ia.md` con al menos 15 entradas significativas.
- Actualizar `CHANGELOG.md`.
- Preparar datos de demostración.
- Comprobar que no hay contraseñas ni claves API en el repositorio.
- Preparar el despliegue si finalmente es obligatorio.

## Prioridad del MVP

1. Autenticación.
2. Catálogo con búsqueda.
3. Ficha de película o serie.
4. Panel de administración.
5. Valoraciones.
6. Reseñas.
7. Listas personales.
8. Comentarios.
9. TMDB, recuperación de contraseña y estadísticas.

## Flujo mínimo de Git

```text
main
develop
feature/auth-catalog-admin
feature/user-interactions
feature/shared-layout
```

Antes de fusionar cualquier rama:

- Ejecutar los tests.
- Revisar las migraciones.
- Ejecutar el formateador.
- Revisar que no haya credenciales.
- Hacer revisión cruzada del pull request.