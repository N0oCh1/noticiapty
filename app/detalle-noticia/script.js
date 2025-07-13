document.addEventListener("DOMContentLoaded", () => {
    // Verificar si hay un usuario en sessionStorage
    const usuario = sessionStorage.getItem("usuario");
    // const usuarioId = sessionStorage.getItem("usuario_id");

    if (usuario) {
        // Si hay un usuario, mostrar el botón de logout
        document.querySelector(".user-info").style.display = "flex"; // Mostrar el área del usuario
        document.querySelector(".nav-auth").style.display = "none"; // Ocultar los botones de autenticación
        document.getElementById("logoutBtn").style.display = "block"; // Mostrar el botón de logout
    } else {
        // Si no hay un usuario, mostrar los botones de autenticación
        document.querySelector(".user-info").style.display = "none"; // Ocultar el área del usuario
        document.querySelector(".nav-auth").style.display = "flex"; // Mostrar los botones de login y registro
    }

    

    const likeBtn = document.getElementById("likeBtn");
    const likeCount = document.getElementById("likeCount");
    // Recuperamos los datos de la noticia desde localStorage
    const noticia = JSON.parse(localStorage.getItem("noticia"));
    console.log("Noticia recuperada:", noticia);

    const noticiaId = noticia ? noticia.id : null; // Obtenemos el ID de la noticia

    const usuarioId = getUsuarioIdFromSession(); // Función para obtener el ID del usuario desde la sesión

    if (!noticiaId) {
        alert("No se pudo encontrar la noticia.");
        return;
    }

    // Obtener los likes actuales al cargar la página
    obtenerLikes(noticiaId);

    // Función para manejar el click en el botón de like
    likeBtn.addEventListener("click", function () {
        if (!usuarioId) {
            alert("Debes estar logueado para dar like.");
            return;
        }

        // Enviar solicitud para dar like
        darLike(usuarioId, noticiaId);
    });

    // Función para dar like a la noticia
    function darLike(usuarioId, noticiaId) {
        fetch("../../api/controllerLike.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                usuario_id: usuarioId,
                noticia_id: noticiaId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.message === "Like registrado correctamente") {
                    // Actualizamos el contador de likes
                    obtenerLikes(noticiaId);
                } else {
                    alert(data.message); // En caso de que el like ya haya sido dado
                }
            })
            .catch((error) => console.error("Error al dar like:", error));
    }

    // Función para obtener los likes de la noticia
    function obtenerLikes(noticiaId) {
        fetch(`../../api/controllerLike.php?noticia_id=${noticiaId}`)
            .then((response) => response.json())
            .then((data) => {
                likeCount.textContent = data.total_likes; // Actualizamos el contador de likes
            })
            .catch((error) => console.error("Error al obtener likes:", error));
    }

    // Función para obtener el ID del usuario desde la sesión (simulado)
    function getUsuarioIdFromSession() {
        fetch("getUsuarioId.php")
            .then((response) => response.json())
            .then((data) => {
                if (data.usuario_id) {
                    const usuarioId = data.usuario_id;
                    console.log("ID de usuario desde la sesión:", usuarioId);
                } else {
                    console.log("El usuario no está logueado.");
                }
            })
            .catch((error) =>
                console.error("Error al obtener usuario_id:", error)
            );
    }

    if (noticia) {
        // Mostramos los detalles de la noticia en el HTML
        document.getElementById("titulo").innerText = noticia.titulo;
        document.getElementById("contenido").innerText = noticia.contenido;
        document.getElementById("autor").innerText = noticia.autor;
        // Formateamos la fecha para que solo muestre el día, mes y año
        const fecha = new Date(noticia.fecha_creacion || noticia.fecha);
        document.getElementById("fecha_creacion").innerText =
            fecha.toLocaleDateString("es-ES"); // Solo fecha sin hora

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

// Función de logout con SweetAlert
function logout() {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Deseas cerrar sesión?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cerrar sesión",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar datos de sesión
            sessionStorage.removeItem("usuario");
            // sessionStorage.removeItem("usuario_id"); // Si lo usas

            // Actualizar la interfaz
            document.querySelector(".user-info").style.display = "none";
            document.querySelector(".nav-auth").style.display = "flex";

            // Mostrar mensaje de éxito
            Swal.fire({
                icon: "success",
                title: "Sesión cerrada",
                text: "Has cerrado sesión correctamente.",
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Redirigir después de cerrar la alerta
                window.location.href = "../index.php";
            });
        }
    });
}
