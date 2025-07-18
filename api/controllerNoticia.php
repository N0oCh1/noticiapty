<?php
require "../class/C_noticia.php";

header("Content-Type: application/json; charset=utf-8");

$noticia = new Noticia();

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
  // Guardar noticia
  $titulo = $_POST["titulo"] ?? "";
  $contenido = $_POST["contenido"] ?? "";
  $categoria = $_POST["categoria"] ?? "";
  $usuario = $_POST["usuario"] ?? "";
  $imagen = $_FILES["imagen"] ?? null;
  $autor = $_POST["autor"] ?? "";
  $activo = 3; // Por defecto: en espera

  $result = $noticia->GuardarNoticia($titulo, $contenido, $categoria, $activo, $usuario, $imagen, $autor);

  if ($result) {
    http_response_code(201);
    echo json_encode(["message" => "Noticia guardada correctamente"]);
  } else {
    http_response_code(500);
    echo json_encode(["message" => "Error al guardar noticia"]);
  }
  exit();
}

if ($method === "PUT") {
  // PUT para cambiar estado de una noticia
  $input = json_decode(file_get_contents("php://input"), true);
  $id = isset($input['id']) ? intval($input['id']) : 0;
  $estado = isset($input['estado']) ? intval($input['estado']) : 0;

  if ($id > 0 && in_array($estado, [1, 2, 3])) {
    $resultado = $noticia->CambiarEstado($id, $estado);
    if ($resultado) {
      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
    } else {
      http_response_code(500);
      echo json_encode(["success" => false, "message" => "Error al actualizar estado"]);
    }
  } else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Datos inválidos para cambio de estado"]);
  }
  exit();
}

if ($method === "GET") {
  // Obtener noticias
  try {
    $categoria = $_GET['category'] ?? 'todas';
    $noticias = $noticia->ObtenerNoticias($categoria);
    http_response_code(200);
    echo json_encode($noticias);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Error al obtener noticias: " . $e->getMessage()]);
  }
  exit();
}

http_response_code(405);
echo json_encode(["message" => "Método no permitido"]);
exit();
?>