document.addEventListener("DOMContentLoaded", () => {
  verificarSesion();
});

let rolUsuario = "";
function verificarSesion() {
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include"
  })
    .then(res => res.json())
    .then(data => {
      if (data.success && ["supervisor", "admin", "editor"].includes(data.rol)) {
        rolUsuario = data.rol;
        cargarNoticias(); // Llama a la función una vez identificado el rol
      } else {
        redirigir("Solo los supervisores y administradores pueden acceder.");
      }
    })
    .catch(() => {
      redirigir("Error al verificar sesión.");
    });
}

function redirigir(mensaje) {
  Swal.fire({
    icon: "error",
    title: "Acceso denegado",
    text: mensaje
  }).then(() => {
    window.location.href = "../index.php";
  });
}

function cargarNoticias() {
  // Si es editor, solo carga sus propias noticias
  const endpoint =
    rolUsuario === "editor"
      ? "../../api/controllerNoticia.php?mis_noticias=true"
      : "../../api/controllerNoticia.php";

  fetch(endpoint, {
    credentials: "include"
  })
    .then(res => res.json())
    .then(noticias => mostrarNoticias(noticias))
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudieron cargar las noticias."
      });
    });
}

function mostrarNoticias(noticias) {
  const tbody = document.querySelector("#noticiasTable tbody");
  tbody.innerHTML = "";

  noticias.forEach(noticia => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${noticia.id}</td>
      <td>${noticia.titulo}</td>
      <td class="contenido-celda" data-contenido="${noticia.contenido.replace(/"/g, '&quot;')}">
    ${noticia.contenido.slice(0, 100)}...
  </td>

      <td>${noticia.categoria}</td>
      <td>${noticia.autor}</td>
      <td class="imagenes-container">
        ${(noticia.imagenes || [])
          .map(
            obj =>
              `<img src="../${obj.imagen}" alt="Imagen noticia" class="imagen-noticia"
                style="cursor: pointer;" onclick="mostrarImagenModal(this.src)"
                onerror="this.src='../../imagenDB/default.png'; this.onerror=null;">`
          )
          .join("")}
      </td>
      <td>${noticia.fecha_creacion}</td>
      <td>
  <select data-id="${noticia.id}" class="estado-select" ${rolUsuario === "editor" ? "disabled" : ""}>
    <option value="1" ${noticia.activo == 1 ? "selected" : ""}>Activo</option>
    <option value="2" ${noticia.activo == 2 ? "selected" : ""}>Inactivo</option>
    <option value="3" ${noticia.activo == 3 ? "selected" : ""}>En espera</option>
  </select>
</td>
<td>
  <button class="btn-guardar" data-id="${noticia.id}" ${rolUsuario === "editor" ? "disabled" : ""}>Guardar</button>
</td>
</tr>
    `;
    tbody.appendChild(fila);


  });

  document.querySelectorAll(".btn-guardar").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      const select = document.querySelector(`select.estado-select[data-id="${id}"]`);
      actualizarEstado(id, select.value);
    });
  });
}

function actualizarEstado(id, estado) {
  fetch("../../api/controllerNoticia.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json"
    },
    credentials: "include",
    body: JSON.stringify({ id: parseInt(id), estado: parseInt(estado) })
  })
    .then(res => res.json())
    .then(data => {
      Swal.fire({
        icon: data.success ? "success" : "error",
        title: data.success ? "Actualizado" : "Error",
        text: data.message || "Error al actualizar estado"
      });
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudo actualizar el estado."
      });
    });
}
function mostrarImagenModal(src) {
  const modal = document.getElementById("modalImagen");
  const img = document.getElementById("imagenAmpliada");
  img.src = src;
  modal.style.display = "flex";
}

// Evento para cerrar el modal al hacer clic fuera de la imagen
document.getElementById("modalImagen").addEventListener("click", () => {
  document.getElementById("modalImagen").style.display = "none";
  document.getElementById("imagenAmpliada").src = "";
});

// Modal de imagen
const modal = document.getElementById("modalImagen");
const imagenGrande = document.getElementById("imagenGrande");
const cerrar = document.getElementById("cerrarModal");

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("imagen-noticia")) {
    imagenGrande.src = e.target.src;
    modal.style.display = "flex";
  }
});

cerrar.onclick = function () {
  modal.style.display = "none";
};

modal.onclick = function (e) {
  if (e.target === modal) modal.style.display = "none";
};
// Delegación de eventos para mostrar el modal de contenido al hacer clic
document.addEventListener("click", function (e) {
  const celda = e.target.closest(".contenido-celda");
  if (celda) {
    const contenidoCompleto = celda.dataset.contenido;

    // Crear modal solo si no existe ya
    let modal = document.querySelector(".modal-contenido");
    if (modal) modal.remove(); // Eliminar uno anterior si ya existe

    modal = document.createElement("div");
    modal.classList.add("modal-contenido");

    modal.innerHTML = `
    <span class="cerrar-modal">&times;</span>
    <div class="contenido-modal-texto">
      <div class="texto-completo">${contenidoCompleto}</div>
    </div>
  `;


    document.body.appendChild(modal);

    // Mostrar el modal (ya tiene `display: flex` en CSS)
    modal.style.display = "flex";

    // Cerrar al hacer clic en la X
    modal.querySelector(".cerrar-modal").addEventListener("click", () => {
      modal.remove();
    });

    // Cerrar al hacer clic fuera del contenido
    modal.addEventListener("click", (ev) => {
      if (ev.target === modal) modal.remove();
    });
  }
});