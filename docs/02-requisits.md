# Requisitos de autenticación y autorización

## Alcance

Este documento define los requisitos implementados para el primer punto del trabajo de Adrián:

- Registro de usuarios.
- Inicio y cierre de sesión.
- Perfil básico.
- Roles `user` y `admin`.
- Protección de rutas privadas.
- Protección del panel de administración.

La recuperación de contraseña queda fuera del MVP actual.

## Actores

### Visitante

Persona que todavía no ha iniciado sesión.

### Usuario registrado

Usuario con rol `user` que puede acceder a las zonas privadas y gestionar su propio perfil.

### Administrador

Usuario con rol `admin` que puede acceder a las rutas administrativas.

## Requisitos funcionales

### RF-01: Registro

Un visitante debe poder crear una cuenta proporcionando nombre, email y contraseña.

**Criterios de aceptación:**

- El nombre es obligatorio.
- El email debe tener formato válido y ser único.
- La contraseña debe tener al menos 8 caracteres.
- La confirmación de contraseña debe coincidir.
- Las cuentas nuevas siempre se crean con rol `user`.
- La contraseña se almacena cifrada.
- Después del registro, el usuario queda autenticado.

### RF-02: Inicio de sesión

Un usuario registrado debe poder iniciar sesión con email y contraseña.

**Criterios de aceptación:**

- Las credenciales correctas permiten iniciar sesión.
- Las credenciales incorrectas muestran un error de validación.
- La sesión se regenera después del login.
- La opción de recordar sesión es opcional.

### RF-03: Cierre de sesión

Un usuario autenticado debe poder cerrar sesión.

**Criterios de aceptación:**

- La sesión se invalida.
- El token CSRF se regenera.
- El usuario queda redirigido al login.

### RF-04: Perfil

Un usuario autenticado debe poder consultar y modificar su nombre y email.

**Criterios de aceptación:**

- Un visitante no puede acceder al perfil.
- El email debe continuar siendo único.
- El usuario no puede modificar su propio rol desde el formulario.
- Si cambia el email, la verificación de email se reinicia.

### RF-05: Autorización administrativa

Solo los usuarios con rol `admin` pueden acceder al panel de administración.

**Criterios de aceptación:**

- Un visitante es redirigido al login.
- Un usuario `user` recibe una respuesta HTTP `403`.
- Un usuario `admin` recibe acceso correcto.
- Ocultar un enlace en la interfaz no sustituye la comprobación del middleware.

## Requisitos no funcionales

- Las contraseñas no se almacenan en texto plano.
- Las rutas privadas utilizan el middleware `auth`.
- Las rutas administrativas utilizan `auth` y `admin`.
- Las validaciones se realizan mediante Form Requests.
- La funcionalidad debe tener tests automatizados.
- No se deben incluir contraseñas reales en el repositorio.
