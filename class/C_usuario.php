<?php
require_once "C_conexion.php"; // Asegúrate de que tu clase de conexión esté incluida correctamente

class Usuario {
    private int $id;
    private string $nombre;
    private string $apellido;
    private string $usuario;
    private string $contrasena;
    private string $rol;
    private bool $activo;
    private $db; // Instancia de la clase db para acceso a la base de datos

    // Constructor que recibe el usuario y la contraseña
    public function __construct($usuario, $password) {
        $this->usuario = $usuario;
        $this->contrasena = $password;
        $this->db = new db(); // Instancia de la clase db
    }

    // Método para verificar el inicio de sesión
    public function verificarLogin() {
        // Usamos el método select de db para obtener el usuario
       
        $user = $this->db->select("usuarios", "id, usuario, contrasena", "usuario = '$this->usuario'");

        if ($user) {
            $user = $user[0]; // Accedemos al primer elemento del array
            if (password_verify($this->contrasena, $user['contrasena'])) {
                // Si el usuario existe y la contraseña es correcta
                $this->id = $user['id']; // Guardamos el ID del usuario
                return true;
            }
        }

        // Si no existe el usuario o la contraseña es incorrecta
        return false;
    }

    // Método para obtener el ID del usuario (si se autenticó correctamente)
    public function getId() {
        return $this->id;
    }

    // Método para registrar un nuevo usuario
    public function registrarUsuario($nombre, $apellido, $usuario, $contrasena, $rol = 'global') {
    // 1. Verificar si el usuario ya existe
    $existe = $this->db->select("usuarios", "*", "usuario = '$usuario'");
    if ($existe && count($existe) > 0) {
        return "duplicate"; // Usuario ya existe
    }

    // 2. Encriptar la contraseña
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // 3. Insertar nuevo usuario
    $data = [
        'nombre' => $nombre,
        'apellido' => $apellido,
        'usuario' => $usuario,
        'contrasena' => $hashedPassword,
        'rol' => $rol,
    ];

    return $this->db->insertSeguro('usuarios', $data); // true o false
}
}
?>

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
