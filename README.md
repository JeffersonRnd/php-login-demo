# Sistema de Login con PHP y MySQL

Un proyecto de ejemplo para aprender cómo funciona un login básico: cómo se conecta PHP con MySQL, cómo se guardan las contraseñas de forma segura y cómo funciona una sesión.

---

## Qué tiene el proyecto

```
php-login-demo/
├── index.php              <- Página de login (formulario)
├── dashboard.php          <- Página protegida (solo si estás logueado)
├── logout.php             <- Cierra la sesión y regresa al login
├── generar-hash.php       <- Herramienta para generar hashes de contraseñas
├── css/
│   └── style.css          <- Estilos visuales
├── includes/
│   ├── config.php         <- Configuración de la base de datos
│   └── auth.php           <- Funciones de autenticación y sesión
└── sql/
    └── database.sql       <- Script para crear la base de datos y los usuarios
```

---

## Requisitos

- **XAMPP** instalado (incluye Apache + MySQL + PHP)
  - Descárgalo desde: https://www.apachefriends.org/es/index.html

---

## Paso 1 — Iniciar XAMPP

1. Abre el **Panel de control de XAMPP**.
2. Haz clic en **Start** junto a **Apache**.
3. Haz clic en **Start** junto a **MySQL**.
4. Ambos deben quedar en verde.

---

## Paso 2 — Copiar el proyecto a XAMPP

1. Descarga el repositorio desde GitHub haciendo clic en **Code → Download ZIP**.
2. Descomprime el archivo. La carpeta que sale se llamará **`php-login-demo-main`** — renómbrala a **`php-login-demo`**.
3. Copia la carpeta dentro de:

   ```
   C:\xampp\htdocs\
   ```

4. El resultado debe quedar así:

   ```
   C:\xampp\htdocs\php-login-demo\
   ```

---

## Paso 3 — Crear la base de datos

1. Abre tu navegador y ve a `http://localhost/phpmyadmin`. Si no carga, prueba con `http://localhost:8080/phpmyadmin`. El puerto depende de cómo tengas configurado Apache en XAMPP.
2. En la barra lateral izquierda haz clic en **Nueva**.
3. En el campo "Nombre de la base de datos" escribe: `login_demo`
4. Haz clic en **Crear**.
5. Una vez creada, selecciona `login_demo` en la barra lateral.
6. Haz clic en la pestaña **SQL** (arriba en el centro).
7. Abre el archivo `sql/database.sql` con el Bloc de notas u otro editor de texto.
8. Copia todo el contenido y pégalo en el cuadro de texto de SQL en phpMyAdmin.
9. Haz clic en **Ejecutar** (botón gris, abajo a la derecha).
10. Si todo salió bien verás la tabla `usuarios` en la barra lateral con 3 usuarios creados.

---

## Paso 4 — Revisar la configuración de conexión

Abre el archivo `includes/config.php` con el Bloc de notas. Verás esto:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // Usuario de MySQL
define('DB_PASS', '');       // Contraseña de MySQL (en XAMPP suele estar vacía)
define('DB_NAME', 'login_demo');
```

En la mayoría de instalaciones de XAMPP no necesitas cambiar nada. Solo modifica `DB_PASS` si tu MySQL tiene contraseña configurada.

---

## Paso 5 — Generar los hashes de contraseña (obligatorio)

Este paso no se puede saltar. El script SQL crea los usuarios con un hash de ejemplo, pero ese hash puede no ser compatible con la versión de PHP que tiene tu XAMPP. Por eso hay que generar uno nuevo y reemplazarlo.

### Nota sobre el puerto

Por defecto XAMPP usa el puerto 80 y las URLs son así:

```
http://localhost/php-login-demo/generar-hash.php
```

Si esa dirección no abre nada, prueba con el puerto 8080:

```
http://localhost:8080/php-login-demo/generar-hash.php
```

Esto pasa cuando otro programa ocupa el puerto 80 en tu PC y XAMPP cambia automáticamente al 8080. Puedes ver qué puerto está usando Apache en el Panel de control de XAMPP, en la columna **Port(s)** junto a Apache. Usa ese número en todas las URLs del proyecto.

---

### Generar el hash

1. Abre tu navegador y ve a `generar-hash.php` (con el puerto que corresponda).

2. Verás una página con un **textarea** que contiene el hash generado para la contraseña `123456`. Se ve algo así:

   ```
   $2y$10$AbCdEfGhIjKlMnOpQrStUuVwXyZaBcDeFgHiJkLmNoPqRsTuVwXyZ
   ```

3. **Selecciona todo el texto del textarea y cópialo.**

   > Nota: cada vez que recargas la página el hash es diferente. Eso es normal — bcrypt genera un valor distinto cada vez. Todos son válidos para la contraseña `123456`.

---

### Reemplazar el hash — con script SQL (recomendado)

1. Ve a `http://localhost/phpmyadmin` (o con :8080 si corresponde).
2. Selecciona la base de datos `login_demo` en la barra lateral.
3. Haz clic en la pestaña **SQL**.
4. Pega y ejecuta lo siguiente, reemplazando `PEGA_AQUI_EL_HASH` con el hash que copiaste:

   ```sql
   UPDATE usuarios SET password = 'PEGA_AQUI_EL_HASH' WHERE email = 'ana@example.com';
   UPDATE usuarios SET password = 'PEGA_AQUI_EL_HASH' WHERE email = 'carlos@example.com';
   UPDATE usuarios SET password = 'PEGA_AQUI_EL_HASH' WHERE email = 'sofia@example.com';
   ```

   El hash va dentro de las comillas simples `' '`. El mismo hash sirve para los tres porque todos usan la contraseña `123456`.

