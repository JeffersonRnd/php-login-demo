<?php
// ============================================
// includes/config.php
// Configuración de la conexión a la base de datos
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // 🔧 Cambia por tu usuario de MySQL
define('DB_PASS', '');           // 🔧 Cambia por tu contraseña
define('DB_NAME', 'login_demo');

/**
 * Crea y devuelve una conexión a MySQL usando PDO.
 * PDO permite usar "prepared statements" que protegen contra SQL Injection.
 */
function conectarDB(): PDO {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // lanza excepciones en error
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // devuelve arrays asociativos
        PDO::ATTR_EMULATE_PREPARES   => false,                    // prepared statements reales
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        return $pdo;
    } catch (PDOException $e) {
        // En producción nunca muestres el error real al usuario
        die("Error de conexión a la base de datos. Revisa config.php");
    }
}
