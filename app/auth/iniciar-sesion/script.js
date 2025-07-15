document
    .getElementById("loginForm")
    .addEventListener("submit", function (event) {
        event.preventDefault();

        const usuario = document.getElementById("usuario").value;
        const password = document.getElementById("password").value;

        if (usuario && password) {
            fetch("../../../api/controllerLogin.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                credentials: "include", // ✅ Esto permite enviar cookies de sesión
                body: JSON.stringify({ usuario, password }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Puedes usar sessionStorage para uso en frontend, pero ya la sesión está guardada en el backend
                        sessionStorage.setItem("usuario", usuario);
                        console.log("Usuario:", usuario);
                        sessionStorage.setItem("usuario_id", data.usuario_id);
                        console.log("Usuario ID:", data.usuario_id);
                        sessionStorage.setItem("rol", data.rol);

                        Swal.fire({
                            icon: "success",
                            title: "¡Bienvenido!",
                            text: "Has iniciado sesión correctamente.",
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = "../../index.php";
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text:
                                data.message ||
                                "Usuario o contraseña incorrectos.",
                            confirmButtonText: "Intentar de nuevo",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error en la solicitud:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Error en la conexión. Intenta de nuevo.",
                    });
                });
        } else {
            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                text: "Por favor, completa todos los campos.",
            });
        }
    });
