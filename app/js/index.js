// Variables globales
let currentPage = 1;
const newsPerPage = 6;
let currentCategory = "todas";

// Contador de visitantes (usando localStorage para demo)
document.addEventListener("DOMContentLoaded", () => {
    const visitors = parseInt(localStorage.getItem("visitors") || "0");
    localStorage.setItem("visitors", visitors + 1);
    document.getElementById("visitorCount").textContent = visitors + 1;

    // Carga inicial de noticias
    loadNews();

    // Event listeners
    document.getElementById("loadMore").addEventListener("click", loadNews);

    // Event delegation para categorías
    document.querySelector(".main-nav").addEventListener("click", (e) => {
        if (e.target.tagName === "A") {
            e.preventDefault();
            currentCategory = e.target.dataset.category;
            document.getElementById("newsGrid").innerHTML = "";
            currentPage = 1;
            loadNews();
        }
    });
});

function loadNews() {
    const url = `../api/controllerNoticia.php?page=${currentPage}&category=${currentCategory}`;

    fetch(url)
        .then((response) => response.json())
        .then((data) => {
            renderNews(data);
            currentPage++;
        })
        .catch((error) => console.error("Error:", error));
}

function renderNews(news) {
    const newsGrid = document.getElementById("newsGrid");
    const defaultImage = "../imagenDB/default.png";

    console.log("Rendering news:", news);

    news.forEach((article) => {
        const card = document.createElement("div");
        card.className = "news-card";

        // Obtener la primera imagen del array de imágenes o usar imagen por defecto
        const imageUrl =
            article.imagenes && article.imagenes.length > 0
                ? article.imagenes[0].imagen
                : defaultImage;
        
        console.log("Image URL:", imageUrl);

        card.innerHTML = `
            <img src="${imageUrl}" 
                 alt="${article.titulo}" 
                 class="news-image"
                 onerror="this.src='${defaultImage}'">
            <div class="news-content">
                <h3 class="news-title">${article.titulo}</h3>
                <p class="news-excerpt">${article.contenido.substring(
                    0,
                    100
                )}...</p>
                <div class="news-meta">
                    <span>${article.autor}</span>
                    <span>${new Date(
                        article.fecha_creacion || article.fecha
                    ).toLocaleDateString()}</span>
                </div>
            </div>
        `;

        newsGrid.appendChild(card);
    });

    // Ocultar botón si no hay más noticias
    if (news.length < newsPerPage) {
        document.getElementById("loadMore").style.display = "none";
    }
}
