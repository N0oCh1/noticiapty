const apiUrl = "../../api/controllerUsuarios.php";

// Verifica si el usuario logueado es admin al intentar obtener la lista
async function verificarSesionYPermiso() {
  try {
    const res = await fetch(apiUrl); // GET sin ID → requiere sesión y rol admin

    if (!res.ok) {
      const error = await res.json();
      Swal.fire({
        icon: "error",
        title: "Acceso denegado",
        text: error.message || "No tienes permisos para acceder.",
      }).then(() => {
        window.location.href = "../index.php";
      });
      return;
    }

    const usuarios = await res.json();
    renderizarUsuarios(usuarios);

  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo verificar la sesión.",
    });
  }
}

// Función para mostrar usuarios en tabla
function renderizarUsuarios(usuarios) {
  const tbody = document.querySelector("#usersTable tbody");
  tbody.innerHTML = "";
  console.log(usuarios);
  usuarios.forEach(user => {
    const tr = document.createElement("tr");
    tr.className = user.activo == 1 ? "active" : "inactive";

    tr.innerHTML = `
      <td>${user.id}</td>
      <td contenteditable="true" data-field="nombre" data-id="${user.id}">${user.nombre}</td>
      <td contenteditable="true" data-field="apellido" data-id="${user.id}">${user.apellido}</td>
      <td contenteditable="true" data-field="usuario" data-id="${user.id}">${user.usuario}</td>
      <td>
        <select data-field="rol" data-id="${user.id}">
          <option value="global" ${user.rol === "global" ? "selected" : ""}>Global</option>
          <option value="publicador" ${user.rol === "publicador" ? "selected" : ""}>Publicador</option>
          <option value="admin" ${user.rol === "admin" ? "selected" : ""}>Admin</option>
        </select>
      </td>
      <td>${user.activo == 1 ? "Sí" : "No"}</td>
      <td>
        <button data-action="toggle" data-id="${user.id}">${user.activo == 1 ? "Desactivar" : "Activar"}</button>
        <button data-action="guarda r" data-id="${user.id}">Guardar Cambios</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

// Evento para agregar usuario
document.getElementById("formAddUser").addEventListener("submit", async e => {
  e.preventDefault();

  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());

  if (!data.nombre || !data.apellido || !data.usuario || !data.contrasena || !data.rol) {
    alert("Complete todos los campos");
    return;
  }

  try {
    const res = await fetch(apiUrl, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    });

    const result = await res.json();

    if (res.status === 201) {
      alert("Usuario agregado correctamente");
      e.target.reset();
      verificarSesionYPermiso(); // recarga usuarios
    } else {
      alert(result.message || "Error al agregar usuario");
    }
  } catch (error) {
    alert("Error de red");
  }
});

// Delegación de eventos para activar/desactivar y guardar cambios
document.querySelector("#usersTable tbody").addEventListener("click", async e => {
  const target = e.target;
  const tr = target.closest("tr");
  if (!tr) return;

  const id = target.dataset.id;
  const action = target.dataset.action;
  if (!id || !action) return;

  if (action === "toggle") {
    const nuevoEstado = tr.classList.contains("active") ? 0 : 1;

    try {
      const res = await fetch(`${apiUrl}?id=${id}`, {
        method: "DELETE", // para cambiar estado activo
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ activo: nuevoEstado })
      });
      const result = await res.json();
      if (res.ok) verificarSesionYPermiso();
      else alert(result.message || "Error al cambiar estado");
    } catch (error) {
      alert("Error de red");
    }

  } else if (action === "guardar") {
    const nombre = tr.querySelector('[data-field="nombre"]').innerText.trim();
    const apellido = tr.querySelector('[data-field="apellido"]').innerText.trim();
    const usuario = tr.querySelector('[data-field="usuario"]').innerText.trim();
    const rol = tr.querySelector('[data-field="rol"]').value;

    if (!nombre || !apellido || !usuario || !rol) {
      alert("Complete todos los campos antes de guardar");
      return;
    }

    try {
      const res = await fetch(`${apiUrl}?id=${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nombre, apellido, usuario, rol })
      });
      const result = await res.json();
      if (res.ok) verificarSesionYPermiso();
      else alert(result.message || "Error al actualizar");
    } catch (error) {
      alert("Error de red");
    }
  }
});

// Inicia validación al cargar
verificarSesionYPermiso();
