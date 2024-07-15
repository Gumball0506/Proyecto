<?php
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Configuración adicional para manejar errores y configuración de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // echo "Conexión exitosa a la base de datos."; // Comentado para no interferir con respuestas JSON
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
