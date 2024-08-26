<?php
include 'conexion.php';
// Consulta para obtener todos los proyectos
$sql = "SELECT ID_ProyectoA, Titulo_Proyecto, Proceso FROM proyectos_alumnos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($proyectos) > 0) {
    foreach ($proyectos as $proyecto) {
        echo "<h3>" . htmlspecialchars($proyecto['Titulo_Proyecto']) . "</h3>";
        echo "<p>Estado actual: " . htmlspecialchars($proyecto['Proceso']) . "</p>";
        echo "<form action='cambiar_estado.php' method='POST'>";
        echo "<input type='hidden' name='id_proyecto' value='" . htmlspecialchars($proyecto['ID_ProyectoA']) . "'>";
        echo "<button type='submit' name='estado' value='Proceso'>En progreso</button>";
        echo "<button type='submit' name='estado' value='Aceptado'>Aceptado</button>";
        echo "<button type='submit' name='estado' value='Rechazado'>Rechazado</button>";
        echo "</form>";
    }
} else {
    echo "<p>No hay proyectos disponibles.</p>";
}
