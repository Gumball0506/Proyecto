// Función para guardar un comentario en el almacenamiento local
function saveComment(comment) {
  try {
    // Obtener todos los comentarios almacenados o inicializar un array vacío si no hay comentarios
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    // Agregar el nuevo comentario al array de comentarios
    comments.push(comment);
    // Guardar el array actualizado de comentarios en el almacenamiento local
    localStorage.setItem("comments", JSON.stringify(comments));
    // Registrar en consola que el comentario se ha guardado exitosamente
    console.log("Comment saved:", comment);
  } catch (error) {
    // Manejar cualquier error que pueda ocurrir al guardar el comentario
    console.error("Error saving comment:", error);
  }
}

// Función para mostrar un comentario en la interfaz de usuario
function displayComment(comment, commentsList) {
  // Crear un nuevo elemento div para el comentario
  const commentDiv = document.createElement("div");
  commentDiv.classList.add("comment"); // Añadir la clase 'comment' al div

  // Establecer el atributo de datos 'id' para identificar el comentario
  commentDiv.dataset.id = comment.id;

  // Crear un párrafo para mostrar el texto del comentario
  const commentText = document.createElement("p");
  commentText.textContent = comment.text;

  // Crear un span para mostrar la fecha del comentario
  const commentDate = document.createElement("span");
  commentDate.classList.add("comment-date"); // Añadir la clase 'comment-date' al span
  commentDate.textContent = comment.date;

  // Crear un div para contener las reacciones a este comentario
  const reactionsContainer = document.createElement("div");
  reactionsContainer.classList.add("reactions-container"); // Añadir la clase 'reactions-container' al div

  // Crear botones de reacción para el comentario
  const likeButton = createReactionButton(
    "👍",
    "like-button",
    comment.id,
    "like"
  );
  const loveButton = createReactionButton(
    "❤️",
    "love-button",
    comment.id,
    "love"
  );
  const hahaButton = createReactionButton(
    "😂",
    "haha-button",
    comment.id,
    "haha"
  );

  // Agregar los botones de reacción al contenedor de reacciones
  reactionsContainer.appendChild(likeButton);
  reactionsContainer.appendChild(loveButton);
  reactionsContainer.appendChild(hahaButton);

  // Crear un botón de opciones para editar/eliminar el comentario
  const optionsButton = document.createElement("button");
  optionsButton.textContent = "⋮"; // Icono para indicar opciones
  optionsButton.classList.add("options-comment-button"); // Añadir la clase 'options-comment-button' al botón
  optionsButton.addEventListener("click", function () {
    openOptionsModal(comment.id, comment.text); // Llamar a la función para abrir la ventana modal de opciones
  });

  // Agregar elementos creados al div principal del comentario
  commentDiv.appendChild(commentDate);
  commentDiv.appendChild(commentText);
  commentDiv.appendChild(optionsButton); // Agregar el botón de opciones al comentario
  commentDiv.appendChild(reactionsContainer); // Agregar el contenedor de reacciones al comentario

  // Agregar el div del comentario a la lista de comentarios proporcionada
  commentsList.appendChild(commentDiv);
}

// Función para crear un botón de reacción
function createReactionButton(text, className, commentId, reactionType) {
  // Crear un nuevo elemento botón
  const button = document.createElement("button");
  button.textContent = text; // Establecer el texto del botón
  button.classList.add(className); // Añadir la clase especificada al botón

  // Añadir un evento click al botón para reaccionar al comentario
  button.addEventListener("click", function () {
    reactToComment(commentId, reactionType); // Llamar a la función para reaccionar al comentario
  });

  // Devolver el botón creado
  return button;
}

// Función para reaccionar a un comentario
function reactToComment(commentId, reactionType) {
  try {
    // Obtener la dirección IP del usuario (simulada para demostración)
    const userIP = getUserIP();

    // Obtener todos los comentarios almacenados o inicializar un array vacío si no hay comentarios
    let comments = JSON.parse(localStorage.getItem("comments")) || [];

    // Mapear sobre los comentarios para encontrar el comentario correspondiente al ID dado
    comments = comments.map((comment) => {
      if (comment.id === commentId) {
        // Verificar si el comentario tiene un objeto de reacciones de usuario
        if (!comment.userReactions) {
          comment.userReactions = {};
        }

        // Verificar si el usuario ya ha reaccionado con este tipo de reacción
        if (comment.userReactions[userIP] === reactionType) {
          // Si el usuario ya ha reaccionado con este tipo, eliminar la reacción
          delete comment.userReactions[userIP];
        } else {
          // Establecer la nueva reacción del usuario
          comment.userReactions[userIP] = reactionType;
        }
      }
      return comment; // Devolver el comentario actualizado
    });

    // Actualizar el almacenamiento local con los comentarios actualizados
    localStorage.setItem("comments", JSON.stringify(comments));

    // Actualizar la interfaz de usuario para mostrar las reacciones actualizadas
    updateReactionsUI(commentId);

    // Registrar en consola la acción realizada
    console.log(`Comment ${commentId} reacted with ${reactionType}`);
  } catch (error) {
    // Manejar cualquier error que pueda ocurrir al reaccionar al comentario
    console.error("Error reacting to comment:", error);
  }
}

