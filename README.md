# Sistema de Login con PHP y MySQL

Un proyecto de ejemplo para aprender como funciona un login basico: como se conecta PHP con MySQL, como se guardan las contrasenas de forma segura y como funciona una sesion.

---

## Que tiene el proyecto

```
php-login-demo/
├── index.php                  <- Pagina de login (formulario)
├── dashboard.php              <- Pagina protegida (solo si estas logueado)
├── logout.php                 <- Cierra la sesion y regresa al login
├── generar-hash-final.php     <- Herramienta para generar hashes de contrasenas
├── css/
│   └── style.css              <- Estilos visuales
├── includes/
│   ├── config.php             <- Configuracion de la base de datos
│   └── auth.php               <- Funciones de autenticacion y sesion
└── sql/
    └── database.sql           <- Script para crear la base de datos y los usuarios
```

---

## Requisitos

- **XAMPP** instalado (incluye Apache + MySQL + PHP)
  - Descargalo desde: https://www.apachefriends.org/es/index.html
- Un navegador web

---

## Paso 1 — Iniciar XAMPP

1. Abre el **Panel de control de XAMPP**.
2. Haz clic en **Start** junto a **Apache**.
3. Haz clic en **Start** junto a **MySQL**.
4. Ambos deben quedar en verde. Si alguno falla, revisa que no tengas otro programa usando el puerto 80 (Apache) o el puerto 3306 (MySQL).

---

## Paso 2 — Copiar el proyecto a XAMPP

1. Descarga y descomprime el archivo `php-login-demo.zip`.
2. Copia la carpeta `php-login-demo` dentro de la carpeta `htdocs` de XAMPP.

   - En **Windows** la ruta es: `C:\xampp\htdocs\`
   - En **Mac** la ruta es: `/Applications/XAMPP/htdocs/`

3. El resultado debe quedar asi:

   ```
   C:\xampp\htdocs\php-login-demo\
   ```

---

## Paso 3 — Crear la base de datos

Tienes dos opciones para crear la base de datos. Usa la que te resulte mas comoda.

### Opcion A — Desde phpMyAdmin (interfaz visual)

1. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
2. En la barra lateral izquierda haz clic en **Nueva** (o **New**).
3. En el campo "Nombre de la base de datos" escribe: `login_demo`
4. Haz clic en **Crear**.
5. Una vez creada, selecciona `login_demo` en la barra lateral.
6. Haz clic en la pestaña **SQL** (arriba en el centro).
7. Abre el archivo `sql/database.sql` con cualquier editor de texto (Notepad, VS Code, etc.).
8. Copia todo el contenido y pegalo en el cuadro de texto de SQL en phpMyAdmin.
9. Haz clic en **Ejecutar** (boton azul, abajo a la derecha).
10. Si todo salio bien veras la tabla `usuarios` en la barra lateral con 3 usuarios creados.

### Opcion B — Desde la linea de comandos

1. Abre una terminal (CMD en Windows o Terminal en Mac).
2. Ejecuta este comando (reemplaza la ruta si es diferente en tu sistema):

   ```bash
   mysql -u root -p < C:\xampp\htdocs\php-login-demo\sql\database.sql
   ```

3. Si MySQL no tiene contrasena (en XAMPP por defecto no la tiene), presiona Enter cuando te la pida.

---

## Paso 4 — Revisar la configuracion de conexion

Abre el archivo `includes/config.php` con un editor de texto. Veras esto:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // Usuario de MySQL
define('DB_PASS', '');       // Contrasena de MySQL (en XAMPP suele estar vacia)
define('DB_NAME', 'login_demo');
```

En la mayoria de instalaciones de XAMPP no necesitas cambiar nada. Solo modifica `DB_PASS` si tu MySQL tiene contrasena configurada.

---

## Paso 5 — Probar el proyecto

1. Abre tu navegador y ve a: `http://localhost/php-login-demo/`
2. Deberas ver el formulario de login.
3. Usa cualquiera de estos usuarios de prueba:

   | Email               | Contrasena |
   |---------------------|------------|
   | ana@example.com     | 123456     |
   | carlos@example.com  | 123456     |
   | sofia@example.com   | 123456     |

4. Si el login funciona correctamente seras redirigido al **dashboard**.

---

## Como funcionan las contrasenas (concepto importante)

Las contrasenas **nunca se guardan como texto plano** en la base de datos. En cambio, se guarda un **hash**, que es una version cifrada e irreversible de la contrasena.

Por ejemplo, la contrasena `123456` se guarda asi en la base de datos:

```
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
```

PHP usa `password_hash()` para generar ese hash y `password_verify()` para compararlo cuando el usuario inicia sesion. El hash nunca se puede "desencriptar" — solo se puede comparar.

---

## Como cambiar la contrasena de un usuario existente

Cuando creas la base de datos con el script SQL, los 3 usuarios ya tienen la contrasena `123456`. Si quieres cambiarla por otra, sigue estos pasos:

