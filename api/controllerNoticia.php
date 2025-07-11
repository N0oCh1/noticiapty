<?php
require "../class/C_noticia.php";


 if($_SERVER["REQUEST_METHOD"]==="POST"){
  header("Content-Type: application/json; charset=utf-8");

  $noticia = new Noticia();

  $titulo = $_POST["titulo"];
  $contenido = $_POST["contenido"];
  $autor = $_POST["autor"];
  $categoria = $_POST["categoria"];
  $activo = $_POST["activo"];
  $imagen = $_FILES["imagen"];


  if($noticia->GuardarNoticia($titulo, $contenido, $autor, $categoria, $activo, $imagen)){
    http_response_code(201);
    echo json_encode([
      "messange" => "Noticia guardada correctamente"
    ]);
  }else{
    http_response_code(500);
    echo json_encode([
      "messange" => "Error al guardar noticia"
    ]);
  }
 }
 if($_SERVER["REQUEST_METHOD"]==="GET"){
  header("Content-Type: application/json; charset=utf-8");
  try{
    $noticia = new Noticia();
    $noticias = $noticia->ObtenerNoticias();
    http_response_code(202);
    echo json_encode($noticias);
  }
  catch(Exception $e){
    http_response_code(500);
    echo json_encode([
      "messange" => "Error al obtener noticias"
    ]);
  }
 }
?>