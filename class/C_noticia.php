<?php
require_once "C_conexion.php";
require_once "C_imagen.php";
require_once "C_subirImagen.php";

class Noticia
{
  private $db;
  private $db_conexion;
  private int $id;
  private string $titulo;
  private string $contenido;
  private int $activo;
  private string $categoria;
  private string $usuario;
  private $imagen;
  private $autor;

  public function __construct()
  {
    $this->db = new db();
    $this->db_conexion = $this->db->getConexion();
  }

  // Guarda una nueva noticia y sus imágenes
  public function GuardarNoticia($titulo, $contenido, $categoria, $activo, $usuario, array $imagen = [], $autor = '')
  {
    $this->titulo = $titulo;
    $this->contenido = $contenido;
    $this->categoria = $categoria;
    $this->activo = $activo;
    $this->usuario = $usuario;
    $this->imagen = $imagen;
    $this->autor = $autor;

    $datos = [
      "titulo" => $this->titulo,
      "contenido" => $this->contenido,
      "categoria_id" => $this->categoria,
      "activo" => $this->activo,
      "usuario_id" => $this->usuario,
      "autor" => $this->autor
    ];

    try {
      $this->db->insertSeguro("noticias", $datos);
      $this->id = $this->db_conexion->lastInsertId();
      $this->GuardarImagen($this->id, $this->imagen);
      $this->db->disconnect();
      return true;
    } catch (Exception $e) {
      error_log("Error al guardar noticia: " . $e->getMessage());
      $this->db->disconnect();
      return false;
    }
  }

  // Obtiene todas las noticias o por categoría
  public function ObtenerNoticias($categoria = 'todas')
  {
    $classImagen = new Imagen();
    $response = [];

    try {
      $selectFields = "n.*, c.nombre AS categoria_nombre, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario";
      $fromTables = "noticias n 
                    LEFT JOIN categorias c ON n.categoria_id = c.id
                    JOIN usuarios u ON n.usuario_id = u.id 
                    AND (u.rol = 'editor' OR u.rol = 'supervisor' OR u.rol = 'admin')";

      if ($categoria === 'todas') {
        $data = $this->db->selectRaw($fromTables, $selectFields, "1 ORDER BY n.id DESC");
      } else {
        $data = $this->db->selectRaw($fromTables, $selectFields, "n.categoria_id = '$categoria' ORDER BY n.id DESC");
      }

      if (is_iterable($data)) {
        foreach ($data as $noticia) {
          $imagenes = $classImagen->ObtenerImagenes($noticia['id']);
          $noticia['imagenes'] = $imagenes;
          $response[] = $noticia;
        }
      }

      $this->db->disconnect();
      return $response;
    } catch (Exception $e) {
      throw new Exception("Error al obtener noticias");
    }
  }

  // Guarda imágenes asociadas a la noticia
  public function GuardarImagen($id_noticia, $imagen)
  {
    $total = count($imagen['name']);
    $guardarImagen = new Imagen();
    $procesar = new ImagenUploader();

    for ($i = 0; $i < $total; $i++) {
      $file = [
        'name' => $imagen['name'][$i],
        'type' => $imagen['type'][$i],
        'tmp_name' => $imagen['tmp_name'][$i],
        'error' => $imagen['error'][$i],
        'size' => $imagen['size'][$i]
      ];

      $imagen_procesada = $procesar->procesarImagen($file);
      $guardarImagen->GuardarImagen($id_noticia, $imagen_procesada['ruta_original'], $imagen_procesada['tipo']);

      // Solo genera y guarda miniatura de la primera imagen
      if ($i === 0) {
        $rutaMinuatura = $procesar->generarMiniatura($imagen_procesada['ruta_original'], $imagen_procesada['tipo']);
        $guardarImagen->GuardarImagen($id_noticia, $rutaMinuatura, $imagen_procesada['tipo']);
      }
    }
  }

  // Cambia el estado de una noticia (activo, inactivo, en espera)
  public function CambiarEstado($id, $estado)
  {
    $tb_name = "noticias";
    $string = "activo = $estado";
    $astriction = "id = $id";

    return $this->db->update($tb_name, $string, $astriction);
  }

  // Obtiene noticias filtradas por usuario (para editores)
  public function ObtenerNoticiasPorUsuario($usuario_id)
  {
    $classImagen = new Imagen();
    $response = [];

    try {
      $selectFields = "n.*, c.nombre AS categoria_nombre, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario";
      $fromTables = "noticias n 
                    LEFT JOIN categorias c ON n.categoria_id = c.id
                    LEFT JOIN usuarios u ON n.usuario_id = u.id";
      $where = "n.usuario_id = '$usuario_id' ORDER BY n.fecha_creacion DESC";

      $noticias = $this->db->selectRaw($fromTables, $selectFields, $where);

      if (is_iterable($noticias)) {
        foreach ($noticias as $noticia) {
          $imagenes = $classImagen->ObtenerImagenes($noticia['id']);
          $noticia['imagenes'] = $imagenes;
          $response[] = $noticia;
        }
      }

      $this->db->disconnect();
      return $response;
    } catch (Exception $e) {
      return [];
    }
  }
}
?>
