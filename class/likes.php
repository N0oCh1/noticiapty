<?php
require_once "C_conexion.php"; // Asegúrate de que tu archivo de conexión esté incluido correctamente

class Likes {
    private $db;

    // Constructor para inicializar la conexión con la base de datos
    public function __construct() {
        $this->db = new db(); // Instanciamos la clase de la conexión a la base de datos
    }

    // Función para dar like a una noticia
    public function darLike($usuario_id, $noticia_id) {
        // Verificar si el usuario ya ha dado like a esta noticia
        $checkLike = $this->db->select("likes", "*", "usuario_id = $usuario_id AND noticia_id = $noticia_id");
        
        if (empty($checkLike)) {
            // Si no ha dado like, insertamos el nuevo like
            $data = [
                'usuario_id' => $usuario_id,
                'noticia_id' => $noticia_id
            ];
            $this->db->insertSeguro('likes', $data); // Insertamos el like en la tabla `likes`
            
           
            return true; // Se ha dado like correctamente
        } else {
            // El usuario ya dio like, no hacer nada o devolver un error si lo deseas
            return false; // Ya existe un like para este usuario en esta noticia
        }
    }

    // ✅ Nueva función para quitar like a una noticia
    public function quitarLike($usuario_id, $noticia_id)
    {
        $condicion = "usuario_id = $usuario_id AND noticia_id = $noticia_id";
        return $this->db->delete("likes", $condicion);
    }

    // Función para obtener la cantidad total de likes de una noticia
    public function obtenerLikes($noticia_id) {
        // Seleccionar la cantidad total de likes de la noticia
        $likes = $this->db->select("likes", "COUNT(*) as total_likes", "noticia_id = $noticia_id");
        return $likes[0]['total_likes']; // Devolver el total de likes
    }

    // Función para verificar si un usuario ya dio like a una noticia
    public function verificarLike($usuario_id, $noticia_id)
    {
        // Usamos el método select personalizado para consultar si existe el like
        $resultado = $this->db->select("likes", "COUNT(*) as total", "usuario_id = $usuario_id AND noticia_id = $noticia_id");

        return $resultado[0]['total'] > 0; // Retorna true si ya dio like, false si no
    }
}
?>
