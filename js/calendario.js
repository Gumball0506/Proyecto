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
  const daysContainer = document.getElementById("days");
  const monthYear = document.getElementById("month-year");
  const prevMonthBtn = document.getElementById("prev-month");
  const nextMonthBtn = document.getElementById("next-month");

  let currentDate = new Date();
  let events = [];
  const importantDates = [
    { date: "01-01", name: "Año Nuevo" },
    { date: "03-08", name: "Día de la Mujer" },
    { date: "04-22", name: "Día de la Tierra" },
    { date: "05-01", name: "Día del Trabajador" },
    { date: "06-21", name: "Inicio del Verano" },
    { date: "09-21", name: "Inicio del Otoño" },
    { date: "10-31", name: "Halloween" },
    { date: "12-25", name: "Navidad" },
    { date: "12-31", name: "Fin de Año" },
    { date: "01-26", name: "Educación Ambiental" },
    { date: "03-05", name: "Eficiencia Energética" },
    { date: "03-21", name: "Día de los Bosques" },
    { date: "04-30", name: "Cero Residuos" },
    { date: "05-17", name: "Día del Reciclaje" },
    { date: "09-16", name: "Preservación de Ozono" },
    { date: "09-21", name: "Limpieza de Playas" },
    { date: "09-21", name: "Gestión de Residuos" },
    { date: "10-05", name: "Gestión del Agua" },
    { date: "10-14", name: "Residuos Electrónicos" },
    { date: "10-21", name: "Ahorro de Energía" },
    { date: "11-16", name: "Protección Patrimonial" },
    { date: "11-21", name: "Aire Puro" },
    { date: "12-05", name: "Día de los Voluntarios" },
    { date: "12-13", name: "Contaminación Química" },
  ];

  const modal = document.getElementById("event-modal");
  const modalContent = document.getElementById("event-details");
  const closeModal = document.querySelector(".close");

  function fetchEvents() {
    fetch("/PHP/calendario.php")
      .then((response) => response.json())
      .then((data) => {
        events = data;
        renderCalendar(currentDate);
      })
      .catch((error) => console.error("Error al obtener los eventos:", error));
  }

  function renderCalendar(date) {
    daysContainer.innerHTML = "";
    monthYear.textContent = date.toLocaleDateString("es-ES", {
      month: "long",
      year: "numeric",
    });

    const firstDayOfMonth = new Date(
      date.getFullYear(),
      date.getMonth(),
      1
    ).getDay();
    const daysInMonth = new Date(
      date.getFullYear(),
      date.getMonth() + 1,
      0
    ).getDate();

    for (let i = 0; i < firstDayOfMonth; i++) {
      const emptyDiv = document.createElement("div");
      emptyDiv.classList.add("empty-day");
      daysContainer.appendChild(emptyDiv);
    }

    for (let i = 1; i <= daysInMonth; i++) {
      const dayDiv = document.createElement("div");
      dayDiv.classList.add("day");

      const dayDate = new Date(date.getFullYear(), date.getMonth(), i);
      const dayString =
        (dayDate.getMonth() + 1).toString().padStart(2, "0") +
        "-" +
        dayDate.getDate().toString().padStart(2, "0");

      const dayNumber = document.createElement("span");
      dayNumber.classList.add("day-number");
      dayNumber.textContent = i;
      dayDiv.appendChild(dayNumber);

      const eventContainer = document.createElement("div");
      eventContainer.classList.add("events-container");

      const dayEvents = events.filter((event) => {
        const eventDateObj = new Date(event.fecha_evento + "T00:00:00");
        return (
          dayDate.getFullYear() === eventDateObj.getFullYear() &&
          dayDate.getMonth() === eventDateObj.getMonth() &&
          dayDate.getDate() === eventDateObj.getDate() &&
          eventDateObj >= new Date(new Date().setHours(0, 0, 0, 0))
        );
      });

      dayEvents.forEach((event) => {
        const eventElement = document.createElement("div");
        eventElement.classList.add("event");
        eventElement.textContent = `Evento: ${event.titulo}`;
        eventContainer.appendChild(eventElement);
      });

      const dayImportantDates = importantDates.filter(
        (importantDate) => importantDate.date === dayString
      );

      if (dayImportantDates.length > 0) {
        const importantElement = document.createElement("div");
        importantElement.classList.add("important-date");
        importantElement.textContent =
          dayImportantDates.length === 1
            ? dayImportantDates[0].name
            : "Día Festivo";
        eventContainer.appendChild(importantElement);
      }

      if (eventContainer.hasChildNodes()) {
        dayDiv.appendChild(eventContainer);
      }

      dayDiv.addEventListener("click", () => showEvents(dayDate));
      daysContainer.appendChild(dayDiv);
    }
  }

  function showEvents(date) {
    const dayString =
      (date.getMonth() + 1).toString().padStart(2, "0") +
      "-" +
      date.getDate().toString().padStart(2, "0");

    const dayEvents = events.filter((event) => {
      const eventDateObj = new Date(event.fecha_evento + "T00:00:00");
      return (
        date.getFullYear() === eventDateObj.getFullYear() &&
        date.getMonth() === eventDateObj.getMonth() &&
        date.getDate() === eventDateObj.getDate() &&
        eventDateObj >= new Date(new Date().setHours(0, 0, 0, 0))
      );
    });

    const importantDayEvents = importantDates.filter(
      (importantDate) => importantDate.date === dayString
    );

    let message = `<h3>Eventos para el día ${date.getDate()}:</h3><ul>`;

    if (dayEvents.length > 0) {
      message += `<li><strong>Eventos:</strong></li><ul>${dayEvents
        .map((event) => `<li>Evento: ${event.titulo}</li>`)
        .join("")}</ul>`;
    }

    if (importantDayEvents.length > 0) {
      message += `<li><strong>Día Mundial:</strong></li><ul>${importantDayEvents
        .map((importantDate) => `<li>${importantDate.name}</li>`)
        .join("")}</ul>`;
    }

    if (!dayEvents.length && !importantDayEvents.length) {
      message = `<p>No hay eventos para el día ${date.getDate()}</p>`;
    }

    const registerLinks = dayEvents
      .map((event) => {
        if (event.url_registro) {
          return `<p>Si deseas saber más de nuestros proyectos! 🌟📚🌍</p>
<a href="/html/publicaciones_public.php">
    <button>Haga clic aquí</button>
</a>`;
        }
        return "";
      })
      .join("");

    const modalContentHtml = `${message}${registerLinks}`;
    modalContent.innerHTML = modalContentHtml;
    modal.style.display = "block";
  }

  closeModal.addEventListener("click", () => {
    modal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  prevMonthBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
  });

  nextMonthBtn.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
  });

  fetchEvents();
});
