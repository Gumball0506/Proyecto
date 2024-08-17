<?php
session_start();
include 'conexion.php'; // Incluye la conexión a la base de datos

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$id_usuario = $_SESSION['user_id'];

if ($accion === 'guardar_calificacion') {
    $id_proyecto = $_POST['ID_Proyecto'];
    $calificacion = $_POST['calificacion'];

    // Verifica si el usuario ya ha calificado este proyecto
    $query = $pdo->prepare("SELECT COUNT(*) FROM calificaciones WHERE ID_Proyecto = :id_proyecto AND ID_Usuario = :id_usuario");
    $query->execute([':id_proyecto' => $id_proyecto, ':id_usuario' => $id_usuario]);
    $existe = $query->fetchColumn();

    if ($existe) {
        // Actualizar calificación existente
        $query = $pdo->prepare("UPDATE calificaciones SET Calificacion = :calificacion WHERE ID_Proyecto = :id_proyecto AND ID_Usuario = :id_usuario");
        $query->execute([':calificacion' => $calificacion, ':id_proyecto' => $id_proyecto, ':id_usuario' => $id_usuario]);
    } else {
        // Insertar nueva calificación
        $query = $pdo->prepare("INSERT INTO calificaciones (ID_Proyecto, ID_Usuario, Calificacion) VALUES (:id_proyecto, :id_usuario, :calificacion)");
        $query->execute([':id_proyecto' => $id_proyecto, ':id_usuario' => $id_usuario, ':calificacion' => $calificacion]);
    }

    echo json_encode(['success' => true]);
} elseif ($accion === 'obtener_calificacion') {
    $id_proyecto = $_GET['ID_Proyecto'];

    $query = $pdo->prepare("SELECT Calificacion FROM calificaciones WHERE ID_Proyecto = :id_proyecto AND ID_Usuario = :id_usuario");
    $query->execute([':id_proyecto' => $id_proyecto, ':id_usuario' => $id_usuario]);
    $calificacion = $query->fetchColumn();

    echo json_encode(['success' => true, 'rating' => $calificacion ?: 0]);
} else {
    echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
