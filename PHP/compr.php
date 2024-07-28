<?php
require 'admin.php'; // Asegúrate de que la conexión a la base de datos esté configurada

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
    $row = $result->fetch_assoc();
    $_SESSION['username'] = $user; // Almacenar el nombre de usuario en la sesión
    echo "<script>window.location.href='/html/publicaciones_public.php';</script>";
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='/html/inicio_de_sesion.php';</script>";
}

// Cerrar conexión
$conn->close();
?>
