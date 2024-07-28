<?php
require_once 'conexion.php';

// Obtener todas las facultades
$stmt = $pdo->prepare("SELECT Nombre FROM Facultad");
$stmt->execute();
$facultades = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($facultades as $facultad) {
    echo "<option value=\"" . htmlspecialchars($facultad) . "\">" . htmlspecialchars($facultad) . "</option>";
}
