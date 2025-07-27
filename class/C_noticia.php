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

      // JOIN con filtro para usuarios con rol 'editor'
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
  public function obtenerEstado($id)
  {
    try {
      // Consulta para obtener el estado de la noticia
      $selectFields = "activo";  // Solo necesitamos la columna 'activo'
      $fromTables = "noticias";  // Tabla 'noticias'
      $where = "id = $id";       // Filtrar por ID de la noticia

      // Ejecutamos la consulta
      $resultado = $this->db->selectRaw($fromTables, $selectFields, $where);

      // Si hay resultados, devolvemos el estado
      if (!empty($resultado) && isset($resultado[0])) {
        return intval($resultado[0]['activo']);  // Aseguramos que el valor sea un entero
      } else {
        // Si no encontramos la noticia, devolvemos null o algún valor predeterminado
        return null;
      }
    } catch (Exception $e) {
      // En caso de error, logueamos y devolvemos null
      error_log("Error al obtener estado de la noticia: " . $e->getMessage());
      return null;
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

  public function ObtenerNoticiaPorId($id)
  {
    $classImagen = new Imagen();

    try {
      $selectFields = "n.id, n.titulo, n.contenido, n.categoria_id, c.nombre AS categoria_nombre, n.autor";
      $fromTables = "noticias n 
                    LEFT JOIN categorias c ON n.categoria_id = c.id";
      $where = "n.id = '$id'";

      $noticias = $this->db->selectRaw($fromTables, $selectFields, $where);

      if (!empty($noticias) && isset($noticias[0])) {
        $noticia = $noticias[0];
        $imagenes = $classImagen->ObtenerImagenes($noticia['id']);
        $noticia['imagenes'] = $imagenes;
        $this->db->disconnect();
        return $noticia;
      } else {
        return null;
      }
    } catch (Exception $e) {
      return null;
    }
  }
 public function ActualizarNoticia($id, $titulo, $contenido, $categoria, $usuario, $imagen = null, $autor = '')
{
    $this->id = intval($id);
    $this->titulo = $titulo;
    $this->contenido = $contenido;
    $this->categoria = $categoria;
    $this->usuario = $usuario;
    $this->autor = $autor;

    try {
        // 1. Actualizar usando el método update de la clase db
        $campos = [
            "titulo" => $this->titulo,
            "contenido" => $this->contenido,
            "categoria_id" => $this->categoria,
            "usuario_id" => $this->usuario,
            "autor" => $this->autor
        ];

        // Construye el string de actualización tipo: titulo = '...', contenido = '...', etc.
        $setString = implode(", ", array_map(function ($k, $v) {
            return "$k = " . (is_numeric($v) ? $v : "'$v'");
        }, array_keys($campos), $campos));

        // Ejecutar update con la clase db
        $this->db->update("noticias", $setString, "id = $this->id");

        // 2. Si hay imágenes nuevas, eliminar las anteriores y subir las nuevas
        if ($imagen && isset($imagen['name']) && count($imagen['name']) > 0 && $imagen['name'][0] !== '') {
            $this->EliminarImagenesPorNoticia($this->id);
            $this->GuardarImagen($this->id, $imagen);
        }

        $this->db->disconnect();
        return true;
    } catch (Exception $e) {
        error_log("Error al actualizar noticia: " . $e->getMessage());
        $this->db->disconnect();
        return false;
    }
}
/**
 * Eliminar imágenes relacionadas a una noticia (para reemplazar con nuevas).
 */
private function EliminarImagenesPorNoticia($id_noticia)
{   
    $classImagen = new Imagen();
    try {
        // Primero obtén las rutas de las imágenes para borrarlas físicamente si quieres (opcional)
        $imagenes = $classImagen->ObtenerImagenes($id_noticia);
        if ($imagenes) {
            foreach ($imagenes as $img) {
                $ruta = $img['imagen'];
                if (file_exists($ruta)) {
                    unlink($ruta); // borra el archivo
                }
            }
        }
        // Luego elimina las filas de la tabla imagenes
        $this->db->delete("imagenes", "noticia_id = $id_noticia");
    } catch (Exception $e) {
        error_log("Error al eliminar imágenes de noticia: " . $e->getMessage());
    }
}
}
?>