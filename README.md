# Instalacion
1. Clona/Descarga el proyecto en `wp-content/plugins/`.
2. Activa el plugin desde el panel de WordPress.

# Cómo se usa
- En el frontend mediante el shortcode `[user_list_ajax]` (ponlo en una página o entrada).

# Acceso (AJAX)
Por defecto, si el usuario **no ha iniciado sesión**, el endpoint AJAX devuelve **403**.

Si quieres permitir acceso público (sin login), añade este filtro en tu theme o en otro plugin:

`add_filter('eit_allow_public', '__return_true');`

# Descripción técnica (resumen)
- **Datos de usuarios**: se obtienen de `BBDD/data.json`, que simula la respuesta de un API externo con un JSON de usuarios.
- **Lógica de negocio**: la clase `UserService` carga y filtra usuarios (por nombre, apellidos y email) y la clase `Paginator` se encarga de la paginación (5 usuarios por página).
- **Controlador AJAX**: `AjaxController` recibe la petición POST (filtros, página actual, etc.) y responde con un JSON paginado que consume el JavaScript.
- **Interfaz en el frontend**: `Shortcode` registra el shortcode `[user_list_ajax]` para mostrar el listado en cualquier página o entrada.
- **AJAX y paginación en el navegador**: el archivo `assets/eit-user-list.js` gestiona las llamadas AJAX, actualiza la tabla, aplica los filtros y dibuja el paginador sin recargar la página.
