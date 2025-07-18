<?php
require "../class/comentario.php";

// Controlador
header("Content-Type: application/json");
$comentario = new Comentario();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['noticia_id'])) {
            $comentarios = $comentario->obtenerComentarios($_GET['noticia_id']);
            echo json_encode($comentarios);
        } else {
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
            echo json_encode(["success" => $exito]);
        } else {
            echo json_encode(["error" => "Faltan datos"]);
        }
        break;
}