// Función para obtener la dirección IP del usuario (simulada para demostración)
function getUserIP() {
  // Aquí podría implementarse la lógica real para obtener la IP del usuario
  // Para este ejemplo, se devuelve una IP simulada
  return "192.168.0.1"; // Valor de IP simulado para demostración
}

// Función para actualizar la interfaz de usuario con las reacciones a un comentario
function updateReactionsUI(commentId) {
  // Encontrar el div del comentario correspondiente usando su ID
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);

  // Si se encuentra el div del comentario
  if (commentDiv) {
    // Encontrar el contenedor de reacciones dentro del div del comentario
    const reactionsContainer = commentDiv.querySelector(".reactions-container");

    // Si se encuentra el contenedor de reacciones
    if (reactionsContainer) {
      // Obtener todos los comentarios almacenados o inicializar un array vacío si no hay comentarios
      let comments = JSON.parse(localStorage.getItem("comments")) || [];

      // Encontrar el comentario específico utilizando su ID
      const comment = comments.find((c) => c.id === commentId);

      // Objeto para contar las reacciones por tipo
      const reactionCounts = {
        like: 0,
        love: 0,
        haha: 0,
      };

      // Contar las reacciones por tipo basándose en las reacciones de usuario en el comentario
      Object.values(comment.userReactions || {}).forEach((reaction) => {
        reactionCounts[reaction]++;
      });

      // Limpiar el contenedor actual de reacciones
      reactionsContainer.innerHTML = "";

      // Mostrar los botones de reacción con el recuento actualizado
      Object.keys(reactionCounts).forEach((reactionType) => {
        const count = reactionCounts[reactionType];
        const reactionButton = createReactionButton(
          getEmojiForReactionType(reactionType), // Obtener el emoji correspondiente al tipo de reacción
          `${reactionType}-button`,
          commentId,
          reactionType
        );
        reactionsContainer.appendChild(reactionButton); // Agregar el botón de reacción al contenedor de reacciones
        if (count > 0) {
          reactionsContainer.insertAdjacentHTML(
            "beforeend",
            `<span>${count}</span>` // Mostrar el contador de reacciones
          );
        }
      });
    }
  }
}

// Evento al cargar el DOM, para mostrar las publicaciones y comentarios almacenados
document.addEventListener("DOMContentLoaded", function () {
  try {
    // Obtener todas las publicaciones almacenadas o inicializar un array vacío si no hay publicaciones
    let posts = JSON.parse(localStorage.getItem("posts")) || [];

    // Invertir el orden de las publicaciones y mostrar cada una
    posts.reverse().forEach(displayPost);

    // Registrar en consola que las publicaciones se han cargado correctamente
    console.log("Posts loaded:", posts);
  } catch (error) {
    // Manejar cualquier error que pueda ocurrir al cargar las publicaciones
    console.error("Error loading posts:", error);
  }

  try {
    // Obtener todos los comentarios almacenados o inicializar un array vacío si no hay comentarios
    let comments = JSON.parse(localStorage.getItem("comments")) || [];

    // Mostrar cada comentario dentro de su publicación correspondiente
    comments.forEach(function (comment) {
      // Encontrar el div de la publicación correspondiente usando su ID de publicación
      const postDiv = document.querySelector(
        `.post[data-id="${comment.postId}"]`
      );

      // Si se encuentra el div de la publicación
      if (postDiv) {
        // Encontrar el contenedor de comentarios dentro del div de la publicación
        const commentsList = postDiv.querySelector(".comments-list");

        // Si se encuentra el contenedor de comentarios
        if (commentsList) {
          // Mostrar el comentario en la interfaz de usuario
          displayComment(comment, commentsList);
        }
      }
    });

    // Registrar en consola que los comentarios se han cargado correctamente
    console.log("Comments loaded:", comments);
  } catch (error) {
    // Manejar cualquier error que pueda ocurrir al cargar los comentarios
    console.error("Error loading comments:", error);
  }
});

