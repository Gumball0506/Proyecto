document.addEventListener("DOMContentLoaded", function () {
  if (sessionActive) {
    // Mostrar el div con el id 'admin'
    document.getElementById("admin").style.display = "block";

    // Configura el MutationObserver para los contenedores específicos
    const observerV = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        const vistasElements =
          document.getElementsByClassName("vistas-container");
        for (let i = 0; i < vistasElements.length; i++) {
          vistasElements[i].style.display = "block";
        }
        observerV.disconnect();
      });
    });

    const observerE = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        const eliminarElements = document.getElementsByClassName(
          "eliminar-proyecto-btn"
        );
        for (let i = 0; i < eliminarElements.length; i++) {
          eliminarElements[i].style.display = "block";
        }
        observerE.disconnect();
      });
    });

    const observerS = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        const statusElements = document.getElementsByClassName(
          "project-status-container"
        );
        for (let i = 0; i < statusElements.length; i++) {
          statusElements[i].style.display = "block";
        }
        observerS.disconnect();
      });
    });

    // Empieza a observar el body por la adición de nuevos nodos
    observerV.observe(document.body, { childList: true, subtree: true });
    observerE.observe(document.body, { childList: true, subtree: true });
    observerS.observe(document.body, { childList: true, subtree: true });
  }

  document
    .getElementById("postForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let titulo = document.getElementById("title").value;
      let descripcion = document.getElementById("description").value;
      let foto = document.getElementById("image").files[0];
      let projectStatus = document.getElementById("projectStatus").checked
        ? "1"
        : "2";
      let formData = new FormData();
      formData.append("accion", "guardar_proyecto");
      formData.append("titulo", titulo);
      formData.append("descripcion", descripcion);
      formData.append("eventDate", document.getElementById("eventDate").value);
      formData.append("foto", foto);
      formData.append("projectStatus", projectStatus);
      formData.append(
        "url_registro",
        document.getElementById("registrationLink").value
      );

      fetch("/PHP/Backend_publicacion.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Proyecto publicado correctamente");
            cargarProyectos(); // Actualiza la lista de proyectos después de publicar
            document.getElementById("postForm").reset(); // Reinicia el formulario después de enviarlo
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

  function cargarProyectos() {
    fetch("/PHP/Backend_publicacion.php?accion=obtener_proyectos")
      .then((response) => response.json())
      .then((proyectos) => {
        mostrarProyectos(proyectos); // Mostrar proyectos en la interfaz
      })
      .catch((error) => {
        console.error("Error al cargar los proyectos:", error);
      });
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

      projectStatusInput.checked = proyecto.Estado ? "Actual" : "Antiguo";

      let projectStatusText = document.createElement("span");
      projectStatusText.textContent = projectStatusInput.checked
        ? "Actual"
        : "Antiguo";

      function actualizarEstado() {
        let nuevoEstado = projectStatusInput.checked ? "Antiguo" : "Antiguo";
        projectStatusText.textContent = nuevoEstado;
        cambiarEstadoProyecto(proyecto.ID_Proyecto, nuevoEstado);
      }

      projectStatusContainer.appendChild(projectStatusLabel);
      projectStatusContainer.appendChild(projectStatusInput);
      projectStatusContainer.appendChild(projectStatusText);

      projectStatusInput.addEventListener("change", function () {
        console.log("Estado cambiado");
        actualizarEstado();
      });

      // Añadir calificación
      let calificacionContainer = document.createElement("div");
      calificacionContainer.classList.add("calificacion-container");

      let calificacionLabel = document.createElement("label");
      calificacionLabel.textContent = "Calificación:";

      let calificacionSelect = document.createElement("select");
      calificacionSelect.id = `calificacion_${proyecto.ID_Proyecto}`;
      for (let i = 1; i <= 5; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        calificacionSelect.appendChild(option);
      }

      let calificacionMensaje = document.createElement("input");
      calificacionMensaje.type = "text";
      calificacionMensaje.id = `mensaje_${proyecto.ID_Proyecto}`;
      calificacionMensaje.placeholder = "Escribe un mensaje (opcional)";

      let calificarBtn = document.createElement("button");
      calificarBtn.textContent = "Calificar";
      calificarBtn.addEventListener("click", function () {
        calificarProyecto(proyecto.ID_Proyecto);
      });

      calificacionContainer.appendChild(calificacionLabel);
      calificacionContainer.appendChild(calificacionSelect);
      calificacionContainer.appendChild(calificacionMensaje);
      calificacionContainer.appendChild(calificarBtn);

      proyectoDiv.appendChild(tituloElement);
      proyectoDiv.appendChild(descripcionElement);
      proyectoDiv.appendChild(enlaceRegistroBoton);
      proyectoDiv.appendChild(vistasElement);
      proyectoDiv.appendChild(imagenElement);
      proyectoDiv.appendChild(eliminarBoton);
      proyectoDiv.appendChild(projectStatusContainer);
      proyectoDiv.appendChild(calificacionContainer);
      proyectosContainer.appendChild(proyectoDiv);
    });
  }

  function obtenerMensaje(calificacion) {
    switch (calificacion) {
      case "1":
        return "Estuvo bien, pero puede mejorar.";
      case "2":
        return "Satisfactorio, pero tiene áreas de mejora.";
      case "3":
        return "Bueno, pero puede ser aún mejor.";
      case "4":
        return "Muy bueno, pero aún hay espacio para mejorar.";
      case "5":
        return "Excelente trabajo. ¡Fantástico!";
      default:
        return "";
    }
  }

  function calificarProyecto(proyecto_id) {
    let calificacion = document.getElementById(
      `calificacion_${proyecto_id}`
    ).value;
    let mensaje = obtenerMensaje(calificacion); // Usa la función para obtener el mensaje predefinido
    let comentario = document.getElementById(`mensaje_${proyecto_id}`).value;

    fetch("/PHP/Backend_calificacion.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `accion=calificar_proyecto&proyecto_id=${proyecto_id}&calificacion=${calificacion}&mensaje=${mensaje}&comentario=${comentario}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Calificación guardada correctamente.");
          cargarProyectos(); // Actualiza la lista de proyectos después de calificar
        } else {
          alert("Error al calificar el proyecto. Por favor, intenta de nuevo.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error al calificar el proyecto.");
      });
  }

  function incrementarVistas(idProyecto) {
    fetch(
      `/PHP/Backend_calificacion.php?accion=incrementar_vistas&id_proyecto=${idProyecto}`,
      {
        method: "POST",
      }
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Vistas incrementadas correctamente.");
        } else {
          console.error("Error al incrementar vistas.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }

  function eliminarProyecto(idProyecto) {
    if (confirm("¿Estás seguro de que deseas eliminar este proyecto?")) {
      fetch(
        `/PHP/Backend_calificacion.php?accion=eliminar_proyecto&id_proyecto=${idProyecto}`,
        {
          method: "POST",
        }
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Proyecto eliminado correctamente.");
            cargarProyectos(); // Actualiza la lista de proyectos después de eliminar
          } else {
            alert(
              "Error al eliminar el proyecto. Por favor, intenta de nuevo."
            );
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error al eliminar el proyecto.");
        });
    }
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
  // Resto del código para manejar comentarios, eliminar proyectos, etc.

  cargarProyectos(); // Cargar proyectos al cargar la página
});
