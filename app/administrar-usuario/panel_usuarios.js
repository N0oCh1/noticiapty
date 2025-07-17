const apiUrlNoticias = "../../api/controllerNoticia.php";

// Verifica si el usuario logueado es supervisor antes de cargar noticias
async function verificarSesionSupervisor() {
  try {
    const res = await fetch("../../api/controllerSessionInfo.php", {
      method: "GET",
      credentials: "include"
    });

    const data = await res.json();

    if (!data.success || data.rol !== "supervisor") {
      Swal.fire({
        icon: "error",
        title: "Acceso denegado",
        text: "Solo los supervisores pueden acceder a este panel.",
      }).then(() => {
        window.location.href = "../index.php";
      });
      return;
    }

    cargarNoticias();

  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo verificar la sesión.",
    });
  }
}

// Cargar todas las noticias
async function cargarNoticias() {
  try {
    const res = await fetch(apiUrlNoticias);
    const noticias = await res.json();

    renderizarNoticias(noticias);
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudieron cargar las noticias.",
    });
  }
}

// Renderizar noticias en tabla
function renderizarNoticias(noticias) {
  const tbody = document.querySelector("#noticiasTable tbody");
  tbody.innerHTML = "";

  noticias.forEach(noticia => {
    const tr = document.createElement("tr");
    tr.className = `estado-${noticia.estado}`; // Para posibles estilos según estado

    const estadoTexto = noticia.estado == 1
      ? "Aprobada"
      : noticia.estado == 2
      ? "Rechazada"
      : "En Espera";

    tr.innerHTML = `
      <td>${noticia.id}</td>
      <td>${noticia.titulo}</td>
      <td>${noticia.categoria}</td>
      <td>${noticia.nombre_autor}</td>
      <td>${estadoTexto}</td>
      <td>
        <button class="estado-btn" data-id="${noticia.id}" data-estado="1">Aprobar</button>
        <button class="estado-btn" data-id="${noticia.id}" data-estado="2">Rechazar</button>
        <button class="estado-btn" data-id="${noticia.id}" data-estado="3">Dejar en espera</button>
      </td>
    `;

    tbody.appendChild(tr);
  });
}

// Delegación para manejar los botones de cambio de estado
document.querySelector("#noticiasTable tbody").addEventListener("click", async e => {
  const target = e.target;
  if (!target.classList.contains("estado-btn")) return;

  const id = target.dataset.id;
  const nuevoEstado = parseInt(target.dataset.estado);

  try {
    const res = await fetch(`${apiUrlNoticias}?id=${id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ estado: nuevoEstado })
    });

    const result = await res.json();

    if (res.ok) {
      Swal.fire("Actualizado", "El estado de la noticia fue actualizado.", "success");
      cargarNoticias();
    } else {
      Swal.fire("Error", result.message || "No se pudo cambiar el estado.", "error");
    }
  } catch (error) {
    Swal.fire("Error", "Error de red al actualizar estado.", "error");
  }
});

// Iniciar proceso
verificarSesionSupervisor();
