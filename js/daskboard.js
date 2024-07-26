document.addEventListener("DOMContentLoaded", function () {
  const content = document.getElementById("content");

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
    fetch("/php/gestionar_solicitudes.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          let solicitudesHTML =
            "<h2>Solicitudes de Proyectos</h2><table><tr><th>ID</th><th>Nombre y Apellidos</th><th>Titulo del Proyecto</th><th>Código del Estudiante</th><th>Proceso</th></tr>";
          data.solicitudes.forEach((solicitud) => {
            let accionesHTML = "";
            let estadoHTML = "";

            if (solicitud.Proceso === "Proceso") {
              accionesHTML = `
                              <button onclick="cambiarEstado(${solicitud.ID_ProyectoA}, 'Aceptado')">Aceptar</button>
                              <button onclick="cambiarEstado(${solicitud.ID_ProyectoA}, 'Rechazado')">Rechazar</button>`;
            } else if (solicitud.Proceso === "Aceptado") {
              estadoHTML = "<span class='estado-aceptado'>Aceptado</span>";
            } else if (solicitud.Proceso === "Rechazado") {
              estadoHTML = "<span class='estado-rechazado'>Rechazado</span>";
            }

            solicitudesHTML += `
                          <tr>
                              <td>${solicitud.ID_ProyectoA}</td>
                              <td>${solicitud.Nombres_Apellidos}</td>
                              <td>${solicitud.Titulo_Proyecto}</td>
                              <td>${solicitud.Codigo_alumno}</td>
                              <td>${estadoHTML || accionesHTML}</td>
                          </tr>
                      `;
          });
          solicitudesHTML += "</table>";
          content.innerHTML = solicitudesHTML;
        } else {
          content.innerHTML =
            "<h2>Error al cargar solicitudes: " + data.message + "</h2>";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        content.innerHTML = "<h2>Error al cargar solicitudes.</h2>";
      });
  });
});

function cambiarEstado(idProyecto, estado) {
  fetch("/php/gestionar_solicitudes.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: idProyecto, estado: estado }),
  })
    .then((response) => response.json())
    .then((result) => {
      alert(result.message);
      document.getElementById("solicitudes").click(); // Recargar solicitudes
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}
