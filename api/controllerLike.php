<?php
require "../class/Likes.php";
header("Content-Type: application/json; charset=utf-8");

$likes = new Likes();

// POST: Dar like
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input["usuario_id"], $input["noticia_id"])) {
        $usuario_id = $input["usuario_id"];
        $noticia_id = $input["noticia_id"];

        // Dar like sin necesidad de 'accion'
        $resultado = $likes->darLike($usuario_id, $noticia_id);
        if ($resultado) {
            http_response_code(201);
            echo json_encode(["message" => "Like registrado correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Ya has dado like"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Faltan parámetros para dar like."]);
    }
}


// DELETE: Quitar like
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input["usuario_id"], $input["noticia_id"])) {
        $usuario_id = $input["usuario_id"];
        $noticia_id = $input["noticia_id"];

        $resultado = $likes->quitarLike($usuario_id, $noticia_id);
        if ($resultado) {
            http_response_code(200);
            echo json_encode(["message" => "Like eliminado correctamente"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "No habías dado like aún."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Faltan parámetros para eliminar like."]);
    }
}

// GET: Obtener likes o verificar
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["usuario_id"], $_GET["noticia_id"])) {
        $yaDioLike = $likes->verificarLike($_GET["usuario_id"], $_GET["noticia_id"]);
        echo json_encode(["ya_dio_like" => $yaDioLike]);
    } elseif (isset($_GET["noticia_id"])) {
        $total = $likes->obtenerLikes($_GET["noticia_id"]);
        echo json_encode(["total_likes" => $total]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Parámetros faltantes."]);
    }
}

?>