// Función para abrir la ventana modal de opciones para un comentario
function openOptionsModal(commentId, commentText) {
  // Encontrar el div del comentario correspondiente usando su ID
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);

  // Si se encuentra el div del comentario
  if (commentDiv) {
    // Mostrar el texto del comentario en la ventana modal de opciones
    document.getElementById("edit-comment-text").value = commentText;

    // Establecer el evento click para el botón de editar comentario
    document
      .getElementById("edit-comment-button")
      .addEventListener("click", function () {
        editComment(commentId); // Llamar a la función para editar el comentario
      });

    // Establecer el evento click para el botón de eliminar comentario
    document
      .getElementById("delete-comment-button")
      .addEventListener("click", function () {
        deleteComment(commentId); // Llamar a la función para eliminar el comentario
      });

    // Mostrar la ventana modal de opciones
    document.getElementById("options-modal").style.display = "block";
  }
}

// Función para editar un comentario
function editComment(commentId) {
  try {
    // Obtener el texto del comentario editado desde el formulario modal
    const editedText = document.getElementById("edit-comment-text").value;

    // Obtener todos los comentarios almacenados o inicializar un array vacío si no hay comentarios
    let comments = JSON.parse(localStorage.getItem("comments")) || [];

    // Mapear sobre los comentarios para encontrar el comentario correspondiente al ID dado
    comments = comments.map((comment) => {
      if (comment.id === commentId) {
        comment.text = editedText; // Establecer el texto editado para el comentario
      }
      return comment; // Devolver el comentario actualizado
    });

    // Actualizar el almacenamiento local con los comentarios actualizados
    localStorage.setItem("comments", JSON.stringify(comments));

    // Cerrar la ventana modal de opciones después de editar el comentario
    closeOptionsModal();

    // Actualizar la interfaz de usuario para mostrar el texto del comentario editado
    updateCommentTextUI(commentId, editedText);

    // Registrar en consola que el comentario se ha editado correctamente
    console.log(`Comment ${commentId} edited:`, editedText);
  } catch (error) {
    // Manejar cualquier error que pueda ocurrir al editar el comentario
    console.error("Error editing comment:", error);
  }
}

// Función para eliminar un comentario
function deleteComment(commentId) {
  try {
    // Obtener todos los comentarios almacenados o inicializar un array vacío si no hay comentarios
    let comments = JSON.parse(localStorage.getItem("comments")) || [];

    // Filtrar los comentarios para excluir el comentario correspondiente al ID dado
    comments = comments.filter((comment) => comment.id !== commentId);

    // Actualizar el almacenamiento local con los comentarios actualizados
    localStorage.setItem("comments", JSON.stringify(comments));

    // Encontrar el div del comentario correspondiente usando su ID
    const commentDiv = document.querySelector(
      `.comment[data-id="${commentId}"]`
    );

    // Si se encuentra el div del comentario, eliminarlo de la interfaz de usuario
    if (commentDiv) {
      commentDiv.remove();
    }

    // Cerrar la ventana modal de opciones después de eliminar el comentario
    closeOptionsModal();

    // Registrar en consola que el comentario se ha eliminado correctamente
    console.log(`Comment ${commentId} deleted`);
  } catch (error) {
    // Manejar cualquier error que pueda ocurrir al eliminar el comentario
    console.error("Error deleting comment:", error);
  }
}

// Función para cerrar la ventana modal de opciones para un comentario
function closeOptionsModal() {
  // Ocultar la ventana modal de opciones
  document.getElementById("options-modal").style.display = "none";

  // Limpiar el evento click para el botón de editar comentario
  document
    .getElementById("edit-comment-button")
    .removeEventListener("click", editComment);

  // Limpiar el evento click para el botón de eliminar comentario
  document
    .getElementById("delete-comment-button")
    .removeEventListener("click", deleteComment);
}

// Función para actualizar la interfaz de usuario con el texto editado de un comentario
function updateCommentTextUI(commentId, editedText) {
  // Encontrar el div del comentario correspondiente usando su ID
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);

  // Si se encuentra el div del comentario
  if (commentDiv) {
    // Encontrar el párrafo dentro del div del comentario para actualizar el texto
    const commentText = commentDiv.querySelector("p");

    // Si se encuentra el párrafo, establecer el texto editado
    if (commentText) {
      commentText.textContent = editedText;
    }
  }
}

