document.addEventListener("DOMContentLoaded", function () {
  // Obtener el ID de la publicación desde los parámetros de la URL
  const urlParams = new URLSearchParams(window.location.search);
  const postId = urlParams.get("id");

  // Obtener las publicaciones almacenadas en el localStorage o inicializar un array vacío si no hay
  let posts = JSON.parse(localStorage.getItem("posts")) || [];

  // Buscar la publicación correspondiente por su ID
  const post = posts.find((p) => p.id === postId);

  // Si se encuentra la publicación, mostrar sus detalles
  if (post) {
    displayPostDetails(post);
  } else {
    // Si no se encuentra la publicación, mostrar un mensaje de error en el div #postDetails
    document.getElementById("postDetails").innerHTML =
      "<p>Publicación no encontrada</p>";
  }
});

function displayPostDetails(post) {
  // Obtener el div donde se mostrarán los detalles de la publicación
  const postDetailsDiv = document.getElementById("postDetails");

  // Crear elementos HTML para mostrar los detalles de la publicación
  const postTitle = document.createElement("h2");
  postTitle.textContent = post.title;

  const postImage = document.createElement("img");
  postImage.src = post.image;
  postImage.alt = post.title; // Agregar atributo alt para accesibilidad

  const postDescription = document.createElement("p");
  postDescription.textContent = post.description;

  const postDate = document.createElement("p");
  postDate.textContent = `Fecha de publicación: ${post.date}`;

  const postLocation = document.createElement("p");
  postLocation.textContent = `Ubicación: ${post.location}`;

  const postContact = document.createElement("p");
  postContact.textContent = `Contacto: ${post.contact}`;

  // Agregar los elementos creados al div #postDetails para mostrar los detalles completos de la publicación
  postDetailsDiv.appendChild(postTitle);
  postDetailsDiv.appendChild(postImage);
  postDetailsDiv.appendChild(postDescription);
  postDetailsDiv.appendChild(postDate);
  postDetailsDiv.appendChild(postLocation);
  postDetailsDiv.appendChild(postContact);
}
