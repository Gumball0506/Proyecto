<?php
header("Content-Type: application/json");

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    $mensajeId = isset($data['id']) ? (int)$data['id'] : 0;

    if ($mensajeId > 0) {
        // Preparar la consulta SQL
        $sql = "DELETE FROM mensajes WHERE ID_Mensaje = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $mensajeId, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Mensaje eliminado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el mensaje.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de mensaje inválido.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
}
