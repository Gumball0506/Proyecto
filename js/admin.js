// Credenciales de administrador (en una aplicación real, esto nunca debería estar en el frontend)
const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'password123';

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === ADMIN_USERNAME && password === ADMIN_PASSWORD) {
        alert('Inicio de sesión exitoso. Redirigiendo...');
        // Redirige a la página de administrador
        window.location.href = 'web1.html';
    } else {
        alert('Credenciales incorrectas. Por favor, intente de nuevo.');
    }
});