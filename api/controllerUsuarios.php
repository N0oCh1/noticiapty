<?php
session_start(); // Inicio sesión para acceder a $_SESSION

require_once "../class/C_usuario.php";
require_once "../utils/security.php"; // Archivo con las funciones validarRolAdmin, validarRolPeriodista, validarRolGeneral

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$usuario = new Usuario();
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Función para ocultar contraseña (igual que antes)
function ocultarContrasena(array $usuarios) {
    if (isset($usuarios[0]) && is_array($usuarios[0])) {
        foreach ($usuarios as &$u) {
            unset($u['contrasena']);
        }
        return $usuarios;
    }
    if (isset($usuarios['contrasena'])) {
        unset($usuarios['contrasena']);
    }
    return $usuarios;
}

// Obtener id usuario sesión
function obtenerUsuarioSesionId() {
    return $_SESSION['usuario_id'] ?? null;
}

// Validar autenticación y rol mínimo requerido
function validarPermiso(int $usuarioId, string $permiso): bool {
    switch ($permiso) {
        case 'admin':
            return validarRolAdmin($usuarioId);
        case 'periodista':
            return validarRolPeriodista($usuarioId);
        case 'general':
            return validarRolGeneral($usuarioId);
        default:
            return false;
    }
}

$usuarioSesionId = obtenerUsuarioSesionId();

switch ($method) {
    case 'GET':
        // Solo admins pueden ver todos los usuarios
        if (!$usuarioSesionId || !validarPermiso($usuarioSesionId, 'admin')) {
            http_response_code(403);
            echo json_encode(["message" => "Permiso denegado"]);
            exit;
        }

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
        // Solo usuarios autenticados pueden crear otros usuarios
        if (!$usuarioSesionId) {
            http_response_code(401);
            echo json_encode(["message" => "No autenticado"]);
            exit;
        }

        $rolSesion = obtenerRolPorId($usuarioSesionId);
        if ($rolSesion === null) {
            http_response_code(401);
            echo json_encode(["message" => "No autenticado"]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre'], $input['apellido'], $input['usuario'], $input['contrasena'], $input['rol'])) {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
            exit;
        }

        // Validar permiso para crear usuario con rol admin o periodista (solo admins)
        if (in_array($input['rol'], ['admin', 'periodista']) && $rolSesion !== 'admin') {
            http_response_code(403);
            echo json_encode(["message" => "No tienes permisos para crear usuarios con rol '{$input['rol']}'"]);
            exit;
        }

        // Para roles distintos a admin o periodista, si quieres podrías agregar más lógica aquí

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
        if (!$usuarioSesionId) {
            http_response_code(401);
            echo json_encode(["message" => "No autenticado"]);
            exit;
        }
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "Se requiere el ID para actualizar"]);
            exit;
        }

        // Aquí podrías validar permisos más específicos según necesidad
        // Por ejemplo, sólo admin puede actualizar ciertos campos

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

    case 'DELETE':
        // Solo admin puede activar/desactivar usuarios
        if (!$usuarioSesionId || !validarPermiso($usuarioSesionId, 'admin')) {
            http_response_code(403);
            echo json_encode(["message" => "Permiso denegado"]);
            exit;
        }

        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "Se requiere el ID para cambiar estado"]);
            exit;
        }

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