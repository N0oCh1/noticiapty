const sessionInfoUrl = "../../api/controllerSessionInfo.php";
const apiUsuariosUrl = "../../api/controllerUsuarios.php";

let usuarioId = null;

async function verificarSesionYObtenerId() {
  try {
    const res = await fetch(sessionInfoUrl);
    const data = await res.json();

    if (!data.success || !data.usuario_id) {
      throw new Error("No hay sesión activa");
    }

    usuarioId = data.usuario_id;
    return true;

  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Sesión no válida",
      text: "Debes iniciar sesión para acceder.",
      confirmButtonText: "Ir al inicio"
    }).then(() => {
      window.location.href = "../index.php";
    });
    return false;
  }
}

async function cargarDatosUsuario() {
  try {
    const res = await fetch(`${apiUsuariosUrl}?id=${usuarioId}`);
    if (!res.ok) throw new Error("No se pudo obtener el usuario");

    const usuario = await res.json();

    document.querySelector('input[name="nombre"]').value = usuario.nombre;
    document.querySelector('input[name="apellido"]').value = usuario.apellido;
    document.querySelector('input[name="usuario"]').value = usuario.usuario;

  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error al cargar datos",
      text: "No se pudo cargar la información del usuario.",
    });
  }
}

document.getElementById("btnEditar").addEventListener("click", () => {
  document.querySelectorAll('input[name="nombre"], input[name="apellido"], input[name="usuario"]')
    .forEach(input => input.removeAttribute("disabled"));

  // Mostrar botón guardar
  document.getElementById("btnGuardar").style.display = "inline-block";

  // Ocultar botón editar
  document.getElementById("btnEditar").style.display = "none";
});

document.getElementById("formEditarSesion").addEventListener("submit", async function (e) {
  e.preventDefault();

  const nombre = document.querySelector('input[name="nombre"]').value.trim();
  const apellido = document.querySelector('input[name="apellido"]').value.trim();
  const usuario = document.querySelector('input[name="usuario"]').value.trim();

  if (!nombre || !apellido || !usuario) {
    Swal.fire({
      icon: "warning",
      title: "Campos incompletos",
      text: "Por favor, completa todos los campos.",
    });
    return;
  }

  const confirm = await Swal.fire({
    icon: "question",
    title: "¿Guardar cambios?",
    text: "Se actualizará tu información personal.",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, guardar",
    cancelButtonText: "Cancelar"
  });

  if (!confirm.isConfirmed) return;

  try {
      const res = await fetch(`${apiUsuariosUrl}?id=${usuarioId}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ nombre, apellido, usuario}),
      });

      // Verifica si la respuesta fue exitosa
      if (!res.ok) {
          const errorText = await res.text();
          console.error("Error de servidor:", errorText); // Muestra el HTML completo para depurar
          throw new Error(`HTTP error! status: ${res.status}`);
      }

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

              // Deshabilitar campos y reiniciar botones
              document
                  .querySelectorAll(
                      'input[name="nombre"], input[name="apellido"], input[name="usuario"]'
                  )
                  .forEach((input) => input.setAttribute("disabled", true));

              document.getElementById("btnGuardar").style.display = "none";
              document.getElementById("btnEditar").style.display =
                  "inline-block";
          }
      } else {
          Swal.fire({
              icon: "error",
              title: "Error",
              text: result.message || "No se pudo actualizar el usuario.",
          });
      }
  } catch (error) {
      Swal.fire({
          icon: "error",
          title: "Error de red",
          text: `No se pudo conectar al servidor. Detalle del error: ${
              error.message || error
          }`,
      });
  }

});

// Ejecutar al cargar
(async function () {
  const sesionValida = await verificarSesionYObtenerId();
  if (sesionValida) {
    cargarDatosUsuario();
  }
})();
