<?php
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

$query = "SELECT ID_ProyectoA, Titulo_Proyecto, Proceso FROM proyectos_alumnos";
$stmt = $pdo->query($query);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($projects);
