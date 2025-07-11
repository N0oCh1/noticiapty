<?php
require_once "C_conexion.php";
require_once "C_imagen.php";
require_once "C_subirImagen.php";

  class Noticia {
    private $db;
    private $db_conexion;
    private int $id;
    private string $titulo;
    private string $contenido;
    private string $autor;
    private bool $activo;
    private string $categoria;
    private $imagen;
    
    public function __construct()
    {
      $this->db = new db();
      $this->db_conexion = $this->db->getConexion();
    }

    public function GuardarNoticia($titulo, $contenido, $autor, $categoria, $activo, array $imagen=[]) {
      $this->titulo = $titulo;
      $this->contenido = $contenido;
      $this->autor = $autor;
      $this->categoria = $categoria;
      $this->activo = $activo;
      $this->imagen = $imagen;


      $datos = array(
        "titulo" => $this->titulo,
        "contenido" => $this->contenido,
        "autor" => $this->autor,
        "categoria" => $this->categoria,
        "activo" => $this->activo
      );
      try{
        $this->db->insertSeguro("noticias", $datos);
        $this->id = $this->db_conexion->lastInsertId();
        $this->GuardarImagen($this->id, $this->imagen);
        $this->db->disconnect();
        return true;
      }
      catch(Exception $e){
        throw new Exception("Error al guardar noticia");
        $this->db->disconnect();
        return false;
      }
    }

    public function ObtenerNoticias() {
      $classImagen = new Imagen();
      try{
        $response = [];
        $data = $this->db->select("noticias", "*");
        foreach($data as $noticia){
          $imagenes = $classImagen->ObtenerImagenes($noticia['id']);
          $noticia['imagenes'] = $imagenes;
          $response[] = $noticia;
        }
        $this->db->disconnect();
        return $response;
      }
      catch(Exception $e){
        throw new Exception("Error al obtener noticias");
        $this->db->disconnect();
        return false;
      }
    }
    private function GuardarImagen($id_noticia,  $imagen) {
      $total = count($imagen['name']);
      $guardarImagen = new Imagen();
      $procesar = new ImagenUploader();
      
      for($i = 0; $i < $total; $i++){
        $file = [
          'name' => $imagen['name'][$i],
          'type' => $imagen['type'][$i],
          'tmp_name' => $imagen['tmp_name'][$i],
          'error' => $imagen['error'][$i],
          'size' => $imagen['size'][$i]
        ];
        $imagen_procesada = $procesar->procesarImagen($file);
        $guardarImagen->GuardarImagen($id_noticia, $imagen_procesada['ruta_original'], $imagen_procesada['tipo']);
        if($i === 0) {
          $rutaMinuatura = $procesar->generarMiniatura($imagen_procesada['ruta_original'], $imagen_procesada['tipo']);
          $guardarImagen->GuardarImagen($id_noticia, $rutaMinuatura, $imagen_procesada['tipo']);
        }
      }
    }
  }
?>