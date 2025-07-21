<?php
require_once '../utils/sanitizar.php';
require_once '../class/C_usuario.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    $data = json_decode(file_get_contents("php://input"), true);

    // Sanitizar entradas (excepto password que solo se recorta)
    $nombre = SanitizarEntrada::limpiarCadena($data['nombre'] ?? '');
    $apellido = SanitizarEntrada::limpiarCadena($data['apellido'] ?? '');
    $usuario = SanitizarEntrada::limpiarCadena($data['usuario'] ?? '');
    $password = trim($data['password'] ?? '');

    // Validación básica de contraseña (longitud y caracteres)
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[-_!@#$%^&*]).{8,}$/';
    if (!preg_match($passwordRegex, $password)) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'success' => false,
            'message' => 'La contraseña no cumple con los requisitos mínimos.'
        ]);
        exit;
    }

    // Validar que todos los campos requeridos estén presentes y no vacíos
    if ($nombre && $apellido && $usuario && $password) {
        $usuarioObj = new Usuario();

        // Importante: en insertarUsuario debes aplicar password_hash() internamente
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
