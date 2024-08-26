$(document).ready(function () {
  fetchProjects();

  function fetchProjects() {
    $.ajax({
      url: "fetch_projects.php",
      method: "GET",
      dataType: "json",
      success: function (projects) {
        const progressContainer = $("#progressContainer");
        progressContainer.empty();

        projects.forEach((project) => {
          const progressBar = $("<div>").addClass("progress-bar");
          const progress = $("<div>").addClass("progress-bar-inner");
          updateProgressBar(progress, project.Proceso);

          progressBar.append(progress);

          const stageButtons = $("<div>").addClass("button-container");

          if (project.Proceso !== "ETAPA4") {
            for (let i = 1; i <= 4; i++) {
              const button = $("<button>").text(`Etapa ${i}`);
              button.on("click", () =>
                updateProjectStage(project.ID_ProyectoA, `ETAPA${i}`)
              );
              stageButtons.append(button);
            }
          } else {
            const hideButton = $("<button>").text("Ocultar Proyecto");
            hideButton.on("click", () => hideProject(project.ID_ProyectoA));
            stageButtons.append(hideButton);
          }

          // Agregar botón de Rechazo
          const rejectButton = $("<button>").text("Rechazado");
          rejectButton.on("click", () => rejectProject(project.ID_ProyectoA));
          stageButtons.append(rejectButton);

          const projectContainer = $("<div>").addClass("project");
          projectContainer.html(
            `<div class="stage">${project.Titulo_Proyecto}</div>`
          );
          projectContainer.append(progressBar);
          projectContainer.append(stageButtons);

          progressContainer.append(projectContainer);
        });
      },
      error: function (xhr, status, error) {
        console.error("Error al cargar proyectos:", error);
      },
    });
  }

  function updateProgressBar(progressElement, stage) {
    const progressPercentages = {
      Etapa1: 25,
      Etapa2: 50,
      Etapa3: 75,
      Etapa4: 100,
      Rechazado: "PROYECTO_RECHAZADO",
    };

    const percentage = progressPercentages[stage] || 0;

    progressElement.css({
      width: "0%",
      transition: "width 1s",
      "background-color": "#0000FF",
    });

    setTimeout(() => {
      progressElement.css("width", `${percentage}%`);
    }, 100);

    progressElement.text(`${percentage}% completado`);
  }

  function updateProjectStage(projectId, newStage) {
    $.ajax({
      url: "update_project_stage.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ ID_ProyectoA: projectId, Proceso: newStage }),
      success: function (result) {
        if (result.success) {
          fetchProjects(); // Recargar proyectos después de la actualización
        } else {
          console.error("Error al actualizar el estado del proyecto.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al actualizar el estado del proyecto:", error);
      },
    });
  }

  function hideProject(projectId) {
    $.ajax({
      url: "hide_project.php",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ ID_ProyectoA: projectId }),
      success: function (result) {
        if (result.success) {
          fetchProjects(); // Recargar proyectos después de ocultar uno
        } else {
          console.error("Error al ocultar el proyecto.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al ocultar el proyecto:", error);
      },
    });
  }

  function rejectProject(projectId) {
    $.ajax({
      url: "update_project_stage.php", // Puedes usar el mismo endpoint que para actualizar la etapa
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ ID_ProyectoA: projectId, Proceso: "Rechazado" }),
      success: function (result) {
        if (result.success) {
          fetchProjects(); // Recargar proyectos después de actualizar a "Rechazado"
        } else {
          console.error("Error al rechazar el proyecto.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al rechazar el proyecto:", error);
      },
    });
  }
});
