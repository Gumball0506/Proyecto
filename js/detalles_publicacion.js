document.addEventListener("DOMContentLoaded", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const postId = urlParams.get("id");

  let posts = JSON.parse(localStorage.getItem("posts")) || [];
  const post = posts.find((p) => p.id === postId);

  if (post) {
    displayPostDetails(post);
  } else {
    document.getElementById("postDetails").innerHTML =
      "<p>Publicación no encontrada</p>";
  }

  const commentForm = document.getElementById("commentForm");
  commentForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const commentInput = document.getElementById("commentInput");
    const text = commentInput.value.trim();
    if (text === "") return;

    const comment = {
      id: generateCommentId(),
      postId: postId,
      text: text,
      date: new Date().toLocaleString(),
    };

    saveComment(comment);
    commentInput.value = "";
    displayComment(comment);
  });
});

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

  loadComments(post.id);
}

function loadComments(postId) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.filter((comment) => comment.postId === postId);
  const commentsList = document.getElementById("commentsList");
  commentsList.innerHTML = "";
  comments.forEach((comment) => {
    displayComment(comment);
  });
}

function displayComment(comment) {
  const commentList = document.getElementById("commentsList");

  const commentDiv = document.createElement("div");
  commentDiv.classList.add("comment");
  commentDiv.dataset.id = comment.id;

  const commentText = document.createElement("p");
  commentText.textContent = comment.text;

  const commentDate = document.createElement("span");
  commentDate.classList.add("comment-date");
  commentDate.textContent = comment.date;

  const reactionsContainer = document.createElement("div");
  reactionsContainer.classList.add("reactions-container");

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

  // Verificar y marcar el botón correspondiente según la reacción del usuario/IP
  const userIP = getUserIP();
  if (comment.userReactions && comment.userReactions[userIP]) {
    switch (comment.userReactions[userIP]) {
      case "like":
        likeButton.classList.add("active");
        break;
      case "love":
        loveButton.classList.add("active");
        break;
      case "haha":
        hahaButton.classList.add("active");
        break;
      default:
        break;
    }
  }

  reactionsContainer.appendChild(likeButton);
  reactionsContainer.appendChild(loveButton);
  reactionsContainer.appendChild(hahaButton);

  const optionsButton = document.createElement("button");
  optionsButton.textContent = "⋮";
  optionsButton.classList.add("options-comment-button");
  optionsButton.addEventListener("click", function () {
    openOptionsModal(comment.id, comment.text);
  });

  commentDiv.appendChild(commentDate);
  commentDiv.appendChild(commentText);
  commentDiv.appendChild(optionsButton);
  commentDiv.appendChild(reactionsContainer);

  commentList.appendChild(commentDiv);
}

function createReactionButton(text, className, commentId, reactionType) {
  const button = document.createElement("button");
  button.textContent = text;
  button.classList.add(className);
  button.addEventListener("click", function () {
    reactToComment(commentId, reactionType);
  });
  return button;
}

function reactToComment(commentId, reactionType) {
  try {
    const userIP = getUserIP();
    let comments = JSON.parse(localStorage.getItem("comments")) || [];
    let commentUpdated = false;

    comments = comments.map((comment) => {
      if (comment.id === commentId) {
        if (!comment.userReactions) {
          comment.userReactions = {};
        }

        // Verificar si el usuario ya ha reaccionado y eliminar la reacción anterior si corresponde
        if (
          comment.userReactions[userIP] &&
          comment.userReactions[userIP] !== reactionType
        ) {
          delete comment.userReactions[userIP];
        }

        // Agregar o actualizar la nueva reacción del usuario
        comment.userReactions[userIP] = reactionType;
        commentUpdated = true; // Indicar que el comentario fue actualizado
      }
      return comment;
    });

    if (commentUpdated) {
      localStorage.setItem("comments", JSON.stringify(comments));
      updateReactionsUI(commentId);
      console.log(`Comment ${commentId} reacted with ${reactionType}`);
    } else {
      console.log(`No changes needed for comment ${commentId}`);
    }
  } catch (error) {
    console.error("Error reacting to comment:", error);
  }
}

function getUserIP() {
  return "192.168.0.1"; // Simulación de IP para demostración
}

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

function updateCommentUI(commentId, newText) {
  const commentDiv = document.querySelector(`.comment[data-id="${commentId}"]`);
  if (commentDiv) {
    const commentText = commentDiv.querySelector("p");
    if (commentText) {
      commentText.textContent = newText;
    }
  }
}

function deleteComment(commentId) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments = comments.filter((comment) => comment.id !== commentId);
  localStorage.setItem("comments", JSON.stringify(comments));
}

function generateCommentId() {
  return "_" + Math.random().toString(36).substr(2, 9); // Generar un ID único simple
}

function saveComment(comment) {
  let comments = JSON.parse(localStorage.getItem("comments")) || [];
  comments.push(comment);
  localStorage.setItem("comments", JSON.stringify(comments));
}
