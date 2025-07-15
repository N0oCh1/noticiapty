document.addEventListener("DOMContentLoaded", () => {
  // Verifica si el usuario está logueado y es publicador
  fetch("../../api/controllerSessionInfo.php", {
    method: "GET",
    credentials: "include"
  })
    .then(res => res.json())
    .then(data => {
      if (!data.success || data.rol !== "publicador") {
        alert("Acceso denegado. Solo los periodistas pueden publicar noticias.");
        window.location.href = "../index.php";
      } else {
        document.getElementById("usuario_id").value = data.usuario_id;
      }
    });

  // Envío del formulario
  const form = document.getElementById("formNoticia");
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("../../api/controllerNoticia.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        alert(data.message);
        if (data.message.includes("correctamente")) {
          window.location.href = "../index.php";
        }
      })
      .catch((error) => {
        console.error("Error al guardar noticia:", error);
        alert("Error al guardar la noticia");
      });
  });
});
