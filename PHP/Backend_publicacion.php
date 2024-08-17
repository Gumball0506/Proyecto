<?php

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


require_once 'conexion.php'; // Asegúrate de que este archivo incluye la conexión a tu base de datos

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejar las diferentes acciones según el valor de $_POST['accion']
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'guardar_proyecto':
            try {
                $titulo = $_POST['titulo'] ?? '';
                $descripcion = $_POST['descripcion'] ?? '';
                $url_registro = $_POST['url_registro'] ?? '';
                $eventDate = $_POST['eventDate'] ?? '';
                $estado_id = $_POST['projectStatus'];
                $foto_temporal = $_FILES['foto']['tmp_name'] ?? null;

                // Validación simple
                if (empty($titulo) || empty($descripcion) || empty($url_registro) || empty($eventDate)) {
                    throw new Exception('Datos incompletos.');
                }

                if (!empty($foto_temporal) && is_uploaded_file($foto_temporal)) {
                    $foto_contenido = file_get_contents($foto_temporal);
                } else {
                    $foto_contenido = null;
                }

                $sql = "INSERT INTO proyectos (Titulo, Descripcion, Foto, Fecha_inicio, url_registro, ID_Estado, ID_Admin) VALUES (?, ?, ?, ?, ?, ?, '1')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$titulo, $descripcion, $foto_contenido, $eventDate, $url_registro, $estado_id]);

                $response = ['success' => true];
                echo json_encode($response);
            } catch (PDOException $e) {
                $response = ['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()];
                echo json_encode($response);
            } catch (Exception $e) {
                $response = ['success' => false, 'error' => $e->getMessage()];
                echo json_encode($response);
            }
            break;
        case 'cambiar_estado':
            // Cambiar el estado de un proyecto
            try {
                $id_proyecto = $_POST['ID_Proyecto'];
                $nuevo_estado = $_POST['Estado'];

                $sql = "UPDATE proyectos SET ID_Estado = ? WHERE ID_Proyecto = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $nuevo_estado);
                $stmt->bindParam(2, $id_proyecto);
                $stmt->execute();

                $response = array('success' => true);
            } catch (Exception $e) {
                $response = array('success' => false, 'error' => $e->getMessage());
            }
            echo json_encode($response);
            break;
        case 'calificar_proyecto':
            $proyecto_id = $_POST['proyecto_id'] ?? '';
            $calificacion = $_POST['calificacion'] ?? '';
            $mensaje = $_POST['mensaje'] ?? '';
            $comentario = $_POST['comentario'] ?? '';
            $id_estudiante = $_SESSION['user_id'] ?? ''; // Asegúrate de que el ID del estudiante esté en la sesión

            if ($proyecto_id && $calificacion && $id_estudiante) {
                // Inserta o actualiza la calificación del proyecto
                $stmt = $pdo->prepare(
                    "INSERT INTO calificaciones (ID_Proyecto, ID_Estudiante, calificacion, mensaje, comentario) 
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                            calificacion = VALUES(calificacion), 
                            mensaje = VALUES(mensaje), 
                            comentario = VALUES(comentario)"
                );

                if ($stmt->execute([$proyecto_id, $id_estudiante, $calificacion, $mensaje, $comentario])) {
                    $response['success'] = true;
                }
            }
            break;
        case 'eliminar_proyecto':
            try {
                $ID_Proyecto = $_POST['ID_Proyecto'];

                // Eliminar proyecto
                $stmt = $pdo->prepare("DELETE FROM proyectos WHERE ID_Proyecto = ?");
                $stmt->bindParam(1, $ID_Proyecto);
                $stmt->execute();

                $response = array('success' => true);
            } catch (Exception $e) {
                $response = array('success' => false, 'error' => $e->getMessage());
            }
            echo json_encode($response);
            break;

        case 'incrementar_vistas':
            try {
                $ID_Proyecto = $_POST['ID_Proyecto'];

                $sql = "INSERT INTO vistas (ID_Proyecto, fecha_vista) VALUES (?, NOW())"; // Utiliza NOW() para insertar la fecha actual
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $ID_Proyecto);
                $stmt->execute();

                $response = array('success' => true);
            } catch (Exception $e) {
                $response = array('success' => false, 'error' => $e->getMessage());
            }
            echo json_encode($response);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? '';

    switch ($accion) {
        case 'obtener_proyectos':
            try {
                $stmt = $pdo->query("SELECT p.ID_Proyecto, p.Titulo, p.Descripcion, p.Foto, p.url_registro, COALESCE(v.total_vistas, 0) AS total_vistas, e.ID_Estado AS Estado 
                                    FROM proyectos p
                                    LEFT JOIN vistas_totales v ON p.ID_Proyecto = v.ID_Proyecto
                                    LEFT JOIN estado e ON p.ID_Estado = e.ID_Estado");
                $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($proyectos as &$proyecto) {
                    $proyecto['Foto'] = base64_encode($proyecto['Foto']);
                }

                echo json_encode($proyectos);
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'error' => $e->getMessage()));
            }
            break;
    }
} else {
    echo json_encode(array('success' => false, 'error' => 'Método de solicitud no soportado'));
}