// Función para obtener el emoji correspondiente al tipo de reacción
function getEmojiForReactionType(reactionType) {
  // Devolver el emoji correspondiente según el tipo de reacción
  switch (reactionType) {
    case "like":
      return "👍";
    case "love":
      return "❤️";
    case "haha":
      return "😂";
    default:
      return "";
  }
}
// Función para abrir la ventana de opciones de comentario
function openOptionsModal(commentId, commentText) {
  // Crear un nuevo elemento div para la ventana modal
  const modal = document.createElement("div");
  modal.classList.add("modal"); // Añadir la clase 'modal' al div

  // Crear un div para el contenido de la ventana modal
  const modalContent = document.createElement("div");
  modalContent.classList.add("modal-content"); // Añadir la clase 'modal-content' al div

  // Crear un input para editar el texto del comentario
  const editInput = document.createElement("input");
  editInput.type = "text";
  editInput.value = commentText; // Establecer el valor del input como el texto actual del comentario
  editInput.classList.add("edit-input"); // Añadir la clase 'edit-input' al input

  // Crear un botón para guardar los cambios editados
  const saveButton = document.createElement("button");
  saveButton.textContent = "Guardar";
  saveButton.classList.add("save-button"); // Añadir la clase 'save-button' al botón
  saveButton.addEventListener("click", function () {
    editComment(commentId, editInput.value); // Llamar a la función para editar el comentario
    modal.remove(); // Cerrar la ventana de opciones
  });

  // Crear un botón para eliminar el comentario
  const deleteButton = document.createElement("button");
  deleteButton.textContent = "Eliminar";
  deleteButton.classList.add("delete-button"); // Añadir la clase 'delete-button' al botón
  deleteButton.addEventListener("click", function () {
    deleteComment(commentId); // Llamar a la función para eliminar el comentario
    document.querySelector(`.comment[data-id="${commentId}"]`).remove(); // Eliminar el comentario de la interfaz de usuario
    modal.remove(); // Cerrar la ventana de opciones
  });

  // Agregar los elementos creados al div de contenido modal
  modalContent.appendChild(editInput);
  modalContent.appendChild(saveButton);
  modalContent.appendChild(deleteButton);

  // Agregar el div de contenido modal al div de la ventana modal
  modal.appendChild(modalContent);

  // Agregar la ventana modal al cuerpo del documento
  document.body.appendChild(modal); // Añadir la ventana de opciones al cuerpo del documento
}

// Función para eliminar un comentario por su ID
function deleteComment(commentId) {
  try {
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    // Filtrar los comentarios para eliminar el comentario correspondiente al ID dado
    comments = comments.filter((comment) => comment.id !== commentId);
    localStorage.setItem("comments", JSON.stringify(comments)); // Actualizar el almacenamiento local
    console.log(`Comment ${commentId} deleted`); // Registro en consola
  } catch (error) {
    console.error("Error deleting comment:", error); // Manejar errores
  }
}

// Función para editar un comentario por su ID
function editComment(commentId, newText) {
  try {
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    // Mapear sobre los comentarios para encontrar el comentario correspondiente al ID dado
    comments = comments.map((comment) => {
      if (comment.id === commentId) {
        comment.text = newText; // Actualizar el texto del comentario
      }
      return comment;
    });
    localStorage.setItem("comments", JSON.stringify(comments)); // Actualizar el almacenamiento local
    // Actualizar el texto del comentario en la interfaz de usuario
    document.querySelector(`.comment[data-id="${commentId}"] p`).textContent =
      newText;
    console.log(`Comment ${commentId} edited`); // Registro en consola
  } catch (error) {
    console.error("Error editing comment:", error); // Manejar errores
  }
}

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