5. Haz clic en **Ejecutar**.

---

### Reemplazar el hash — manualmente en phpMyAdmin

Si prefieres no usar SQL, puedes editar las celdas directamente desde la interfaz:

1. Ve a `http://localhost/phpmyadmin` y selecciona `login_demo`.
2. Haz clic en la tabla **usuarios** en la barra lateral.
3. Verás la lista de los 3 usuarios. Haz clic en el ícono de **lápiz (Editar)** que aparece a la izquierda de la fila de `ana@example.com`.
4. Se abrirá un formulario con todos los campos del usuario.
5. Busca el campo **password**, borra el contenido que tiene y pega el hash que copiaste.
6. Haz clic en **Continuar** (o **Go**) para guardar.
7. Repite el mismo proceso para `carlos@example.com` y `sofia@example.com`.

---

## Paso 6 — Probar el proyecto

1. Abre tu navegador y ve a `http://localhost/php-login-demo/` (o con :8080).
2. Verás el formulario de login.
3. Usa cualquiera de estos usuarios:

   | Email               | Contraseña |
   |---------------------|------------|
   | ana@example.com     | 123456     |
   | carlos@example.com  | 123456     |
   | sofia@example.com   | 123456     |

4. Si el login funciona correctamente serás redirigido al **dashboard**.

---

## Cómo funcionan las contraseñas (concepto importante)

Las contraseñas **nunca se guardan como texto plano** en la base de datos. En cambio, se guarda un **hash**, que es una versión cifrada e irreversible de la contraseña.

Por ejemplo, la contraseña `123456` se guarda así en la base de datos:

```
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
```

PHP usa `password_hash()` para generar ese hash y `password_verify()` para compararlo cuando el usuario inicia sesión. El hash nunca se puede "desencriptar" — solo se puede comparar.

---

## Cómo cambiar la contraseña de un usuario

1. Abre `generar-hash.php` con el Bloc de notas y cambia esta línea:

   ```php
   $password = "123456";
   ```

   Por la contraseña que quieras, por ejemplo:

   ```php
   $password = "miClave2024";
   ```

2. Guarda el archivo y ve a `generar-hash.php` en el navegador para obtener el nuevo hash. Cópialo del textarea.

3. Actualiza la base de datos con el nuevo hash usando cualquiera de estas dos formas:

   **Con script SQL** — ve a phpMyAdmin → SQL y ejecuta:

   ```sql
   UPDATE usuarios SET password = 'PEGA_AQUI_EL_HASH' WHERE email = 'ana@example.com';
   ```

   El hash va dentro de las comillas simples `' '`.

   **Manualmente** — ve a phpMyAdmin → tabla `usuarios` → clic en el lápiz de editar junto al usuario → borra el campo password → pega el nuevo hash → Continuar.

---

## Cómo agregar un nuevo usuario

1. Genera el hash de la contraseña del nuevo usuario con `generar-hash.php` y cópialo.

2. **Con script SQL** — ve a phpMyAdmin → SQL y ejecuta:

   ```sql
   INSERT INTO usuarios (nombre, email, password)
   VALUES ('Juan Perez', 'juan@example.com', 'PEGA_AQUI_EL_HASH');
   ```

   El hash va dentro de las comillas simples `' '`.

3. **Manualmente** — ve a phpMyAdmin → tabla `usuarios` → haz clic en la pestaña **Insertar** → completa los campos `nombre`, `email` y `password` (pega el hash en este último) → haz clic en **Continuar**.

---

## Resumen del flujo del login

```
1. El usuario escribe su email y contraseña en el formulario (index.php)
        |
        v
2. PHP busca el email en la tabla "usuarios" de MySQL con un SELECT
        |
        v
3. MySQL devuelve la fila del usuario (si el email existe)
        |
        v
4. PHP compara la contraseña ingresada con el hash guardado usando password_verify()
        |
        v
5. Si coincide: se crea una sesión ($_SESSION) y se redirige a dashboard.php
   Si no coincide: se muestra un mensaje de error
        |
        v
6. dashboard.php revisa al inicio si existe la sesión. Si no hay sesión, regresa al login.
```

---

## Preguntas frecuentes

**El login muestra "Error de conexión a la base de datos"**
Verifica que MySQL esté corriendo en XAMPP y que los datos en `config.php` sean correctos. También asegúrate de haber ejecutado el script SQL para crear la base de datos.

**El login dice "Email o contraseña incorrectos" aunque escribo bien los datos**
No ejecutaste el Paso 5. El hash del script SQL es de ejemplo y puede no ser compatible con tu versión de PHP. Genera un hash nuevo con `generar-hash.php` y reemplázalo en la base de datos siguiendo ese paso.

**La página no abre con `http://localhost/...`**
Prueba con `http://localhost:8080/...`. Puedes confirmar el puerto en el Panel de control de XAMPP, columna **Port(s)** junto a Apache.

**Cada vez que genero el hash es diferente, ¿está mal?**
No, es correcto. bcrypt genera un hash diferente cada vez porque usa un valor aleatorio interno llamado "salt". Lo importante es que `password_verify()` puede comparar correctamente cualquiera de esos hashes con la contraseña original.

**¿Puedo usar este proyecto en producción (internet)?**
Este proyecto es para aprender. Antes de usarlo en un sitio real necesitarías agregar: HTTPS, validaciones más estrictas, límite de intentos de login, y mover las credenciales de la base de datos a un archivo de entorno.
