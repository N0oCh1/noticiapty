<?php
require_once "../class/C_usuario.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$usuario = new Usuario();
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

switch ($method) {
    //obtener info de 1 usuario
  case 'GET':
    if ($id) {
      $data = $usuario->obtenerUsuarioPorId($id);
      if ($data) {
        http_response_code(200); // OK
        echo json_encode($data);
      } else {
        http_response_code(404); // Not Found
        echo json_encode(["message" => "Usuario no encontrado"]);
      }
    //obtener todos los usuarios
    } else {
      $data = $usuario->obtenerUsuarios();
      if ($data && count($data) > 0) {
        http_response_code(200);
        echo json_encode($data);
      } else {
        http_response_code(404);
        echo json_encode(["message" => "No hay usuarios registrados"]);
      }
    }
    break;
    // Insertar nuevo usuario
  case 'POST':
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['nombre'], $input['apellido'], $input['usuario'], $input['contrasena'], $input['rol'])) {
      http_response_code(400); // Bad Request
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

    if ($ok) {
      http_response_code(201); // Created
      echo json_encode(["success" => true]);
    } else {
      http_response_code(500); // Internal Server Error
      echo json_encode(["message" => "No se pudo crear el usuario"]);
    }
    break;
    // Actualizar usuario
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
    // Desactivar usuario
  case 'DELETE':
    if (!$id) {
      http_response_code(400);
      echo json_encode(["message" => "Se requiere el ID para desactivar"]);
      exit;
    }

    $ok = $usuario->desactivarUsuario($id);
    if ($ok) {
      http_response_code(200);
      echo json_encode(["success" => true]);
    } else {
      http_response_code(500);
      echo json_encode(["message" => "No se pudo desactivar el usuario"]);
    }
    break;

  default:
    http_response_code(405); // Method Not Allowed
    echo json_encode(["message" => "Método HTTP no permitido"]);
    break;
}
?>
