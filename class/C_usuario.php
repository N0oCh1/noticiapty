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
