<?php
session_start();
require_once "../class/visita.php";

// Verificar si ya se contó la visita en esta sesión
if (!isset($_SESSION['visita_contada'])) {
    $visita = new Visita();
    $visita->GuardarVisita();

    // Marcar que ya se contó la visita
    $_SESSION['visita_contada'] = true;
}

// Obtener el total de visitas (esto se puede mostrar siempre)
$visita = new Visita();
$totalVisitas = $visita->ObtenerVisitas();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="index.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="../logo.png" />

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>NoticiaPTY - Noticias de Panamá</title>
</head>

<body>
    <!-- Menú horizontal superior -->
    <nav class="top-nav">
        <div class="nav-brand"><a href="index.php">NoticiaPTY</a></div>
        <div class="visitor-count">
            <i class="fas fa-users"></i>
            <span id="visitorCount"><?php echo $totalVisitas . " "; ?>visitas</span>
        </div>
        <div class="username">
            <p id="usernameDisplay" class="username-text"></p>


        </div>


        <div class="user-info" style="display: none;">


            <!-- Botón para supervisores -->
            <button id="supervisorPanelBtn" style="display: none;">
                <i class="fas fa-chart-line"></i> Panel de Noticias</button>

            <!-- Mostrar nombre de usuario y rol -->
            <button id="publicarBtn" style="display: none; ">
                <i class="fas fa-newspaper"></i> Publicar Noticia</button>

            <!-- Botón solo visible si el usuario es admin (se controla por JS) -->
            <button id="adminBtn" style="display: none; ">
                <i class="fas fa-users-cog"></i> Administrar Usuarios</button>
            
            <button id="btn-editar" style="display: none;">
                <i class="fa-solid fa-user"></i> Perfil</button>

            <button id="logoutBtn" onclick="logout()">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </button>
        </div>

        <div class="nav-auth">
            <div class="auth-btn-container">
                <a href="auth/iniciar-sesion/index.html" class="auth-btn login-btn">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
                <a href="auth/registro/index.html" class="auth-btn register-btn">
                    <i class="fas fa-user-plus"></i> Registrarse
                </a>


            </div>

        </div>
    </nav>

    <!-- Menú central de categorías -->
    <nav class="main-nav">
        <ul>
            <li><a href="#" data-category="todas">Todas</a></li>
            <li><a href="#" data-category="1">Deportes</a></li>
            <li><a href="#" data-category="2">Política</a></li>
            <li><a href="#" data-category="3">Tecnología</a></li>
            <li>
                <a href="#" data-category="4">Entretenimiento</a>
            </li>
        </ul>
    </nav>


    <div id="wrapper">
        <!-- Contenedor principal de noticias -->
        <main class="news-container">
            <div id="newsGrid" class="news-grid"></div>
            <button id="loadMore" class="load-more-btn">
                <i class="fas fa-sync"></i> Cargar más noticias
            </button>
        </main>


    </div>


    <?php include "footer.php"; ?>

    <script src="index.js"></script>

</body>

</html>