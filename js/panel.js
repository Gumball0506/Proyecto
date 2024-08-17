document.addEventListener("DOMContentLoaded", function() {
    fetch('/php/notif.php')
        .then(response => {
            // Verificamos si la respuesta es válida
            if (!response.ok) {
                thrownewError('Error en la respuesta de la red');
            }
            return response.json();
        })
        .then(data => {
            // Verificamos si data tiene contenido
            if (!Array.isArray(data) || data.length === 0) {
                console.warn('No hay eventos próximos o el formato de datos es incorrecto.');
                return;
            }

            // Mostrar la primera notificación
            const evento = data[0];
            console.log(evento);  // Verificar los datos recibidos// Crear y mostrar la notificación en el DOM
            const notificacionDiv = document.createElement('div');
            notificacionDiv.style.position = 'fixed';
            notificacionDiv.style.top = '50%';
            notificacionDiv.style.left = '50%';
            notificacionDiv.style.transform = 'translate(-50%, -50%)';
            notificacionDiv.style.padding = '20px';
            notificacionDiv.style.backgroundColor = '#f9f9f9';
            notificacionDiv.style.border = '1px solid #ccc';
            notificacionDiv.style.zIndex = '1000';
            notificacionDiv.style.boxShadow = '0px 0px 10px rgba(0,0,0,0.5)';

            notificacionDiv.innerHTML = `
                <h3>${evento.Titulo}</h3>
                <p>${evento.descripcion}</p>
                <p><strong>Fecha:</strong> ${evento.Fecha_Inicio}</p>
                <button id="cerrar-notificacion">Cerrar</button>
            `;

            document.body.appendChild(notificacionDiv);

            // Cerrar notificación
            document.getElementById('cerrar-notificacion').addEventListener('click', function() {
                document.body.removeChild(notificacionDiv);
            });
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });
});
