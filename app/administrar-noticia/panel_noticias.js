document.addEventListener("DOMContentLoaded", () => {
  // Verifica si el usuario es un supervisor
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include"
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success || data.rol !== "supervisor") {
        Swal.fire({
          icon: "error",
          title: "Acceso denegado",
          text: "Solo los supervisores pueden acceder.",
          confirmButtonText: "OK"
        }).then(() => {
          window.location.href = "../../login.html";
        });
      } else {
        cargarNoticias(); // Carga noticias si tiene acceso
      }
    })
    .catch(error => {
      console.error("Error al verificar sesión:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Error al verificar sesión",
        confirmButtonText: "OK"
      }).then(() => {
        window.location.href = "../../login.html";
      });
    });
});

function cargarNoticias() {
  fetch("../../api/controllerNoticia.php")
    .then(res => res.json())
    .then(noticias => {
      const tabla = document.getElementById("noticiasTable");
      const tbody = tabla.querySelector("tbody");
      tbody.innerHTML = ""; // Limpiar contenido anterior
      console.log(noticias);
      noticias.forEach(noticia => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
          <td>${noticia.id}</td>
          <td>${noticia.titulo}</td>
          <td>${noticia.contenido}</td>
          <td>${noticia.categoria}</td>
          <td>${noticia.autor}</td>
          <td>
            <select data-id="${noticia.id}" class="estado-select">
              <option value="1" ${noticia.activo == 1 ? "selected" : ""}>Activo</option>
              <option value="2" ${noticia.activo == 2 ? "selected" : ""}>Inactivo</option>
              <option value="3" ${noticia.activo == 3 ? "selected" : ""}>En espera</option>
            </select>
          </td>
          <td>
            <button class="btn-guardar" data-id="${noticia.id}">Guardar</button>
          </td>
        `;
        tbody.appendChild(fila);
      });

      document.querySelectorAll(".btn-guardar").forEach(boton => {
        boton.addEventListener("click", () => {
          const id = boton.dataset.id;
          const select = document.querySelector(`select.estado-select[data-id="${id}"]`);
          const nuevoEstado = select.value;
          cambiarEstado(id, nuevoEstado);
        });
      });
    })
    .catch(err => {
      console.error("Error al cargar noticias:", err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Error al cargar noticias"
      });
    });
}

function cambiarEstado(id, nuevoEstado) {
  console.log(`Cambiando estado de la noticia ${id} a ${nuevoEstado}`);
  fetch("../../api/controllerNoticia.php", {
    method: "PUT",
    headers: {
      "Content-Type": "application/json"
    },
    credentials: "include",
    body: JSON.stringify({
      id: parseInt(id),
      estado: parseInt(nuevoEstado)
    })
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "Actualizado",
          text: "Estado actualizado correctamente"
        }).then(() => cargarNoticias());
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: data.message || "Error al actualizar estado"
        });
      }
    })
    .catch(error => {
      console.error("Error al actualizar estado:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Error al actualizar estado"
      });
    });
}
