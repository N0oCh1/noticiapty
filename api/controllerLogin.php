<?php

try {
    require_once "../class/C_usuario.php";

    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        header("Content-Type: application/json; charset=utf-8");

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['usuario'], $data['password'])) {
            $usuarioInput = $data['usuario'];
            $passwordInput = $data['password'];

            $usuarioObj = new Usuario();

            $datosLogin = $usuarioObj->verificarLogin($usuarioInput, $passwordInput);

            if ($datosLogin) {
                // Guardar el ID y rol del usuario en sesión
                $_SESSION['usuario_id'] = $datosLogin['id'];
                $_SESSION['rol'] = $datosLogin['rol'];

                http_response_code(200); // OK
                echo json_encode([
                    'success' => true,
                    'usuario_id' => $datosLogin['id'],
                    'nombre' => $datosLogin['nombre'],
                    'apellido' => $datosLogin['apellido'],
                    'usuario' => $datosLogin['usuario'],
                    'rol' => $datosLogin['rol']
                ]);
            } else {
                http_response_code(401); // Unauthorized
                echo json_encode([
                    'success' => false,
                    'message' => 'Usuario o contraseña incorrectos'
                ]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode([
                'success' => false,
                'message' => 'Faltan datos: usuario o password'
            ]);
        }
    } else {
        http_response_code(405); // Method Not Allowed
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido'
        ]);
    }
} catch (Exception $e) {
    error_log("Error en el servidor: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'success' => false,
        'message' => 'Error interno del servidor'
    ]);
}
?>
