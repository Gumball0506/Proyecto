<?php
include 'conexion.php';

$hoy = date('Y-m-d');
$notificacion_evento = date('Y-m-d', strtotime($hoy . ' + 3 days'));

try {
    $sql = "SELECT ID_Proyecto, Titulo, descripcion, Fecha_Inicio FROM proyectos WHERE Fecha_Inicio <= :notificacion_evento AND Fecha_Inicio >= :hoy";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':notificacion_evento', $notificacion_evento);
    $stmt->bindParam(':hoy', $hoy);
    $stmt->execute();

    $eventos_proximos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($eventos_proximos);
} catch (PDOException $e) {
    echo 'Error en la consulta: ' . $e->getMessage();
}

