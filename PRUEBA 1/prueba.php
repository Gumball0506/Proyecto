<?php
session_start(); // Iniciar sesión

// Mostrar el contenido actual de la sesión para depuración
echo "<pre>";
var_dump($_SESSION); // Muestra el contenido de la sesión
echo "</pre>";

// Verificar si el ID del estudiante está presente en la sesión
if (isset($_SESSION['estudiante_id'])) {
    echo "El ID del estudiante ha sido guardado y reconocido correctamente.<br>";
    echo "ID del estudiante: " . $_SESSION['estudiante_id'] . "<br>";
    echo "Correo electrónico: " . $_SESSION['email'] . "<br>";
    echo "Código de estudiante: " . $_SESSION['codigo_estudiante'] . "<br>";
} else {
    echo "El ID del estudiante no ha sido reconocido.";
}
