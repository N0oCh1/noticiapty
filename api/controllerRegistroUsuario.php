<?php

require_once "../class/C_usuario.php"; // Asegúrate de incluir la clase Usuario

// Comprobamos si la solicitud es un POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    // Recibimos los datos desde el frontend
    $data = json_decode(file_get_contents("php://input"), true);

    // Validamos que los campos necesarios estén presentes
    if (isset($data['nombre'], $data['apellido'], $data['usuario'], $data['password'])) {
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $usuario = $data['usuario'];
        $password = $data['password'];

        // Instanciamos la clase Usuario para registrar al nuevo usuario
        $usuarioObj = new Usuario($usuario, $password);

        // Llamamos al método para registrar el nuevo usuario
        $registroResultado = $usuarioObj->registrarUsuario($nombre, $apellido, $usuario, $password);

if ($registroResultado === true) {
    echo json_encode([
        'success' => true,
        'message' => 'Usuario registrado correctamente.'
    ]);
} elseif ($registroResultado === "duplicate") {
    echo json_encode([
        'success' => false,
        'message' => 'El nombre de usuario ya está en uso.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar el usuario.'
    ]);
}

}
}
?>