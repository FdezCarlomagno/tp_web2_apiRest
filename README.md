# API de Guitarras 🎸

Este proyecto proporciona una API RESTful para gestionar guitarras y categorías en una base de datos. La API permite realizar operaciones CRUD sobre los recursos de `guitarras` y `categorias`, además de obtener un token de usuario. Cada ruta está diseñada siguiendo los principios de RESTful para mantener una estructura clara y semántica.

## Rutas

### Guitarras

1. **GET `/guitarras`**
   - **Descripción**: Obtiene la lista de todas las guitarras.
   - **Método**: `GET`
   - **Controlador**: `controller`
   - **Acción**: `getGuitarras`
   - **RESTful**: Esta ruta representa la colección completa de guitarras.

2. **GET `/guitarras/:categoria`**
   - **Descripción**: Obtiene todas las guitarras de una categoría específica.
   - **Método**: `GET`
   - **Controlador**: `controller`
   - **Acción**: `getGuitarrasByCategoria`
   - **RESTful**: Representa una subcolección de guitarras basada en una categoría específica.

3. **GET `/guitarras/guitarra/:id`**
   - **Descripción**: Obtiene una guitarra específica por su `id`.
   - **Método**: `GET`
   - **Controlador**: `controller`
   - **Acción**: `getGuitarraByID`
   - **RESTful**: Representa un recurso individual de guitarra, identificado por su `id`.

4. **POST `/guitarras`**
   - **Descripción**: Añade una nueva guitarra a la colección.
   - **Método**: `POST`
   - **Controlador**: `controller`
   - **Acción**: `addGuitarra`
   - **RESTful**: Representa la creación de un nuevo recurso dentro de la colección de guitarras.

5. **PUT `/guitarras/guitarra/:id`**
   - **Descripción**: Actualiza una guitarra específica.
   - **Método**: `PUT`
   - **Controlador**: `controller`
   - **Acción**: `updateGuitarra`
   - **RESTful**: Representa la actualización de un recurso específico de guitarra, identificado por `id`.

6. **DELETE `/guitarras/guitarra/:id`**
   - **Descripción**: Elimina una guitarra específica.
   - **Método**: `DELETE`
   - **Controlador**: `controller`
   - **Acción**: `deleteGuitarra`
   - **RESTful**: Representa la eliminación de un recurso específico de guitarra, identificado por `id`.

### Categorías

1. **GET `/categorias`**
   - **Descripción**: Obtiene la lista de todas las categorías de guitarras.
   - **Método**: `GET`
   - **Controlador**: `controller`
   - **Acción**: `getCategorias`
   - **RESTful**: Representa la colección completa de categorías.


## Query params

1. **filtrar `/guitarras?filtrar=`**
   - **Categorias**: Se puede filtrar por cualquier categoria de guitarra
   - **en-oferta**: Se filtra por las guitarras que estan en oferta

2. **ordenar `/guitarras?orderBy=`**
   - **precio**: Se puede ordenar por precio. Ademas se puede concatenar con otro query param ASC o DESC
   - **nombre**: Se puede ordenar por nombre. Ademas se puede concatenar con otro query param ASC o DESC
   - **asc**: Se puede ordenar por cualquiera de esos campos ascendentemente.
   - **desc**: Se puede ordenar por cualquiera de esos campos descendentemente.

### Paginación

3. **page `/guitarras?page=`**: Establece el numero de pagina.
4. **limit `/guitarras?limit=`**: Establece el numero de registros a mostrar.

Ejemplo:
   - Si quiero mostrar los primeros 3 registros de la segunda pagina la URL seria asi:
     localhost/tp_web_api/api/guitarras?page=2&limit=3

### Autenticación

1. **GET `/user/token`**
   - **Descripción**: Genera y devuelve un token de autenticación para el usuario.
   - **Método**: `GET`
   - **Controlador**: `User_controller`
   - **Acción**: `getToken`
   - **RESTful**: Representa la generación de un recurso de autenticación para el usuario.

## Explicación RESTful

Esta API sigue los principios de diseño RESTful:
- **Recursos claramente definidos**: Cada entidad importante (como `guitarras` y `categorias`) tiene su propio punto de acceso a través de rutas semánticas.
- **Métodos HTTP**: Se usan métodos HTTP específicos para cada tipo de operación (GET para obtener datos, POST para crear nuevos datos, PUT para actualizar y DELETE para eliminar).
- **Acciones centradas en los recursos**: Las rutas y métodos están estructurados de manera que la acción y el recurso sobre el que actúan sean claros, siguiendo las convenciones de REST.

Esta estructura RESTful permite que los clientes de la API interactúen con los recursos de una manera intuitiva y predecible, mejorando la mantenibilidad y escalabilidad del servicio.

## Notas

Para el funcionamiento correcto de la autenticación con token, asegúrate de llamar a `/user/token` y proporcionar el token en el encabezado de autorización (`Authorization: Bearer <token>`).

