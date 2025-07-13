<?php
require_once "C_conexion.php";

class Usuario {
  private db $db;
  private PDO $conexion;

  private int $id;
  private string $nombre;
  private string $apellido;
  private string $usuario;
  private string $contrasena;
  private string $rol;
  private bool $activo;

  public function __construct() {
    $this->db = new db();
    $this->conexion = $this->db->getConexion();
  }

  // MÉTODO: Insertar nuevo usuario
  public function insertarUsuario(string $nombre, string $apellido, string $usuario, string $contrasena, string $rol): bool {
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->usuario = $usuario;
    $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $this->rol = $rol;
    $this->activo = true;

    $datos = [
      "nombre" => $this->nombre,
      "apellido" => $this->apellido,
      "usuario" => $this->usuario,
      "contrasena" => $this->contrasena,
      "rol" => $this->rol,
      "activo" => $this->activo
    ];

    try {
      $this->db->insertSeguro("usuarios", $datos);
      $this->id = $this->conexion->lastInsertId();
      $this->db->disconnect();
      return true;
    } catch (Exception $e) {
      $this->db->disconnect();
      return false;
    }
  }

  // MÉTODO: Obtener todos los usuarios
  public function obtenerUsuarios(): array|false {
    try {
      $usuarios = $this->db->select("usuarios", "*");
      $this->db->disconnect();
      return $usuarios;
    } catch (Exception $e) {
      $this->db->disconnect();
      return false;
    }
  }

  // MÉTODO: Obtener un usuario por ID
  public function obtenerUsuarioPorId(int $id): array|false {
    try {
      $resultado = $this->db->select("usuarios", "*", "id = $id");
      $this->db->disconnect();
      return $resultado ? $resultado[0] : false;
    } catch (Exception $e) {
      $this->db->disconnect();
      return false;
    }
  }

  // MÉTODO: Actualizar un usuario por ID
  public function actualizarUsuario(int $id, array $nuevosDatos): bool {
    try {
      $campos = "";
      foreach ($nuevosDatos as $clave => $valor) {
        if ($clave == "contrasena") {
          $valor = password_hash($valor, PASSWORD_DEFAULT);
        }
        $campos .= "$clave = " . $this->conexion->quote($valor) . ", ";
      }
      $campos = rtrim($campos, ", ");

      $resultado = $this->db->update("usuarios", $campos, "id = $id");
      $this->db->disconnect();
      return $resultado;
    } catch (Exception $e) {
      $this->db->disconnect();
      return false;
    }
  }

  // MÉTODO: Desactivar usuario (soft delete)
  public function desactivarUsuario(int $id): bool {
    try {
      $resultado = $this->db->update("usuarios", "activo = 0", "id = $id");
      $this->db->disconnect();
      return $resultado;
    } catch (Exception $e) {
      $this->db->disconnect();
      return false;
    }
  }

  // MÉTODO opcional: convertir datos en array
  public function toArray(): array {
    return [
      'id' => $this->id,
      'nombre' => $this->nombre,
      'apellido' => $this->apellido,
      'usuario' => $this->usuario,
      'contrasena' => $this->contrasena,
      'rol' => $this->rol,
      'activo' => $this->activo,
    ];
  }
}
