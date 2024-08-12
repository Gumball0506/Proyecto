document.addEventListener("DOMContentLoaded", function () {
  if (sessionActive) {
    // Mostrar el div con el id 'admin'
    document.getElementById("admin").style.display = "block";
    document.getElementById("stat").style.display = "block";

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

      let comentariosBoton = document.createElement("button");
      comentariosBoton.innerHTML =
        '<i class="fas fa-comments"></i> Comentarios';
      comentariosBoton.classList.add("fb-style-button1", "comentarios-btn");
      comentariosBoton.addEventListener("click", function () {
        toggleComentarios(proyecto.ID_Proyecto);
      });

      let eliminarBoton = document.createElement("button");
      eliminarBoton.innerHTML = '<i class="fas fa-trash"></i>';
      eliminarBoton.classList.add("eliminar-proyecto-btn");
      eliminarBoton.id = "eliminar";
      eliminarBoton.addEventListener("click", function () {
        eliminarProyecto(proyecto.ID_Proyecto);
      });

      let comentariosContainer = document.createElement("div");
      comentariosContainer.id = "comentarios-" + proyecto.ID_Proyecto;
      comentariosContainer.classList.add("comentarios-container");
      comentariosContainer.style.display = "none";

      let comentarioForm = document.createElement("form");
      comentarioForm.id = "form-comentario-" + proyecto.ID_Proyecto;
      comentarioForm.classList.add("form-comentario", "fb-style-comment-form");
      comentarioForm.innerHTML = `<textarea id="comentario-${proyecto.ID_Proyecto}" placeholder="Agregar un comentario"></textarea>
        <button type="submit">Comentar</button>`;
      comentarioForm.addEventListener("submit", function (event) {
        event.preventDefault();
        let comentario = document.getElementById(
          "comentario-" + proyecto.ID_Proyecto
        ).value;
        agregarComentario(proyecto.ID_Proyecto, comentario);
      });

      let projectStatusContainer = document.createElement("div");
      projectStatusContainer.classList.add("project-status-container");
      projectStatusContainer.id = "status";

      let projectStatusLabel = document.createElement("label");
      projectStatusLabel.textContent = "Estado del Proyecto:";

      let projectStatusInput = document.createElement("input");
      projectStatusInput.type = "checkbox";

      // Inicializa el estado del checkbox
      projectStatusInput.checked = proyecto.Estado === "1"; // Asegúrate de comparar correctamente

      // Crea un elemento para mostrar el texto del estado
      let projectStatusText = document.createElement("span");
      projectStatusText.textContent = projectStatusInput.checked
        ? "Actual"
        : "Antiguo"; // Cambiado a "Antiguo"

      // Función para actualizar el texto y el estado
      function actualizarEstado() {
        let nuevoEstado = projectStatusInput.checked ? "1" : "2"; // "1" para "Actual" y "2" para "Antiguo"
        projectStatusText.textContent = projectStatusInput.checked
          ? "Actual"
          : "Antiguo"; // Cambia el texto según el estado
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
      proyectoDiv.appendChild(comentariosBoton);
      proyectoDiv.appendChild(comentariosContainer);
      proyectoDiv.appendChild(comentarioForm);
      proyectoDiv.appendChild(projectStatusContainer);
      proyectosContainer.appendChild(proyectoDiv);
    });
  }

  // Resto del código JavaScript para manejar eventos, cargar comentarios, etc.

  function cargarComentarios(proyecto_id) {
    fetch(
      `/PHP/Backend_publicacion.php?accion=obtener_comentarios&ID_Proyecto=${proyecto_id}`
    )
      .then((response) => response.json())
      .then((comentarios) => mostrarComentarios(comentarios, proyecto_id))
      .catch((error) =>
        console.error("Error al cargar los comentarios:", error)
      );
  }

  function mostrarComentarios(comentarios, proyecto_id) {
    let comentariosContainer = document.getElementById(
      "comentarios-" + proyecto_id
    );
    comentariosContainer.innerHTML = ""; // Limpiar el contenedor antes de actualizar

    comentarios.forEach((comentario) => {
      let comentarioDiv = document.createElement("div");
      comentarioDiv.classList.add("comentario");

      let comentarioTexto = document.createElement("p");
      comentarioTexto.textContent = comentario.Comentario;

      let editarBoton = document.createElement("button");
      editarBoton.textContent = "Editar";
      editarBoton.addEventListener("click", function () {
        editarComentario(comentario.ID_Comentario);
      });

      let eliminarBoton = document.createElement("button");
      eliminarBoton.innerHTML = "Eliminar";
      eliminarBoton.addEventListener("click", function () {
        eliminarComentario(comentario.ID_Comentario);
      });

      comentarioDiv.appendChild(comentarioTexto);
      comentarioDiv.appendChild(editarBoton);
      comentarioDiv.appendChild(eliminarBoton);

      comentariosContainer.appendChild(comentarioDiv);
    });
  }

  function toggleComentarios(proyecto_id) {
    let comentariosDiv = document.getElementById("comentarios-" + proyecto_id);
    if (comentariosDiv.style.display === "none") {
      comentariosDiv.style.display = "block";
      cargarComentarios(proyecto_id);
    } else {
      comentariosDiv.style.display = "none";
    }
  }
  function agregarComentario(proyecto_id, comentario) {
    // Verificar si el comentario contiene palabras prohibidas
    let palabrasProhibidas = detectarPalabrasProhibidas(comentario);

    if (palabrasProhibidas.length > 0) {
      alert(
        `El comentario contiene palabras ofensivas: ${palabrasProhibidas.join(
          ", "
        )}, y no puede ser publicado.`
      );
      return;
    }

    fetch("/PHP/Backend_publicacion.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `accion=agregar_comentario&ID_Proyecto=${encodeURIComponent(
        proyecto_id
      )}&Comentario=${encodeURIComponent(comentario)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Comentario agregado correctamente");
          cargarComentarios(proyecto_id);
          document.getElementById("comentario-" + proyecto_id).value = "";
        } else {
          alert("Error al agregar el comentario. Por favor, intenta de nuevo.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error al agregar el comentario.");
      });
  }

  // Función para detectar palabras prohibidas en un comentario
  function detectarPalabrasProhibidas(comentario) {
    // Convertir el comentario a minúsculas para evitar problemas de mayúsculas y minúsculas
    let comentarioLimpio = comentario.toLowerCase();

    // Separar el comentario en palabras individuales
    let palabras = comentarioLimpio.split(/\s+/);

    // Crear un array para almacenar las palabras prohibidas encontradas
    let palabrasProhibidasEncontradas = [];

    // Verificar si alguna palabra está en la lista de palabras prohibidas
    for (let palabra of palabras) {
      if (bannedWords.includes(palabra)) {
        // Si se encuentra una palabra prohibida, agregarla al array de palabras prohibidas encontradas
        palabrasProhibidasEncontradas.push(palabra);
      }
    }

    // Retornar las palabras prohibidas encontradas
    return palabrasProhibidasEncontradas;
  }

  // Palabras prohibidas
  const bannedWords = [
    "mrd",
    "conchatumadre",
    "huevón",
    "huevona",
    "chuchamadre",
    "conchudo",
    "conchuda",
    "cojudo",
    "cojuda",
    "mierda",
    "carajo",
    "puta",
    "pichula",
    "chucha",
    "cholo",
    "chola",
    "serrano",
    "serrana",
    "chuchatumadre",
    "cachudo",
    "cachuda",
    "maricón",
    "pendejo",
    "pendeja",
    "comemierda",
    "imbécil",
    "idiota",
    "baboso",
    "babosa",
    "conchetumadre",
    "cabrón",
    "cabrona",
    "cojudez",
    "huevada",
    "chupa",
    "mamahuevo",
    "concha",
    "chúpame",
    "malnacido",
    "malnacida",
    "hijo de puta",
    "reconcha",
    "jodido",
    "jodida",
    "cagón",
    "cagona",
    "puto",
    "putamadre",
    "mierdera",
    "chupapinga",
    "conchadetumadre",
    "chucha de tu madre",
    "rosquete",
    "mamaverga",
    "huevonazo",
    "huevonaza",
    "compadre",
    "jetón",
    "jetona",
    "tarado",
    "tarada",
    "gil",
    "mongol",
    "pajero",
    "pajera",
    "gillpollas",
    "chupapico",
    "recontra",
    "reconchudo",
    "reconchuda",
    "conchesumare",
    "carechimba",
    "malparido",
    "malparida",
    "cagada",
    "manyado",
    "manyada",
    "cachero",
    "cachera",
    "chupetín",
    "lameculos",
    "culo",
    "conchesumadre",
    "huevón de mierda",
    "cojudazo",
    "cojudaza",
    "cholo de mierda",
    "serrano de mierda",
    "basura",
    "imbécil de mierda",
    "maricón de mierda",
    "perra",
    "puto el que lee",
    "concha tu hermana",
    "conchatuhermana",
    "chupamela",
    "pichula triste",
    "conchudo de mierda",
    "ctm",
    "ctmr",
    "ptm",
    "csm",
    "hdp",
    "qlo",
    "wbn",
    "mrd",
    "hdlgp",
    "chm",
    "ptmr",
    "csmr",
    "cnr",
    "pdt",
    "cliao",
    "tmr",
    "dtm",
    "hmno",
    "qlp",
    "mierda",
    "mam4hu3v0",
    "mi3rd4",
    "coño",
    "chingada",
    "culero",
    "pendejada",
    "estúpido",
    "gonorrea",
    "tonto",
    "trol",
    "capullo",
    "negro",
    "blanco",
    "indio",
    "gringo",
    "porno",
    "orgasmo",
    "prostitución",
    "violar",
    "gay",
    "lesbiana",
    "trans",
    "homosexual",
    "travesti",
    "pelotudo",
    "Pelotudo",
  ];

  // Ejemplo de uso
  const comentarioEjemplo =
    "Este es un comentario con palabras prohibidas como mrd y conchatumadre.";
  const palabrasProhibidas = detectarPalabrasProhibidas(comentarioEjemplo);

  console.log("Palabras prohibidas encontradas:", palabrasProhibidas);

  function editarComentario(comentario_id) {
    let nuevoComentario = prompt("Ingresa el nuevo comentario:");
    if (nuevoComentario) {
      fetch("/PHP/Backend_publicacion.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `accion=editar_comentario&ID_Comentario=${encodeURIComponent(
          comentario_id
        )}&Comentario=${encodeURIComponent(nuevoComentario)}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Comentario editado correctamente");
            cargarComentarios(data.proyecto_id);
          } else {
            alert(
              "Error al editar el comentario. Por favor, intenta de nuevo."
            );
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  }

  function eliminarComentario(comentario_id) {
    let confirmacion = confirm(
      "¿Estás seguro de que deseas eliminar este comentario?"
    );
    if (confirmacion) {
      fetch("/PHP/Backend_publicacion.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `accion=eliminar_comentario&ID_Comentario=${encodeURIComponent(
          comentario_id
        )}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Comentario eliminado correctamente");
            cargarComentarios(data.proyecto_id);
          } else {
            alert(
              "Error al eliminar el comentario. Por favor, intenta de nuevo."
            );
          }
        })
        .catch((error) => console.error("Error:", error));
    }
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
  function cambiarEstadoProyecto(proyecto_id, nuevoEstado) {
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
            "Estado del proyecto actualizado correctamente, Se paso al área proyectos antiguos"
          );
        } else {
          alert("Error al actualizar el estado del proyecto.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Ocurrió un error al cambiar el estado.");
      });
  }

  cargarProyectos(); // Cargar proyectos al cargar la página
});
