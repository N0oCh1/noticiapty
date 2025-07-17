<?php
require "../class/C_noticia.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  header("Content-Type: application/json; charset=utf-8");
  $noticia = new Noticia();

  $titulo = $_POST["titulo"];
  $contenido = $_POST["contenido"];
  $categoria = $_POST["categoria"];
  $usuario = $_POST["usuario"];
  $imagen = $_FILES["imagen"];
  $autor = $_POST["autor"];

  $activo = 3; // Por defecto, 3 significa "en espera"
  $result = $noticia->GuardarNoticia($titulo, $contenido, $categoria, $activo, $usuario, $imagen, $autor);
  if ($result) {
    http_response_code(201);
    echo json_encode([
      "message" => "Noticia guardada correctamente"
    ]);
  } else {
    http_response_code(500);
    echo json_encode([
      "message" => "Error al guardar noticia"
    ]);
  }
  exit(); // Detener ejecución luego de POST
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  header("Content-Type: application/json; charset=utf-8");
  try {
    $noticia = new Noticia();
    $categoria = isset($_GET['category']) ? $_GET['category'] : 'todas';
    $noticias = $noticia->ObtenerNoticias($categoria);
    http_response_code(200);
    echo json_encode($noticias);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
      "message" => "Error al obtener noticias: " . $e->getMessage()
    ]);
  }
  exit();
}

// Si no es ni POST ni GET, puedes enviar error 405 Method Not Allowed
http_response_code(405);
echo json_encode(["message" => "Método no permitido"]);
exit();
?>