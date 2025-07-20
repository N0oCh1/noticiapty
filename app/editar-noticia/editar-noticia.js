document.addEventListener("DOMContentLoaded", () => {
  // Obtener el ID de la noticia desde la URL
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

  // Referencias a elementos
  const form = document.getElementById("formNoticia");
  const inputImagen = document.getElementById("imagen");
  const previewContainer = document.createElement("div");
  previewContainer.id = "previewContainer";
  previewContainer.style.display = "flex";
  previewContainer.style.gap = "10px";
  previewContainer.style.marginTop = "10px";
  inputImagen.parentNode.insertBefore(previewContainer, inputImagen.nextSibling);

  // Verificar sesión y rol
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include",
  })
    .then((res) => res.json())
    .then((data) => {
      if (
        !data.success ||
        !["supervisor", "admin", "editor"].includes(data.rol)
      ) {
        Swal.fire({
          icon: "error",
          title: "Acceso denegado",
          text: "No tienes permiso para editar noticias.",
        }).then(() => {
          window.location.href = "panel_noticias.php";
        });
        return;
      }

      // Poner usuario_id en el input oculto
      document.getElementById("usuario_id").value = data.usuario_id;

      // Cargar datos actuales de la noticia
      cargarNoticia(noticiaId);
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo verificar sesión.",
      });
    });

  // Función para cargar noticia por id y rellenar formulario
  function cargarNoticia(id) {
    fetch(`../../api/controllerNoticia.php?id=${id}`, {
      credentials: "include",
    })
      .then((res) => res.json())
      .then((data) => {
        console.log(data);
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

        // Setear id en el hidden input
        document.getElementById("noticia_id").value = noticia.id;

        // Rellenar campos
        document.getElementById("titulo").value = noticia.titulo;
        document.getElementById("contenido").value = noticia.contenido;
        document.getElementById("categoria").value = noticia.categoria_id; // ✅ cambio aquí
        document.getElementById("autor").value = noticia.autor;

        // Mostrar previsualización de imágenes actuales (filtrar thumbnails)
        previewContainer.innerHTML = "";
        if (noticia.imagenes && noticia.imagenes.length > 0) {
          const imagenesPrincipales = noticia.imagenes.filter(
            (imgObj) => !imgObj.imagen.includes("thumb_")
          );

          imagenesPrincipales.forEach((imgObj) => {
            const img = document.createElement("img");

            // Ajustar ruta: reemplazar "../imagenDB" por "../../imagenDB"
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

  // Previsualizar imágenes nuevas al cambiar input
  inputImagen.addEventListener("change", () => {
    previewContainer.innerHTML = ""; // limpiar preview previa
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

  // Manejo del submit para actualizar noticia con confirmación
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const nuevasImagenes = inputImagen.files;

    // Validar cantidad de imágenes si se seleccionan
    if (nuevasImagenes.length > 0 && nuevasImagenes.length !== 3) {
      Swal.fire({
        icon: "warning",
        title: "Cantidad de Imágenes",
        text: "Debes seleccionar exactamente 3 imágenes o dejar el campo vacío para mantener las actuales.",
      });
      return;
    }

    // Confirmación antes de enviar
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

        // Si no seleccionaron nuevas imágenes, no enviar campo "imagen"
        if (nuevasImagenes.length === 0) {
          formData.delete("imagen");
        }

        // Agregar _method=PUT para simular método PUT
        formData.append("_method", "PUT");

        fetch("../../api/controllerNoticia.php", {
          method: "POST", // Cambiamos a POST para que PHP lo reconozca bien
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
              console.log(data);
              if (data.success) {
                window.location.href = "panel_noticias.php";
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
});
