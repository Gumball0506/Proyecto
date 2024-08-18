<?php
// Conexión a la base de datos
include 'conexion.php';

try {
    // Consulta SQL con INNER JOIN para obtener los detalles de los estudiantes junto con los mensajes
    $query = $pdo->prepare("
        SELECT 
            m.ID_Mensaje, 
            e.Nombre, 
            e.Apellido, 
            e.Email, 
            m.Fecha AS Fecha_Envio, 
            m.Asunto, 
            m.Mensaje 
        FROM 
            mensajes m
        INNER JOIN 
            estudiantes e ON m.ID_Usuario = e.ID_Estudiante
    ");
    $query->execute();
    $mensajes = $query->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los mensajes en formato JSON
    echo json_encode(['success' => true, 'mensajes' => $mensajes]);
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode(['success' => false, 'message' => 'Error al obtener los mensajes: ' . $e->getMessage()]);
}
