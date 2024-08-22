document.addEventListener("DOMContentLoaded", function () {
  fetch("/PHP/notif.php")
    .then((response) => {
      // Verificamos si la respuesta es válida
      if (!response.ok) {
        throw new Error("Error en la respuesta de la red");
      }
      return response.json();
    })
    .then((data) => {
      // Verificamos si data tiene contenido
      if (!Array.isArray(data) || data.length === 0) {
        console.warn(
          "No hay eventos próximos o el formato de datos es incorrecto."
        );
        return;
      }

      // Mostrar la primera notificación
      const evento = data[0];
      console.log(evento); // Verificar los datos recibidos

      // Crear y mostrar la notificación en el DOM
      const notificacionDiv = document.createElement("div");
      notificacionDiv.className = "notificacion-evento"; // Añadir clase CSS
      notificacionDiv.innerHTML = `
                <span class="cerrar">&times;</span>
                <h3>${evento.Titulo}</h3>
                <p>${evento.descripcion}</p>
                <p><strong>Fecha:</strong> ${evento.Fecha_Inicio}</p>
            `;

      document.body.appendChild(notificacionDiv);

      // Mostrar la notificación
      notificacionDiv.style.display = "block";

      // Cerrar notificación
      document.querySelector(".cerrar").addEventListener("click", function () {
        notificacionDiv.classList.add("fade-out"); // Añadir clase para desvanecimiento
        setTimeout(() => document.body.removeChild(notificacionDiv), 500); // Esperar a que termine la animación
      });
    })
    .catch((error) => {
      console.error("Error en la petición:", error);
    });
});
