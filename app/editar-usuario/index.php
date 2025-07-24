<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Usuario en Sesión</title>
  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="../../logo.png" />
  <link rel="stylesheet" href="../css/paneles-comunes.css" />
  <style>
    html,
    body {
      height: 100vh;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      height: 100vh;

    }

    /* Estilo para el contenedor */
    .container {
      width: 400px;
      margin: 4rem auto;
      padding: 30px;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    /* Título de sección */
    .container h2 {
      font-size: 1.8rem;
      margin-bottom: 25px;
      color: #2c3e50;
      text-align: center;
    }

    /* Formulario */
    .form-usuario {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 8px;
      color: #34495e;
      font-size: 1rem;
    }

    .form-group input {
      padding: 12px;
      font-size: 1rem;
      border: 1px solid #ccd1d9;
      border-radius: 8px;
      transition: all 0.25s ease-in-out;
    }

    .form-group input:focus {
      border-color: #3498db;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
      outline: none;
    }

    .form-group input[disabled] {
      background-color: #f2f2f2;
      color: #7f8c8d;
      cursor: not-allowed;
    }

    /* Botones */
    .botones {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 20px;
    }

    .btn {
      font-family: inherit;
      font-size: 1rem;
      padding: 10px 18px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    /* Botón Editar */
    .btn-editar {
      background-color: #3498db;
      color: #fff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-editar:hover {
      background-color: #2980b9;
      transform: scale(1.03);
    }

    .btn-editar:active {
      background-color: #2471a3;
      transform: scale(0.97);
    }

    /* Botón Guardar */
    .btn-guardar {
      background-color: #27ae60;
      color: #fff;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: none;
    }

    .btn-guardar:hover {
      background-color: #219150;
      transform: scale(1.03);
    }

    .btn-guardar:active {
      background-color: #1e8449;
      transform: scale(0.97);
    }
  </style>
</head>

<body>

  <header>
    <h1>Perfil</h1>
  </header>
  <!-- Botón de regreso -->
  <div style="margin-bottom: 20px">
    <button
      class="volver-btn"
      onclick="window.location.href='../'"
      style="background-color: #6c757d">
      ← Volver al Panel de Noticias
    </button>
  </div>
  <div class="container">
    <h2>Editar mis datos</h2>
    <form id="formEditarSesion" class="form-usuario">
      <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" disabled>
      </div>
      <div class="form-group">
        <label>Apellido</label>
        <input type="text" name="apellido" disabled>
      </div>
      <div class="form-group">
        <label>Usuario</label>
        <input type="text" name="usuario" disabled>
      </div>
      <div class="botones">
        <button type="button" id="btnEditar" class="btn btn-editar">Editar</button>
        <button type="submit" id="btnGuardar" class="btn btn-guardar" style="display: none;">Guardar cambios</button>
      </div>
    </form>

  </div>

  <?php include "../footer.php"; ?>



  <script src="editar_usuario.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>