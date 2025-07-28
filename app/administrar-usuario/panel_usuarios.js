const apiUrl = "../../api/controllerUsuarios.php";
let usuariosCargados = []; // Para almacenar todos los usuarios una sola vez

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
    usuariosCargados = usuarios; 
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
          <option value="editor" ${user.rol === "editor" ? "selected" : ""}>Editor</option>
          <option value="admin" ${user.rol === "admin" ? "selected" : ""}>Admin</option>
          <option value="supervisor" ${user.rol === "supervisor" ? "selected" : ""}>Supervisor</option>
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
}

// Evento para agregar usuario
document.getElementById("formAddUser").addEventListener("submit", async e => {
  e.preventDefault();

  const formData = new FormData(e.target);
  const data = Object.fromEntries(formData.entries());

  if (!data.nombre || !data.apellido || !data.usuario || !data.contrasena || !data.rol) {
    Swal.fire({
      icon: "warning",
      title: "Campos incompletos",
      text: "Complete todos los campos",
    });
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
      Swal.fire({
        icon: "success",
        title: "Usuario agregado",
        text: "El usuario fue agregado correctamente",
      });
      e.target.reset();
      verificarSesionYPermiso(); // recarga usuarios
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: result.message || "Error al agregar usuario",
      });
    }
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error de red",
      text: "No se pudo conectar con el servidor.",
    });
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
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ activo: nuevoEstado })
      });
      const result = await res.json();
      if (res.ok) {
        Swal.fire({
          icon: "success",
          title: "Estado actualizado",
          text: "El estado del usuario ha sido actualizado.",
        });
        verificarSesionYPermiso();
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: result.message || "Error al cambiar estado",
        });
      }
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Error de red",
        text: "No se pudo conectar con el servidor.",
      });
    }

  } else if (action === "guardar") {
    const nombre = tr.querySelector('[data-field="nombre"]').innerText.trim();
    const apellido = tr.querySelector('[data-field="apellido"]').innerText.trim();
    const usuario = tr.querySelector('[data-field="usuario"]').innerText.trim();
    const rol = tr.querySelector('[data-field="rol"]').value;

    if (!nombre || !apellido || !usuario || !rol) {
      Swal.fire({
        icon: "warning",
        title: "Campos incompletos",
        text: "Complete todos los campos antes de guardar",
      });
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
          if (result.message && result.message === "No se realizaron cambios") {
              Swal.fire({
                  icon: "info",
                  title: "Sin cambios",
                  text: "No se realizaron cambios en los datos del usuario.",
                  timer: 2000,
                  showConfirmButton: false,
              });
          } else {
              Swal.fire({
                  icon: "success",
                  title: "Actualizado correctamente",
                  text: "Tus datos han sido modificados.",
                  timer: 2000,
                  showConfirmButton: false,
              });
          }
      } else {
          Swal.fire({
              icon: "error",
              title: "Error",
              text: result.message || "Error al actualizar usuario",
          });
      }
  } catch (error) {
      console.error("Error al guardar usuario:", error);
      Swal.fire({
        icon: "error",
        title: "Error de red",
        text: `No se pudo conectar con el servidor. ${error}`
      });
    }
  }
});

document.getElementById("buscadorUsuarios").addEventListener("input", function () {
  const palabra = this.value.trim().toLowerCase();

  if (palabra === "") {
    renderizarUsuarios(usuariosCargados); // muestra todos si no hay texto
    return;
  }

  const filtrados = usuariosCargados.filter(user =>
    user.nombre.toLowerCase().includes(palabra) ||
    user.apellido.toLowerCase().includes(palabra) ||
    user.usuario.toLowerCase().includes(palabra)
  );

  renderizarUsuarios(filtrados);
});

// Inicia validación al cargar
verificarSesionYPermiso();

document.getElementById("filtrosUsuarios").addEventListener("click", function (e) {
  if (e.target.tagName !== "BUTTON") return;

  const filtro = e.target.dataset.filtro;

  let filtrados = [];

  switch (filtro) {
    case "activos":
      filtrados = usuariosCargados.filter(u => u.activo == 1);
      break;
    case "inactivos":
      filtrados = usuariosCargados.filter(u => u.activo == 0);
      break;
    case "supervisor":
      filtrados = usuariosCargados.filter(u => u.rol === "supervisor");
      break;
    case "editor":
      filtrados = usuariosCargados.filter(u => u.rol === "editor");
      break;
    default:
      filtrados = usuariosCargados;
      break;
  }

  renderizarUsuarios(filtrados);
});
