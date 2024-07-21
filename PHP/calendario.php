<?php
require_once 'conexion.php';

try {
    $stmt = $pdo->query("SELECT titulo, fecha_evento, url_registro FROM calendario");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
