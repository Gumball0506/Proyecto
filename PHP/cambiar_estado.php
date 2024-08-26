<?php
include 'conexion.php';
require 'seguimiento.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_proyecto = $_POST['id_proyecto'];
    $nuevo_estado = $_POST['estado'];

    // Depuración: Mostrar los valores recibidos
    echo "ID del proyecto: " . htmlspecialchars($id_proyecto) . "<br>";
    echo "Nuevo estado: " . htmlspecialchars($nuevo_estado) . "<br>";

    // Verifica si los valores no están vacíos
    if (!empty($id_proyecto) && !empty($nuevo_estado)) {
        $sql = "UPDATE proyectos_alumnos SET Proceso = :nuevo_estado WHERE ID_ProyectoA = :id_proyecto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nuevo_estado', $nuevo_estado);
        $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Estado actualizado con éxito.";
            echo "<br>Filas afectadas: " . $stmt->rowCount();
             header("Location: b.php"); // Redirige de nuevo a la lista de proyectos
            // exit();

        
 // Redirige de nuevo a la lista de proyectos
            exit();
        } else {
            echo "Error al actualizar el estado.";
        }
    } else {
        echo "Error: ID del proyecto o estado no válido.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
