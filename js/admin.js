const ADMIN_USERNAME = 'admin';
let ADMIN_PASSWORD = localStorage.getItem('ADMIN_PASSWORD') || 'password123';

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === ADMIN_USERNAME && password === ADMIN_PASSWORD) {
        alert('Inicio de sesión exitoso');  
        // Redirige a la página de administrador
        opciones.style.display = 'block';
    } else {
        alert('Credenciales incorrectas. Por favor, intente de nuevo.');
    }
});