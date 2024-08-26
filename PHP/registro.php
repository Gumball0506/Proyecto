<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proyecto_integrador";

try {
    // Crear una conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Configurar el manejo de errores de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $codigo = $_POST['codigo'];
    $id_facultad = $_POST['facultad'];

    // Verificar si el email o el código ya están registrados
    $sql_check = "SELECT * FROM estudiantes WHERE Email = :email OR Codigo_Estudiante = :codigo";
    $stmt = $conn->prepare($sql_check);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
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
            <p>⚠️ Esta cuenta ya está registrada. ⚠️</p>
        </div>
        <a href="/html/registro.html">Volver al formulario de registro</a>
    </div>
</body>
</html>';
    } else {
        // Preparar la sentencia SQL para insertar
        $sql_insert = "INSERT INTO estudiantes (Nombre, Apellido, Email, Codigo_Estudiante, ID_Facultad)
                       VALUES (:nombre, :apellido, :email, :codigo, :id_facultad)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':codigo', $codigo);
        $stmt->bindParam(':id_facultad', $id_facultad);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
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

        .success-message {
            color: #4CAF50;
            background-color: #DFF2BF;
            border: 1px solid #4CAF50;
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
        <div class="success-message">
            <p>✅ ¡Tu registro ha sido todo un éxito! 🎉</p>
        </div>
        <a href="/html/registro.html">Volver al formulario de registro</a>
    </div>
</body>
</html>';
        } else {
            echo "Error: No se pudo completar el registro.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
