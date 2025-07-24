<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/footer.css" />

    <link rel="icon" type="image/png" sizes="16x16" href="../../logo.png" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Detalle de Noticia</title>
</head>

<body>
    <!-- Menú horizontal superior -->
    <nav class="top-nav">
        <div class="nav-brand">
            <a href="../../index.php">NoticiaPTY</a>
        </div>
        <div class="username">
            <p id="usernameDisplay" class="username-text"></p>
        </div>

        <div class="user-info" style="display: none">
            <!-- Botón para supervisores -->
            <button id="supervisorPanelBtn" style="display: none">
                <i class="fas fa-chart-line"></i> Panel de Noticias
            </button>
            <!-- Mostrar nombre de usuario y rol -->
            <button id="publicarBtn" style="display: none">
                <i class="fas fa-newspaper"></i> Publicar Noticia
            </button>

            <!-- Botón solo visible si el usuario es admin (se controla por JS) -->
            <button id="adminBtn" style="display: none">
                <i class="fas fa-users-cog"></i> Administrar Usuarios
            </button>

            <button id="btn-editar" style="display: none;">
                <i class="fa-solid fa-user"></i> Perfil</button>
                
            <button id="logoutBtn" onclick="logout()">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </button>
        </div>

        <div class="nav-auth">
            <a
                href="../auth/iniciar-sesion/index.html"
                class="auth-btn login-btn">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </a>
            <a
                href="../auth/registro/index.html"
                class="auth-btn register-btn">
                <i class="fas fa-user-plus"></i> Registrarse
            </a>
        </div>
    </nav>

    <!-- Contenedor de la noticia -->
    <div class="noticia-detalle">
        <h1 id="titulo"></h1>
        <div class="meta-info">
            <p><strong>Autor:</strong> <span id="autor"></span></p>
            <p>
                <strong>Publicado por:</strong>
                <span id="publicador"></span>
            </p>
            <p><strong>Fecha:</strong> <span id="fecha_creacion"></span></p>
        </div>

        <div class="imagen1-container">
            <img id="imagen1" class="noticia-imagen" alt="Imagen 1" />
        </div>
        <p id="contenido"></p>

        <div class="imagenes">
            <img id="imagen2" class="noticia-imagen" alt="Imagen 2" />
            <img id="imagen3" class="noticia-imagen" alt="Imagen 3" />
        </div>

        <!-- Interacciones del post -->
        <div class="post-interactions">
            <button id="likeBtn" class="like-btn">
                <i class="fas fa-thumbs-up"></i> Like (<span id="likeCount">0</span>)
            </button>
            <div class="comment-count">
                <p>
                    <strong><span id="commentCount">0</span>
                        Comentarios</strong>
                </p>
            </div>
        </div>

        <!-- Sección de comentarios -->

        <div class="comment-section">
            <h2>Comentarios</h2>

            <div id="commentsContainer" class="comments-list">
                <!-- Comentarios aparecerán aquí -->
            </div>
            <form id="commentForm">
                <textarea
                    id="commentText"
                    placeholder="Escribe tu comentario..."
                    required></textarea>
                <button class="submit-comment" type="submit">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
</body>
<?php include "../footer.php"; ?>

</html>