<?php
header('Content-Type: application/json');
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
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Consulta para obtener el total de proyectos por mes
    $stmtProjects = $pdo->query("
    SELECT MONTHNAME(Fecha_Publicacion) AS Mes, COUNT(*) AS Total
    FROM proyectos
    GROUP BY Mes
    ORDER BY MONTH(Fecha_Publicacion)
");
    $projectCounts = $stmtProjects->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obtener las vistas por mes
    $stmtViews = $pdo->query("
    SELECT MONTHNAME(p.Fecha_Publicacion) AS Mes, SUM(vt.total_vistas) AS Total_Vistas
    FROM vistas_totales vt
    JOIN proyectos p ON vt.ID_Proyecto = p.ID_Proyecto
    GROUP BY Mes
    ORDER BY MONTH(p.Fecha_Publicacion)
");
    $viewsByMonth = $stmtViews->fetchAll(PDO::FETCH_ASSOC);


    // Respuesta
    echo json_encode(['projectCounts' => $projectCounts, 'viewsByMonth' => $viewsByMonth]);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    echo json_encode(['error' => 'Error al cargar los datos.']);
}
