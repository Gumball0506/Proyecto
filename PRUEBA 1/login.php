<?php
session_start(); // Iniciar sesión

// Detalles de la conexión a la base de datos
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

// Verifica si los datos han sido enviados
if (isset($_POST['email'], $_POST['codigo'])) {
    $email = $_POST['email'];
    $codigo = $_POST['codigo'];

    try {
        // Preparar y ejecutar la consulta SQL
        $sql = "SELECT * FROM estudiantes WHERE Email = :email AND Codigo_Estudiante = :codigo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email, 'codigo' => $codigo]);

        if ($stmt->rowCount() > 0) {
            // Autenticación correcta
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $row['ID_Estudiante']; // Asignar el ID del estudiante a la sesión
            $_SESSION['email'] = $email;

            // Redirigir a la página de verificación
            header("Location: /PHP/Backend_calificacion.php");
            exit();
        } else {
            // Autenticación fallida
            header("Location: /PRUEBA%201/prueba.php"); // Redirigir a la página de login
            exit();
        }
    } catch (PDOException $e) {
        error_log("Error en la consulta: " . $e->getMessage());
        die("Error en la consulta: " . $e->getMessage());
    }
} else {
    // Manejo de error si no se enviaron los datos
    echo "Por favor, completa todos los campos.";
}
