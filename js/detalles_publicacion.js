// Escuchar el evento 'DOMContentLoaded' para asegurar que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  // Obtener los parámetros de la URL
  const urlParams = new URLSearchParams(window.location.search);
  const postId = urlParams.get("id"); // Obtener el ID del post de los parámetros

  // Obtener los posts guardados en localStorage o un array vacío si no existen
  let posts = JSON.parse(localStorage.getItem("posts")) || [];
  const post = posts.find((p) => p.id === postId); // Buscar el post con el ID especificado

  if (post) {
    displayPostDetails(post); // Mostrar los detalles del post
    loadComments(postId); // Cargar y mostrar los comentarios del post
  } else {
    // Mostrar un mensaje de error si no se encuentra el post
    document.getElementById("postDetails").innerHTML =
      "<p>Publicación no encontrada</p>";
  }

  // Manejar el evento 'submit' del formulario de comentarios
  const commentForm = document.getElementById("commentForm");
  commentForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
    const commentInput = document.getElementById("commentInput");
    const text = commentInput.value.trim(); // Obtener y limpiar el texto del comentario
    if (text === "") return; // No hacer nada si el comentario está vacío

    // Crear un objeto de comentario
    const comment = {
      id: generateCommentId(), // Generar un ID único para el comentario
      postId: postId, // Asignar el ID del post al comentario
      text: text, // Asignar el texto del comentario
      date: new Date().toLocaleString(), // Asignar la fecha actual al comentario
    };

    saveComment(comment); // Guardar el comentario en localStorage
    commentInput.value = ""; // Limpiar el campo de entrada del comentario
    displayComment(comment); // Mostrar el comentario en la UI
  });
});

// Función para mostrar los detalles del post
function displayPostDetails(post) {
  document.getElementById("postImage").src = post.image;
  document.getElementById("postTitle").textContent = post.title;
  document.getElementById("postDescription").textContent = post.description;
  document.getElementById(
    "postDate"
  ).textContent = `Fecha de publicación: ${post.date}`;
  document.getElementById(
    "postLocation"
  ).textContent = `Ubicación: ${post.location}`;
  document.getElementById(
    "postContact"
  ).textContent = `Contacto: ${post.contact}`;
}

// Función para cargar y mostrar los comentarios de un post
function loadComments(postId) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.filter((comment) => comment.postId === postId); // Filtrar comentarios por postId
  const commentsList = document.getElementById("commentsList");
  commentsList.innerHTML = ""; // Limpiar lista de comentarios
  comments.forEach((comment) => {
    displayComment(comment); // Mostrar cada comentario
  });
}

// Función para mostrar un comentario en la UI
function displayComment(comment) {
  const commentList = document.getElementById("commentsList");

  const commentDiv = document.createElement("div");
  commentDiv.classList.add("comment");
  commentDiv.dataset.id = comment.id; // Asignar ID del comentario al atributo de datos

  const commentText = document.createElement("p");
  commentText.textContent = comment.text; // Asignar texto del comentario

  const commentDate = document.createElement("span");
  commentDate.classList.add("comment-date");
  commentDate.textContent = comment.date; // Asignar fecha del comentario

  const optionsButton = document.createElement("button");
  optionsButton.textContent = "⋮";
  optionsButton.classList.add("options-comment-button");
  optionsButton.addEventListener("click", function () {
    openOptionsModal(comment.id, comment.text); // Abrir modal de opciones
  });

  // Añadir elementos al div del comentario
  commentDiv.appendChild(commentText);
  commentDiv.appendChild(commentDate);
  commentDiv.appendChild(optionsButton);

  // Añadir el div del comentario a la lista de comentarios
  commentList.appendChild(commentDiv);
}

// Función para abrir un modal de opciones (editar/eliminar) para un comentario
function openOptionsModal(commentId, commentText) {
  const modal = document.createElement("div");
  modal.classList.add("modal");

  const modalContent = document.createElement("div");
  modalContent.classList.add("modal-content");

  const editInput = document.createElement("input");
  editInput.type = "text";
  editInput.value = commentText;
  editInput.classList.add("edit-input");

  const saveButton = document.createElement("button");
  saveButton.textContent = "Guardar";
  saveButton.classList.add("save-button");
  saveButton.addEventListener("click", function () {
    editComment(commentId, editInput.value); // Editar comentario
    modal.remove(); // Cerrar modal
  });

  const deleteButton = document.createElement("button");
  deleteButton.textContent = "Eliminar";
  deleteButton.classList.add("delete-button");
  deleteButton.addEventListener("click", function () {
    deleteComment(commentId); // Eliminar comentario
    document.querySelector(`.comment[data-id="${commentId}"]`).remove(); // Remover comentario de la UI
    modal.remove(); // Cerrar modal
  });

  // Añadir elementos al contenido del modal
  modalContent.appendChild(editInput);
  modalContent.appendChild(saveButton);
  modalContent.appendChild(deleteButton);
  modal.appendChild(modalContent);

  // Añadir modal al body del documento
  document.body.appendChild(modal);
}

// Función para editar un comentario en localStorage
function editComment(commentId, newText) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.map((comment) => {
    if (comment.id === commentId) {
      comment.text = newText; // Actualizar texto del comentario
    }
    return comment;
  });
  localStorage.setItem("comments", JSON.stringify(comments)); // Guardar comentarios actualizados en localStorage
  updateCommentUI(commentId, newText); // Actualizar la UI con el nuevo texto del comentario
}

// Función para actualizar la UI con el nuevo texto del comentario editado
function updateCommentUI(commentId, newText) {
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
  if (commentDiv) {
    const commentText = commentDiv.querySelector("p");
    if (commentText) {
      commentText.textContent = newText; // Actualizar texto en la UI
    }
  }
}

// Función para eliminar un comentario de localStorage
function deleteComment(commentId) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.filter((comment) => comment.id !== commentId); // Filtrar el comentario eliminado
  localStorage.setItem("comments", JSON.stringify(comments)); // Guardar comentarios actualizados en localStorage
}

// Función para generar un ID único para un comentario
function generateCommentId() {
  return "_" + Math.random().toString(36).substr(2, 9); // Generar un ID único
}

// Función para guardar un comentario en localStorage
function saveComment(comment) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments.push(comment); // Añadir nuevo comentario
  localStorage.setItem("comments", JSON.stringify(comments)); // Guardar comentarios en localStorage
}
