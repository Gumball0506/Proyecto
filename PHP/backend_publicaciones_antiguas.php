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
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'eliminar_proyectos':
            $ids = isset($_POST['ids']) ? explode(',', $_POST['ids']) : [];

            if (!empty($ids)) {
                try {
                    // Asegúrate de que los IDs se traten como enteros para evitar inyecciones SQL
                    $ids = array_map('intval', $ids);
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $stmt = $pdo->prepare("DELETE FROM proyectos_antiguos WHERE ID_Proyecto IN ($placeholders)");
                    $stmt->execute($ids);

                    echo json_encode(array('success' => true));
                } catch (Exception $e) {
                    echo json_encode(array('success' => false, 'error' => $e->getMessage()));
                }
            } else {
                echo json_encode(array('success' => false, 'error' => 'No se proporcionaron IDs.'));
            }
            break;
        default:
            echo json_encode(array('success' => false, 'error' => 'Acción no válida'));
            break;
    }
} else {
    echo json_encode(array('success' => false, 'error' => 'Método no soportado'));
}
