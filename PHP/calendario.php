<?php
require_once 'conexion.php';
/*
    ----------------------------------------------------
    Anti-Copyright
    ----------------------------------------------------
    Este trabajo es realizado por:
    - Harold Ortiz Abra Loza
    - William Vega
    - Sergio Vidal
    - Elizabeth Campos
    - Lily Roque
    ----------------------------------------------------
    © 2024 Responsabilidad Social Universitaria. 
    Todos los derechos reservados.
    ----------------------------------------------------
*/

try {
    $stmt = $pdo->query("SELECT titulo, fecha_evento, url_registro FROM calendario");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
