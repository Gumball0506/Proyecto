document.addEventListener("DOMContentLoaded", function () {
  // Variables para calificación por estrellas
  const stars = document.querySelectorAll(".star");
  const ratingMessage = document.getElementById("rating-message");
  const projectId = 123; // ID del proyecto (esto debe ser dinámico en una aplicación real)
  const userId = 456; // ID del usuario (esto debe ser dinámico en una aplicación real)

  function setRating(rating) {
    stars.forEach((star) => {
      star.style.color = star.dataset.value <= rating ? "gold" : "gray";
    });
    updateMessage(rating);
  }

  function updateMessage(rating) {
    let message = "";
    switch (rating) {
      case 5:
        message = "¡Fantástico proyecto!";
        break;
      case 4:
        message = "¡Muy bueno!";
        break;
      case 3:
        message = "Bueno, pero puede mejorar.";
        break;
      case 2:
        message = "Necesita mejoras.";
        break;
      case 1:
        message = "Poco recomendable.";
        break;
      default:
        message = "";
    }
    ratingMessage.textContent = message;
  }

  // Cargar la calificación al iniciar
  fetch(
    `/PHP/Backend_calificacion.php?accion=obtener_calificacion&ID_Proyecto=${projectId}&ID_Usuario=${userId}`
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        setRating(data.rating);
      }
    })
    .catch((error) => console.error("Error al cargar la calificación:", error));

  // Manejar clics en las estrellas
  stars.forEach((star) => {
    star.addEventListener("click", function () {
      const rating = parseInt(this.dataset.value);
      setRating(rating);

      // Guardar la calificación
      fetch(`/PHP/Backend_calificacion.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          accion: "guardar_calificacion",
          ID_Proyecto: projectId,
          ID_Usuario: userId,
          calificacion: rating,
        }).toString(),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            console.log("Calificación guardada correctamente.");
          }
        })
        .catch((error) =>
          console.error("Error al guardar la calificación:", error)
        );
    });
  });

  if (sessionActive) {
    // Mostrar el div con el id 'admin'
    document.getElementById("admin").style.display = "block";

    // Configura los MutationObservers para los elementos
    setupObservers();
  }

  // Manejar el envío del formulario de publicación de proyectos
  document
    .getElementById("postForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let formData = new FormData(this);
      formData.append("accion", "guardar_proyecto");

      fetch("/PHP/Backend_publicacion.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Proyecto publicado correctamente");
            cargarProyectos(); // Actualiza la lista de proyectos después de publicar
            this.reset(); // Reinicia el formulario después de enviarlo
          } else {
            alert(
              "Error al publicar el proyecto. Por favor, intenta de nuevo."
            );
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error al publicar el proyecto.");
        });
    });

  function setupObservers() {
    // Configura el MutationObserver para elementos específicos
    const observers = {
      vistas: new MutationObserver((mutations) => {
        mutations.forEach(() => {
          document.querySelectorAll(".vistas-container").forEach((element) => {
            element.style.display = "block";
          });
        });
      }),
      eliminar: new MutationObserver((mutations) => {
        mutations.forEach(() => {
          document
            .querySelectorAll(".eliminar-proyecto-btn")
            .forEach((element) => {
              element.style.display = "block";
            });
        });
      }),
      status: new MutationObserver((mutations) => {
        mutations.forEach(() => {
          document
            .querySelectorAll(".project-status-container")
            .forEach((element) => {
              element.style.display = "block";
            });
        });
      }),
    };

    for (const key in observers) {
      observers[key].observe(document.body, { childList: true, subtree: true });
    }
  }

  function cargarProyectos() {
    fetch("/PHP/Backend_publicacion.php?accion=obtener_proyectos")
      .then((response) => response.json())
      .then((proyectos) => mostrarProyectos(proyectos))
      .catch((error) => console.error("Error al cargar los proyectos:", error));
  }

  function mostrarProyectos(proyectos) {
    let proyectosContainer = document.getElementById("posts");
    proyectosContainer.innerHTML = "";

    proyectos.forEach((proyecto) => {
      let proyectoDiv = document.createElement("div");
      proyectoDiv.classList.add("proyecto");

      let tituloElement = document.createElement("h3");
      tituloElement.textContent = proyecto.Titulo;

      let descripcionElement = document.createElement("p");
      descripcionElement.textContent = proyecto.Descripcion;

      let imagenElement = document.createElement("img");
      imagenElement.src = "data:image/png;base64," + proyecto.Foto;
      imagenElement.alt = proyecto.Titulo;

      let vistasElement = document.createElement("div");
      vistasElement.classList.add("vistas-container");
      vistasElement.id = "vistas";

      let vistasTexto = document.createElement("span");
      vistasTexto.textContent = "";

      let vistasIcono = document.createElement("i");
      vistasIcono.classList.add("fas", "fa-eye");

      let vistasNumero = document.createElement("span");
      vistasNumero.textContent = proyecto.total_vistas;

      vistasElement.appendChild(vistasTexto);
      vistasElement.appendChild(vistasIcono);
      vistasElement.appendChild(vistasNumero);

      let enlaceRegistroBoton = document.createElement("a");
      enlaceRegistroBoton.textContent = "Registro";
      enlaceRegistroBoton.href = proyecto.url_registro;
      enlaceRegistroBoton.classList.add("enlace-registro-btn");
      enlaceRegistroBoton.addEventListener("click", function () {
        incrementarVistas(proyecto.ID_Proyecto);
      });

      let eliminarBoton = document.createElement("button");
      eliminarBoton.innerHTML = '<i class="fas fa-trash"></i>';
      eliminarBoton.classList.add("eliminar-proyecto-btn");
      eliminarBoton.id = "eliminar";
      eliminarBoton.addEventListener("click", function () {
        eliminarProyecto(proyecto.ID_Proyecto);
      });

      let projectStatusContainer = document.createElement("div");
      projectStatusContainer.classList.add("project-status-container");
      projectStatusContainer.id = "status";

      let projectStatusLabel = document.createElement("label");
      projectStatusLabel.textContent = "Estado del Proyecto:";

      let projectStatusInput = document.createElement("input");
      projectStatusInput.type = "checkbox";

      // Inicializa el estado del checkbox
      projectStatusInput.checked = proyecto.Estado ? "Actual" : "Antiguo";

      // Crea un elemento para mostrar el texto del estado
      let projectStatusText = document.createElement("span");
      projectStatusText.textContent = projectStatusInput.checked
        ? "Actual"
        : "Actual";

      // Función para actualizar el texto y el estado
      function actualizarEstado() {
        let nuevoEstado = projectStatusInput.checked ? "Antiguo" : "Antiguo";
        projectStatusText.textContent = nuevoEstado;
        cambiarEstadoProyecto(proyecto.ID_Proyecto, nuevoEstado);
      }

      // Añade el texto y el checkbox al contenedor
      projectStatusContainer.appendChild(projectStatusLabel);
      projectStatusContainer.appendChild(projectStatusInput);
      projectStatusContainer.appendChild(projectStatusText);

      // Actualiza el estado cuando cambia el checkbox
      projectStatusInput.addEventListener("change", function () {
        console.log("Estado cambiado"); // Agrega este log para verificar
        actualizarEstado();
      });

      proyectoDiv.appendChild(tituloElement);
      proyectoDiv.appendChild(descripcionElement);
      proyectoDiv.appendChild(enlaceRegistroBoton);
      proyectoDiv.appendChild(vistasElement);
      proyectoDiv.appendChild(imagenElement);
      proyectoDiv.appendChild(eliminarBoton);
      proyectoDiv.appendChild(projectStatusContainer);
      proyectosContainer.appendChild(proyectoDiv);
    });
  }

  function eliminarProyecto(proyecto_id) {
    let confirmacion = confirm(
      "¿Estás seguro de que deseas eliminar este proyecto?"
    );
    if (confirmacion) {
      fetch("/PHP/Backend_publicacion.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `accion=eliminar_proyecto&ID_Proyecto=${encodeURIComponent(
          proyecto_id
        )}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Proyecto eliminado correctamente");
            cargarProyectos();
          } else {
            alert(
              "Error al eliminar el proyecto. Por favor, intenta de nuevo."
            );
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  }

  function incrementarVistas(proyecto_id) {
    fetch("/PHP/Backend_publicacion.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `accion=incrementar_vistas&ID_Proyecto=${encodeURIComponent(
        proyecto_id
      )}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          obtenerVistasTotales(proyecto_id); // Actualizar las vistas después de incrementarlas
        } else {
          alert(
            "Error al incrementar las vistas. Por favor, intenta de nuevo."
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Un exito");
      });
  }

  function cambiarEstadoProyecto(proyecto_id, estadoActual) {
    let nuevoEstado = estadoActual ? "Actual" : "Antiguo";

    fetch("/PHP/Backend_publicacion.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `accion=cambiar_estado&ID_Proyecto=${encodeURIComponent(
        proyecto_id
      )}&Estado=${encodeURIComponent(nuevoEstado)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(
            "Estado del proyecto actualizado correctamente, se cambio a Antiguo"
          );
          cargarProyectos();
        } else {
          alert(
            "Error al actualizar el estado del proyecto. Por favor, intenta de nuevo."
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Un exito");
      });
  }

  cargarProyectos(); // Cargar proyectos al cargar la página
});
