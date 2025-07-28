document.addEventListener("DOMContentLoaded", () => {
  

  verificarSesion();
});

let noticiasCargadas = [];
let rolUsuario = "";

// Verifica la sesión del usuario y su rol
function verificarSesion() {
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include"
  })
    .then(res => res.json())
    .then(data => {
      if (data.success && ["supervisor", "admin", "editor"].includes(data.rol)) {
        rolUsuario = data.rol;
        cargarNoticias();
      } else {
        redirigir("Solo los supervisores y administradores pueden acceder.");
      }
    })
    .catch(() => {
      redirigir("Error al verificar sesión.");
    });
}

// Redirige a la página de inicio con una alerta
function redirigir(mensaje) {
  Swal.fire({
    icon: "error",
    title: "Acceso denegado",
    text: mensaje
  }).then(() => {
    window.location.href = "../index.php";
  });
}

// Carga las noticias dependiendo del rol
function cargarNoticias() {
  const endpoint =
    rolUsuario === "editor"
      ? "../../api/controllerNoticia.php?mis_noticias=true"
      : "../../api/controllerNoticia.php";

  fetch(endpoint, {
    credentials: "include"
  })
    .then(res => res.json())
    .then(noticias => {
      noticiasCargadas = noticias;
      mostrarNoticias(noticias);
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No se pudieron cargar las noticias."
      });
    });
}

// Muestra las noticias en la tabla
function mostrarNoticias(noticias) {
  const tbody = document.querySelector("#noticiasTable tbody");
  tbody.innerHTML = "";

  if (!noticias || noticias.length === 0) {
    const filaVacia = document.createElement("tr");
    filaVacia.innerHTML = `
      <td colspan="9" style="text-align: center; font-weight: bold; padding: 20px;">
        No hay noticias disponibles.
      </td>
    `;
    tbody.appendChild(filaVacia);
    return;
  }

  noticias.forEach(noticia => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${noticia.id}</td>
      <td>${noticia.titulo}</td>
      <td class="contenido-celda" data-contenido="${noticia.contenido.replace(/"/g, '&quot;')}">
        ${noticia.contenido.slice(0, 100)}...
      </td>
      <td>${noticia.categoria_nombre}</td>
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
        <button class="btn-editar" data-id="${noticia.id}">Editar</button>
        <button class="btn-guardar" data-id="${noticia.id}" ${rolUsuario === "editor" ? "disabled" : ""}>Guardar</button>
      </td>
    `;
    tbody.appendChild(fila);
  });

  // Asigna eventos a los botones de guardar
  document.querySelectorAll(".btn-guardar").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      const select = document.querySelector(`select.estado-select[data-id="${id}"]`);
      actualizarEstado(id, select.value);
    });
  });

  // Asigna eventos a los botones de editar (¡aquí está la corrección!)
  document.querySelectorAll(".btn-editar").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      window.location.href = `../editar-noticia/index.html?id=${id}`;
    });
  });
}


/// Actualiza el estado de una noticia
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
        title: data.success ? (data.message === "No se realizaron cambios en el estado" ? "Sin cambios" : "Actualizado") : "Error",
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


// Muestra imagen en modal
function mostrarImagenModal(src) {
  const modal = document.getElementById("modalImagen");
  const img = document.getElementById("imagenAmpliada");
  img.src = src;
  modal.style.display = "flex";
}

// Cierra el modal de imagen al hacer clic fuera
document.getElementById("modalImagen").addEventListener("click", () => {
  document.getElementById("modalImagen").style.display = "none";
  document.getElementById("imagenAmpliada").src = "";
});

// Modal de imagen (click sobre imagen para ampliar)
const modal = document.getElementById("modalImagen");
const imagenGrande = document.getElementById("imagenGrande");
const cerrar = document.getElementById("cerrarModal");

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("imagen-noticia")) {
    imagenGrande.src = e.target.src;
    modal.style.display = "flex";
  }
});

// Cierra modal con botón de cerrar
cerrar.onclick = function () {
  modal.style.display = "none";
};

// Cierra modal si se hace clic fuera de la imagen
modal.onclick = function (e) {
  if (e.target === modal) modal.style.display = "none";
};

// Muestra modal con el contenido completo de la noticia
document.addEventListener("click", function (e) {
  const celda = e.target.closest(".contenido-celda");
  if (celda) {
    const contenidoCompleto = celda.dataset.contenido;

    let modal = document.querySelector(".modal-contenido");
    if (modal) modal.remove();

    modal = document.createElement("div");
    modal.classList.add("modal-contenido");

    modal.innerHTML = `
      <span class="cerrar-modal">&times;</span>
      <div class="contenido-modal-texto">
        <div class="texto-completo">${contenidoCompleto}</div>
      </div>
    `;

    document.body.appendChild(modal);
    modal.style.display = "flex";

    modal.querySelector(".cerrar-modal").addEventListener("click", () => {
      modal.remove();
    });

    modal.addEventListener("click", ev => {
      if (ev.target === modal) modal.remove();
    });
  }
});

// Filtrado de noticias mientras se escribe
document.getElementById("buscadorNoticias").addEventListener("input", function () {
  const palabra = this.value.trim();
  if (palabra === "") {
    cargarNoticias();
    return;
  }
  buscarNoticias(palabra);
});

// Filtra las noticias en memoria por coincidencia
function buscarNoticias(palabra) {
  const filtro = palabra.toLowerCase();

  const filtradas = noticiasCargadas.filter(noticia =>
    noticia.titulo.toLowerCase().includes(filtro) ||
    noticia.contenido.toLowerCase().includes(filtro) ||
    noticia.categoria_nombre.toLowerCase().includes(filtro) ||
    noticia.autor.toLowerCase().includes(filtro)
  );

  mostrarNoticias(filtradas);
}

document.getElementById("btnMostrarTodas").addEventListener("click", () => {
  mostrarNoticias(noticiasCargadas);
});

document.getElementById("btnFiltrarActivas").addEventListener("click", () => {
  const activas = noticiasCargadas.filter(n => n.activo == 1);
  mostrarNoticias(activas);
});

document.getElementById("btnFiltrarInactivas").addEventListener("click", () => {
  const inactivas = noticiasCargadas.filter(n => n.activo == 2);
  mostrarNoticias(inactivas);
});

document.getElementById("btnFiltrarEspera").addEventListener("click", () => {
  const espera = noticiasCargadas.filter(n => n.activo == 3);
  mostrarNoticias(espera);
});

