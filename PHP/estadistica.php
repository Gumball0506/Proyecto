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

    // Consulta para obtener las vistas totales de cada proyecto
    $stmt = $pdo->query("
        SELECT vt.ID_Proyecto, vt.total_vistas, p.Titulo 
        FROM vistas_totales vt
        JOIN proyectos p ON vt.ID_Proyecto = p.ID_Proyecto
    ");
    $totalViews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener el total de vistas de la página
    $stmtPageViews = $pdo->query("SELECT SUM(view_count) as total_page_views FROM page_views");
    $totalPageViews = $stmtPageViews->fetch(PDO::FETCH_ASSOC)['total_page_views'] ?? 0;

    // Respuesta
    echo json_encode(['total_views' => $totalViews, 'total_page_views' => $totalPageViews]);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    echo json_encode(['error' => 'Error al cargar los datos.']);
}
