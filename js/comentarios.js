// Función para guardar un comentario en el almacenamiento local
function saveComment(comment) {
  try {
    // Obtener los comentarios existentes desde localStorage
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    // Añadir el nuevo comentario a la lista de comentarios
    comments.push(comment);
    // Guardar la lista actualizada de comentarios en localStorage
    localStorage.setItem("comments", JSON.stringify(comments));
    console.log("Comment saved:", comment);
  } catch (error) {
    // Manejar errores durante el guardado del comentario
    console.error("Error saving comment:", error);
  }
}

// Función para mostrar un comentario en la interfaz de usuario
function displayComment(comment, commentsList) {
  // Crear un contenedor para el comentario
  const commentDiv = document.createElement("div");
  commentDiv.classList.add("comment");
  commentDiv.dataset.id = comment.id;

  // Crear y añadir el texto del comentario
  const commentText = document.createElement("p");
  commentText.textContent = comment.text;
  commentDiv.appendChild(commentText);

  // Crear y añadir la fecha del comentario
  const commentDate = document.createElement("span");
  commentDate.classList.add("comment-date");
  commentDate.textContent = comment.date;
  commentDiv.appendChild(commentDate);

  // Crear y añadir el contenedor para las reacciones
  const reactionsContainer = document.createElement("div");
  reactionsContainer.classList.add("reactions-container");

  // Crear botones de reacción y añadirlos al contenedor de reacciones
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
  reactionsContainer.appendChild(likeButton);
  reactionsContainer.appendChild(loveButton);
  reactionsContainer.appendChild(hahaButton);

  // Crear y añadir el botón de opciones para el comentario
  const optionsButton = document.createElement("button");
  optionsButton.textContent = "⋮";
  optionsButton.classList.add("options-comment-button");
  optionsButton.addEventListener("click", function () {
    openOptionsModal(comment.id, comment.text);
  });
  commentDiv.appendChild(optionsButton);

  // Añadir el contenedor de reacciones al contenedor del comentario
  commentDiv.appendChild(reactionsContainer);

  // Añadir el contenedor del comentario a la lista de comentarios en la interfaz de usuario
  commentsList.appendChild(commentDiv);
}

// Función para crear un botón de reacción
function createReactionButton(text, className, commentId, reactionType) {
  const button = document.createElement("button");
  button.textContent = text;
  button.classList.add(className);
  button.addEventListener("click", function () {
    reactToComment(commentId, reactionType);
  });
  return button;
}

// Función para manejar las reacciones a un comentario
function reactToComment(commentId, reactionType) {
  try {
    // Obtener la dirección IP del usuario (simulada)
    const userIP = getUserIP();
    // Obtener los comentarios desde localStorage
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    // Actualizar las reacciones del comentario correspondiente
    comments = comments.map((comment) => {
      if (comment.id === commentId) {
        if (!comment.userReactions) {
          comment.userReactions = {};
        }
        if (comment.userReactions[userIP] === reactionType) {
          delete comment.userReactions[userIP];
        } else {
          comment.userReactions[userIP] = reactionType;
        }
      }
      return comment;
    });
    // Guardar la lista actualizada de comentarios en localStorage
    localStorage.setItem("comments", JSON.stringify(comments));
    // Actualizar la interfaz de usuario con las reacciones del comentario
    updateReactionsUI(commentId);
    console.log(`Comment ${commentId} reacted with ${reactionType}`);
  } catch (error) {
    // Manejar errores durante la reacción al comentario
    console.error("Error reacting to comment:", error);
  }
}

// Función para obtener la dirección IP del usuario (simulada para demostración)
function getUserIP() {
  return "192.168.0.1";
}

// Función para actualizar la interfaz de usuario con las reacciones a un comentario
function updateReactionsUI(commentId) {
  // Obtener el contenedor del comentario por su ID
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
  if (commentDiv) {
    const reactionsContainer = commentDiv.querySelector(".reactions-container");
    if (reactionsContainer) {
      // Obtener los comentarios desde localStorage
      let comments = JSON.parse(localStorage.getItem("comments")) || [];
      const comment = comments.find((c) => c.id === commentId);

      // Contar las reacciones del comentario
      const reactionCounts = { like: 0, love: 0, haha: 0 };
      Object.values(comment.userReactions || {}).forEach((reaction) => {
        reactionCounts[reaction]++;
      });

      // Limpiar el contenedor de reacciones
      reactionsContainer.innerHTML = "";

      // Crear botones de reacción actualizados con el conteo de reacciones
      Object.keys(reactionCounts).forEach((reactionType) => {
        const count = reactionCounts[reactionType];
        const reactionButton = createReactionButton(
          getEmojiForReactionType(reactionType),
          `${reactionType}-button`,
          commentId,
          reactionType
        );
        reactionsContainer.appendChild(reactionButton);
        if (count > 0) {
          reactionsContainer.insertAdjacentHTML(
            "beforeend",
            `<span>${count}</span>`
          );
        }
      });
    }
  }
}

