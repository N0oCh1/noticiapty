// Variables globales
let currentPage = 1;
const initialNewsCount = 3; // Número inicial de noticias a mostrar
const newsPerPage = 2; // Noticias a cargar en "cargar más"
let currentCategory = "todas";
let allNews = []; // Almacenar todas las noticias

document.addEventListener("DOMContentLoaded", () => {
  

    // Carga inicial de noticias
    loadInitialNews();

    // Event listeners
    document.getElementById("loadMore").addEventListener("click", loadMoreNews);

    // Event delegation para categorías
    document.querySelector(".main-nav").addEventListener("click", (e) => {
        if (e.target.tagName === "A") {
            e.preventDefault();
            currentCategory = e.target.dataset.category;
            document.getElementById("newsGrid").innerHTML = "";
            currentPage = 1;
            loadInitialNews();
        }
    });
});

function loadInitialNews() {
    const url = `../api/controllerNoticia.php?category=${currentCategory}`;


    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            // Ordenar noticias por fecha descendente
            allNews = data.sort(
                (a, b) =>
                    new Date(b.fecha_creacion) - new Date(a.fecha_creacion)
            );

            // Mostrar solo las primeras 3 noticias
            renderNews(allNews.slice(0, initialNewsCount));

            // Mostrar botón si hay más noticias
            document.getElementById("loadMore").style.display =
                allNews.length > initialNewsCount ? "block" : "none";
        })
        .catch((error) => console.error("Error:", error));
}

function loadMoreNews() {
    const startIndex = document.querySelectorAll(".news-card").length;
    const nextNews = allNews.slice(startIndex, startIndex + newsPerPage);

    if (nextNews.length > 0) {
        renderNews(nextNews);
    }

    // Ocultar botón si no hay más noticias
    if (startIndex + nextNews.length >= allNews.length) {
        document.getElementById("loadMore").style.display = "none";
    }
}

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

function createFeaturedNewsCard(article, className) {
    const card = document.createElement("div");
    card.className = `news-card ${className}`;

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
                <span>${article.autor}</span>
                <span>${new Date(
                    article.fecha_creacion || article.fecha
                ).toLocaleDateString()}</span>
            </div>
        </div>
    `;

    return card;
}


