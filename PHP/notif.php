<?php
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

$hoy = date('Y-m-d');
$notificacion_evento = date('Y-m-d', strtotime($hoy . ' + 3 days'));

try {
    $sql = "SELECT ID_Proyecto, Titulo, descripcion, Fecha_Inicio FROM proyectos WHERE Fecha_Inicio <= :notificacion_evento AND Fecha_Inicio >= :hoy";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':notificacion_evento', $notificacion_evento);
    $stmt->bindValue(':hoy', $hoy);
    $stmt->execute();

    $eventos_proximos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($eventos_proximos);
} catch (PDOException $e) {
    echo 'Error en la consulta: ' . $e->getMessage();
}
