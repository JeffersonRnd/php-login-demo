<?php
// ============================================
// logout.php  →  Cierra la sesión
// ============================================

require_once 'includes/auth.php';

// Destruye todos los datos de sesión
cerrarSesion();

// Redirige al login con un mensaje opcional
header('Location: index.php?logout=1');
exit;
