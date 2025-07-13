document.addEventListener("DOMContentLoaded", () => {
    // Recuperamos los datos de la noticia desde localStorage
    const noticia = JSON.parse(localStorage.getItem("noticia"));

    if (noticia) {
        // Mostramos los detalles de la noticia en el HTML
        document.getElementById("titulo").innerText = noticia.titulo;
        document.getElementById("contenido").innerText = noticia.contenido;
        document.getElementById("autor").innerText = noticia.autor;
         // Formateamos la fecha para que solo muestre el día, mes y año
    const fecha = new Date(noticia.fecha_creacion || noticia.fecha);
    document.getElementById('fecha_creacion').innerText = fecha.toLocaleDateString('es-ES');  // Solo fecha sin hora

        // Mostrar las tres imágenes asociadas
        if (noticia.imagenes && noticia.imagenes.length > 0) {
            document.getElementById("imagen1").src =
                "../" + noticia.imagenes[0].imagen;
            document.getElementById("imagen2").src =
                "../" + noticia.imagenes[1]?.imagen ||
                "../imagenDB/default.png";
            document.getElementById("imagen3").src =
                "../" + noticia.imagenes[2]?.imagen ||
                "../imagenDB/default.png";
        } else {
            document.getElementById("imagen1").src = "../imagenDB/default.png";
            document.getElementById("imagen2").src = "../imagenDB/default.png";
            document.getElementById("imagen3").src = "../imagenDB/default.png";
        }
    } else {
        document.getElementById("titulo").innerText = "Noticia no encontrada.";
    }
});
