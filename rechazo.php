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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['ID_ProyectoA'])) {
        echo json_encode(['success' => false, 'message' => 'ID de proyecto no proporcionado.']);
        exit;
    }

    $idProyecto = $data['ID_ProyectoA'];

    try {
        // Actualizar la visibilidad del proyecto a 0 (oculto)
        $stmt = $pdo->prepare("UPDATE proyectos_alumnos SET visible = 0 WHERE ID_ProyectoA = :id");
        $stmt->execute(['id' => $idProyecto]);

        if ($stmt->rowCount()) {
            echo json_encode(['success' => true, 'message' => 'Proyecto rechazado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el proyecto o ya estaba oculto.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado.']);
}
