document
    .getElementById("registerForm")
    .addEventListener("submit", function (event) {
        event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

        // Obtener los valores del formulario
        const nombre = document.getElementById("nombre").value;
        const apellido = document.getElementById("apellido").value;
        const usuario = document.getElementById("usuario").value;
        const password = document.getElementById("password").value;

        // Crear el objeto con los datos del formulario
        const data = {
            nombre: nombre,
            apellido: apellido,
            usuario: usuario,
            password: password,
        };

        // Enviar los datos al backend mediante fetch (POST)
        fetch("../../../api/controllerRegistroUsuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
            .then((response) => {
                console.log("Respuesta del servidor:", response);
                if (!response.ok) {
                    return response.text();
                }
                return response.json();
            })
            .then((data) => {
                console.log("Datos recibidos:", data);
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "¡Registro exitoso!",
                        text: "Usuario registrado correctamente.",
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(() => {
                        window.location.href =
                            "../iniciar-sesion/index.html";
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text:
                            data.message || "No se pudo registrar el usuario.",
                        confirmButtonText: "Intentar de nuevo",
                    });
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Hubo un error al registrar el usuario.",
                });
            });
    });
