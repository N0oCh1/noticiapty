<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <title>Publicar Noticia</title>
        <link rel="stylesheet" href="../css/paneles-comunes.css" />
        <link rel="stylesheet" href="../css/formulario_noticias.css" />
        <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="../../logo.png" />
    </head>
    <header>
        <h1>Publicar Noticia</h1>
    </header>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Botón de regreso -->
        <div style="margin-bottom: 20px">
            <button
                class="volver-btn"
                onclick="window.location.href='../index.php'"
                style="background-color: #6c757d"
            >
                ← Volver al Inicio
            </button>
        </div>

        <form id="formNoticia" enctype="multipart/form-data">
            <input type="hidden" id="usuario_id" name="usuario" />

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required />

            <label for="contenido">Contenido:</label>
            <textarea
                name="contenido"
                id="contenido"
                rows="10"
                required
            ></textarea>

            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria" required>
                <option value="">Selecciona una categoría</option>
                <option value="1">Deportes</option>
                <option value="2">Política</option>
                <option value="3">Tecnología</option>
                <option value="4">Entretenimiento</option>
            </select>

            <label for="imagen">Imágenes (exactamente 3):</label>
            <input
                type="file"
                name="imagen[]"
                id="imagen"
                accept="image/*"
                multiple
                required
            />

            <label for="autor">Autor (nombre apellido):</label>
            <input
                type="text"
                name="autor"
                id="autor"
                required
                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+(?:\s[A-Za-zÁÉÍÓÚáéíóúÑñ]+)+$"
                title="Debe ingresar nombre y apellido separados por un espacio."
            />

            <button type="submit">Guardar Noticia</button>
        </form>

        <?php include "../footer.php"; ?>
        <script src="formulario-noticia.js"></script>
    </body>
</html>
