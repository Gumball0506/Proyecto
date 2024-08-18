document.addEventListener("DOMContentLoaded", function () {
  cargarProyectosAntiguos(); // Llamar a la función para cargar los proyectos antiguos

  function cargarProyectosAntiguos() {
    fetch("/PHP/backend_publicaciones_antiguas.php?accion=obtener_proyectos")
      .then((response) => response.json())
      .then((proyectos) => {
        console.log("Proyectos cargados:", proyectos); // Depuración
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

      if (sessionActive) {
        let checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.value = proyecto.ID_Proyecto;
        proyectoDiv.appendChild(checkbox); // Añadir el checkbox al contenedor del proyecto
      }

      let imagenElement = document.createElement("img");
      imagenElement.src = "data:image/jpeg;base64," + proyecto.Foto;
      imagenElement.alt = proyecto.Titulo;
      imagenElement.style.cursor = "pointer"; // Indica que la imagen es clickeable

      // Agregar un event listener para redirigir al enlace cuando se haga clic en la imagen
      imagenElement.addEventListener("click", function () {
        window.open(
          "https://www.facebook.com/FIEIOficial?locale=es_LA",
          "_blank"
        );
      });

      let tituloElement = document.createElement("h3");
      tituloElement.textContent = proyecto.Titulo;

      proyectoDiv.appendChild(imagenElement);
      proyectoDiv.appendChild(tituloElement);

      proyectosContainer.appendChild(proyectoDiv);
    });
  }

  document.getElementById("eliminarBtn").addEventListener("click", function () {
    eliminarProyectosSeleccionados();
  });

  function eliminarProyectosSeleccionados() {
    let checkboxes = document.querySelectorAll(
      ".proyecto-card input[type='checkbox']:checked"
    );
    let ids = Array.from(checkboxes).map((checkbox) => checkbox.value);

    if (ids.length > 0) {
      if (
        confirm(
          "¿Estás seguro de que quieres eliminar los proyectos seleccionados?"
        )
      ) {
        fetch("/PHP/backend_publicaciones_antiguas.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            accion: "eliminar_proyectos",
            ids: ids.join(","), // Enviar IDs como una cadena separada por comas
          }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert("Proyectos eliminados correctamente.");
              cargarProyectosAntiguos(); // Recargar proyectos
            } else {
              alert(
                "Error al eliminar los proyectos: " +
                  (data.error || "Desconocido")
              );
            }
          })
          .catch((error) => {
            console.error("Error:", error);
            alert("Hubo un problema al eliminar los proyectos.");
          });
      }
    } else {
      alert("Por favor, selecciona al menos un proyecto para eliminar.");
    }
  }
});
