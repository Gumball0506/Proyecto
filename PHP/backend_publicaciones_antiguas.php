<?php
require_once 'conexion.php'; // Asegúrate de que este archivo incluya la conexión a tu base de datos
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

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? '';

    switch ($accion) {
        case 'obtener_proyectos':
            try {
                $stmt = $pdo->query("SELECT ID_Proyecto, Titulo, Foto FROM proyectos_antiguos");
                $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Decodificar imágenes base64 para envío al frontend
                foreach ($proyectos as &$proyecto) {
                    $proyecto['Foto'] = base64_encode($proyecto['Foto']);
                }

                echo json_encode($proyectos);
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'error' => $e->getMessage()));
            }
            break;
        default:
            echo json_encode(array('success' => false, 'error' => 'Acción no válida'));
            break;
    }
} else {
    echo json_encode(array('success' => false, 'error' => 'Método no soportado'));
}
