<?php
// Backend_publicacion.php

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

                $sql = "INSERT INTO proyectos (Titulo, Descripcion, Foto, Fecha_inicio, url_registro, ID_Estado) VALUES (?, ?, ?, ?, ?, ?)";
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
        case 'agregar_comentario':
            try {
                $ID_Proyecto = $_POST['ID_Proyecto'];
                $Comentario = $_POST['Comentario'];

                $sql = "INSERT INTO comentarios (ID_Proyecto, Comentario) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $ID_Proyecto);
                $stmt->bindParam(2, $Comentario);
                $stmt->execute();

                $response = array('success' => true);
            } catch (Exception $e) {
                $response = array('success' => false, 'error' => $e->getMessage());
            }
            echo json_encode($response);
            break;

        case 'editar_comentario':
            try {
                $ID_Comentario = $_POST['ID_Comentario'];
                $Comentario = $_POST['Comentario'];

                $sql = "UPDATE comentarios SET Comentario = ? WHERE ID_Comentario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $Comentario);
                $stmt->bindParam(2, $ID_Comentario);
                $stmt->execute();

                $response = array('success' => true);
            } catch (Exception $e) {
                $response = array('success' => false, 'error' => $e->getMessage());
            }
            echo json_encode($response);
            break;

        case 'eliminar_comentario':
            try {
                $ID_Comentario = $_POST['ID_Comentario'];

                // Obtener el ID del proyecto antes de eliminar el comentario
                $stmt = $pdo->prepare("SELECT ID_Proyecto FROM comentarios WHERE ID_Comentario = ?");
                $stmt->bindParam(1, $ID_Comentario);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $ID_Proyecto = $row['ID_Proyecto'];

                $sql = "DELETE FROM comentarios WHERE ID_Comentario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(1, $ID_Comentario);
                $stmt->execute();

                $response = array('success' => true, 'proyecto_id' => $ID_Proyecto);
            } catch (Exception $e) {
                $response = array('success' => false, 'error' => $e->getMessage());
            }
            echo json_encode($response);
            break;

        case 'eliminar_proyecto':
            try {
                $ID_Proyecto = $_POST['ID_Proyecto'];

                // Eliminar comentarios asociados al proyecto
                $stmt = $pdo->prepare("DELETE FROM comentarios WHERE ID_Proyecto = ?");
                $stmt->bindParam(1, $ID_Proyecto);
                $stmt->execute();

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

        case 'obtener_comentarios':
            try {
                $ID_Proyecto = $_GET['ID_Proyecto'];

                $stmt = $pdo->prepare("SELECT ID_Comentario, Comentario FROM comentarios WHERE ID_Proyecto = ?");
                $stmt->bindParam(1, $ID_Proyecto);
                $stmt->execute();
                $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($comentarios);
            } catch (Exception $e) {
                echo json_encode(array('success' => false, 'error' => $e->getMessage()));
            }
            break;
    }
} else {
    echo json_encode(array('success' => false, 'error' => 'Método de solicitud no soportado'));
}
