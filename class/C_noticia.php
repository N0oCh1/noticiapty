<?php
require_once "C_conexion.php";
  class Noticia {
    private $db;

    private int $id;
    private string $titulo;
    private string $contenido;
    private string $autor;
    private bool $activo;
    private string $categoria;
    private string $imagen;
    
    public function __construct($titulo, $contenido, $autor, $categoria, $imagen, $activo)
    {
      $this->db = new db();
      $this->titulo = $titulo;
      $this->contenido = $contenido;
      $this->autor = $autor;
      $this->categoria = $categoria;
      $this->imagen = $imagen;
      $this->activo = $activo;
    }

    public function GuardarNoticia() {
      
    }
  }
?>