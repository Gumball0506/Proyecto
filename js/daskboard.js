document.addEventListener("DOMContentLoaded", function () {
  var loadingOverlay = document.getElementById("loadingOverlay");
  loadingOverlay.style.display = "none";

  document.getElementById("principal").addEventListener("click", function () {
    content.innerHTML =
      "<h2>Principal</h2><p>Contenido principal del sistema de información de responsabilidad social.</p>";
  });

  document.getElementById("vistas").addEventListener("click", function () {
    content.innerHTML =
      "<h2>Vistas</h2><p>Sección de vistas del sistema de información de responsabilidad social.</p>";
  });

  document.getElementById("registros").addEventListener("click", function () {
    content.innerHTML =
      "<h2>Registros</h2><p>Sección de registros del sistema de información de responsabilidad social.</p>";
  });

  document.getElementById("solicitudes").addEventListener("click", function () {
    fetchSolicitudes();
  });
});

function fetchSolicitudes() {
  const content = document.getElementById("content");
  content.innerHTML = "<h2>Cargando...</h2>";
  fetch("/PHP/gestionar_solicitudes.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        renderSolicitudes(data.solicitudes);
      } else {
        displayError(data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      displayError("Error al cargar solicitudes.");
    });
}

function renderSolicitudes(solicitudes) {
  const content = document.getElementById("content");
  let solicitudesHTML = `
      <h2>Solicitudes de Proyectos</h2>
      <table>
          <tr>
              <th>ID</th>
              <th>Nombre y Apellidos</th>
              <th>Título del Proyecto</th>
              <th>Código del Estudiante</th>
              <th>Proceso</th>
          </tr>`;

  solicitudes.forEach((solicitud) => {
    solicitudesHTML += `
          <tr>
              <td>${solicitud.ID_ProyectoA}</td>
              <td>${solicitud.Nombres_Apellidos}</td>
              <td>${solicitud.Titulo_Proyecto}</td>
              <td>${solicitud.Codigo_alumno}</td>
              <td>${renderAcciones(solicitud)}</td>
          </tr>`;
  });

  solicitudesHTML += "</table>";
  content.innerHTML = solicitudesHTML;
}

function renderAcciones(solicitud) {
  if (solicitud.Proceso === "Proceso") {
    return `
          <button onclick="cambiarEstado(${solicitud.ID_ProyectoA}, 'Aceptado')">Aceptar</button>
          <button onclick="cambiarEstado(${solicitud.ID_ProyectoA}, 'Rechazado')">Rechazar</button>`;
  } else {
    return `<span class='estado-${solicitud.Proceso.toLowerCase()}'>${
      solicitud.Proceso
    }</span>`;
  }
}

function displayError(message) {
  const content = document.getElementById("content");
  content.innerHTML = `<h2>Error al cargar solicitudes: ${message}</h2>`;
}

function mostrarMensaje(mensaje, tipo) {
  const content = document.getElementById("content");
  const mensajeHTML = `<div class="mensaje ${tipo}">${mensaje}</div>`;
  content.innerHTML += mensajeHTML;

  const mensajeElemento = content.lastChild;
  setTimeout(() => {
    mensajeElemento.classList.add("desaparecer");
    setTimeout(() => {
      mensajeElemento.remove();
    }, 500);
  }, 3000);
}

function cambiarEstado(idProyecto, estado) {
  const loadingOverlay = document.getElementById("loadingOverlay");
  const loadingMessage = document.getElementById("loadingMessage");

  loadingOverlay.style.display = "flex";
  loadingMessage.innerHTML = "Cambiando estado...";

  fetch("/PHP/gestionar_solicitudes.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: idProyecto, estado: estado }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error en la respuesta del servidor.");
      }
      return response.json();
    })
    .then((result) => {
      loadingMessage.innerHTML = "El estado ha sido cambiado correctamente.";
      setTimeout(() => {
        loadingOverlay.style.display = "none";
        mostrarMensaje(result.message, result.success ? "exito" : "error");
      }, 2000);

      if (result.success) {
        fetchSolicitudes();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      loadingMessage.innerHTML = "Error al cambiar el estado.";
      setTimeout(() => {
        loadingOverlay.style.display = "none";
      }, 2000);
    });
}
