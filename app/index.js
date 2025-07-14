// Variables globales
let currentPage = 1;
const initialNewsCount = 3; // Número inicial de noticias a mostrar
const newsPerPage = 4; // Noticias a cargar en "cargar más"
let currentCategory = "todas";
let allNews = []; // Almacenar todas las noticias

document.addEventListener("DOMContentLoaded", () => {
    // Carga inicial de noticias
    loadAllNews();

    // Event listeners
    document.getElementById("loadMore").addEventListener("click", loadMoreNews);

    // Event delegation para categorías
    document.querySelector(".main-nav").addEventListener("click", (e) => {
        if (e.target.tagName === "A") {
            e.preventDefault();

            const selectedCategory = e.target.dataset.category;
            currentCategory =
                selectedCategory === "todas"
                    ? "todas"
                    : parseInt(selectedCategory);

            document.getElementById("newsGrid").innerHTML = "";
            currentPage = 1;
            loadFilteredNews();
        }
    });

    // Verificar si hay un usuario en sessionStorage
    const usuario = sessionStorage.getItem("usuario");
    // const usuarioId = sessionStorage.getItem("usuario_id");

    if (usuario) {
        // Si hay un usuario, mostrar el botón de logout
        document.querySelector(".user-info").style.display = "flex"; // Mostrar el área del usuario
        document.querySelector(".nav-auth").style.display = "none"; // Ocultar los botones de autenticación
        document.getElementById("logoutBtn").style.display = "block"; // Mostrar el botón de logout
    } else {
        // Si no hay un usuario, mostrar los botones de autenticación
        document.querySelector(".user-info").style.display = "none"; // Ocultar el área del usuario
        document.querySelector(".nav-auth").style.display = "flex"; // Mostrar los botones de login y registro
    }
});

// Función de logout con SweetAlert y cierre de sesión en backend
function logout() {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¿Deseas cerrar sesión?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cerrar sesión",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Paso 1: Llamar al backend para destruir la sesión del servidor
            fetch("../api/logoutController.php")
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        // Paso 2: Limpiar datos de sesión del navegador
                        sessionStorage.removeItem("usuario");
                        // sessionStorage.removeItem("usuario_id"); // si usas ID también

                        // Paso 3: Actualizar la interfaz
                        document.querySelector(".user-info").style.display =
                            "none";
                        document.querySelector(".nav-auth").style.display =
                            "flex";

                        // Paso 4: Mostrar mensaje de éxito y redirigir
                        Swal.fire({
                            icon: "success",
                            title: "Sesión cerrada",
                            text: "Has cerrado sesión correctamente.",
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = "../index.php";
                        });
                    } else {
                        throw new Error(
                            data.message || "No se pudo cerrar sesión."
                        );
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.message || "Error al cerrar sesión.",
                    });
                });
        }
    });
}

// Función para cargar todas las noticias
function loadAllNews() {
    const url = "../api/controllerNoticia.php"; // Traer todas las noticias sin filtro

    fetch(url)
        .then((response) => response.text()) // Recibe como texto crudo
        .then((text) => {
            console.log("Respuesta cruda:", text);
            return JSON.parse(text); // intenta parsear JSON manualmente para capturar el error
        })
        .then((data) => {
            allNews = data;
            console.log("All news loaded:", allNews);
            // Mostrar las noticias filtradas según la categoría inicial
            console.log(loadFilteredNews());
        })
        .catch((error) => console.error("Error:", error));
}

// Función para cargar noticias filtradas por categoría
function loadFilteredNews() {
    // Filtrar noticias según la categoría seleccionada y activo = 1

    console.log("🟡 DEBUG de tipos y valores");

    allNews.forEach((news) => {
        console.log("Activo:", news.activo, "Tipo:", typeof news.activo);

        console.log("Tipos:", typeof news.categoria_id, typeof currentCategory);
        console.log("Valores:", news.categoria_id, currentCategory);
    });

    const filteredNews =
        currentCategory === "todas"
            ? allNews.filter((news) => Number(news.activo) === 1)
            : allNews.filter(
                  (news) =>
                      Number(news.categoria_id) === Number(currentCategory) &&
                      Number(news.activo) === 1
              );

    console.log("Filtered news:", filteredNews);
    // Ordenar las noticias por fecha descendente
    const sortedNews = filteredNews.sort(
        (a, b) => new Date(b.fecha_creacion) - new Date(a.fecha_creacion)
    );
    console.log("Sorted news:", sortedNews);

    // Verificar si no hay noticias después del filtrado
    if (sortedNews.length === 0) {
        // Mostrar el mensaje de "No hay noticias para esta categoría"
        document.getElementById("newsGrid").innerHTML =
            "<p style='text-align: center; font-size: 18px; margin-top: 15rem; color: #2c3e50;'>No hay noticias para esta categoría.</p>";
        document.getElementById("loadMore").style.display = "none"; // Ocultar el botón de cargar más
    } else {
        // Código para cargar noticias...

        // Renderizar las noticias
        renderNews(sortedNews.slice(0, initialNewsCount));

        // Mostrar botón "Cargar más" si hay más noticias
        document.getElementById("loadMore").style.display =
            sortedNews.length > initialNewsCount ? "block" : "none";
    }
}

