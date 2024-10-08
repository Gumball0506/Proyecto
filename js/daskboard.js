/*
    ----------------------------------------------------
    Comentario Anti-Copyright
    ----------------------------------------------------
    Este trabajo es realizado por:
    - Harold Ortiz Abra Loza
    - William Vega
    - Sergio Vidal
    - Elizabeth Campos
    - Lily Roque
    ----------------------------------------------------
    © 2024 Responsabilidad Social Universitaria. 
    Todos los derechos reservados.
    ----------------------------------------------------
*/

// Aquí puedes incluir tu código JavaScript.

document.addEventListener("DOMContentLoaded", function () {
  const loadingOverlay = document.getElementById("loadingOverlay");
  const loadingMessage = document.getElementById("loadingMessage");
  const content = document.getElementById("content");
  loadingOverlay.style.display = "none";

  document.getElementById("principal").addEventListener("click", function () {
    content.innerHTML =
      "<h2>Principal</h2><p>Contenido principal del sistema de información de responsabilidad social.</p>";
  });

  document.getElementById("vistas").addEventListener("click", function () {
    content.innerHTML =
      "<h2>Vistas</h2><p>Sección de vistas del sistema de información de responsabilidad social.</p>" +
      "<div class='chart' id='views-chart'>" +
      "<canvas id='viewsCanvas'></canvas>" +
      "</div>";

    fetchDataAndRenderChart();
  });

  document.getElementById("registros").addEventListener("click", function () {
    content.innerHTML = `
      <h2>Estadistica</h2>
      <p>Estadísticas sobre los proyectos y vistas.</p>
      <div class='charts'>
        <div class='chart' id='project-stats'>
          <canvas id='projectStatsCanvas'></canvas>
        </div>
        <div class='chart' id='views-stats'>
          <canvas id='viewsStatsCanvas'></canvas>
        </div>
      </div>
    `;

    fetchProjectStatsAndRenderChart();
  });

  document.getElementById("solicitudes").addEventListener("click", function () {
    fetchSolicitudes();
  });

  function fetchDataAndRenderChart() {
    fetch("/PHP/estadistica.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error("Error:", data.error);
          content.innerHTML = "<h2>Error al cargar los datos.</h2>";
          return;
        }
        renderViewsChart(data.total_views, data.total_page_views);
      })
      .catch((error) => {
        console.error("Error al cargar los datos:", error);
        content.innerHTML = "<h2>Error al cargar los datos.</h2>";
      });
  }

  function renderViewsChart(totalViews, totalPageViews) {
    var ctx = document.getElementById("viewsCanvas").getContext("2d");

    const labels = totalViews.map((view) => view.Titulo);
    const viewsData = totalViews.map((view) => view.total_vistas);

    const deficitError = viewsData.map(
      (viewCount) => totalPageViews - viewCount
    );

    var viewsChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          {
            label: "Total de registros",
            data: viewsData,
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
          },
          {
            label: "Total de vistas",
            data: Array(labels.length).fill(totalPageViews),
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 1,
          },
          {
            label: "Déficit de Error",
            type: "line",
            data: deficitError,
            fill: false,
            borderColor: "rgba(75, 192, 192, 1)",
            tension: 0.1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  function fetchProjectStatsAndRenderChart() {
    fetch("/PHP/estadistica_dashboard.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error("Error:", data.error);
          content.innerHTML = "<h2>Error al cargar los datos.</h2>";
          return;
        }
        renderDashboardCharts(data.projectCounts, data.viewsByMonth);
      })
      .catch((error) => {
        console.error("Error al cargar los datos:", error);
        content.innerHTML = "<h2>Error al cargar los datos.</h2>";
      });
  }

  function renderDashboardCharts(projectCounts, viewsByMonth) {
    const projectChartCtx = document
      .getElementById("projectStatsCanvas")
      .getContext("2d");
    const viewsChartCtx = document
      .getElementById("viewsStatsCanvas")
      .getContext("2d");

    const projectLabels = projectCounts.map((count) => count.Mes);
    const projectData = projectCounts.map((count) => count.Total);

    const viewsLabels = viewsByMonth.map((view) => view.Mes);
    const viewsData = viewsByMonth.map((view) => view.Total_Vistas);

    new Chart(projectChartCtx, {
      type: "bar",
      data: {
        labels: projectLabels,
        datasets: [
          {
            label: "Proyectos por Mes",
            data: projectData,
            backgroundColor: "rgba(54, 162, 235, 0.6)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: "Número de Proyectos",
            },
          },
          x: {
            title: {
              display: true,
              text: "Meses",
            },
          },
        },
      },
    });

    new Chart(viewsChartCtx, {
      type: "line",
      data: {
        labels: viewsLabels,
        datasets: [
          {
            label: "Vistas por Mes",
            data: viewsData,
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 1,
            fill: false,
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: "Número de Vistas",
            },
          },
          x: {
            title: {
              display: true,
              text: "Meses",
            },
          },
        },
      },
    });
  }

  function fetchSolicitudes() {
    content.innerHTML = "<h2>Cargando...</h2>";
    loadingOverlay.style.display = "flex";

    fetch("/PHP/gestionar_solicitudes.php")
      .then((response) => response.json())
      .then((data) => {
        loadingOverlay.style.display = "none";
        if (data.success) {
          renderSolicitudes(data.solicitudes);
        } else {
          displayError(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        loadingOverlay.style.display = "none";
        displayError("Error al cargar solicitudes.");
      });
  }

  function renderSolicitudes(solicitudes) {
    let solicitudesHTML = `
      <h2>Solicitudes de Proyectos</h2>
      <table>
          <tr>
              <th>ID</th>
              <th>Nombre y Apellidos</th>
              <th>Título del Proyecto</th>
              <th>Código del Estudiante</th>
              <th>Correo</th>
              <th>Proceso</th>
              <th>Opciones</th>
          </tr>`;

    solicitudes.forEach((solicitud) => {
      solicitudesHTML += `
          <tr data-id="${solicitud.ID_ProyectoA}">
              <td>${solicitud.ID_ProyectoA}</td>
              <td>${solicitud.Nombres_Apellidos}</td>
              <td>${solicitud.Titulo_Proyecto}</td>
              <td>${solicitud.Codigo_alumno}</td>
              <td>${solicitud.Correo_Electronico}</td>
              <td>${solicitud.Proceso || "Proceso"}</td>
              <td>${renderOpciones(solicitud)}</td>
          </tr>`;
    });

    solicitudesHTML += "</table>";
    content.innerHTML = solicitudesHTML;
  }
  window.mostrarFormularioInformar = function (id) {
    const content = document.getElementById("content");
    document.getElementById("informarForm").style.display = "block";
    // Optional: Pre-fill the form or manage which ID to use
    document.getElementById("informarMessage").setAttribute("data-id", id);
  };

  window.cerrarFormulario = function () {
    document.getElementById("informarForm").style.display = "none";
  };

  window.enviarNotificacion = function () {
    const messageElement = document.getElementById("informarMessage");
    const message = messageElement.value;
    const id = messageElement.getAttribute("data-id");

    if (!message.trim()) {
      alert("El mensaje no puede estar vacío.");
      return;
    }

    loadingOverlay.style.display = "flex";
    loadingMessage.textContent = "Enviando mensaje...";

    fetch("/PHP/enviar_mensaje.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: id, mensaje: message }),
    })
      .then((response) => response.json())
      .then((result) => {
        loadingOverlay.style.display = "none";
        if (result.success) {
          mostrarMensaje(result.message, "exito");
          cerrarFormulario();
          // Limpiar el campo de texto
          messageElement.value = "";
        } else {
          mostrarMensaje(result.message, "error");
        }
      })
      .catch((error) => {
        loadingOverlay.style.display = "none";
        console.error("Error:", error);
        mostrarMensaje("Error al enviar el mensaje.", "error");
      });
  };

  function renderOpciones(solicitud) {
    return `
    <button onclick="verDocumento(${solicitud.ID_ProyectoA})">Visualizar</button>
    <button onclick="descargarDocumento(${solicitud.ID_ProyectoA})">Descargar</button>`;
  }

  window.verDocumento = function (id) {
    window.open(`/PHP/ver_documento.php?id=${id}`, "_blank");
  };

  window.descargarDocumento = function (id) {
    window.location.href = `/PHP/descargar_documento.php?id=${id}`;
  };

  function displayError(message) {
    content.innerHTML = `<h2>Error al cargar solicitudes: ${message}</h2>`;
  }

  function mostrarMensaje(mensaje, tipo) {
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
  window.revertirEstado = function (id) {
    // Selecciona la fila con el ID dado
    const fila = document.querySelector(`tr[data-id="${id}"]`);
    if (!fila) {
      alert("Solicitud no encontrada.");
      return;
    }

    // Revertir el estado a "Proceso" directamente
    cambiarEstado(id, "Proceso");
  };

  window.cambiarEstado = function (id, nuevoEstado) {
    const fila = document.querySelector(`tr[data-id="${id}"]`);
    if (!fila) {
      alert("Solicitud no encontrada.");
      return;
    }

    const botones = fila.querySelectorAll(".estado-boton");

    botones.forEach((btn) => {
      btn.classList.remove("activo", "aceptado", "rechazado");
      btn.disabled = false;
    });

    const botonSeleccionado = fila.querySelector(
      `button[data-estado="${nuevoEstado}"]`
    );
    if (botonSeleccionado) {
      botonSeleccionado.classList.add("activo");
      botonSeleccionado.disabled = true;
    }

    loadingOverlay.style.display = "flex";
    loadingMessage.textContent = "Cambiando estado...";

    fetch("/PHP/gestionar_solicitudes.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: id, estado: nuevoEstado }),
    })
      .then((response) => response.json())
      .then((result) => {
        loadingOverlay.style.display = "none";
        if (result.success) {
          mostrarMensaje(result.message, "exito");
        } else {
          mostrarMensaje(result.message, "error");
        }
      })
      .catch((error) => {
        loadingOverlay.style.display = "none";
        console.error("Error:", error);
        mostrarMensaje("Error al cambiar estado.", "error");
      });
  };
  document.getElementById("mensajes").addEventListener("click", function () {
    fetchMensajes();
  });

  function fetchMensajes() {
    fetch("/PHP/obtener_mensajes.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          renderMensajes(data.mensajes);
        } else {
          displayError(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        displayError("Error al cargar los mensajes.");
      });
  }

  function renderMensajes(mensajes) {
    // Ordenar los mensajes: 'Pendiente' primero, 'Por Leer' después, 'Leído' después, 'Respondido' al final
    mensajes.sort((a, b) => {
      const order = ["Pendiente", "Por Leer", "Leído", "Respondido"];
      return order.indexOf(a.Estado) - order.indexOf(b.Estado);
    });

    let mensajesHTML = `
        <h2>Mensajes Recibidos</h2>
        <table>
            <tr>
                <th>Nombre y Apellidos</th>
                <th>Email</th>
                <th>Fecha de Envío</th>
                <th>Mensaje</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>`;

    mensajes.forEach((mensaje) => {
      // Determinar cuál botón debe estar marcado como seleccionado
      const selectedState =
        localStorage.getItem(`selectedState-${mensaje.ID_Mensaje}`) ||
        mensaje.Estado;

      mensajesHTML += `
            <tr id="mensaje-${mensaje.ID_Mensaje}">
                <td>${mensaje.Nombre} ${mensaje.Apellido}</td>
                <td>${mensaje.Email}</td>
                <td>${mensaje.Fecha_Envio}</td>
                <td>${mensaje.Mensaje}</td>
                <td>
                    <div class="estado-buttons">
                        <button class="${
                          selectedState === "Leído" ? "active" : ""
                        }" onclick="actualizarEstadoMensaje(${
        mensaje.ID_Mensaje
      }, 'Leído')">Leído</button>
                        <button class="${
                          selectedState === "Respondido" ? "active" : ""
                        }" onclick="actualizarEstadoMensaje(${
        mensaje.ID_Mensaje
      }, 'Respondido')">Respondido</button>
                        <button class="${
                          selectedState === "Por Leer" ? "active" : ""
                        }" onclick="actualizarEstadoMensaje(${
        mensaje.ID_Mensaje
      }, 'Por Leer')">No Leído</button>
                    </div>
                </td>
                <td>
                    <button onclick="eliminarMensaje(${
                      mensaje.ID_Mensaje
                    })">Eliminar</button>
                </td>
            </tr>`;
    });

    mensajesHTML += "</table>";
    document.getElementById("content").innerHTML = mensajesHTML;
  }

  window.actualizarEstadoMensaje = function (mensajeId, nuevoEstadoMensaje) {
    fetch(`/PHP/cambiar_estado_mensaje.php`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: mensajeId, estado: nuevoEstadoMensaje }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Guardar el nuevo estado en localStorage
          localStorage.setItem(
            `selectedState-${mensajeId}`,
            nuevoEstadoMensaje
          );
          fetchMensajes(); // Recargar los mensajes después de cambiar el estado
        } else {
          displayError(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        displayError("Error al cambiar el estado del mensaje.");
      });
  };

  window.eliminarMensaje = function (mensajeId) {
    if (confirm("¿Estás seguro de que deseas eliminar este mensaje?")) {
      fetch(`/PHP/eliminar_mensaje.php`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: mensajeId }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            fetchMensajes(); // Recargar los mensajes después de eliminar
          } else {
            displayError(data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          displayError("Error al eliminar el mensaje.");
        });
    }
  };

  function displayError(message) {
    document.getElementById(
      "content"
    ).innerHTML = `<h2>Error</h2><p>${message}</p>`;
  }
});
