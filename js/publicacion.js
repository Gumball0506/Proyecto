// Palabras prohibidas
const bannedWords = [
  "mrd",
  "MRD",
  "NRD",
  "nrd",
  "Conchatumadre",
  "Huevón",
  "Huevona",
  "Chuchamadre",
  "Conchudo",
  "Conchuda",
  "Cojudo",
  "Cojuda",
  "Mierda",
  "Carajo",
  "Puta",
  "Pichula",
  "Chucha",
  "Cholo",
  "Chola",
  "Serrano",
  "Serrana",
  "Chuchatumadre",
  "Cachudo",
  "Cachuda",
  "Maricón",
  "Pendejo",
  "Pendeja",
  "Comemierda",
  "Imbécil",
  "Idiota",
  "Baboso",
  "Babosa",
  "Conchetumadre",
  "Cabrón",
  "Cabrona",
  "Cojudez",
  "Huevada",
  "Chupa",
  "Mamahuevo",
  "Concha",
  "Chúpame",
  "Malnacido",
  "Malnacida",
  "Hijo de puta",
  "Reconcha",
  "Jodido",
  "Jodida",
  "Cagón",
  "Cagona",
  "Puto",
  "Putamadre",
  "Mierdera",
  "Chupapinga",
  "Conchadetumadre",
  "Chucha de tu madre",
  "Rosquete",
  "Mamaverga",
  "Huevonazo",
  "Huevonaza",
  "Compadre",
  "Jetón",
  "Jetona",
  "Tarado",
  "Tarada",
  "Gil",
  "Mongol",
  "Pajero",
  "Pajera",
  "Gillpollas",
  "Chupapico",
  "Recontra",
  "Reconchudo",
  "Reconchuda",
  "Conchesumare",
  "Carechimba",
  "Malparido",
  "Malparida",
  "Cagada",
  "Manyado",
  "Manyada",
  "Cachero",
  "Cachera",
  "Chupetín",
  "Lameculos",
  "Culo",
  "Conchesumadre",
  "Huevón de mierda",
  "Cojudazo",
  "Cojudaza",
  "Cholo de mierda",
  "Serrano de mierda",
  "Basura",
  "Imbécil de mierda",
  "Maricón de mierda",
  "Perra",
  "Puto el que lee",
  "Concha tu hermana",
  "Conchatuhermana",
  "Chupamela",
  "Pichula triste",
  "Conchudo de mierda",
  "CTM",
  "CTMR",
  "PTM",
  "CSM",
  "HDP",
  "QLO",
  "WBN",
  "MRD",
  "HDLGP",
  "CHM",
  "PTMR",
  "CSMR",
  "CNR",
  "PDT",
  "CLIAO",
  "TMR",
  "DTM",
  "HMNO",
  "QLP",
  "MIERDA",
  "mAm4Hu3v0",
  "mi3rd4",
  "Coño",
  "Chingada",
  "Culero",
  "Pendejada",
  "Estúpido",
  "Gonorrea",
  "Tonto",
  "Trol",
  "Capullo",
  "Negro",
  "Blanco",
  "Indio",
  "Gringo",
  "Porno",
  "Orgasmo",
  "Prostitución",
  "Violar",
  "Gay",
  "Lesbiana",
  "Trans",
  "Homosexual",
  "Travesti",
];

