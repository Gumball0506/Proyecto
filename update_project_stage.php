<?php
header('Content-Type: application/json');

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

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ID_ProyectoA']) && isset($data['Proceso'])) {
    $idProyectoA = $data['ID_ProyectoA'];
    $proceso = $data['Proceso'];

    $query = "UPDATE proyectos_alumnos SET Proceso = :proceso WHERE ID_ProyectoA = :idProyectoA";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':proceso', $proceso, PDO::PARAM_INT);
    $stmt->bindParam(':idProyectoA', $idProyectoA, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
