<?php
session_start(); // Inicio sesión para acceder a $_SESSION

require_once "../class/C_usuario.php";

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

// Obtener rol del usuario logueado (por sesión)
function obtenerRolSesion() {
    if (!isset($_SESSION['usuario_id'])) return null;
    $usr = new Usuario();
    return $usr->obtenerRolPorId($_SESSION['usuario_id']);
}

switch ($method) {
    case 'GET':
        // igual que antes
        // ...
        break;

    case 'POST':
        // Solo usuarios autenticados pueden crear otros usuarios
        if (!isset($_SESSION['usuario_id'])) {
            http_response_code(401);
            echo json_encode(["message" => "No autenticado"]);
            exit;
        }

        $rolSesion = obtenerRolSesion();
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

        // Validar permiso para crear usuario con rol admin o periodista
        if (in_array($input['rol'], ['admin', 'periodista']) && $rolSesion !== 'admin') {
            http_response_code(403);
            echo json_encode(["message" => "No tienes permisos para crear usuarios con rol '{$input['rol']}'"]);
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
        // Igual, aquí podrías agregar validación si quieres
        break;

    case 'DELETE':
        // Igual
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método HTTP no permitido"]);
        break;
}
?>
