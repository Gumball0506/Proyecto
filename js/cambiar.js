document.getElementById('cambiarForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');
        return;
    }
    
    // Aquí normalmente enviarías una solicitud al servidor para actualizar la contraseña
    // Como es un ejemplo, simularemos que se actualizó la contraseña
    alert('Tu contraseña ha sido actualizada exitosamente.');
    
    // Actualizar la contraseña en el admin.js (esto es solo para el ejemplo)
    localStorage.setItem('ADMIN_PASSWORD', newPassword);
    
    // Redirigir al inicio de sesión
    window.location.href = 'inicio_de_sesion.html';
}); 