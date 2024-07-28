<?php
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // echo "Conexión exitosa a la base de datos."; // Comenta o elimina esta línea en producción
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage()); // Registra el error en el archivo de log del servidor
    die("Error de conexión: " . $e->getMessage());
}
