const apiUrl = "../../api/controllerUsuarios.php";

// Función para obtener y mostrar usuarios
async function cargarUsuarios() {
  try {
    const res = await fetch(apiUrl);
    if (!res.ok) throw new Error("Error al cargar usuarios");
    const usuarios = await res.json();

    if (usuarios.message) throw new Error(usuarios.message);

    const tbody = document.querySelector("#usersTable tbody");
    tbody.innerHTML = "";

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
            <option value="usuario" ${user.rol === "usuario" ? "selected" : ""}>Usuario</option>
            <option value="periodista" ${user.rol === "periodista" ? "selected" : ""}>Periodista</option>
            <!-- Si quieres incluir admin solo si el usuario tiene permiso -->
            <!--<option value="admin" ${user.rol === "admin" ? "selected" : ""}>Admin</option>-->
          </select>
        </td>
        <td>${user.activo == 1 ? "Sí" : "No"}</td>
        <td>
          <button data-action="toggle" data-id="${user.id}">${user.activo == 1 ? "Desactivar" : "Activar"}</button>
          <button data-action="guardar" data-id="${user.id}">Guardar Cambios</button>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch (error) {
    alert(error.message);
  }
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
      cargarUsuarios();
    } else {
      alert(result.message || "Error al agregar usuario");
    }
  } catch (error) {
    alert("Error de red");
  }
});

// Delegación de eventos para toggle y guardar cambios
document.querySelector("#usersTable tbody").addEventListener("click", async e => {
  const target = e.target;
  const tr = target.closest("tr");
  if (!tr) return;

  const id = target.dataset.id;
  if (!id) {
    alert("ID inválido");
    return;
  }
  const action = target.dataset.action;

  if (action === "toggle") {
    const nuevoEstado = tr.classList.contains("active") ? 0 : 1;

    try {
      const res = await fetch(`${apiUrl}?id=${id}`, {
        method: "PUT",  // Asegúrate que backend acepta PUT para esto
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ activo: nuevoEstado })
      });
      const result = await res.json();
      if (res.ok) {
        alert(`Usuario ${nuevoEstado === 1 ? "activado" : "desactivado"}`);
        cargarUsuarios();
      } else {
        alert(result.message || "Error al cambiar estado");
      }
    } catch (error) {
      alert("Error de red");
    }

  } else if (action === "guardar") {
    // Mejor usar innerText para evitar HTML
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
      if (res.ok) {
        alert("Usuario actualizado");
        cargarUsuarios();
      } else {
        alert(result.message || "Error al actualizar usuario");
      }
    } catch (error) {
      alert("Error de red");
    }
  }
});

// Cargar usuarios al inicio
cargarUsuarios();
