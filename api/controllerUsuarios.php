<?php
session_start(); // Inicio sesión para acceder a $_SESSION

require_once "../class/C_usuario.php";
require_once "../utils/security.php";     // contiene las validaciones de rol (admin, editor, general)
require_once "../utils/validaciones.php"; // contiene validarPermiso, ocultarContrasena, etc.

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$usuario = new Usuario();
$id = idGetValido();
$usuarioSesionId = $_SESSION['usuario_id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $data = $usuario->obtenerUsuarioPorId($id);
            if ($data) {
                $data = ocultarContrasena($data);
                http_response_code(200);
                echo json_encode($data);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } else {
            if (!$usuarioSesionId || !validarPermiso($usuarioSesionId, 'admin')) {
                http_response_code(403);
                echo json_encode(["message" => "Permiso denegado"]);
                exit;
            }
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
        if (!$usuarioSesionId || !validarPermiso($usuarioSesionId, 'admin')) {
            http_response_code(401);
            echo json_encode(["message" => "No autenticado"]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);

        if (!validarCamposRequeridos($input, ['nombre', 'apellido', 'usuario', 'contrasena', 'rol'])) {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
            exit;
        }

        // Validar permiso para crear usuario con rol sensible
        if (in_array($input['rol'], ['admin']) && !validarRolAdmin($usuarioSesionId)) {
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
        } elseif ($ok) {
            http_response_code(201);
            echo json_encode(["success" => true]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "No se pudo crear el usuario"]);
        }
        break;

    case 'PUT':
        try{ 
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

            // Decodificar los datos enviados en el PUT
            $input = json_decode(file_get_contents("php://input"), true);
            if (!$input) {
                http_response_code(400);
                echo json_encode(["message" => "Datos de actualización no válidos"]);
                exit;
            }

            // Verificar si el frontend intenta actualizar el rol sin ser admin
            if (array_key_exists('rol', $input) && !validarRolAdmin($usuarioSesionId) && $_SESSION['rol'] !== 'admin') {
                http_response_code(403);
                echo json_encode(["message" => "No tienes permisos para actualizar el rol a '{$input['rol']}'"]);
                exit;
            }
            // Obtener usuario actual de la base de datos
            $usuarioActual = $usuario->obtenerUsuarioPorId($id);
            if (!$usuarioActual) {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
                exit;
            }

            // Comparar si hubo cambios reales
            $datosNoHanCambiado = true;
            foreach ($input as $key => $value) {
                // Asegúrate de comparar solo campos que existen en la BD
                if (isset($usuarioActual[$key])) {
                    if ((string)$usuarioActual[$key] !== (string)$value) {
                        $datosNoHanCambiado = false;
                        break;
                    }
                }
            }

            if ($datosNoHanCambiado) {
                http_response_code(200);
                echo json_encode(["message" => "No se realizaron cambios"]);
                exit;
            }

            // Realizar la actualización si los datos cambiaron
            $ok = $usuario->actualizarUsuario($id, $input);
            if ($ok) {
                http_response_code(200);
                echo json_encode(["success" => true]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "No se pudo actualizar el usuario"]);
            }
            break;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error interno del servidor: ".$e->getMessage()]);
        }

    case 'DELETE':
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
        if (!isset($input['activo']) || !validarBinario($input['activo'])) {
            http_response_code(400);
            echo json_encode(["message" => "Se requiere el estado activo (0 o 1)"]);
            exit;
        }

        $nuevoEstado = intval($input['activo']);
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