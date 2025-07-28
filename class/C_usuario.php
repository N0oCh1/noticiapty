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

  // ===========================
  // MÉTODO: Verificar Login
  // ===========================
  public function verificarLogin(string $usuario, string $password): array|false {
    $resultado = $this->db->select("usuarios", "id, nombre, apellido, usuario, contrasena, rol, activo", "usuario = " . $this->conexion->quote($usuario));

    if ($resultado && count($resultado) > 0) {
        $usuarioData = $resultado[0];

        if (password_verify($password, $usuarioData['contrasena'])) {
            // Asignar valores internos por si se usan luego
            $this->id = $usuarioData['id'];
            $this->nombre = $usuarioData['nombre'];
            $this->apellido = $usuarioData['apellido'];
            $this->usuario = $usuarioData['usuario'];
            $this->rol = $usuarioData['rol'];
            $this->activo = $usuarioData['activo'];

            // Devolver info útil al frontend o controlador
            return [
                "id" => $usuarioData['id'],
                "nombre" => $usuarioData['nombre'],
                "apellido" => $usuarioData['apellido'],
                "usuario" => $usuarioData['usuario'],
                "rol" => $usuarioData['rol'],
                "activo"=> $usuarioData['activo']
            ];
        }
    }
    return false;
  }


  // ===========================
  // MÉTODO: Insertar nuevo usuario
  // ===========================
  public function insertarUsuario(string $nombre, string $apellido, string $usuario, string $contrasena, string $rol): bool|string {
    // Validar si ya existe
    $existe = $this->db->select("usuarios", "*", "usuario = " . $this->conexion->quote($usuario));
    if ($existe && count($existe) > 0) {
      return "duplicate";
    }

    // Asignar valores y encriptar contraseña
    $this->nombre = $nombre;
    $this->apellido = $apellido;
    $this->usuario = $usuario;
    $this->contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
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

  // ===========================
  // MÉTODO: Obtener todos los usuarios
  // ===========================
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

  // ===========================
  // MÉTODO: Obtener usuario por ID
  // ===========================
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

  // ===========================
  // MÉTODO: Actualizar usuario por ID
  // ===========================
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

  // ===========================
  // MÉTODO: Desactivar usuario
  // ===========================
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

  // ===========================
  // GETTERS opcionales
  // ===========================
  public function getId(): int {
    return $this->id;
  }

  public function getRol(): string {
    return $this->rol;
  }

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
  // ===========================
  // Obtener rol por ID
  // ===========================
  public function obtenerRolPorId(int $id): ?string {
      try {
          $resultado = $this->db->select("usuarios", "rol", "id = $id");
          $this->db->disconnect();
          if ($resultado && count($resultado) > 0) {
              return $resultado[0]['rol'];
          }
          return null;
      } catch (Exception $e) {
          $this->db->disconnect();
          return null;
      }
  }
}
?>