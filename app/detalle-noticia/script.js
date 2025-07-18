const usuario = sessionStorage.getItem("usuario");

const usuarioId = sessionStorage.getItem("usuario_id");
console.log("Usuario ID:", usuarioId);
const likeBtn = document.getElementById("likeBtn");
const likeCount = document.getElementById("likeCount");
const noticia = JSON.parse(localStorage.getItem("noticia"));
console.log(noticia);
const noticiaId = noticia ? noticia.id : null;
console.log("Noticia ID:", noticiaId);

// Comentarios
const commentForm = document.getElementById("commentForm");
const commentText = document.getElementById("commentText");
const commentCount = document.getElementById("commentCount");

document.addEventListener("DOMContentLoaded", () => {
    // Verificar sesión desde el servidor
    fetch("../../api/controllerSessionInfo.php", {
        method: "GET",
        credentials: "include",
    })
        .then((res) => res.json())
        .then((data) => {
            console.log("Datos de sesión:", data);
            if (data.success) {
                sessionStorage.setItem("usuario_id", data.usuario_id);
                sessionStorage.setItem("rol", data.rol);

                // Mostrar nombre de usuario
                const usernameDisplay =
                    document.getElementById("usernameDisplay");
                if (usernameDisplay) {
                    usernameDisplay.textContent = `Hola, ${usuario}`;
                }

                document.querySelector(".user-info").style.display = "flex";
                document.querySelector(".nav-auth").style.display = "none";
                document.getElementById("logoutBtn").style.display = "block";

                if (data.rol === "admin") {
                    const adminBtn = document.getElementById("adminBtn");
                    if (adminBtn) {
                        adminBtn.style.display = "inline-block";
                        adminBtn.addEventListener("click", () => {
                            window.location.href =
                                "../../app/administrar-usuario/index.html";
                        });
                    }
                }

                if (data.rol === "publicador") {
                    const publicarBtn = document.getElementById("publicarBtn");
                    if (publicarBtn) {
                        publicarBtn.style.display = "inline-block";
                        publicarBtn.addEventListener("click", () => {
                            window.location.href =
                                "../../app/crear-noticia/index.html";
                        });
                    }
                }
            } else {
                document.querySelector(".user-info").style.display = "none";
                document.querySelector(".nav-auth").style.display = "flex";
            }
        })
        .catch((error) => {
            console.error("Error al verificar sesión:", error);
        });

    cargarComentarios();

    function cargarComentarios() {
        if (!noticiaId) return;

        fetch(`../../api/controllerComentario.php?noticia_id=${noticiaId}`)
            .then((res) => res.json())
            .then((data) => {
                commentCount.textContent = data.length;

                const commentsContainer =
                    document.getElementById("commentsContainer");
                commentsContainer.innerHTML = ""; // Limpiar contenedor

                // Mapeo y organización jerárquica
                const comentariosMap = {};
                data.forEach((c) => {
                    c.children = [];
                    comentariosMap[c.id] = c;
                });

                const comentariosRaiz = [];
                data.forEach((c) => {
                    if (c.comentario_padre_id) {
                        if (comentariosMap[c.comentario_padre_id]) {
                            comentariosMap[c.comentario_padre_id].children.push(
                                c
                            );
                        }
                    } else {
                        comentariosRaiz.push(c);
                    }
                });

                // Renderiza los comentarios raíz recursivamente
                comentariosRaiz.forEach((comentario) => {
                    const div = renderComentario(comentario);
                    commentsContainer.appendChild(div);
                });
            })
            .catch((err) => console.error("Error cargando comentarios:", err));
    }

    // Función recursiva para renderizar un comentario y sus hijos
    function renderComentario(comentario) {
        const div = document.createElement("div");
        div.className = "comentario";

        if (comentario.usuario_id == usuarioId) {
            div.classList.add("comentario-propio");
        }

        const fechaObj = new Date(comentario.fecha_creacion);
        const fecha = fechaObj.toLocaleDateString("es-ES");
        const hora = fechaObj.toLocaleTimeString("es-ES", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
        });

        div.innerHTML = `
        <div class="comentario-header">
            <div>
                <p class="comentario-usuario"><strong>${comentario.usuario}</strong></p>
            </div>
            <div class="comentario-fecha-hora">
                <p class="comentario-fecha">${fecha}</p>
                <p class="comentario-hora">${hora}</p>
            </div>
        </div>
        <p class="comentario-texto">${comentario.contenido}</p>
        <button class="responder-btn" data-id="${comentario.id}">Responder</button>
        <div class="respuestas"></div> <!-- Aquí se insertan hijos -->
    `;

        // Botón de responder
        div.querySelector(".responder-btn").addEventListener("click", (e) => {
            const respuestaForm = crearFormularioRespuesta(comentario.id);
            const contenedorRespuestas = div.querySelector(".respuestas");

            // Evitar múltiples formularios
            contenedorRespuestas.innerHTML = "";
            contenedorRespuestas.appendChild(respuestaForm);
        });

        // Recursividad para hijos
        const contenedorRespuestas = div.querySelector(".respuestas");
        comentario.children.forEach((child) => {
            const childDiv = renderComentario(child);
            contenedorRespuestas.appendChild(childDiv);
        });

        return div;
    }

    // Formulario para responder comentarios
    function crearFormularioRespuesta(comentarioPadreId) {
        const form = document.createElement("form");
        form.className = "form-respuesta";

        const textarea = document.createElement("textarea");
        textarea.placeholder = "Escribe una respuesta...";
        textarea.required = true;

        const btn = document.createElement("button");
        btn.type = "submit";
        btn.textContent = "Enviar respuesta";
        btn.className = "submit-comment";

        form.appendChild(textarea);
        form.appendChild(btn);

        form.addEventListener("submit", function (e) {
            console.log("responder")
            e.preventDefault();
            const texto = textarea.value.trim();
            if (!texto || !usuarioId) return;

            fetch("../../api/controllerComentario.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    noticia_id: noticiaId,
                    usuario_id: usuarioId,
                    contenido: texto,
                    comentario_padre_id: comentarioPadreId,
                }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        cargarComentarios(); // Recargar todos los comentarios
                    }
                })
                .catch((err) => console.error("Error al responder:", err));
        });

        return form;
    }



    commentForm.addEventListener("submit", function (e) {
        e.preventDefault();

        if (!usuarioId) {
            Swal.fire({
                icon: "warning",
                title: "Debes iniciar sesión",
                text: "Inicia sesión para poder comentar.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Iniciar sesión",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../auth/iniciar-sesion/index.html"; // Ajusta la ruta si es necesario
                }
            });
            return;
        }


        const contenido = commentText.value.trim();
        if (contenido.length === 0) {
            alert("Comentario vacío.");
            return;
        }

        fetch("../../api/controllerComentario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                noticia_id: noticiaId,
                usuario_id: usuarioId,
                contenido: contenido,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    commentText.value = "";
                    cargarComentarios();
                } else {
                    alert("Error al enviar comentario.");
                }
            })
            .catch((err) => console.error("Error enviando comentario:", err));
    });

    let yaDioLike = false;

    if (!noticiaId) {
        alert("No se pudo encontrar la noticia.");
        return;
    }

    obtenerLikes(noticiaId);

    if (usuarioId) {
        verificarSiUsuarioDioLike(usuarioId, noticiaId).then((dioLike) => {
            yaDioLike = dioLike;
            actualizarBotonLike();
        });
    }

    likeBtn.addEventListener("click", function () {
        if (!usuarioId) {
            Swal.fire({
                icon: "warning",
                title: "Inicia sesión",
                text: "Debes estar logueado para dar like.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Iniciar sesión",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../auth/iniciar-sesion/index.html"; // Ajusta la ruta si es diferente
                }
            });
            return;
        }


        if (!yaDioLike) {
            darLike(usuarioId, noticiaId);
        } else {
            quitarLike(usuarioId, noticiaId);
        }
    });

    function actualizarBotonLike() {
        if (yaDioLike) {
            likeBtn.innerHTML = `<i class="fas fa-thumbs-up"></i> Ya te gusta <span class="like-count">${likeCount.textContent}</span>`;
            likeBtn.style.backgroundColor = "#6c757d";
            likeBtn.disabled = false;
        } else {
            likeBtn.innerHTML = `<i class="fas fa-thumbs-up"></i> Like <span class="like-count">${likeCount.textContent}</span>`;
            likeBtn.style.backgroundColor = "#28a745";
            likeBtn.disabled = false;
        }
    }

    function verificarSiUsuarioDioLike(usuarioId, noticiaId) {
        return fetch(
            `../../api/controllerLike.php?usuario_id=${usuarioId}&noticia_id=${noticiaId}`
        )
            .then((res) => res.json())
            .then((data) => data.ya_dio_like || false)
            .catch((err) => {
                console.error("Error verificando si ya dio like:", err);
                return false;
            });
    }

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
            .then((res) => res.json())
            .then((data) => {
                if (data.message.includes("Like registrado")) {
                    yaDioLike = true;
                    actualizarBotonLike();
                    obtenerLikes(noticiaId);
                } else {
                    alert(data.message);
                }
            })
            .catch((err) => console.error("Error al dar like:", err));
    }

    function quitarLike(usuarioId, noticiaId) {
        fetch("../../api/controllerLike.php", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                usuario_id: usuarioId,
                noticia_id: noticiaId,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.message.includes("eliminado")) {
                    yaDioLike = false;
                    actualizarBotonLike();
                    obtenerLikes(noticiaId);
                } else {
                    alert(data.message);
                }
            })
            .catch((err) => console.error("Error al quitar like:", err));
    }

    function obtenerLikes(noticiaId) {
        fetch(`../../api/controllerLike.php?noticia_id=${noticiaId}`)
            .then((res) => res.json())
            .then((data) => {
                const totalLikes = data.total_likes || 0;
                likeCount.textContent = totalLikes;

                // Actualizar el texto del botón para mostrar el contador actualizado
                actualizarBotonLike();
            })
            .catch((err) => console.error("Error al obtener likes:", err));
    }
    console.log("noticia:",noticia);

    if (noticia) {
        document.getElementById("titulo").innerText = noticia.titulo;
        document.getElementById("contenido").innerText = noticia.contenido;
        document.getElementById("autor").innerText = noticia.autor;
        document.getElementById(
            "publicador"
        ).innerText = `${noticia.nombre_usuario} ${noticia.apellido_usuario}`;

        const fecha = new Date(noticia.fecha_creacion || noticia.fecha);
        document.getElementById("fecha_creacion").innerText =
            fecha.toLocaleDateString("es-ES");

        if (noticia.imagenes && noticia.imagenes.length > 0) {
            document.getElementById("imagen1").src =
                "../" + noticia.imagenes[1].imagen;
            document.getElementById("imagen2").src =
                "../" +
                (noticia.imagenes[0]?.imagen || "../../imagenDB/default.png");
            document.getElementById("imagen3").src =
                "../" +
                (noticia.imagenes[2]?.imagen || "../../imagenDB/default.png");
        } else {
            document.getElementById("imagen1").src =
                "../../imagenDB/default.png";
            document.getElementById("imagen2").src =
                "../../imagenDB/default.png";
            document.getElementById("imagen3").src =
                "../../imagenDB/default.png";
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
            fetch("../../api/logoutController.php")
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        sessionStorage.clear();
                        document.querySelector(".user-info").style.display =
                            "none";
                        document.querySelector(".nav-auth").style.display =
                            "flex";

                        Swal.fire({
                            icon: "success",
                            title: "Sesión cerrada",
                            text: "Has cerrado sesión correctamente.",
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = "../index.php";
                        });
                    } else {
                        throw new Error(
                            data.message || "No se pudo cerrar sesión."
                        );
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.message || "Error al cerrar sesión.",
                    });
                });
        }
    });
}
