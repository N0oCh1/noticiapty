<?php
require "../class/Likes.php"; // Asegúrate de incluir tu clase Likes correctamente

// Si la solicitud es POST (para dar like)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json; charset=utf-8");

    // Verificamos si se ha enviado el usuario y noticia ID
    if (isset($_POST["usuario_id"]) && isset($_POST["noticia_id"])) {
        $usuario_id = $_POST["usuario_id"];
        $noticia_id = $_POST["noticia_id"];

        $likes = new Likes();

        // Intentamos dar like a la noticia
        $likeExitoso = $likes->darLike($usuario_id, $noticia_id);

        if ($likeExitoso) {
            http_response_code(201);
            echo json_encode([
                "message" => "Like registrado correctamente"
            ]);
        } else {
            http_response_code(400);
            echo json_encode([
                "message" => "Ya has dado like a esta noticia"
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            "message" => "Faltan parámetros (usuario_id, noticia_id)"
        ]);
    }
}

// Si la solicitud es GET (para obtener los likes de una noticia)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    header("Content-Type: application/json; charset=utf-8");

    // Verificamos si se ha enviado el ID de la noticia
    if (isset($_GET["noticia_id"])) {
        $noticia_id = $_GET["noticia_id"];

        $likes = new Likes();

        // Obtenemos el total de likes de la noticia
        $totalLikes = $likes->obtenerLikes($noticia_id);

        http_response_code(200);
        echo json_encode([
            "total_likes" => $totalLikes
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            "message" => "Falta el parámetro noticia_id"
        ]);
    }
}
?>
