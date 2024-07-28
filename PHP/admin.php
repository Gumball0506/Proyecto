<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proyecto_integrador";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Iniciar sesión
session_start();

// Obtener datos del formulario
$user = $_POST['username'];
$pass = $_POST['password'];

// Preparar y ejecutar consulta
$sql = $conn->prepare("SELECT * FROM administrador WHERE Nombre = ? AND Contraseña = ?");
$sql->bind_param("ss", $user, $pass);
$sql->execute();
$result = $sql->get_result();

// Verificar si el usuario existe
if ($result->num_rows > 0) {
    $_SESSION['username'] = $user;
    echo "<script>alert('Inicio de sesión exitoso'); window.location.href='/html/web1.html';</script>";
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='/html/inicio_de_sesion.php';</script>";
}

// Cerrar conexión
$conn->close();
?>
