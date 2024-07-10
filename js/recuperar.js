document.getElementById('recuperarForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    
    // Aquí normalmente enviarías una solicitud al servidor para enviar el correo
    // Como es un ejemplo, simularemos que se envió el correo
    alert(`Se ha enviado un enlace de recuperación a ${email}. Por favor, revisa tu correo.`);
    
    // Simular redirección a una página de cambio de contraseña
    setTimeout(() => {
        window.location.href = 'cambiar_contraseña.html';
    }, 2000);
});