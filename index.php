<?php
// ============================================
// index.php  —  Página de Login
// ============================================

require_once 'includes/auth.php';

// Si ya está logueado, lo mandamos directo al dashboard
if (estaLogueado()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Procesa el formulario cuando llega por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Por favor completa todos los campos.';
    } else {
        $usuario = autenticar($email, $password);

        if ($usuario) {
            guardarSesion($usuario);
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="pagina-login">

    <div class="tarjeta">
        <div class="tarjeta-cabecera">
            <div class="icono">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                     fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h1>Iniciar sesión</h1>
            <p>Ingresa tus datos para entrar</p>
        </div>

        <?php if ($error): ?>
            <div class="alerta alerta-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
            <div class="alerta alerta-ok">Sesión cerrada correctamente.</div>
        <?php endif; ?>

        <form method="POST" action="index.php">

            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="tucorreo@ejemplo.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="boton boton-principal">
                Entrar &rarr;
            </button>

        </form>

        <div class="caja-ayuda">
            <p><strong>Usuarios de prueba:</strong></p>
            <ul>
                <li>ana@example.com</li>
                <li>carlos@example.com</li>
                <li>sofia@example.com</li>
            </ul>
            <p>Contrasena para todos: <code>123456</code></p>
        </div>
    </div>

    <!-- Diagrama que explica el flujo del login -->
    <div class="diagrama">
        <h2>Como funciona el login</h2>
        <div class="pasos">
            <div class="paso">
                <div class="paso-num">1</div>
                <p>Usuario escribe <strong>email + contrasena</strong></p>
            </div>
            <div class="flecha">&rarr;</div>
            <div class="paso">
                <div class="paso-num">2</div>
                <p>PHP busca el email en la <strong>base de datos</strong></p>
            </div>
            <div class="flecha">&rarr;</div>
            <div class="paso">
                <div class="paso-num">3</div>
                <p>Compara la contrasena con el <strong>hash</strong> guardado</p>
            </div>
            <div class="flecha">&rarr;</div>
            <div class="paso">
                <div class="paso-num">4</div>
                <p>Crea una <strong>sesion</strong> y redirige al dashboard</p>
            </div>
        </div>
    </div>

</body>
</html>