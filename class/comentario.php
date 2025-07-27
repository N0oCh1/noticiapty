<?php
require_once "C_conexion.php";

class Comentario
{
    private $db;

    public function __construct()
    {
        $this->db = new db(); // Usamos tu clase personalizada
    }

    // Insertar nuevo comentario de forma segura
    public function insertarComentario($noticia_id, $usuario_id, $contenido, $comentario_padre_id = null)
    {
        $data = [
            "noticia_id" => $noticia_id,
            "usuario_id" => $usuario_id,
            "contenido" => $contenido,
            "comentario_padre_id" => $comentario_padre_id
        ];
        return $this->db->insertSeguro("comentarios", $data);
    }


    // Obtener todos los comentarios de una noticia, incluyendo el nombre del usuario
    public function obtenerComentarios($noticia_id)
    {
         $sql = "SELECT c.id, c.contenido, c.fecha_creacion, c.usuario_id, c.comentario_padre_id, u.usuario AS usuario
        FROM comentarios c
        JOIN usuarios u ON c.usuario_id = u.id
        WHERE c.noticia_id = :noticia_id
        ORDER BY c.fecha_creacion ASC";


        try {
            $stmt = $this->db->getConexion()->prepare($sql);
            $stmt->bindParam(":noticia_id", $noticia_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    // Eliminar un comentario por ID
    public function eliminarComentario($id)
    {   
        try {
            $resultado = $this->db->delete("comentarios", "id = :id", [":id" => $id]);
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }
}
