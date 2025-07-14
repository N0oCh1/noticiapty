const apiUrl = "../../api/controllerUsuarios.php";
const sessionInfoUrl = "../../api/controllerSessionInfo.php";

// Verifica si el usuario logueado es admin
async function verificarSesionYPermiso() {
  try {
    const res = await fetch(sessionInfoUrl);
    const data = await res.json();

    if (!data.success) {
      Swal.fire({
        icon: "error",
        title: "Acceso denegado",
        text: "Debes iniciar sesión para acceder a esta página.",
      }).then(() => {
        window.location.href = "../../login.html"; // Redirige al login
      });
      return;
    }

    if (data.rol !== "admin") {
      Swal.fire({
        icon: "warning",
        title: "Acceso restringido",
        text: "No tienes permisos para ver esta sección.",
      }).then(() => {
        window.location.href = "../../index.php"; // Redirige a home u otra parte
      });
      return;
    }

    // Si es admin, cargar usuarios
    cargarUsuarios();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo verificar la sesión.",
    });
  }
}

// Ejecutar verificación al cargar
verificarSesionYPermiso();