// Función para mostrar una publicación en la interfaz de usuario
function displayPost(post) {
  // Crear un nuevo div para la publicación
  const postDiv = document.createElement("div");
  postDiv.classList.add("post"); // Añadir la clase 'post' al div
  postDiv.dataset.id = post.id; // Establecer el ID de la publicación como atributo de datos
  postDiv.dataset.date = post.date; // Añadir fecha de la publicación como atributo de datos

  // Crear un elemento h2 para el título de la publicación
  const postTitle = document.createElement("h2");
  postTitle.textContent = post.title;

  // Crear una imagen para la publicación
  const postImage = document.createElement("img");
  postImage.src = post.image;

  // Crear un párrafo para la descripción de la publicación
  const postDescription = document.createElement("p");
  postDescription.textContent = post.description;

  // Crear un contenedor para los botones de acción (ver y eliminar)
  const buttonsContainer = document.createElement("div");
  buttonsContainer.classList.add("buttons-container"); // Añadir la clase 'buttons-container' al div

  // Crear el botón para ver detalles de la publicación
  const viewButton = document.createElement("button");
  const viewIcon = document.createElement("i");
  viewIcon.classList.add("fas", "fa-eye"); // Añadir clases para el icono de "ver"
  viewButton.appendChild(viewIcon); // Añadir el icono al botón
  viewButton.addEventListener("click", function () {
    window.location.href = `/html/detalles_publicacion.html?id=${post.id}`; // Redirigir a los detalles de la publicación
  });

  // Crear el botón para eliminar la publicación
  const deleteButton = document.createElement("button");
  const deleteIcon = document.createElement("i");
  deleteIcon.classList.add("fas", "fa-trash"); // Añadir clases para el icono de "eliminar"
  deleteButton.appendChild(deleteIcon); // Añadir el icono al botón
  deleteButton.addEventListener("click", function () {
    deletePost(post.id); // Llamar a la función para eliminar la publicación
    postDiv.remove(); // Eliminar la publicación de la interfaz de usuario
  });

  // Agregar botones creados al contenedor de botones
  buttonsContainer.appendChild(viewButton);
  buttonsContainer.appendChild(deleteButton);

  // Crear un contenedor para los comentarios de la publicación
  const commentsContainer = document.createElement("div");
  commentsContainer.classList.add("comments-container"); // Añadir la clase 'comments-container' al div

  // Crear un div para listar los comentarios
  const commentsList = document.createElement("div");
  commentsList.classList.add("comments-list"); // Añadir la clase 'comments-list' al div

  // Crear un formulario para añadir comentarios
  const commentForm = document.createElement("form");
  commentForm.classList.add("comment-form"); // Añadir la clase 'comment-form' al formulario
  const commentInput = document.createElement("input");
  commentInput.type = "text";
  commentInput.placeholder = "Escribe un comentario..."; // Placeholder para el input de comentario
  const commentButton = document.createElement("button");
  commentButton.type = "submit";
  commentButton.textContent = "Comentar"; // Texto del botón de comentario

  // Agregar elementos al formulario de comentario
  commentForm.appendChild(commentInput);
  commentForm.appendChild(commentButton);

  // Agregar elementos al contenedor de comentarios
  commentsContainer.appendChild(commentsList);
  commentsContainer.appendChild(commentForm);

  // Manejar el evento submit del formulario de comentario
  commentForm.addEventListener("submit", function (e) {
    e.preventDefault();
    if (commentInput.value.trim() !== "") {
      const comment = {
        id: Date.now().toString(), // Generar un ID único para el comentario
        postId: post.id,
        text: commentInput.value.trim(),
        date: new Date().toLocaleString(),
      };
      saveComment(comment); // Guardar el comentario
      displayComment(comment, commentsList); // Mostrar el comentario en la interfaz de usuario
      commentInput.value = ""; // Limpiar el input de comentario
    }
  });

  // Agregar todos los elementos de la publicación al div de la publicación
  postDiv.appendChild(postTitle);
  postDiv.appendChild(postImage);
  postDiv.appendChild(postDescription);
  postDiv.appendChild(buttonsContainer);
  postDiv.appendChild(commentsContainer);

  // Encontrar el contenedor de publicaciones en el documento
  const postsContainer = document.querySelector(".posts-container");
  let inserted = false;

  // Insertar la publicación en orden cronológico inverso por fecha
  for (let i = 0; i < postsContainer.children.length; i++) {
    const currentPost = postsContainer.children[i];
    const currentPostDate = new Date(currentPost.dataset.date);
    const newPostDate = new Date(post.date);
    if (newPostDate > currentPostDate) {
      postsContainer.insertBefore(postDiv, currentPost); // Insertar antes de la publicación actual si es más reciente
      inserted = true;
      break;
    }
  }
  if (!inserted) {
    postsContainer.appendChild(postDiv); // Si no se insertó, añadir al final del contenedor de publicaciones
  }
}

// Función para eliminar una publicación por su ID
function deletePost(postId) {
  try {
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    // Filtrar las publicaciones para eliminar la que coincide con el ID dado
    posts = posts.filter((post) => post.id !== postId);
    localStorage.setItem("posts", JSON.stringify(posts)); // Actualizar el almacenamiento local
    console.log(`Post ${postId} deleted`); // Registro en consola
  } catch (error) {
    console.error("Error deleting post:", error); // Manejar errores
  }
}
