// Evento que se ejecuta al enviar el formulario de publicación
document.getElementById("postForm").addEventListener("submit", function (e) {
  e.preventDefault(); // Evitar el comportamiento predeterminado del formulario

  // Obtener los valores del formulario
  const title = document.getElementById("title").value;
  const description = document.getElementById("description").value;
  const imageInput = document.getElementById("image");

  // Verificar si se seleccionó un archivo de imagen
  if (imageInput.files && imageInput.files[0]) {
    const reader = new FileReader();

    // Evento que se ejecuta cuando se carga la imagen
    reader.onload = function (e) {
      const imageUrl = e.target.result; // Obtener la URL de la imagen cargada
      const postId = Date.now().toString(); // Generar un ID único basado en la hora actual
      const currentDate = new Date().toLocaleString(); // Obtener la fecha y hora actual

      // Crear objeto de publicación con los datos obtenidos
      const post = {
        id: postId,
        title: title,
        image: imageUrl,
        description: description,
        date: currentDate,
        location: "Desconocido", // Información adicional por defecto
        contact: "contacto@ejemplo.com", // Información adicional por defecto
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
    let posts = JSON.parse(localStorage.getItem("posts")) || []; // Obtener las publicaciones almacenadas
    posts.push(post); // Agregar la nueva publicación
    posts.sort((a, b) => new Date(b.date) - new Date(a.date)); // Ordenar las publicaciones por fecha
    localStorage.setItem("posts", JSON.stringify(posts)); // Guardar las publicaciones en el almacenamiento local
    console.log("Post saved:", post); // Registro en consola
  } catch (error) {
    console.error("Error saving post:", error); // Manejar errores
  }
}

// Función para mostrar una publicación en la interfaz de usuario
function displayPost(post) {
  const postDiv = document.createElement("div"); // Crear un nuevo elemento div
  postDiv.classList.add("post"); // Añadir clase 'post' al div
  postDiv.dataset.id = post.id; // Añadir ID de la publicación como atributo de datos

  // Crear elementos para mostrar los detalles de la publicación
  const postTitle = document.createElement("h2");
  postTitle.textContent = post.title;

  // Crear imagen para la publicación
  const postImage = document.createElement("img");
  postImage.src = post.image;
  postImage.addEventListener("click", function () {
    window.location.href = `/html/detalles_publicacion.html?id=${post.id}`; // Redireccionar al hacer clic en la imagen
  });

  const postDescription = document.createElement("p");
  postDescription.textContent = post.description;

  // Crear contenedor para los botones de visualización y eliminación
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container");

  // Crear botón de visualización
  const viewButton = document.createElement("button");
  const viewIcon = document.createElement("i");
  viewIcon.classList.add("fas", "fa-eye"); // Añadir icono de visualización
  viewButton.appendChild(viewIcon);
  viewButton.addEventListener("click", function () {
    // No se realiza ninguna acción al hacer clic en el botón de visualización
  });

  // Crear botón de eliminación
  const deleteButton = document.createElement("button");
  const deleteIcon = document.createElement("i");
  deleteIcon.classList.add("fas", "fa-trash"); // Añadir icono de eliminación
  deleteButton.appendChild(deleteIcon);
  deleteButton.addEventListener("click", function () {
    deletePost(post.id); // Eliminar la publicación
    postDiv.remove(); // Quitar la publicación de la interfaz de usuario
  });

  // Añadir los botones al contenedor de botones
  buttonsContainer.appendChild(viewButton);
  buttonsContainer.appendChild(deleteButton);

  // Crear contenedor para los comentarios
  const commentsContainer = document.createElement("div");
  commentsContainer.classList.add("comments-container");

  // Crear lista de comentarios
  const commentsList = document.createElement("div");
  commentsList.classList.add("comments-list");

  // Crear formulario para agregar nuevos comentarios
  const commentForm = document.createElement("form");
  commentForm.classList.add("comment-form");
  const commentInput = document.createElement("input");
  commentInput.type = "text";
  commentInput.placeholder = "Escribe un comentario...";
  const commentButton = document.createElement("button");
  commentButton.type = "submit";
  commentButton.textContent = "Comentar";

  // Añadir elementos al formulario de comentarios
  commentForm.appendChild(commentInput);
  commentForm.appendChild(commentButton);
  commentsContainer.appendChild(commentsList);
  commentsContainer.appendChild(commentForm);

  // Evento que se ejecuta al enviar el formulario de comentarios
  commentForm.addEventListener("submit", function (e) {
    e.preventDefault();
    if (commentInput.value.trim() !== "") {
      const comment = {
        postId: post.id, // ID de la publicación asociada al comentario
        text: commentInput.value.trim(), // Texto del comentario
        date: new Date().toLocaleString(), // Fecha y hora del comentario
      };
      saveComment(comment); // Guardar el comentario en el almacenamiento local
      displayComment(comment, commentsList); // Mostrar el comentario en la interfaz de usuario
      commentInput.value = ""; // Limpiar el campo de entrada de comentarios
    }
  });

  // Añadir todos los elementos al div de la publicación
  postDiv.appendChild(postTitle);
  postDiv.appendChild(postImage);
  postDiv.appendChild(postDescription);
  postDiv.appendChild(buttonsContainer);
  postDiv.appendChild(commentsContainer);

  // Insertar la publicación en la interfaz de usuario en el lugar adecuado según la fecha
  const postsContainer = document.querySelector(".posts-container");
  let inserted = false;
  for (let i = 0; i < postsContainer.children.length; i++) {
    const currentPost = postsContainer.children[i];
    const currentPostDate = new Date(currentPost.dataset.date);
    const newPostDate = new Date(post.date);
    if (newPostDate > currentPostDate) {
      postsContainer.insertBefore(postDiv, currentPost);
      inserted = true;
      break;
    }
  }
  if (!inserted) {
    postsContainer.appendChild(postDiv);
  }
}

// Función para eliminar una publicación por su ID
function deletePost(postId) {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    posts = posts.filter((post) => post.id !== postId); // Filtrar publicaciones para eliminar la seleccionada
    localStorage.setItem("posts", JSON.stringify(posts)); // Actualizar el almacenamiento local
    console.log(`Post ${postId} deleted`); // Registro en consola
  } catch (error) {
    console.error("Error deleting post:", error); // Manejar errores
  }
}
