<?php
require "../class/C_noticia.php";
session_start();
require_once "../utils/validaciones.php";
require_once "../utils/sanitizar.php";

header("Content-Type: application/json; charset=utf-8");

$noticia = new Noticia();
$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
  // Detectar simulación de PUT con POST (_method=PUT)
  if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    // Actualizar noticia
    $id = intval($_POST["noticia_id"] ?? 0);
    if (!validarId($id)) {
      http_response_code(400);
      echo json_encode(["success" => false, "message" => "ID de noticia inválido"]);
      exit();
    }

    $datos = [
      'titulo' => $_POST["titulo"] ?? "",
      'contenido' => $_POST["contenido"] ?? "",
      'categoria' => $_POST["categoria"] ?? "",
      'usuario' => $_POST["usuario"] ?? "",
      'autor' => $_POST["autor"] ?? ""
    ];

    if (!validarCamposRequeridos($datos, ['titulo', 'contenido', 'categoria', 'usuario', 'autor'])) {
      http_response_code(400);
      echo json_encode(["success" => false, "message" => "Datos incompletos"]);
      exit();
    }

    $datos = sanitizarArray($datos);

    $imagen = $_FILES["imagen"] ?? null;

    $result = $noticia->ActualizarNoticia($id, $datos['titulo'], $datos['contenido'], $datos['categoria'], $datos['usuario'], $imagen, $datos['autor']);

    if ($result) {
      http_response_code(200);
      echo json_encode(["success" => true, "message" => "Noticia actualizada correctamente"]);
    } else {
      http_response_code(500);
      echo json_encode(["success" => false, "message" => "Error al actualizar noticia"]);
    }
    exit();
  }

  // Guardar noticia (crear)
  $datos = [
    'titulo' => $_POST["titulo"] ?? "",
    'contenido' => $_POST["contenido"] ?? "",
    'categoria' => $_POST["categoria"] ?? "",
    'usuario' => $_POST["usuario"] ?? "",
    'autor' => $_POST["autor"] ?? ""
  ];

  if (!validarCamposRequeridos($datos, ['titulo', 'contenido', 'categoria', 'usuario', 'autor'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Datos incompletos"]);
    exit();
  }

  $datos = sanitizarArray($datos);

  $imagen = $_FILES["imagen"] ?? null;

  $activo = 3; // Por defecto: en espera

  $result = $noticia->GuardarNoticia($datos['titulo'], $datos['contenido'], $datos['categoria'], $activo, $datos['usuario'], $imagen, $datos['autor']);

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

  if (!validarId($id) || !validarEstadoNoticia($estado)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Datos inválidos para cambio de estado"]);
    exit();
  }

  // Obtener el estado actual de la noticia
  $estadoActual = $noticia->obtenerEstado($id);

  // Depuración para ver el estado actual
  error_log("Estado actual: " . $estadoActual);

  // Si el estado no ha cambiado, no hacer nada
  if ($estadoActual === $estado) {
    echo json_encode(["success" => true, "message" => "El estado no ha cambiado"]);
    exit();
  }

  // Si el estado cambió, proceder a la actualización
  $resultado = $noticia->CambiarEstado($id, $estado);

  if ($resultado) {
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
  } else {
    echo json_encode(["success" => false, "message" => "No se pudo actualizar el estado"]);
  }
  exit();
}



if ($method === "GET") {
  try {
    // Buscar noticia por id si se pasa parámetro 'id'
    if (isset($_GET['id'])) {
      $id = intval($_GET['id']);
      if (!validarId($id)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID inválido"]);
        exit();
      }
      $resultado = $noticia->ObtenerNoticiaPorId($id);
      if ($resultado) {
        http_response_code(200);
        echo json_encode(["success" => true, "noticia" => $resultado]);
      } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Noticia no encontrada"]);
      }
      exit();
    }

    // Si se pasa el parámetro "mis_noticias=true", devolver solo las del usuario en sesión
    if (isset($_GET['mis_noticias']) && $_GET['mis_noticias'] === "true") {
      if (isset($_SESSION['usuario_id'])) {
        $usuario = $_SESSION['usuario_id'];
        $noticiasUsuario = $noticia->ObtenerNoticiasPorUsuario($usuario);
        http_response_code(200);
        echo json_encode($noticiasUsuario);
        exit();
      } else {
        http_response_code(401);
        echo json_encode(["message" => "No hay usuario en sesión"]);
        exit();
      }
    }

    // Si no se pidió "mis_noticias", se devuelven todas o por categoría
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
?>
