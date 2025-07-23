document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formNoticia");
  const inputImagen = document.getElementById("imagen");

  // Contenedor para previsualización de imágenes
  const previewContainer = document.createElement("div");
  previewContainer.id = "previewContainer";
  previewContainer.style.display = "flex";
  previewContainer.style.gap = "10px";
  previewContainer.style.marginTop = "10px";
  inputImagen.parentNode.insertBefore(previewContainer, inputImagen.nextSibling);

  // Verificar sesión y rol para autorización
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include",
  })
    .then((res) => res.json())
    .then((data) => {
      if (!data.success || !["supervisor", "admin", "editor"].includes(data.rol)) {
        Swal.fire({
          icon: "error",
          title: "No autorizado",
          text: "No tienes permiso para editar noticias.",
        }).then(() => {
          window.location.href = "panel_noticias.php";
        });
        return;
      }

      // Poner usuario_id en input oculto para envío
      document.getElementById("usuario_id").value = data.usuario_id;

      // Obtener ID de noticia desde la URL
      const urlParams = new URLSearchParams(window.location.search);
      const noticiaId = urlParams.get("id");

      if (!noticiaId) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "ID de noticia no proporcionado.",
        }).then(() => {
          window.location.href = "../administrar-noticia/";
        });
        return;
      }

      // Cargar datos actuales de la noticia
      cargarNoticia(noticiaId);

      // Agregar eventos al formulario y input imagen
      agregarEventos();
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo verificar sesión.",
      });
    });

  /**
   * Carga los datos de la noticia por ID y rellena el formulario
   * @param {string} id - ID de la noticia
   */
  function cargarNoticia(id) {
    fetch(`../../api/controllerNoticia.php?id=${id}`, {
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => {
        if (!data.success || !data.noticia) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: data.message || "No se encontró la noticia.",
          }).then(() => {
            window.location.href = "panel_noticias.php";
          });
          return;
        }

        const noticia = data.noticia;

        // Setear ID en input oculto
        document.getElementById("noticia_id").value = noticia.id;

        // Rellenar campos del formulario con datos actuales
        document.getElementById("titulo").value = noticia.titulo;
        document.getElementById("contenido").value = noticia.contenido;
        document.getElementById("categoria").value = noticia.categoria_id;
        document.getElementById("autor").value = noticia.autor;

        // Mostrar previsualización de imágenes actuales (excluyendo thumbnails)
        previewContainer.innerHTML = "";
        if (noticia.imagenes && noticia.imagenes.length > 0) {
          const imagenesPrincipales = noticia.imagenes.filter(
            (imgObj) => !imgObj.imagen.includes("thumb_")
          );

          imagenesPrincipales.forEach((imgObj) => {
            const img = document.createElement("img");
            const rutaAjustada = imgObj.imagen.replace("../imagenDB", "../../imagenDB");
            img.src = rutaAjustada;
            img.style.width = "100px";
            img.style.height = "100px";
            img.style.objectFit = "cover";
            img.style.border = "1px solid #ccc";
            img.style.borderRadius = "4px";
            previewContainer.appendChild(img);
          });
        }
      })
      .catch(() => {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Error cargando datos de la noticia.",
        });
      });
  }

  /**
   * Agrega eventos para previsualización y manejo del submit del formulario
   */
  function agregarEventos() {
    // Previsualizar nuevas imágenes al seleccionar archivos
    inputImagen.addEventListener("change", () => {
      previewContainer.innerHTML = ""; // Limpiar previsualización previa
      const files = inputImagen.files;

      Array.from(files).forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const img = document.createElement("img");
          img.src = e.target.result;
          img.style.width = "100px";
          img.style.height = "100px";
          img.style.objectFit = "cover";
          img.style.border = "1px solid #ccc";
          img.style.borderRadius = "4px";
          previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });

    // Manejar envío del formulario para actualizar noticia
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const nuevasImagenes = inputImagen.files;

      // Validar que si hay imágenes, sean exactamente 3
      if (nuevasImagenes.length > 0 && nuevasImagenes.length !== 3) {
        Swal.fire({
          icon: "warning",
          title: "Cantidad de Imágenes",
          text: "Debes seleccionar exactamente 3 imágenes o dejar el campo vacío para mantener las actuales.",
        });
        return;
      }

      // Confirmar actualización antes de enviar
      Swal.fire({
        title: "¿Estás seguro?",
        text: "Se actualizarán los datos de la noticia.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, actualizar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData(form);

          // Si no hay nuevas imágenes, eliminar el campo 'imagen' para no enviar archivos
          if (nuevasImagenes.length === 0) {
            formData.delete("imagen");
          }

          // Simular método PUT vía _method
          formData.append("_method", "PUT");

          fetch("../../api/controllerNoticia.php", {
            method: "POST",
            credentials: "include",
            body: formData,
          })
            .then((res) => res.json())
            .then((data) => {
              Swal.fire({
                icon: data.success ? "success" : "error",
                title: data.success ? "Noticia Actualizada" : "Error",
                text: data.message || (data.success ? "Actualización exitosa." : "Error al actualizar."),
              }).then(() => {
                if (data.success) {
                  window.location.href = "../administrar-noticia/";
                }
              });
            })
            .catch(() => {
              Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo actualizar la noticia.",
              });
            });
        }
      });
    });
  }
});
