// Cuando se envía el formulario de publicación
document.getElementById("postForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Evitar el comportamiento predeterminado de envío

  // Obtener los valores del formulario
  const title = document.getElementById("title").value;
  const description = document.getElementById("description").value;
  const imageInput = document.getElementById("image");

  // Verificar si se seleccionó un archivo de imagen
  if (imageInput.files && imageInput.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      const imageUrl = e.target.result;
      const postId = Date.now().toString(); // Generar ID único basado en el tiempo actual
      const currentDate = new Date().toLocaleString(); // Obtener la fecha y hora actual

      // Crear objeto de publicación
      const post = {
        id: postId,
        title: title,
        image: imageUrl,
        description: description,
        date: currentDate,
        location: "Desconocido",
        contact: "contacto@ejemplo.com",
      };

      savePost(post); // Guardar la publicación en el almacenamiento local
      displayPost(post); // Mostrar la publicación en la interfaz de usuario
      document.getElementById("postForm").reset(); // Reiniciar el formulario
    };

    // Leer el archivo de imagen como URL
    reader.readAsDataURL(imageInput.files[0]);
  }
});

// Función para guardar la publicación en el almacenamiento local
function savePost(post) {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    posts.push(post); // Agregar la nueva publicación al arreglo
    // Ordenar las publicaciones por fecha más reciente antes de guardarlas
    posts.sort((a, b) => new Date(b.date) - new Date(a.date));
    localStorage.setItem("posts", JSON.stringify(posts)); // Guardar en localStorage
    console.log("Post saved:", post); // Registrar la publicación guardada en la consola
  } catch (error) {
    console.error("Error saving post:", error); // Manejar errores al guardar la publicación
  }
}

// Función para mostrar una publicación en la interfaz de usuario
function displayPost(post) {
  const postDiv = document.createElement("div"); // Crear un nuevo elemento div
  postDiv.classList.add("post"); // Agregar la clase 'post' al div
  postDiv.dataset.id = post.id; // Establecer el atributo de datos 'id' con el ID de la publicación

  // Crear elementos HTML para mostrar los detalles de la publicación
  const postTitle = document.createElement("h2");
  postTitle.textContent = post.title;

  const postImage = document.createElement("img");
  postImage.src = post.image;

  const postDescription = document.createElement("p");
  postDescription.textContent = post.description;

  const viewButton = document.createElement("button");
  viewButton.textContent = "Visualizar";
  viewButton.addEventListener("click", function () {
    window.location.href = `/html/detalles_publicacion.html?id=${post.id}`;
  });

  const deleteButton = document.createElement("button");
  deleteButton.textContent = "Eliminar";
  deleteButton.addEventListener("click", function () {
    deletePost(post.id); // Eliminar la publicación al hacer clic en el botón
    postDiv.remove(); // Quitar el div de la interfaz de usuario
  });

  // Agregar elementos al div de la publicación
  postDiv.appendChild(postTitle);
  postDiv.appendChild(postImage);
  postDiv.appendChild(postDescription);
  postDiv.appendChild(viewButton);
  postDiv.appendChild(deleteButton);

  // Insertar la nueva publicación en orden de fecha más reciente
  const postsContainer = document.querySelector(".posts-container");
  let inserted = false; // Bandera para controlar si se ha insertado la publicación
  for (let i = 0; i < postsContainer.children.length; i++) {
    const currentPost = postsContainer.children[i];
    const currentPostDate = new Date(currentPost.dataset.date);
    const newPostDate = new Date(post.date);
    if (newPostDate > currentPostDate) {
      // Comparar fechas para determinar el orden
      postsContainer.insertBefore(postDiv, currentPost); // Insertar antes de la publicación actual
      inserted = true;
      break; // Salir del bucle una vez insertada la publicación
    }
  }
  if (!inserted) {
    postsContainer.appendChild(postDiv); // Si es la más antigua, añadir al final
  }
}

// Evento al cargar el DOM, para mostrar las publicaciones almacenadas
document.addEventListener("DOMContentLoaded", function () {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || []; // Obtener publicaciones del localStorage
    posts.reverse().forEach(displayPost); // Mostrar publicaciones en orden de más reciente primero
    console.log("Posts loaded:", posts); // Registrar las publicaciones cargadas en la consola
  } catch (error) {
    console.error("Error loading posts:", error); // Manejar errores al cargar las publicaciones
  }

  getLocalStorageSize(); // Obtener el tamaño total del almacenamiento local
});

// Función para calcular y mostrar el tamaño total del almacenamiento local
function getLocalStorageSize() {
  let total = 0;
  for (let x in localStorage) {
    if (localStorage.hasOwnProperty(x)) {
      total += (localStorage[x].length + x.length) * 2; // Calcular tamaño en bytes
    }
  }
  console.log("LocalStorage size (bytes):", total); // Mostrar tamaño total en la consola
  return total; // Devolver el tamaño total
}

// Función para eliminar una publicación por su ID
function deletePost(postId) {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    posts = posts.filter((post) => post.id !== postId); // Filtrar publicaciones para eliminar la seleccionada
    localStorage.setItem("posts", JSON.stringify(posts)); // Actualizar almacenamiento local sin la publicación eliminada
    console.log(`Post ${postId} deleted`); // Registrar la eliminación en la consola
  } catch (error) {
    console.error("Error deleting post:", error); // Manejar errores al eliminar la publicación
  }
}
