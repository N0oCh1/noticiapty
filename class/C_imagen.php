<?php
require_once "C_conexion.php";

  class Imagen {
    private $db;


    public function __construct() {
      $this->db = new db();
    }

    public function GuardarImagen($id_noticia, $rutaImagen, $tipoImagen) {
      $data = array(
        "noticia_id" => $id_noticia,
        "imagen" => $rutaImagen,
        "tipo_imagen" => $tipoImagen
      );
      try{
        $this->db->insertSeguro("imagenes", $data);
        return true;
      }
      catch(Exception $e){
        throw new Exception("Error al guardar imagen");
      }
    }
    public function ObtenerImagenes($id_noticia) {
      try{
        $data = $this->db->select("imagenes", "imagen, tipo_imagen", "noticia_id = $id_noticia ORDER BY id ASC");
        return $data;
      }
      catch(Exception $e){
        throw new Exception("Error al obtener imagenes");
      }
    }
  }
?>