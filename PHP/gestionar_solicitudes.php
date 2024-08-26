<?php
require 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query('
            SELECT pa.ID_ProyectoA, 
                   pa.Titulo_Proyecto, 
                   e.Codigo_estudiante AS Codigo_alumno, 
                   pa.Proceso, 
                   CONCAT(e.Nombre, " ", e.Apellido) AS Nombres_Apellidos, 
                   e.Email AS Correo_Electronico
            FROM proyectos_alumnos pa
            JOIN estudiantes e ON pa.ID_Estudiante = e.ID_Estudiante
        ');
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Desencriptar el correo electrónico para cada solicitud
        foreach ($solicitudes as &$solicitud) {
            $solicitud['Correo_Electronico'] = descifrar($solicitud['Correo_Electronico']);
        }

        echo json_encode(['success' => true, 'solicitudes' => $solicitudes]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener las solicitudes: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !isset($data['estado'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $idProyecto = $data['id'];
    $estado = $data['estado'];

    try {
        $stmt = $pdo->prepare("UPDATE proyectos_alumnos SET Proceso = :estado WHERE ID_ProyectoA = :id");
        $stmt->execute(['estado' => $estado, 'id' => $idProyecto]);

        echo json_encode(['success' => true, 'message' => 'Estado cambiado exitosamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado']);
}
