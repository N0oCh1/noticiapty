<?php


try {
    require_once "../class/C_usuario.php"; // Asegúrate de incluir la clase Usuario

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        header("Content-Type: application/json; charset=utf-8");

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['usuario'], $data['password'])) {
            $usuario = $data['usuario'];
            $password = $data['password'];

            $usuarioObj = new Usuario($usuario, $password);

            if ($usuarioObj->verificarLogin()) {
                echo json_encode(['success' => true, 'usuario_id' => $usuarioObj->getId()]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        }
    }
} catch (Exception $e) {
    // Loguear el error y devolver una respuesta JSON adecuada
    error_log("Error en el servidor: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
}
?>