### Paso 1 — Generar el nuevo hash

1. Abre tu navegador y ve a: `http://localhost/php-login-demo/generar-hash-final.php`
2. Veras una pagina simple con un **textarea** que contiene el hash generado para la contrasena `123456`.
3. Si quieres generar el hash de una contrasena diferente, abre el archivo `generar-hash-final.php` con un editor de texto y cambia esta linea:

   ```php
   $password = "123456";
   ```

   Por ejemplo, para la contrasena `miClave2024`:

   ```php
   $password = "miClave2024";
   ```

4. Guarda el archivo y recarga la pagina en el navegador (`http://localhost/php-login-demo/generar-hash-final.php`).
5. **Copia el hash completo** que aparece en el textarea. Se ve algo asi:

   ```
   $2y$10$AbCdEfGhIjKlMnOpQrStUuVwXyZaBcDeFgHiJkLmNoPqRsTuVwXyZ
   ```

   Nota: cada vez que generas el hash de la misma contrasena el resultado es diferente. Eso es normal y correcto — bcrypt usa un "salt" aleatorio. Todos los hashes que genera son validos.

### Paso 2 — Actualizar la base de datos con el nuevo hash

1. Ve a `http://localhost/phpmyadmin`
2. Selecciona la base de datos `login_demo` en la barra lateral.
3. Haz clic en la pestaña **SQL**.
4. Escribe una consulta como esta (reemplaza el hash y el email):

   ```sql
   UPDATE usuarios
   SET password = '$2y$10$AbCdEfGhIjKlMnOpQrStUuVwXyZaBcDeFgHiJkLmNoPqRsTuVwXyZ'
   WHERE email = 'ana@example.com';
   ```

5. Haz clic en **Ejecutar**.
6. Ahora `ana@example.com` ya puede entrar con su nueva contrasena.

---

## Como agregar un nuevo usuario

Tienes dos formas de hacerlo.

### Opcion A — Directamente en el script SQL (antes de crear la base de datos)

Abre `sql/database.sql` y agrega una linea en la seccion de INSERT:

```sql
INSERT INTO usuarios (nombre, email, password) VALUES
    ('Ana Garcia',    'ana@example.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
    ('Carlos Lopez',  'carlos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
    ('Sofia Torres',  'sofia@example.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
    ('Juan Perez',    'juan@example.com',   'PEGA_AQUI_EL_HASH');  <- nuevo usuario
```

Recuerda: primero genera el hash con `generar-hash-final.php` y luego pegalo en el lugar de `PEGA_AQUI_EL_HASH`.

### Opcion B — Desde phpMyAdmin (si la base de datos ya existe)

1. Ve a `http://localhost/phpmyadmin`
2. Selecciona `login_demo` y luego la tabla `usuarios`.
3. Haz clic en la pestaña **SQL**.
4. Ejecuta esta consulta (con el hash generado previamente):

   ```sql
   INSERT INTO usuarios (nombre, email, password)
   VALUES ('Juan Perez', 'juan@example.com', 'PEGA_AQUI_EL_HASH');
   ```

5. Haz clic en **Ejecutar** y el nuevo usuario ya podra iniciar sesion.

---

## Resumen del flujo del login

```
1. El usuario escribe su email y contrasena en el formulario (index.php)
        |
        v
2. PHP busca el email en la tabla "usuarios" de MySQL con un SELECT
        |
        v
3. MySQL devuelve la fila del usuario (si el email existe)
        |
        v
4. PHP compara la contrasena ingresada con el hash guardado usando password_verify()
        |
        v
5. Si coincide: se crea una sesion ($_SESSION) y se redirige a dashboard.php
   Si no coincide: se muestra un mensaje de error
        |
        v
6. dashboard.php revisa al inicio si existe la sesion. Si no hay sesion, regresa al login.
```

---

## Preguntas frecuentes

**El login muestra "Error de conexion a la base de datos"**
Verifica que MySQL este corriendo en XAMPP y que los datos en `config.php` sean correctos. Tambien asegurate de haber ejecutado el script SQL para crear la base de datos.

**El login dice "Email o contrasena incorrectos" aunque escribo bien los datos**
Lo mas probable es que el hash en la base de datos no corresponda al algoritmo de PHP de tu version. Genera un hash nuevo con `generar-hash-final.php` y actualiza la base de datos siguiendo los pasos de la seccion "Como cambiar la contrasena".

**Cada vez que genero el hash es diferente, esta mal?**
No, es correcto. bcrypt genera un hash diferente cada vez porque usa un valor aleatorio interno llamado "salt". Lo importante es que `password_verify()` puede comparar correctamente cualquiera de esos hashes con la contrasena original.

**Puedo usar este proyecto en produccion (internet)?**
Este proyecto es para aprender. Antes de usarlo en un sitio real necesitarias agregar: HTTPS, validaciones mas estrictas, limite de intentos de login, y mover las credenciales de la base de datos a un archivo de entorno.
