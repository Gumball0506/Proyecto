<?php
session_start();
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'verificar_codigo':
            $codigo_estudiante = $_POST['codigo_estudiante'] ?? '';
            if (!empty($codigo_estudiante)) {
                $stmt = $pdo->prepare('SELECT ID_Estudiante FROM estudiantes WHERE Codigo_Estudiante = ?');
                $stmt->execute([$codigo_estudiante]);
                $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($estudiante) {
                    echo json_encode(['existe' => true, 'id_estudiante' => $estudiante['ID_Estudiante']]);
                } else {
                    echo json_encode(['existe' => false]);
                }
            } else {
                echo json_encode(['existe' => false]);
            }
            break;
        case 'verificar_codigo_admin':
            $codigo_admin = $_POST['codigo_admin'] ?? '';
            if (!empty($codigo_admin)) {
                $stmt = $pdo->prepare('SELECT ID_Admin FROM administradores WHERE Codigo_Admin = ?');
                $stmt->execute([$codigo_admin]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($admin) {
                    echo json_encode(['existe' => true, 'id_admin' => $admin['ID_Admin']]);
                } else {
                    echo json_encode(['existe' => false]);
                }
            } else {
                echo json_encode(['existe' => false]);
            }
            break;
        case 'calificar_proyecto':
            $codigo_estudiante = $_POST['codigo_estudiante'] ?? '';
            $id_proyecto = $_POST['proyecto_id'] ?? '';
            $calificacion = $_POST['calificacion'] ?? '';

            if (!empty($codigo_estudiante) && !empty($id_proyecto) && !empty($calificacion)) {
                // Verificar el código del estudiante
                $stmt = $pdo->prepare('SELECT ID_Estudiante FROM estudiantes WHERE Codigo_Estudiante = ?');
                $stmt->execute([$codigo_estudiante]);
                $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($estudiante) {
                    $id_estudiante = $estudiante['ID_Estudiante'];

                    // Verificar si el estudiante ya ha calificado el proyecto
                    $stmt = $pdo->prepare('SELECT ID_Calificacion FROM calificaciones WHERE ID_Estudiante = ? AND ID_Proyecto = ?');
                    $stmt->execute([$id_estudiante, $id_proyecto]);
                    $calificacionExistente = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($calificacionExistente) {
                        // Actualizar calificación existente
                        try {
                            $stmt = $pdo->prepare('UPDATE calificaciones SET calificacion = ? WHERE ID_Estudiante = ? AND ID_Proyecto = ?');
                            $stmt->execute([$calificacion, $id_estudiante, $id_proyecto]);
                            echo json_encode(['success' => true, 'mensaje' => 'Calificación actualizada correctamente.']);
                        } catch (PDOException $e) {
                            error_log("Error al actualizar calificación: " . $e->getMessage());
                            echo json_encode(['success' => false, 'error' => 'Error al actualizar la calificación']);
                        }
                    } else {
                        // Insertar nueva calificación
                        try {
                            $stmt = $pdo->prepare('INSERT INTO calificaciones (ID_Proyecto, ID_Estudiante, calificacion) VALUES (?, ?, ?)');
                            $stmt->execute([$id_proyecto, $id_estudiante, $calificacion]);
                            echo json_encode(['success' => true, 'mensaje' => 'Calificación guardada correctamente.']);
                        } catch (PDOException $e) {
                            error_log("Error al guardar calificación: " . $e->getMessage());
                            echo json_encode(['success' => false, 'error' => 'Error al guardar la calificación']);
                        }
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Código de estudiante incorrecto.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            }
            break;
        case 'verificar_calificacion':
            $codigo_estudiante = $_POST['codigo_estudiante'] ?? '';
            $id_proyecto = $_POST['proyecto_id'] ?? '';

            if (!empty($codigo_estudiante) && !empty($id_proyecto)) {
                // Verificar el código del estudiante
                $stmt = $pdo->prepare('SELECT ID_Estudiante FROM estudiantes WHERE Codigo_Estudiante = ?');
                $stmt->execute([$codigo_estudiante]);
                $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($estudiante) {
                    $id_estudiante = $estudiante['ID_Estudiante'];

                    // Verificar si el estudiante ya ha calificado el proyecto
                    $stmt = $pdo->prepare('SELECT ID_Calificacion FROM calificaciones WHERE ID_Estudiante = ? AND ID_Proyecto = ?');
                    $stmt->execute([$id_estudiante, $id_proyecto]);
                    $calificacionExistente = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($calificacionExistente) {
                        echo json_encode(['calificado' => true]);
                    } else {
                        echo json_encode(['calificado' => false]);
                    }
                } else {
                    echo json_encode(['calificado' => false]);
                }
            } else {
                echo json_encode(['calificado' => false]);
            }
            break;
    }
}
