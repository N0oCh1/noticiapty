<?php
class db {
  private $conexion;

  public function __construct() {
    $this->conectar();
  }

  private function conectar() {
    if ($this->conexion === null) {
      $sql_host = "127.0.0.1";
      $sql_db = "noticiapty";
      $sql_user = "User";
      $sql_pass = "user";

      $url_conexion = "mysql:host=$sql_host;dbname=$sql_db;charset=utf8mb4";
      try {
        $this->conexion = new PDO($url_conexion, $sql_user, $sql_pass);
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
      }
    }
  }

  public function getConexion() {
    $this->conectar();
    return $this->conexion;
  }

  public function disconnect() {
    $this->conexion = null;
  }

  public function insert($tb_name, $cols, $val) {
    $this->conectar();
    $cols = $cols ? "($cols)" : "";
    $sql = "INSERT INTO $tb_name $cols VALUES ($val)";
    try {
      if ($this->conexion->exec($sql)) {
        return true;
      };
    } catch (PDOException $e) {
      echo "Error al insertar: " . $e->getMessage();
      return false;
    }
  }

  public function insertSeguro($tb_name, $data) {
    $this->conectar();
    $columns = implode(", ", array_keys($data));
    $placeholders = ":" . implode(", :", array_keys($data));
    $sql = "INSERT INTO $tb_name ($columns) VALUES ($placeholders)";
    try {
      $stmt = $this->conexion->prepare($sql);
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

  public function select($tb_name, $cols, $astriction = null) {
    $this->conectar();
    $sql = $astriction === null
      ? "SELECT $cols FROM $tb_name"
      : "SELECT $cols FROM $tb_name WHERE $astriction";
    try {
      $stmt = $this->conexion->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Error al seleccionar: " . $e->getMessage();
      return false;
    }
  }

  public function update($tb_name, $string, $astriction) {
    $this->conectar();
    $sql = "UPDATE $tb_name SET $string WHERE $astriction";
    try {
      if ($this->conexion->exec($sql)) {
        return true;
      }
    } catch (PDOException $e) {
      echo "Error al Modificar: " . $e->getMessage();
      return false;
    }
  }

  public function selectRaw($from, $fields = "*", $where = "1") {
    $this->conectar();
    try {
      $sql = "SELECT $fields FROM $from WHERE $where";
      $stmt = $this->conexion->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function delete($table, $condition, $params = []) {
    $this->conectar();
    $sql = "DELETE FROM $table WHERE $condition";
    try {
      $stmt = $this->conexion->prepare($sql);
      foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
      }
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error al eliminar: " . $e->getMessage();
      return false;
    }
  }
}
?>