document.addEventListener("DOMContentLoaded", function () {
  if (sessionActive) {
    // Mostrar el div con el id 'admin'
    document.getElementById("admin").style.display = "block";

    // Configura el MutationObserver para el div con la clase 'vistas-container'
    const observerV = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        // Obtener todos los elementos con la clase 'vistas-container'
        const vistasElements =
          document.getElementsByClassName("vistas-container");

        // Iterar sobre cada elemento encontrado
        for (let i = 0; i < vistasElements.length; i++) {
          vistasElements[i].style.display = "block";
        }

        // Deja de observar después de encontrar y mostrar el elemento
        observerV.disconnect();
      });
    });
    // Configura el MutationObserver para el div con la clase 'vistas-container'
    const observerE = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        // Obtener todos los elementos con la clase 'vistas-container'
        const vistasElements = document.getElementsByClassName(
          "eliminar-proyecto-btn"
        );

        // Iterar sobre cada elemento encontrado
        for (let i = 0; i < vistasElements.length; i++) {
          vistasElements[i].style.display = "block";
        }

        // Deja de observar después de encontrar y mostrar el elemento
        observerV.disconnect();
      });
    });
    // Configura el MutationObserver para el div con la clase 'vistas-container'
    const observerS = new MutationObserver(function (mutations) {
      mutations.forEach(function (mutation) {
        // Obtener todos los elementos con la clase 'vistas-container'
        const vistasElements = document.getElementsByClassName(
          "project-status-container"
        );

        // Iterar sobre cada elemento encontrado
        for (let i = 0; i < vistasElements.length; i++) {
          vistasElements[i].style.display = "block";
        }

        // Deja de observar después de encontrar y mostrar el elemento
        observerV.disconnect();
      });
    });

    // Empieza a observar el body por la adición de nuevos nodos
    observerV.observe(document.body, { childList: true, subtree: true });
    observerE.observe(document.body, { childList: true, subtree: true });
    observerS.observe(document.body, { childList: true, subtree: true });
  }
});

