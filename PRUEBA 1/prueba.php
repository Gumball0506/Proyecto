<?php
session_start(); // Iniciar sesión

// Detalles de la conexión a la base de datos
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

// Mostrar el contenido actual de la sesión para depuración
echo "<pre>";
var_dump($_SESSION); // Muestra el contenido de la sesión
echo "</pre>";

// Verificar si el ID del estudiante está presente en la sesión
if (isset($_SESSION['user_id'])) {
    echo "El ID del estudiante ha sido guardado y reconocido correctamente.";
} else {
    echo "El ID del estudiante no ha sido reconocido.";
}
