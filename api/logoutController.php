<?php
session_start();
session_unset();       // Limpia todas las variables de sesión
session_destroy();     // Destruye la sesión

header("Content-Type: application/json");
echo json_encode([
    "success" => true,
    "message" => "Sesión cerrada correctamente"
]);
exit;
?>