<?php
$password = "123456";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Generador de Hash de Contraseña</h2>";
echo "<p><strong>Contraseña:</strong> " . $password . "</p>";
echo "<p><strong>Hash generado:</strong></p>";
echo "<textarea style='width:100%; height:100px; font-family:monospace;'>" . $hash . "</textarea>";
echo "<p><strong>Copia este hash y actualiza la base de datos</strong></p>";
?>