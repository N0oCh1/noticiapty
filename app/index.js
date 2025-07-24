// Variables globales
let currentPage = 1;
const initialNewsCount = 5;
const newsPerPage = 6;
let currentCategory = "todas";
let allNews = [];
const usuario = sessionStorage.getItem("usuario");
document.addEventListener("DOMContentLoaded", () => {
    // Verificar sesión desde el servidor
    fetch("../api/controllerSessionInfo.php", {
        method: "GET",
        credentials: "include",
    })
        .then((res) => res.json())
        .then((data) => {
            console.log("Datos de sesión:", data);
            if (data.success) {
                sessionStorage.setItem("usuario_id", data.usuario_id);
                sessionStorage.setItem("rol", data.rol);

                // Mostrar nombre de usuario
                const usernameDisplay =
                    document.getElementById("usernameDisplay");
                if (usernameDisplay) {
                    usernameDisplay.textContent = `Hola, ${usuario}`;
                }

                document.querySelector(".user-info").style.display = "flex";
                document.querySelector(".nav-auth").style.display = "none";
                document.getElementById("logoutBtn").style.display = "block";
                                // Mostrar botón de perfil si hay sesión
                const perfilBtn = document.getElementById("btn-editar");
                if (perfilBtn && data.success) {
                    perfilBtn.style.display = "inline-block";
                    perfilBtn.addEventListener("click", () => {
                        window.location.href = "../app/editar-usuario/";
                    });
                }
                
                if (data.rol === "admin") {
                    const adminBtn = document.getElementById("adminBtn");
                    if (adminBtn) {
                        adminBtn.style.display = "inline-block";
                        adminBtn.addEventListener("click", () => {
                            window.location.href =
                                "../app/administrar-usuario/";
                        });
                    }
                }

                if (data.rol === "supervisor"  || data.rol === "admin" || data.rol === "editor") {
                    const supervisorBtn = document.getElementById("supervisorPanelBtn");
                    if (supervisorBtn) {
                        supervisorBtn.style.display = "inline-block";
                        supervisorBtn.addEventListener("click", () => {
                            window.location.href =
                                "../app/administrar-noticia/index.php";
                        });
                    }
                }

                if (data.rol === "editor" || data.rol === "admin" || data.rol === "supervisor") {
                    const publicarBtn = document.getElementById("publicarBtn");
                    if (publicarBtn) {
                        publicarBtn.style.display = "inline-block";
                        publicarBtn.addEventListener("click", () => {
                            window.location.href =
                                "../app/crear-noticia/index.php";
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
            currentCategory =
                selectedCategory === "todas"
                    ? "todas"
                    : parseInt(selectedCategory);
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

                        document.querySelector(".user-info").style.display =
                            "none";
                        document.querySelector(".nav-auth").style.display =
                            "flex";

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

// Cargar todas las noticias
function loadAllNews() {
    fetch("../api/controllerNoticia.php")
        .then((response) => response.text())
        .then((text) => {
            return JSON.parse(text);
        })
        .then((data) => {
            console.log("Noticias cargadas:", data);
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
        document.getElementById("wrapper").style.minHeight = "100vh"; // Ajustar altura del wrapper si no hay noticias
            document.getElementById("newsGrid").innerHTML =
                "<p style='text-align: center; font-size: 18px; color: #2c3e50;'>No hay noticias para esta categoría.</p>";
        document.getElementById("loadMore").style.display = "none";
    } else {
        document.getElementById("wrapper").style.minHeight = ""; // Quitar minHeight cuando sí hay noticias

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
                const card = createFeaturedNewsCard(
                    article,
                    "secondary-news-card"
                );
                secondaryContainer.appendChild(card);
            });
            newsGrid.appendChild(secondaryContainer);
        }

        if (news.length > 3) {
            const extraContainer = document.createElement("div");
            extraContainer.className = "secondary-news";
            news.slice(3).forEach((article) => {
                const card = createFeaturedNewsCard(
                    article,
                    "secondary-news-card"
                );
                extraContainer.appendChild(card);
            });
            newsGrid.appendChild(extraContainer);
        }
    } else {
        const moreContainer = document.createElement("div");
        moreContainer.className = "secondary-news";
        news.forEach((article) => {
            console.log(article);
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
        window.location.href = "../app/detalle-noticia/";
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
            <span><strong>Autor:</strong> ${article.autor}</span>
            
    </span>
            <span>${new Date(
                article.fecha_creacion || article.fecha
            ).toLocaleDateString()}</span>
        </div>
    </div>
`;

    return card;
}
