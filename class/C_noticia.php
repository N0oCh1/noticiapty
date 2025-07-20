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

  public function GuardarNoticia($titulo, $contenido, $categoria, $activo, $usuario, array $imagen = [], $autor = '')
  {
    $this->titulo = $titulo;
    $this->contenido = $contenido;
    $this->categoria = $categoria;
    $this->activo = $activo;
    $this->usuario = $usuario;
    $this->imagen = $imagen;
    $this->autor = $autor;


    $datos = array(
      "titulo" => $this->titulo,
      "contenido" => $this->contenido,
      "categoria_id" => $this->categoria,
      "activo" => $this->activo,
      "usuario_id" => $this->usuario,
      "autor" => $this->autor  
    );

    try {
      $this->db->insertSeguro("noticias", $datos);
      $this->id = $this->db_conexion->lastInsertId();
      $this->GuardarImagen($this->id, $this->imagen);
      $this->db->disconnect();
      return true;
    } catch (Exception $e) {
      // Puedes registrar el error o devolverlo
      error_log("Error al guardar noticia: " . $e->getMessage());
      $this->db->disconnect();
      return false;
    }
  }




  public function ObtenerNoticias($categoria = 'todas')
  {
    $classImagen = new Imagen();
    try {
      $response = [];

      // JOIN con filtro para usuarios con rol 'publicador'
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


  public function GuardarImagen($id_noticia,  $imagen)
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
      if ($i === 0) {
        $rutaMinuatura = $procesar->generarMiniatura($imagen_procesada['ruta_original'], $imagen_procesada['tipo']);
        $guardarImagen->GuardarImagen($id_noticia, $rutaMinuatura, $imagen_procesada['tipo']);
      }
    }
  }

  public function CambiarEstado($id, $estado) {
    $tb_name = "noticias";
    $string = "activo = $estado";
    $astriction = "id = $id";

    return $this->db->update($tb_name, $string, $astriction);
  }
  
  public function ObtenerNoticiasPorUsuario($usuario_id) {
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
      // Puedes loguear si necesitas
      return [];
    }
  }
  public function buscarNoticias($palabraCla){
    $classImagen = new Imagen();
    $response = [];

    $selectFields = "n.id, n.titulo, n.contenido, n.fecha_creacion, c.nombre AS categoria, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario";
    $fromTables = "noticias n
      LEFT JOIN categorias c ON n.categoria_id = c.id
      LEFT JOIN usuarios u ON n.usuario_id = u.id";

    // Preparamos la cláusula WHERE sin usar parámetros directamente, ya que selectRaw no usa bind
    $searchTerm = addslashes($palabraCla); // escapa para prevenir errores de sintaxis
    $where = "(n.titulo LIKE '%$searchTerm%' OR n.contenido LIKE '%$searchTerm%') AND n.activo = 1 ORDER BY n.fecha_creacion DESC";

    try {
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
      // Registrar error si es necesario
      error_log("Error al buscar noticias: " . $e->getMessage());
      return [];
    }
  }
}
?>