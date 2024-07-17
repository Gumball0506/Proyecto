<?php
include_once 'conexion.php'; // Asegúrate de incluir correctamente el archivo de conexión

// Verificar si se recibió el ID del proyecto como parámetro
if (isset($_GET['id'])) {
    $id_proyecto = $_GET['id'];

    // Consulta SQL para obtener los detalles del proyecto según el ID
    $sql = "SELECT * FROM proyectos_antiguos WHERE ID_Proyecto = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id_proyecto, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener resultados
        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si se encontró algún resultado
        if ($proyecto) {
            // Devolver los detalles del proyecto como respuesta JSON
            echo json_encode($proyecto);
        } else {
            // Si no se encuentra ningún proyecto con ese ID, devolver un error 404
            http_response_code(404);
            echo json_encode(array('message' => 'Proyecto no encontrado'));
        }
    } catch (PDOException $e) {
        // Si ocurre un error en la consulta, devolver un error 500
        http_response_code(500);
        echo json_encode(array('message' => 'Error al obtener detalles del proyecto: ' . $e->getMessage()));
    }
} else {
    // Si no se proporciona un ID, devolver un error 400
    http_response_code(400);
    echo json_encode(array('message' => 'ID de proyecto no proporcionado'));
}
