<?php
// ============================================
// includes/auth.php
// Funciones de autenticación y sesión
// ============================================

require_once __DIR__ . '/config.php';

/**
 * Inicia la sesión de forma segura (solo llama session_start una vez).
 */
function iniciarSesion(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Verifica si el usuario ya inició sesión.
 */
function estaLogueado(): bool {
    iniciarSesion();
    return isset($_SESSION['usuario_id']);
}

/**
 * Redirige al login si el usuario NO está autenticado.
 * Úsala al inicio de páginas protegidas.
 */
function requiereLogin(): void {
    if (!estaLogueado()) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Intenta autenticar al usuario con email y contraseña.
 * Devuelve el array del usuario si es correcto, o false si no.
 *
 * ¿Por qué prepared statements?
 *   Si armáramos el SQL con concatenación: "WHERE email = '$email'"
 *   un atacante podría escribir: ' OR '1'='1  → acceso sin contraseña.
 *   Con prepared statements el valor se trata siempre como DATO, nunca como SQL.
 */
function autenticar(string $email, string $password): array|false {
    $pdo = conectarDB();

    // 1️⃣ Busca al usuario por email
    $stmt = $pdo->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    // 2️⃣ Si no existe el email, falla
    if (!$usuario) {
        return false;
    }

    // 3️⃣ Compara la contraseña ingresada con el HASH guardado en la BD
    //    password_verify() hace esto de forma segura; nunca comparamos texto plano
    if (!password_verify($password, $usuario['password'])) {
        return false;
    }

    return $usuario;
}

/**
 * Guarda los datos del usuario en la sesión (lo "loguea").
 */
function guardarSesion(array $usuario): void {
    iniciarSesion();
    session_regenerate_id(true); // previene session fixation
    $_SESSION['usuario_id']    = $usuario['id'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    $_SESSION['usuario_email']  = $usuario['email'];
}

/**
 * Destruye la sesión (logout).
 */
function cerrarSesion(): void {
    iniciarSesion();
    session_unset();
    session_destroy();
}
