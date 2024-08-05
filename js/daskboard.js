document.addEventListener("DOMContentLoaded", function () {
  const loadingOverlay = document.getElementById("loadingOverlay");
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

    // Calcular el déficit de error para cada proyecto
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
            data: Array(labels.length).fill(totalPageViews), // Coloca el total de vistas de la página en cada barra
            backgroundColor: "rgba(255, 99, 132, 0.2)",
            borderColor: "rgba(255, 99, 132, 1)",
            borderWidth: 1,
          },
          {
            label: "Déficit de Error",
            type: "line", // Tipo de gráfico de línea
            data: deficitError,
            fill: false, // No llenar debajo de la línea
            borderColor: "rgba(75, 192, 192, 1)",
            tension: 0.1, // Suavizar la línea
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

    // Gráfico de proyectos por mes
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

    // Gráfico de vistas por mes
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
    const content = document.getElementById("content");
    content.innerHTML = "<h2>Cargando...</h2>";
    loadingOverlay.style.display = "flex"; // Mostrar el overlay

    fetch("/PHP/gestionar_solicitudes.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((data) => {
        loadingOverlay.style.display = "none"; // Ocultar el overlay
        if (data.success) {
          renderSolicitudes(data.solicitudes);
        } else {
          displayError(data.message);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        loadingOverlay.style.display = "none"; // Ocultar el overlay
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

  function cambiarEstado(idProyecto, estado) {
    loadingOverlay.style.display = "flex"; // Mostrar el overlay
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
});
