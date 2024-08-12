<?php
require_once 'conexion.php';
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
// Obtener todas las facultades
$stmt = $pdo->prepare("SELECT Nombre FROM Facultad");
$stmt->execute();
$facultades = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($facultades as $facultad) {
    echo "<option value=\"" . htmlspecialchars($facultad) . "\">" . htmlspecialchars($facultad) . "</option>";
}
