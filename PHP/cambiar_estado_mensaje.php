<?php
header("Content-Type: application/json");

// Configuración de la conexión a la base de datos
include 'conexion.php';

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $mensajeId = isset($data['id']) ? (int)$data['id'] : 0;
    $nuevoEstadoMensaje = isset($data['estado']) ? $data['estado'] : '';

    if ($mensajeId > 0 && in_array($nuevoEstadoMensaje, ["Pendiente", "Por Leer", "Leído", "Respondido"])) {
        $query = $pdo->prepare("UPDATE mensajes SET Estado = :estado WHERE ID_Mensaje = :id");
        $query->bindParam(':estado', $nuevoEstadoMensaje);
        $query->bindParam(':id', $mensajeId, PDO::PARAM_INT);

        if ($query->execute()) {
            echo json_encode(['success' => true, 'message' => 'Estado del mensaje actualizado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el estado del mensaje.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos: ' . $e->getMessage()]);
}
