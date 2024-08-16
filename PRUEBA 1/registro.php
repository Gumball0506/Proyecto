<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proyecto_integrador";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$codigo = $_POST['codigo'];
$id_facultad = $_POST['facultad'];

// Preparar la sentencia SQL
$sql = "INSERT INTO estudiantes (Nombre, Apellido, Email, Codigo_Estudiante, ID_Facultad)
        VALUES ('$nombre', '$apellido', '$email', '$codigo', '$id_facultad')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
