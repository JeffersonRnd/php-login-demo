-- ============================================
-- Base de datos para el sistema de login PHP
-- ============================================

CREATE DATABASE IF NOT EXISTS login_demo
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE login_demo;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    email       VARCHAR(150)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,       -- guardamos el hash, NUNCA texto plano
    creado_en   DATETIME      DEFAULT CURRENT_TIMESTAMP
);

-- Usuarios de ejemplo  (contraseña: "123456" hasheada con bcrypt)
INSERT INTO usuarios (nombre, email, password) VALUES
    ('Ana García',    'ana@example.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
    ('Carlos López',  'carlos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
    ('Sofía Torres',  'sofia@example.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Nota: el hash corresponde a password_hash("123456", PASSWORD_BCRYPT)
