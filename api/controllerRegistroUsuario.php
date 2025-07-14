<?php

require_once "../class/C_usuario.php"; // Incluir la clase actualizada

// Verificar que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    $data = json_decode(file_get_contents("php://input"), true);

    // Validar campos obligatorios
    if (isset($data['nombre'], $data['apellido'], $data['usuario'], $data['password'])) {
        $nombre = trim($data['nombre']);
        $apellido = trim($data['apellido']);
        $usuario = trim($data['usuario']);
        $password = $data['password'];

        $usuarioObj = new Usuario();

        $registroResultado = $usuarioObj->insertarUsuario($nombre, $apellido, $usuario, $password, 'global');

        if ($registroResultado === true) {
            http_response_code(201); // Created
            echo json_encode([
                'success' => true,
                'message' => 'Usuario registrado correctamente.'
            ]);
        } elseif ($registroResultado === "duplicate") {
            http_response_code(409); // Conflict
            echo json_encode([
                'success' => false,
                'message' => 'El nombre de usuario ya está en uso.'
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar el usuario.'
            ]);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos obligatorios (nombre, apellido, usuario, password).'
        ]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'success' => false,
        'message' => 'Método HTTP no permitido. Usa POST.'
    ]);
}
?>
