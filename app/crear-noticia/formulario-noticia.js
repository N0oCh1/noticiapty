document.addEventListener("DOMContentLoaded", () => {
  // Verifica si el usuario está logueado y es publicador
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include"
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success || (data.rol !== "supervisor" && data.rol !== "admin" && data.rol !== "editor")) {
        Swal.fire({
          icon: 'error',
          title: 'Acceso denegado',
          text: 'Solo los supervisores, administradores y editores pueden crear noticias.',
        }).then(() => {
          window.location.href = "../index.php";
        });
      } else {
        document.getElementById("usuario_id").value = data.usuario_id;
      }
    });

  // Variables para form y input de imágenes
  const form = document.getElementById("formNoticia");
  const inputImagen = document.getElementById("imagen");

  // Crear contenedor para previsualizar imágenes
  const previewContainer = document.createElement("div");
  previewContainer.id = "previewContainer";
  previewContainer.style.display = "flex";
  previewContainer.style.gap = "10px";
  previewContainer.style.marginTop = "10px";
  inputImagen.parentNode.insertBefore(previewContainer, inputImagen.nextSibling);

  // Mostrar previsualización cuando se seleccionan imágenes
  inputImagen.addEventListener("change", () => {
    previewContainer.innerHTML = ""; // limpiar previsualización
    const files = inputImagen.files;

    Array.from(files).forEach(file => {
      const reader = new FileReader();
      reader.onload = e => {
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

  // Validar mínimo 3 imágenes y enviar formulario
  form.addEventListener("submit", function (e) {
    if (inputImagen.files.length < 3 || inputImagen.files.length > 3) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Cantidad de Imágenes',
        text: 'Por favor, selecciona exactamente 3 imágenes.',
      });
      return;
    }
    if (inputImagen.files.length > 3) {
        e.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Demasiadas imágenes",
            text: "Solo se permiten 3 imágenes como máximo.",
        });
        return;
    }

    e.preventDefault();

    const formData = new FormData(form);

    fetch("../../api/controllerNoticia.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        Swal.fire({
          icon: data.message.includes("correctamente") ? 'success' : 'info',
          title: 'Noticia en estado de espera',
          text: data.message,
        }).then(() => {
          if (data.message.includes("correctamente")) {
            window.location.href = "../index.php";
          }
        });
      })
      .catch((error) => {
        console.error("Error al guardar noticia:", error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Ocurrió un error al guardar la noticia.',
        });
      });
  });
});
