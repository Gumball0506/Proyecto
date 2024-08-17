<?php
session_start();
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

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
                    try {
                        $stmt = $pdo->prepare('INSERT INTO calificaciones (ID_Proyecto, ID_Estudiante, calificacion) VALUES (?, ?, ?)');
                        $stmt->execute([$id_proyecto, $id_estudiante, $calificacion]);
                        echo json_encode(['success' => true]);
                    } catch (PDOException $e) {
                        error_log("Error al guardar calificación: " . $e->getMessage());
                        echo json_encode(['success' => false, 'error' => 'Error al guardar la calificación']);
                    }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Código de estudiante incorrecto.']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            }
            break;
    }
}
