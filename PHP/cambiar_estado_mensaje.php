<?php
header("Content-Type: application/json");

// Configuración de la conexión a la base de datos
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos de la solicitud
    $data = json_decode(file_get_contents('php://input'), true);
    $id = isset($data['id']) ? (int)$data['id'] : 0;
    $estado = isset($data['estado']) ? $data['estado'] : '';

    if ($id > 0 && in_array($estado, ["Por Leer", "Leído", "Respondido"])) {
        // Preparar la consulta SQL
        $sql = "UPDATE mensajes SET Estado = :estado WHERE ID_Mensaje = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Estado del mensaje actualizado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado del mensaje.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID de mensaje o estado inválido.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
}
