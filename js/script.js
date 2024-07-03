document.getElementById("postForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const title = document.getElementById("title").value;
  const description = document.getElementById("description").value;
  const imageInput = document.getElementById("item");

  if (imageInput.files && imageInput.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      const imageUrl = e.target.result;
      const postId = Date.now().toString();

      const post = {
        id: postId,
        title: title,
        image: imageUrl,
        description: description,
        date: new Date().toLocaleString(),
        location: "Desconocido",
        contact: "contacto@ejemplo.com",
      };

      savePost(post);
      displayCard(post); // Mostrar en el sistema de cartas
      document.getElementById("postForm").reset();
    };

    reader.readAsDataURL(imageInput.files[0]);
  }
});

function savePost(post) {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    posts.push(post);
    localStorage.setItem("posts", JSON.stringify(posts));
    console.log("Post saved:", post);
  } catch (error) {
    console.error("Error saving post:", error);
  }
}

function displayCard(post) {
  const cardDiv = document.createElement("div");
  cardDiv.classList.add("item");
  cardDiv.style.backgroundImage = `url(${post.image})`;

  const contentDiv = document.createElement("div");
  contentDiv.classList.add("content");

  const cardTitle = document.createElement("div");
  cardTitle.classList.add("name");
  cardTitle.textContent = post.title;

  const cardDescription = document.createElement("div");
  cardDescription.classList.add("des");
  cardDescription.textContent = post.description;

  const cardButton = document.createElement("btn1");
  cardButton.textContent = "MÁS INFORMACIÓN";

  contentDiv.appendChild(cardTitle);
  contentDiv.appendChild(cardDescription);
  contentDiv.appendChild(cardButton);

  cardDiv.appendChild(contentDiv);

  document.querySelector(".cartasfotos").appendChild(cardDiv); // Agregar al contenedor de cartas
}

document.addEventListener("DOMContentLoaded", function () {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    posts.forEach(function (post) {
      displayCard(post); // Mostrar cartas existentes al cargar la página
    });
    console.log("Posts loaded:", posts);
  } catch (error) {
    console.error("Error loading posts:", error);
  }

  getLocalStorageSize();
});

function getLocalStorageSize() {
  let total = 0;
  for (let x in localStorage) {
    if (localStorage.hasOwnProperty(x)) {
      total += (localStorage[x].length + x.length) * 2;
    }
  }
  console.log("LocalStorage size (bytes):", total);
  return total;
}

// Código para el desplazamiento de cartas
let next = document.querySelector(".next");
let prev = document.querySelector(".prev");

next.addEventListener("click", function () {
  let items = document.querySelectorAll(".item");
  document.querySelector(".cartasfotos").appendChild(items[0]);
});

prev.addEventListener("click", function () {
  let items = document.querySelectorAll(".item");
  document.querySelector(".cartasfotos").prepend(items[items.length - 1]);
});
