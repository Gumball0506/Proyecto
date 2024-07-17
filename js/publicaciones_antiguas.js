document.addEventListener("DOMContentLoaded", function () {
  cargarProyectosAntiguos(); // Llamar a la función para cargar los proyectos antiguos

  function cargarProyectosAntiguos() {
    fetch("/PHP/backend_publicaciones_antiguas.php?accion=obtener_proyectos")
      .then((response) => response.json())
      .then((proyectos) => {
        mostrarProyectosAntiguos(proyectos); // Mostrar proyectos en la interfaz
      })
      .catch((error) => {
        console.error("Error al cargar los proyectos antiguos:", error);
      });
  }

  function mostrarProyectosAntiguos(proyectos) {
    let proyectosContainer = document.getElementById("posts");
    proyectosContainer.innerHTML = "";

    proyectos.forEach((proyecto) => {
      let proyectoDiv = document.createElement("div");
      proyectoDiv.classList.add("proyecto-card");

      let tituloElement = document.createElement("h3");
      tituloElement.textContent = proyecto.Titulo;

      let imagenElement = document.createElement("img");
      imagenElement.src = "data:image/jpeg;base64," + proyecto.Foto;
      imagenElement.alt = proyecto.Titulo;

      proyectoDiv.appendChild(tituloElement);
      proyectoDiv.appendChild(imagenElement);

      // Añadir evento de clic a cada tarjeta
      proyectoDiv.addEventListener("click", function () {
        window.open(
          "https://www.facebook.com/FIEIOficial?locale=es_LA",
          "_blank"
        );
      });

      proyectosContainer.appendChild(proyectoDiv);
    });
  }
});
