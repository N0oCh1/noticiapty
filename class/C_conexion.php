<?php
  class db {
    private $conexion;
  
    public function __construct() {
      $sql_host="127.0.0.1";
      $sql_db = "noticiapty";
      $sql_user="root";
      $sql_pass="demo";

      $url_conexion = "mysql:host=$sql_host;dbname=$sql_db;charset=utf8mb4";
      try{
        $this->conexion = new PDO($url_conexion, $sql_user, $sql_pass);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
      catch(PDOException $e){
        echo "error de conexion: ".$e->getMessage();
      }
    }
    public function getConexion (){
		  return $this->conexion;
	  }
    public function disconnect()
	  {
		  $this->conexion = null; 
	  }

    public function insert($tb_name, $cols, $val)
	  {
    	$cols = $cols ? "($cols)" : "";
   		$sql = "INSERT INTO $tb_name $cols VALUES ($val)";
    	try {
      	  if($this->conexion->exec($sql)){
            return true;
          };
  	  	} catch (PDOException $e) {
          echo "Error al insertar: " . $e->getMessage();
          return false;
    	}
	  }
    public function insertSeguro($tb_name, $data)
    {
      $columns = implode(", ", array_keys($data));
      $placeholders = ":" . implode(", :", array_keys($data));

      $sql = "INSERT INTO $tb_name ($columns) VALUES ($placeholders)";

      try {
          $stmt = $this->conexion->prepare($sql);

          // Asignar valores con bind
          foreach ($data as $key => $value) {
              $stmt->bindValue(":$key", $value);
          }

          $stmt->execute();
          return true;
      } catch (PDOException $e) {
          echo "Error en INSERT: " . $e->getMessage();
          return false;
      }
    }
    public function select($tb_name, $cols, $astriction=null)
	  {
      $sql = $astriction === null ? "SELECT $cols FROM $tb_name" : "SELECT $cols FROM $tb_name where $astriction";
      try{
        $stml = $this->conexion->prepare($sql);
        $stml->execute();
        return $stml->fetchAll(PDO::FETCH_ASSOC);
      }
      catch(PDOException $e){
        echo "Error al seleccionar: " . $e->getMessage();
        return false;
      }
    }
    public function update($tb_name, $string, $astriction)
	  {
      $sql = "UPDATE $tb_name SET $string where $astriction";
        try {
            if($this->conexion->exec($sql)){
              return true;
          }
        } catch (PDOException $e) {
            echo "Error al Modificar: " . $e->getMessage();
            return false;
        }
    }

  public function selectRaw($from, $fields = "*", $where = "1")
  {
    try {
      $sql = "SELECT $fields FROM $from WHERE $where";
      $stmt = $this->conexion->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC); // ← Esto trae los datos correctamente
    } catch (PDOException $e) {
      // Puedes registrar el error si quieres para depurar
      return []; // ← Devuelve un arreglo vacío en caso de error
    }
  }

  public function delete($table, $condition)
  {
    $sql = "DELETE FROM $table WHERE $condition";
    try {
      return $this->conexion->exec($sql); // Devuelve cuántas filas fueron afectadas
    } catch (PDOException $e) {
      echo "Error al eliminar: " . $e->getMessage();
      return false;
    }
  }

  // Obtiene una noticia por su ID
public function ObtenerNoticiaPorId(int $id)
{
    $classImagen = new Imagen();

    try {
        $selectFields = "n.*, c.nombre AS categoria_nombre, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario";
        $fromTables = "noticias n 
                      LEFT JOIN categorias c ON n.categoria_id = c.id
                      LEFT JOIN usuarios u ON n.usuario_id = u.id";
        $where = "n.id = $id";

        $resultado = $this->db->selectRaw($fromTables, $selectFields, $where);

        if (count($resultado) === 1) {
            $noticia = $resultado[0];
            $imagenes = $classImagen->ObtenerImagenes($noticia['id']);
            $noticia['imagenes'] = $imagenes;
            $this->db->disconnect();
            return $noticia;
        } else {
            $this->db->disconnect();
            return null; // No se encontró la noticia
        }
    } catch (Exception $e) {
        error_log("Error al obtener noticia por ID: " . $e->getMessage());
        $this->db->disconnect();
        return null;
    }
  }
}
?>