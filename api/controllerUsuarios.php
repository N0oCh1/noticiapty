<?php
require_once "../class/C_usuario.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$usuario = new Usuario();
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Función para ocultar contraseña en la respuesta
function ocultarContrasena(array $usuarios) {
    // Si es un array de usuarios (lista)
    if (isset($usuarios[0]) && is_array($usuarios[0])) {
        foreach ($usuarios as &$u) {
            unset($u['contrasena']);
        }
        return $usuarios;
    }
    // Si es un solo usuario
    if (isset($usuarios['contrasena'])) {
        unset($usuarios['contrasena']);
    }
    return $usuarios;
}

switch ($method) {
    case 'GET':
        if ($id) {
            $data = $usuario->obtenerUsuarioPorId($id);
            if ($data) {
                $data = ocultarContrasena($data);
                http_response_code(200); // OK
                echo json_encode($data);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } else {
            $data = $usuario->obtenerUsuarios();
            if ($data && count($data) > 0) {
                $data = ocultarContrasena($data);
                http_response_code(200);
                echo json_encode($data);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "No hay usuarios registrados"]);
            }
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre'], $input['apellido'], $input['usuario'], $input['contrasena'], $input['rol'])) {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
            exit;
        }

        $ok = $usuario->insertarUsuario(
            $input['nombre'],
            $input['apellido'],
            $input['usuario'],
            $input['contrasena'],
            $input['rol']
        );

        if ($ok === "duplicate") {
            http_response_code(409);
            echo json_encode(["message" => "El usuario ya existe"]);
        } else if ($ok) {
            http_response_code(201);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "No se pudo crear el usuario"]);
        }
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "Se requiere el ID para actualizar"]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input) {
            http_response_code(400);
            echo json_encode(["message" => "Datos de actualización no válidos"]);
            exit;
        }

        $ok = $usuario->actualizarUsuario($id, $input);
        if ($ok) {
            http_response_code(200);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "No se pudo actualizar el usuario"]);
        }
        break;

    // Ahora DELETE sirve para cambiar activo a 0 o 1 (toggle)
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "Se requiere el ID para cambiar estado"]);
            exit;
        }

        // Esperamos recibir en JSON el nuevo estado activo (0 o 1)
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['activo'])) {
            http_response_code(400);
            echo json_encode(["message" => "Se requiere el estado activo (0 o 1)"]);
            exit;
        }

        $nuevoEstado = intval($input['activo']);
        if ($nuevoEstado !== 0 && $nuevoEstado !== 1) {
            http_response_code(400);
            echo json_encode(["message" => "Estado activo inválido, debe ser 0 o 1"]);
            exit;
        }

        $ok = $usuario->actualizarUsuario($id, ['activo' => $nuevoEstado]);
        if ($ok) {
            http_response_code(200);
            echo json_encode(["success" => true, "activo" => $nuevoEstado]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "No se pudo actualizar el estado del usuario"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método HTTP no permitido"]);
        break;
}
?>
