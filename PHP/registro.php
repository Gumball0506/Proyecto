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

// Verificar si el email o el código ya están registrados
$sql_check = "SELECT * FROM estudiantes WHERE Email = ? OR Codigo_Estudiante = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ss", $email, $codigo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si hay un registro existente con el mismo email o código
    echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Fallido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .error-message {
            color: #D8000C;
            background-color: #FFD2D2;
            border: 1px solid #D8000C;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-message">
            <p>Esta cuenta ya está registrada.</p>
        </div>
        <a href="/html/registro.html">Volver al formulario de registro</a>
    </div>
</body>
</html>';
} else {
    // Preparar la sentencia SQL
    $sql_insert = "INSERT INTO estudiantes (Nombre, Apellido, Email, Codigo_Estudiante, ID_Facultad)
                   VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $codigo, $id_facultad);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Registro exitoso.";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Cerrar la conexión
$stmt->close();
$conn->close();
