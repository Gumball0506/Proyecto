<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "RSUFIEI";
$password = "Bicicleta123*";
$dbname = "Responsabilidad_Social";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
