# Chinadev / EZQStock

Proyecto PHP (OSWA-INV modificado) para gestión de stock y ventas, preparado para correr en PHP 7.4 dentro de Docker y usando una base de datos MariaDB/MySQL remota (`ezqstock`).

## Requisitos

- macOS, Linux o Windows
- [Docker](https://www.docker.com/) instalado (incluye Docker Compose v2, comando `docker compose`)
- (Opcional) Python 3 + `pymysql` si querés reimportar el dump SQL con `run_sql_file.py`

## Estructura principal

- `public_html/`: código PHP de la aplicación (document root del servidor web)
- `Dockerfile`: imagen PHP 7.4 + Apache con extensiones necesarias (mysqli, pdo_mysql, intl, mbstring, gd, etc.)
- `docker-compose.yml`: define el servicio `web` que levanta la app en `http://localhost:8000`
- `run_sql_file.py`: script para importar el dump SQL en la base `ezqstock`
- `u460083963_ezqstock.sql`: dump de la base de datos

## 1. Levantar la aplicación con Docker

1. Parado en la carpeta del proyecto:

   ```bash
   cd /Users/agustinramirez/Proyectos/chinadev
   ```

2. Construí la imagen y levantá el contenedor:

   ```bash
   docker compose up --build
   ```

3. Abrí el navegador en:

   - `http://localhost:8000`

   El `DocumentRoot` del contenedor está mapeado a `./public_html`, así que cualquier cambio en esos archivos se refleja directamente en el navegador.

4. Para detener el contenedor:

   - En la terminal donde corre: `Ctrl + C`
   - O bien desde otra terminal:

     ```bash
     cd /Users/agustinramirez/Proyectos/chinadev
     docker compose down
     ```

## 2. Base de datos

La aplicación está configurada para usar una base de datos MariaDB/MySQL remota llamada `ezqstock`.

- Las credenciales y el host se definen en:
  - `public_html/includes/config.php`
- El script Python `run_sql_file.py` también usa esa misma configuración en `DB_CONFIG`.

Mientras ese servidor de base de datos remoto esté disponible y las credenciales sean correctas, **no hace falta** levantar un MySQL local: la app funciona directamente contra esa base.

## 3. Reimportar el dump SQL (opcional)

Si necesitás volver a importar el dump `u460083963_ezqstock.sql` (por ejemplo, a otra instancia de MariaDB/MySQL), podés usar el script Python.

1. Crear/activar un entorno virtual (opcional pero recomendado):

   ```bash
   cd /Users/agustinramirez/Proyectos/chinadev
   python3 -m venv env
   source env/bin/activate
   ```

2. Instalar dependencias:

   ```bash
   pip install pymysql
   ```

3. Editar `run_sql_file.py` si necesitás apuntar a otra base de datos (host, puerto, usuario, contraseña, nombre de BD).

4. Ejecutar la importación:

   ```bash
   python run_sql_file.py u460083963_ezqstock.sql
   ```

El script:
- Divide el archivo en sentencias SQL respetando strings.
- Ejecuta cada sentencia en orden.
- Desactiva temporalmente `FOREIGN_KEY_CHECKS` para evitar errores de claves foráneas durante la carga.
- Se detiene y muestra el error si alguna sentencia falla.

## 4. Configuración de la aplicación PHP

Configuración clave:

- Archivo principal de configuración de BD: `public_html/includes/config.php`
- Bootstrap de la app: `public_html/includes/load.php`
- Layouts compartidos: `public_html/layouts/header.php` y `public_html/layouts/footer.php`

Si cambiás host/credenciales de la base de datos, hacelo siempre en `config.php`. El resto de la app usa esas constantes.

## 5. Problemas comunes

- **El puerto 8000 ya está en uso**:
  - Modificar `docker-compose.yml` en la sección `ports`, por ejemplo:

    ```yaml
    ports:
      - "8080:80"
    ```

  - Y luego acceder a `http://localhost:8080`.

- **El contenedor se cae al iniciar**:
  - Ver logs con:

    ```bash
    docker compose logs -f
    ```

- **Errores de conexión a la base de datos**:
  - Verificar host, puerto, usuario y contraseña en `public_html/includes/config.php`.
  - Comprobar que la base `ezqstock` exista y sea accesible desde la IP donde corre Docker.

## 6. Desarrollo

- Editá los archivos PHP/JS/CSS dentro de `public_html/`.
- No hace falta reconstruir la imagen para cambios simples de código; con el volumen mapeado, los cambios se ven al refrescar el navegador.

Si querés, puedo agregar al README instrucciones específicas de acceso (usuario/contraseña de login) o un pequeño mapa de las pantallas principales del sistema.