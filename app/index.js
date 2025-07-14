// Variables globales
let currentPage = 1;
const initialNewsCount = 3;
const newsPerPage = 4;
let currentCategory = "todas";
let allNews = [];

document.addEventListener("DOMContentLoaded", () => {
    // Verificar sesión desde el servidor
    fetch("../api/controllerSessionInfo.php", {
        method: "GET",
        credentials: "include"
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                sessionStorage.setItem("usuario_id", data.usuario_id);
                sessionStorage.setItem("rol", data.rol);

                document.querySelector(".user-info").style.display = "flex";
                document.querySelector(".nav-auth").style.display = "none";
                document.getElementById("logoutBtn").style.display = "block";

                if (data.rol === "admin") {
                    const adminBtn = document.getElementById("adminBtn");
                    if (adminBtn) {
                        adminBtn.style.display = "inline-block";
                        adminBtn.addEventListener("click", () => {
                            window.location.href = "../app/administrar-usuario/index.html";
                        });
                    }
                }
            } else {
                document.querySelector(".user-info").style.display = "none";
                document.querySelector(".nav-auth").style.display = "flex";
            }
        })
        .catch((error) => {
            console.error("Error al verificar sesión:", error);
        });

    // Cargar noticias
    loadAllNews();

    // Eventos
    document.getElementById("loadMore").addEventListener("click", loadMoreNews);

    document.querySelector(".main-nav").addEventListener("click", (e) => {
        if (e.target.tagName === "A") {
            e.preventDefault();
            const selectedCategory = e.target.dataset.category;
            currentCategory = selectedCategory === "todas" ? "todas" : parseInt(selectedCategory);
            document.getElementById("newsGrid").innerHTML = "";
            currentPage = 1;
            loadFilteredNews();
        }
    });

    // Logout
    document.getElementById("logoutBtn").addEventListener("click", logout);
});

// Cerrar sesión
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
            fetch("../api/logoutController.php")
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        sessionStorage.clear();

                        document.querySelector(".user-info").style.display = "none";
                        document.querySelector(".nav-auth").style.display = "flex";

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
                        throw new Error(data.message || "No se pudo cerrar sesión.");
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

// Cargar todas las noticias
function loadAllNews() {
    fetch("../api/controllerNoticia.php")
        .then((response) => response.text())
        .then((text) => {
            return JSON.parse(text);
        })
        .then((data) => {
            allNews = data;
            loadFilteredNews();
        })
        .catch((error) => console.error("Error:", error));
}

// Cargar noticias filtradas
function loadFilteredNews() {
    const filteredNews =
        currentCategory === "todas"
            ? allNews.filter((news) => Number(news.activo) === 1)
            : allNews.filter(
                  (news) =>
                      Number(news.categoria_id) === Number(currentCategory) &&
                      Number(news.activo) === 1
              );

    const sortedNews = filteredNews.sort(
        (a, b) => new Date(b.fecha_creacion) - new Date(a.fecha_creacion)
    );

    if (sortedNews.length === 0) {
        document.getElementById("newsGrid").innerHTML =
            "<p style='text-align: center; font-size: 18px; margin-top: 15rem; color: #2c3e50;'>No hay noticias para esta categoría.</p>";
        document.getElementById("loadMore").style.display = "none";
    } else {
        renderNews(sortedNews.slice(0, initialNewsCount));
        document.getElementById("loadMore").style.display =
            sortedNews.length > initialNewsCount ? "block" : "none";
    }
}

// Cargar más noticias
function loadMoreNews() {
    const startIndex = document.querySelectorAll(".news-card").length;

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

    if (startIndex + nextNews.length >= filteredNews.length) {
        document.getElementById("loadMore").style.display = "none";
    }
}

// Renderizar noticias
function renderNews(news) {
    const newsGrid = document.getElementById("newsGrid");
    const defaultImage = "../imagenDB/default.png";

    if (document.querySelectorAll(".news-card").length === 0) {
        if (news.length > 0) {
            const mainCard = createFeaturedNewsCard(news[0], "main-news");
            newsGrid.appendChild(mainCard);
        }

        if (news.length > 1) {
            const secondaryContainer = document.createElement("div");
            secondaryContainer.className = "secondary-news";
            news.slice(1, 3).forEach((article) => {
                const card = createFeaturedNewsCard(article, "secondary-news-card");
                secondaryContainer.appendChild(card);
            });
            newsGrid.appendChild(secondaryContainer);
        }

        if (news.length > 3) {
            const extraContainer = document.createElement("div");
            extraContainer.className = "secondary-news";
            news.slice(3).forEach((article) => {
                const card = createFeaturedNewsCard(article, "secondary-news-card");
                extraContainer.appendChild(card);
            });
            newsGrid.appendChild(extraContainer);
        }
    } else {
        const moreContainer = document.createElement("div");
        moreContainer.className = "secondary-news";
        news.forEach((article) => {
            const card = createFeaturedNewsCard(article, "secondary-news-card");
            moreContainer.appendChild(card);
        });
        newsGrid.appendChild(moreContainer);
    }
}

// Crear tarjeta de noticia
function createFeaturedNewsCard(article, className) {
    const card = document.createElement("a");
    card.className = `news-card ${className}`;
    card.href = "#";

    card.addEventListener("click", () => {
        localStorage.setItem("noticia", JSON.stringify(article));
        window.location.href = "../app/detalle-noticia/index.html";
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
                <span>${article.nombre_usuario} ${article.apellido_usuario}</span>
                <span>${new Date(article.fecha_creacion || article.fecha).toLocaleDateString()}</span>
            </div>
        </div>
    `;

    return card;
}
