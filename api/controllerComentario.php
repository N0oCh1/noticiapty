<?php
require "../class/comentario.php";
require "../utils/sanitizar.php";
require "../utils/security.php";
session_start();
// Controlador
header("Content-Type: application/json");
$comentario = new Comentario();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['noticia_id'])) {
            $comentarios = $comentario->obtenerComentarios($_GET['noticia_id']);
            http_response_code(200);
            echo json_encode($comentarios);
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Falta noticia_id"]);
        }
        break;

    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);
        if (isset($input['noticia_id'], $input['usuario_id'], $input['contenido'])) {
            $comentario_padre_id = isset($input['comentario_padre_id']) ? $input['comentario_padre_id'] : null;
            $exito = $comentario->insertarComentario(
                $input['noticia_id'],
                $input['usuario_id'],
                $input['contenido'],
                $comentario_padre_id
            );
            if ($exito) {
                http_response_code(201); // Created
                echo json_encode(["success" => true]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["error" => "No se pudo insertar el comentario"]);
            }
        } else {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Faltan datos"]);
        }
        break;

    case 'DELETE':
        
        if (!validarRolAdmin($_SESSION["usuario_id"]) && !($_GET["usuario_id"]==$_SESSION["usuario_id"])) {
            http_response_code(403); // Forbidden
            echo json_encode(["error" => "No tienes permiso para eliminar comentarios"]);
            exit;
        }
        $id = $_GET['id'] ?? null;
        try {
            $id = SanitizarEntrada::limpiarCadena($id);
            if (isset($id)) {
                $exito = $comentario->eliminarComentario($id);
                if ($exito) {
                    http_response_code(200); // OK
                    echo json_encode(["success" => true]);
                } else {
                    http_response_code(404); // Not Found
                    echo json_encode(["error" => "Comentario no encontrado o ya eliminado"]);
                }
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(["error" => "Falta id"]);
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Error al eliminar: " . $e->getMessage()]);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>