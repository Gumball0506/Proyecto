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
        case 'agregar_comentario':
            try {
                // Asegúrate de que el usuario está autenticado
                if (!isset($_SESSION['user_id'])) {
                    echo json_encode(['success' => false, 'message' => 'No estás autenticado.']);
                    exit();
                }

                // Obtener los datos del cuerpo de la solicitud
                $input = json_decode(file_get_contents('php://input'), true);

                // Depurar datos recibidos
                error_log(print_r($input, true));

                // Verificar que se recibió el comentario y el ID del proyecto
                if (isset($input['accion']) && $input['accion'] === 'agregar_comentario' && isset($input['ID_Proyecto']) && isset($input['comentario'])) {
                    $id_proyecto = $input['ID_Proyecto'];
                    $comentario = trim($input['comentario']);
                    $id_estudiante = $_SESSION['user_id'];

                    // Validar datos
                    if (empty($comentario) || empty($id_proyecto)) {
                        echo json_encode(['success' => false, 'error' => 'Comentario o ID del proyecto vacío']);
                        exit;
                    }

                    // Insertar el comentario en la base de datos
                    $sql = "INSERT INTO comentarios (ID_Proyecto, ID_Estudiante, Comentario, Fecha) VALUES (:id_proyecto, :id_estudiante, :comentario, NOW())";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        'id_proyecto' => $id_proyecto,
                        'id_estudiante' => $id_estudiante,
                        'comentario' => $comentario,
                    ]);

                    // Devolver una respuesta en formato JSON
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
                }
            } catch (PDOException $e) {
                error_log("Error en la base de datos: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()]);
            } catch (Exception $e) {
                error_log("Error: " . $e->getMessage());
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;

        case 'editar_comentario':
            try {
                $id_comentario = $_POST['ID_Comentario'];
                $nuevo_comentario = $_POST['Comentario'];

                // Verificar si el comentario pertenece al usuario actual
                $sql = "SELECT ID_Estudiante FROM comentarios WHERE ID_Comentario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_comentario]);
                $comentario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($comentario && $comentario['ID_Estudiante'] === $_SESSION['user_id']) {
                    // Permitir la edición
                    $sql = "UPDATE comentarios SET Comentario = ? WHERE ID_Comentario = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nuevo_comentario, $id_comentario]);

                    $response = ['success' => true];
                } else {
                    $response = ['success' => false, 'error' => 'No tienes permiso para editar este comentario.'];
                }
            } catch (PDOException $e) {
                $response = ['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()];
            }
            echo json_encode($response);
            break;

        case 'eliminar_comentario':
            try {
                $id_comentario = $_POST['ID_Comentario'];

                // Verificar si el comentario pertenece al usuario actual
                $sql = "SELECT ID_Estudiante FROM comentarios WHERE ID_Comentario = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$id_comentario]);
                $comentario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($comentario && $comentario['ID_Estudiante'] === $_SESSION['user_id']) {
                    // Permitir la eliminación
                    $sql = "DELETE FROM comentarios WHERE ID_Comentario = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$id_comentario]);

                    $response = ['success' => true];
                } else {
                    $response = ['success' => false, 'error' => 'No tienes permiso para eliminar este comentario.'];
                }
            } catch (PDOException $e) {
                $response = ['success' => false, 'error' => 'Error en la base de datos: ' . $e->getMessage()];
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
