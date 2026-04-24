<?php
// ============================================
// dashboard.php  —  Pagina protegida (solo logueados)
// ============================================

require_once 'includes/auth.php';

// Si no esta logueado, lo mandamos al login
requiereLogin();

// Los datos del usuario vienen de la sesion
$nombre = $_SESSION['usuario_nombre'];
$email  = $_SESSION['usuario_email'];
$id     = $_SESSION['usuario_id'];

// Saludo segun la hora
$hora = (int) date('H');
if ($hora < 12)      $saludo = 'Buenos dias';
elseif ($hora < 19)  $saludo = 'Buenas tardes';
else                 $saludo = 'Buenas noches';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard &mdash; <?= htmlspecialchars($nombre) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="pagina-dashboard">

    <!-- Barra superior -->
    <nav class="navbar">
        <div class="nav-marca">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 style="vertical-align:middle; margin-right:6px;">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            LoginDemo
        </div>
        <div class="nav-usuario">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="vertical-align:middle;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?= htmlspecialchars($nombre) ?>
            </span>
            <a href="logout.php" class="boton boton-salir">Cerrar sesion</a>
        </div>
    </nav>

    <main class="contenido-principal">

        <!-- Bienvenida -->
        <section class="bienvenida">
            <h1><?= $saludo ?>, <span class="nombre-destacado"><?= htmlspecialchars($nombre) ?></span></h1>
            <p>Iniciaste sesion correctamente. Tu sesion esta activa.</p>
        </section>

        <!-- Tarjetas -->
        <section class="cuadricula">

            <div class="bloque">
                <div class="bloque-icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h3>Datos de tu cuenta</h3>
                <table class="tabla-datos">
                    <tr>
                        <th>ID en la BD:</th>
                        <td><code><?= $id ?></code></td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td><?= htmlspecialchars($nombre) ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?= htmlspecialchars($email) ?></td>
                    </tr>
                    <tr>
                        <th>ID de sesion:</th>
                        <td><code><?= substr(session_id(), 0, 16) ?>...</code></td>
                    </tr>
                </table>
            </div>

            <div class="bloque">
                <div class="bloque-icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                        <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                    </svg>
                </div>
                <h3>Que ocurrio en la base de datos</h3>
                <ol class="lista-pasos">
                    <li>Se ejecuto un <code>SELECT</code> buscando tu email.</li>
                    <li>MySQL devolvio tu fila de la tabla <code>usuarios</code>.</li>
                    <li>PHP comprobo tu contrasena contra el <strong>hash bcrypt</strong>.</li>
                    <li>Se creo una <code>$_SESSION</code> con tus datos.</li>
                    <li>Esta pagina revisa <code>$_SESSION</code> en cada carga.</li>
                </ol>
            </div>

            <div class="bloque">
                <div class="bloque-icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                    </svg>
                </div>
                <h3>Por que hashes y no texto plano</h3>
                <p>
                    Si guardaramos <code>"123456"</code> directo en la BD y alguien robara la base de datos,
                    todas las contrasenas quedarian expuestas.
                    Con <strong>bcrypt</strong> cada hash es unico e irreversible.
                </p>
                <div class="ejemplo-hash">
                    123456 &rarr; $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2...
                </div>
            </div>

            <div class="bloque">
                <div class="bloque-icono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <h3>Conceptos de seguridad aplicados</h3>
                <ul class="lista-seguridad">
                    <li>&#10003; <strong>Prepared statements</strong> &mdash; evita SQL Injection</li>
                    <li>&#10003; <strong>password_hash / verify</strong> &mdash; contrasenas seguras</li>
                    <li>&#10003; <strong>session_regenerate_id</strong> &mdash; evita session fixation</li>
                    <li>&#10003; <strong>htmlspecialchars</strong> &mdash; evita XSS</li>
                    <li>&#10003; <strong>requiereLogin()</strong> &mdash; protege paginas privadas</li>
                </ul>
            </div>

        </section>

        <!-- Boton de logout -->
        <div class="seccion-salida">
            <p>Cuando termines, cierra tu sesion:</p>
            <a href="logout.php" class="boton boton-salir">
                Cerrar sesion
            </a>
        </div>

    </main>

</body>
</html>