// Evento al cargar el DOM, para mostrar las publicaciones y comentarios almacenados
document.addEventListener("DOMContentLoaded", function () {
  try {
    // Obtener y mostrar las publicaciones desde localStorage
    let posts = JSON.parse(localStorage.getItem("posts")) || [];
    posts.reverse().forEach(displayPost);
    console.log("Posts loaded:", posts);
  } catch (error) {
    // Manejar errores durante la carga de publicaciones
    console.error("Error loading posts:", error);
  }

  try {
    // Obtener y mostrar los comentarios desde localStorage
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    comments.forEach(function (comment) {
      const postDiv = document.querySelector(
        `.post[data-id="${comment.postId}"]`
      );
      if (postDiv) {
        const commentsList = postDiv.querySelector(".comments-list");
        if (commentsList) {
          displayComment(comment, commentsList);
        }
      }
    });
    console.log("Comments loaded:", comments);
  } catch (error) {
    // Manejar errores durante la carga de comentarios
    console.error("Error loading comments:", error);
  }
});

// Función para abrir la ventana modal de opciones para un comentario
function openOptionsModal(commentId, commentText) {
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
  if (commentDiv) {
    document.getElementById("edit-comment-text").value = commentText;
    document
      .getElementById("edit-comment-button")
      .addEventListener("click", function () {
        editComment(commentId);
      });
    document
      .getElementById("delete-comment-button")
      .addEventListener("click", function () {
        deleteComment(commentId);
      });
    document.getElementById("options-modal").style.display = "block";
  }
}

// Función para editar un comentario
function editComment(commentId) {
  try {
    const editedText = document.getElementById("edit-comment-text").value;
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    comments = comments.map((comment) => {
      if (comment.id === commentId) {
        comment.text = editedText;
      }
      return comment;
    });
    localStorage.setItem("comments", JSON.stringify(comments));
    closeOptionsModal();
    updateCommentTextUI(commentId, editedText);
    console.log(`Comment ${commentId} edited:`, editedText);
  } catch (error) {
    console.error("Error editing comment:", error);
  }
}

// Función para eliminar un comentario
function deleteComment(commentId) {
  try {
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    comments = comments.filter((comment) => comment.id !== commentId);
    localStorage.setItem("comments", JSON.stringify(comments));

    const commentDiv = document.querySelector(
      `.comment[data-id="${commentId}"]`
    );
    if (commentDiv) {
      commentDiv.remove();
    }

    closeOptionsModal();
    console.log(`Comment ${commentId} deleted`);
  } catch (error) {
    console.error("Error deleting comment:", error);
  }
}

// Función para cerrar la ventana modal de opciones para un comentario
function closeOptionsModal() {
  document.getElementById("options-modal").style.display = "none";
  document
    .getElementById("edit-comment-button")
    .removeEventListener("click", editComment);
  document
    .getElementById("delete-comment-button")
    .removeEventListener("click", deleteComment);
}

// Función para actualizar la interfaz de usuario con el texto editado de un comentario
function updateCommentTextUI(commentId, editedText) {
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
  if (commentDiv) {
    const commentText = commentDiv.querySelector("p");
    if (commentText) {
      commentText.textContent = editedText;
    }
  }
}

// Función para obtener el emoji correspondiente al tipo de reacción
function getEmojiForReactionType(reactionType) {
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
    editComment(commentId, editInput.value);
    modal.remove();
  });

  const deleteButton = document.createElement("button");
  deleteButton.textContent = "Eliminar";
  deleteButton.classList.add("delete-button");
  deleteButton.addEventListener("click", function () {
    deleteComment(commentId);
    document.querySelector(`.comment[data-id="${commentId}"]`).remove();
    modal.remove();
  });

  modalContent.appendChild(editInput);
  modalContent.appendChild(saveButton);
  modalContent.appendChild(deleteButton);
  modal.appendChild(modalContent);

  document.body.appendChild(modal);
}

// Función para editar un comentario
function editComment(commentId, newText) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.map((comment) => {
    if (comment.id === commentId) {
      comment.text = newText;
    }
    return comment;
  });
  localStorage.setItem("comments", JSON.stringify(comments));
  updateCommentUI(commentId, newText);
}

// Función para actualizar la interfaz de usuario del comentario editado
function updateCommentUI(commentId, newText) {
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
  if (commentDiv) {
    const commentText = commentDiv.querySelector("p");
    if (commentText) {
      commentText.textContent = newText;
    }
  }
}

// Función para eliminar un comentario
function deleteComment(commentId) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.filter((comment) => comment.id !== commentId);
  localStorage.setItem("comments", JSON.stringify(comments));
}

// Función para guardar un comentario
function saveComment(comment) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments.push(comment);
  localStorage.setItem("comments", JSON.stringify(comments));
}
