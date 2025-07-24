<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel de Control Usuarios</title>
  <link rel="stylesheet" href="../css/paneles-comunes.css" />
  <link rel="stylesheet" href="../css/panel_usuarios.css" />
  <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="../../logo.png" />
</head>
<header>
  <h1>Administrar Usuarios</h1>
</header>
<body>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Botón de regreso -->
  <div style="margin-bottom: 20px;">
    <button class="volver-btn" onclick="window.location.href='../index.php'">
      ← Volver al Inicio
    </button>
  </div>
  <div class="container">
    <!-- Formulario para agregar usuario -->
  <form id="formAddUser">
    <h2>Agregar Nuevo Usuario</h2>
    <label>Nombre:
      <input type="text" name="nombre" required />
    </label>
    <label>Apellido:
      <input type="text" name="apellido" required />
    </label>
    <label>Usuario:
      <input type="text" name="usuario" required />
    </label>
    <label>Contraseña:
      <input type="password" name="contrasena" required />
    </label>
    <label>Rol:
      <select name="rol" required>
        <option value="">Seleccione</option>
        <option value="editor">Editor</option>
        <option value="global">Global</option>
        <option value="admin">Admin</option>
        <option value="supervisor">Supervisor</option>
      </select>
    </label>
    <button type="submit">Agregar Usuario</button>
  </form>
  <input
    type="text"
    id="buscadorUsuarios"
    placeholder="Buscar usuario por nombre, apellido o nombre de usuario..."
    style="margin: 1rem 0; padding: 0.5rem; width: 100%; max-width: 400px;"
  />
  <div id="filtrosUsuarios" style="margin-bottom: 1rem;">
    <button data-filtro="todos">Todos</button>
    <button data-filtro="activos">Activos</button>
    <button data-filtro="inactivos">Inactivos</button>
    <button data-filtro="supervisor">supervisores</button>
    <button data-filtro="editor">Editores</button>
  </div>


  <!-- Tabla con usuarios -->
  <h2>Lista de Usuarios</h2>
  <table id="usersTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Usuario</th>
        <th>Rol</th>
        <th>Activo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <!-- Aquí se llenarán los usuarios -->
    </tbody>
  </table>
  </div>

  <?php include "../footer.php"; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="panel_usuarios.js"></script>
</body>
</html>