document.addEventListener("DOMContentLoaded", function () {
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
        console.log("Proyectos cargados:", proyectos); // Log para depuración
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

      // Inicializa el estado del checkbox
      projectStatusInput.checked = proyecto.Estado;

      // Crea un elemento para mostrar el texto del estado
      let projectStatusText = document.createElement("span");
      projectStatusText.textContent = projectStatusInput.checked
        ? "Actual"
        : "Antiguo";

      // Función para actualizar el texto y el estado
      function actualizarEstado() {
        let nuevoEstado = projectStatusInput.checked ? "Actual" : "Antiguo";
        projectStatusText.textContent = nuevoEstado;
        cambiarEstadoProyecto(proyecto.ID_Proyecto, nuevoEstado);
      }

      // Añade el texto y el checkbox al contenedor
      projectStatusContainer.appendChild(projectStatusLabel);
      projectStatusContainer.appendChild(projectStatusInput);
      projectStatusContainer.appendChild(projectStatusText);

      // Actualiza el estado cuando cambia el checkbox
      projectStatusInput.addEventListener("change", function () {
        actualizarEstado();
      });

      let starRatingDiv = document.createElement("div");
      starRatingDiv.classList.add("star-rating");

      for (let i = 5; i >= 1; i--) {
        let starInput = document.createElement("input");
        starInput.type = "radio";
        starInput.id = `star${i}-${proyecto.ID_Proyecto}`;
        starInput.name = `rating-${proyecto.ID_Proyecto}`;
        starInput.value = i;

        let starLabel = document.createElement("label");
        starLabel.htmlFor = `star${i}-${proyecto.ID_Proyecto}`;
        starLabel.textContent = "★";

        // Evento para manejar la selección de estrellas
        starInput.addEventListener("change", function () {
          // Muestra el modal de verificación
          verificarCodigoEstudiante(proyecto.ID_Proyecto, i);
        });

        starRatingDiv.appendChild(starInput);
        starRatingDiv.appendChild(starLabel);
      }
      let editarBoton = document.createElement("button");
      editarBoton.textContent = "Editar";
      editarBoton.classList.add("editar-proyecto-btn");
      editarBoton.addEventListener("click", function () {
        mostrarFormularioEdicion(proyecto);
      });
      proyectoDiv.appendChild(editarBoton);
      proyectoDiv.appendChild(tituloElement);
      proyectoDiv.appendChild(descripcionElement);
      proyectoDiv.appendChild(imagenElement);
      proyectoDiv.appendChild(vistasElement);
      proyectoDiv.appendChild(enlaceRegistroBoton);
      proyectoDiv.appendChild(eliminarBoton);
      proyectoDiv.appendChild(projectStatusContainer);
      proyectoDiv.appendChild(starRatingDiv);
      proyectosContainer.appendChild(proyectoDiv);
    });
  }
  function mostrarFormularioEdicion(proyecto) {
    document.getElementById("editProjectId").value = proyecto.ID_Proyecto;
    document.getElementById("editTitle").value = proyecto.Titulo;
    document.getElementById("editDescription").value = proyecto.Descripcion;
    document.getElementById("editRegistrationLink").value =
      proyecto.url_registro;
    document.getElementById("editEventDate").value = proyecto.Fecha_inicio;

    document.getElementById("editFormContainer").style.display = "block";
    document.getElementById("overlay").style.display = "block";
  }

  // Captura el evento submit del formulario de edición
  document
    .getElementById("editForm")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      let projectId = document.getElementById("editProjectId").value;
      let title = document.getElementById("editTitle").value;
      let description = document.getElementById("editDescription").value;
      let url = document.getElementById("editRegistrationLink").value;
      let eventDate = document.getElementById("editEventDate").value;

      let formData = new FormData();
      formData.append("accion", "editar_proyecto");
      formData.append("ID_Proyecto", projectId);
      formData.append("Titulo", title);
      formData.append("Descripcion", description);
      formData.append("url_registro", url);
      formData.append("Fecha_inicio", eventDate);

      fetch("/PHP/Backend_publicacion.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            cargarProyectos(); // Recargar proyectos
            cancelarEdicion();
          } else {
            alert("Error al editar el proyecto: " + data.error);
          }
        })
        .catch((error) => {
          console.error("Error al editar el proyecto:", error);
        });
    });

  function cancelarEdicion() {
    document.getElementById("editFormContainer").style.display = "none";
    document.getElementById("overlay").style.display = "none";
  }

  // Agregar evento al botón de cancelar
  document
    .getElementById("cancelEdit")
    .addEventListener("click", cancelarEdicion);

  function verificarCodigoEstudiante(idProyecto, calificacion) {
    let codigo = prompt(
      "Ingrese su código de estudiante para verificar su identidad:"
    );

    if (codigo) {
      fetch("/PHP/Backend_calificaciones.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
          accion: "verificar_codigo",
          codigo_estudiante: codigo,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.existe) {
            // Código de estudiante válido, verificar si ya ha calificado
            fetch("/PHP/Backend_calificaciones.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/x-www-form-urlencoded",
              },
              body: new URLSearchParams({
                accion: "verificar_calificacion",
                codigo_estudiante: codigo,
                proyecto_id: idProyecto,
              }),
            })
              .then((response) => response.json())
              .then((data) => {
                if (data.calificado) {
                  // El estudiante ya ha calificado
                  let actualizar = confirm(
                    "Este estudiante ya ha calificado este proyecto. ¿Desea actualizar su calificación?"
                  );
                  if (actualizar) {
                    guardarCalificacion(idProyecto, calificacion, codigo);
                  }
                } else {
                  // El estudiante no ha calificado aún
                  guardarCalificacion(idProyecto, calificacion, codigo);
                }
              })
              .catch((error) => {
                console.error("Error:", error);
                alert(
                  "Hubo un problema al verificar la calificación existente."
                );
              });
          } else {
            alert(
              "Código de estudiante incorrecto. No se puede proceder con la calificación."
            );
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Hubo un problema al verificar el código de estudiante.");
        });
    }
  }

  function guardarCalificacion(idProyecto, calificacion, codigo) {
    fetch("/PHP/Backend_calificaciones.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        accion: "calificar_proyecto",
        codigo_estudiante: codigo,
        proyecto_id: idProyecto,
        calificacion: calificacion,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.mensaje || "Calificación guardada correctamente.");
        } else {
          alert(data.error || "Error al guardar la calificación.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Hubo un problema al guardar la calificación.");
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
  // Resto del código para manejar comentarios, eliminar proyectos, etc.

  cargarProyectos(); // Cargar proyectos al cargar la página
});
