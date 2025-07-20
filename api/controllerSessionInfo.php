<?php
session_start();
header("Content-Type: application/json");
if (isset($_SESSION['usuario_id'], $_SESSION['rol'])) {
    echo json_encode([
        "success" => true,
        "usuario_id" => $_SESSION['usuario_id'],
        "rol" => $_SESSION['rol']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No hay sesión activa"
    ]);
}
?>