// Función para cargar más noticias
function loadMoreNews() {
    const startIndex = document.querySelectorAll(".news-card").length;

    // Filtrar noticias según la categoría seleccionada y activo = 1
    const filteredNews =
        currentCategory === "todas"
            ? allNews.filter((news) => Number(news.activo) === 1)
            : allNews.filter(
                  (news) =>
                      Number(news.categoria_id) === Number(currentCategory) &&
                      Number(news.activo) === 1
              );

    const nextNews = filteredNews.slice(startIndex, startIndex + newsPerPage);

    if (nextNews.length > 0) {
        renderNews(nextNews);
    }

    // Ocultar botón si no hay más noticias
    if (startIndex + nextNews.length >= filteredNews.length) {
        document.getElementById("loadMore").style.display = "none";
    }
}

// Función para renderizar las noticias
function renderNews(news) {
    console.log("Rendering news:", news);
    const newsGrid = document.getElementById("newsGrid");
    const defaultImage = "../imagenDB/default.png";

    // Para las primeras 3 noticias, usar diseño destacado
    if (document.querySelectorAll(".news-card").length === 0) {
        // Primera noticia (más importante)
        if (news.length > 0) {
            const mainNews = news[0];
            const mainCard = createFeaturedNewsCard(mainNews, "main-news");
            newsGrid.appendChild(mainCard);
        }

        // Dos noticias secundarias
        if (news.length > 1) {
            const secondaryNewsContainer = document.createElement("div");
            secondaryNewsContainer.className = "secondary-news";

            news.slice(1, 3).forEach((article) => {
                const card = createFeaturedNewsCard(
                    article,
                    "secondary-news-card"
                );
                secondaryNewsContainer.appendChild(card);
            });

            newsGrid.appendChild(secondaryNewsContainer);
        }

        // Resto de noticias
        if (news.length > 3) {
            const secondaryNewsContainer = document.createElement("div");
            secondaryNewsContainer.className = "secondary-news";
            news.slice(3).forEach((article) => {
                const card = createFeaturedNewsCard(
                    article,
                    "secondary-news-card"
                );
                secondaryNewsContainer.appendChild(card);
            });

            newsGrid.appendChild(secondaryNewsContainer);
        }
    } else {
        // Para cargar más noticias, usarlas en bloques de dos
        const secondaryNewsContainer = document.createElement("div");
        secondaryNewsContainer.className = "secondary-news";
        news.forEach((article) => {
            const card = createFeaturedNewsCard(article, "secondary-news-card");
            secondaryNewsContainer.appendChild(card);
        });

        newsGrid.appendChild(secondaryNewsContainer);
    }
}

// Función para crear las tarjetas de noticias
function createFeaturedNewsCard(article, className) {
    const card = document.createElement("a"); // Usamos <a> en lugar de <div>
    card.className = `news-card ${className}`;
    card.href = "#"; // Evitamos que el enlace se redirija automáticamente

    // Al hacer clic en el card, almacenamos los datos de la noticia en localStorage
    card.addEventListener("click", () => {
        localStorage.setItem("noticia", JSON.stringify(article));
        window.location.href = "../app/detalle-noticia/index.html"; // Redirigimos a la página de detalles
    });
    const imageUrl =
        article.imagenes && article.imagenes.length > 0
            ? article.imagenes[0].imagen
            : "../imagenDB/default.png";

    card.innerHTML = `
        <img src="${imageUrl}" 
             alt="${article.titulo}" 
             class="news-image"
             onerror="this.src='../imagenDB/default.png'">
        <div class="news-content">
            <h3 class="news-title">${article.titulo}</h3>
            <p class="news-excerpt">${article.contenido.substring(
                0,
                className === "main-news" ? 500 : 100
            )}...</p>
            <div class="news-meta">
                <span>${article.nombre_usuario} ${
        article.apellido_usuario
    }</span>

                <span>${new Date(
                    article.fecha_creacion || article.fecha
                ).toLocaleDateString()}</span>
            </div>
        </div>
    `;

    return card;
}
