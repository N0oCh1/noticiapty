<?php
require_once "C_conexion.php";

class Visita {
    private $db;

    public function __construct() {
        $this->db = new db(); // Conexión a la base de datos
    }

    // Guardar un nuevo visitante
    public function GuardarVisita() {
        try {
            // Aumentar el contador de visitas
            $this->db->update("visitas", "cantidad = cantidad + 1", "id = 1");
            return true;
        } catch (Exception $e) {
            throw new Exception("Error al guardar visita");
        }
    }

    // Obtener la cantidad total de visitas
    public function ObtenerVisitas() {
        try {
            $data = $this->db->select("visitas", "cantidad", "id = 1");
            return $data[0]['cantidad'];
        } catch (Exception $e) {
            throw new Exception("Error al obtener cantidad de visitas");
        }
    }
}
?>
