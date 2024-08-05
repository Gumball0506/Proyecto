<?php
require_once 'conexion.php'; // Asegúrate de que esta línea cargue el archivo con la conexión PDO

// Establecer el nombre de la página
$page_name = 'pagina_publicaciones';

try {
    // Verificar si la página ya existe en la base de datos
    $sql = "SELECT view_count FROM page_views WHERE page_name = :page_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['page_name' => $page_name]);

    if ($stmt->rowCount() > 0) {
        // Si la página existe, incrementar el conteo de vistas
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $new_count = $row['view_count'] + 1;
        $sql = "UPDATE page_views SET view_count = :view_count WHERE page_name = :page_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['view_count' => $new_count, 'page_name' => $page_name]);
    } else {
        // Si la página no existe, insertar un nuevo registro
        $sql = "INSERT INTO page_views (page_name, view_count) VALUES (:page_name, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['page_name' => $page_name]);
    }
} catch (PDOException $e) {
    // Manejo de errores
    error_log("Error en la consulta: " . $e->getMessage());
    die("Error en la consulta: " . $e->getMessage());
}

// Cerrar la conexión (opcional, PDO se cierra automáticamente al final del script)
$pdo = null;
