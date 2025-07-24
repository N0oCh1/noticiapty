<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Panel de Control Noticias</title>
  <link rel="stylesheet" href="../css/paneles-comunes.css" />
  <link rel="stylesheet" href="../css/panel_aprobacion.css" />
  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="../../logo.png" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link
    </head>

<body>
  <header>
    <h1>Administrar Noticias</h1>
  </header>

  <!-- Botón de regreso -->
  <div style="margin-bottom: 20px;">
    <button class="volver-btn" onclick="window.location.href='../index.php'">
      ← Volver al Inicio
    </button>
  </div>

  <div class="container">
    <input
      type="text"
      id="buscadorNoticias"
      placeholder="Buscar noticias..."
      style="margin-bottom: 20px; padding: 8px; width: 300px;" />
    <div class="filtros-noticias">
      <button id="btnMostrarTodas" class="btn-filtro">
        <i class="fas fa-list"></i>Todas
      </button>
      <button id="btnFiltrarActivas" class="btn-filtro">
        <i class="fas fa-check-circle"></i>Activas
      </button>
      <button id="btnFiltrarInactivas" class="btn-filtro">
        <i class="fas fa-times-circle"></i>Inactivas
      </button>
      <button id="btnFiltrarEspera" class="btn-filtro">
        <i class="fas fa-clock"></i>En Espera
      </button>
    </div>


    <!-- Tabla con noticias -->
    <h2>Lista de Noticias</h2>
    <table id="noticiasTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Contenido</th>
          <th>Categoría</th>
          <th>Autor</th>
          <th>Imagen</th>
          <th>Fecha de Creación</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- Se cargan filas aquí -->
      </tbody>
    </table>
    <!-- Modal para imagen -->
    <div id="modalImagen" class="modal-imagen" style="display: none;">
      <span id="cerrarModal" class="cerrar-modal">&times;</span>
      <img class="contenido-modal" id="imagenGrande" src="" alt="Imagen ampliada">
    </div>

  </div>



  <?php include "../footer.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="panel_noticias.js"></script>
</body>

</html>