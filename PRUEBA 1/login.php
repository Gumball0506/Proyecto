<?php
session_start(); // Iniciar sesión para manejar la sesión del usuario

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

// Obtener los datos del formulario
$email = $_POST['email'];
$codigo = $_POST['codigo'];

try {
    // Preparar y ejecutar la consulta SQL
    $sql = "SELECT * FROM estudiantes WHERE Email = :email AND Codigo_Estudiante = :codigo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email, 'codigo' => $codigo]);

    if ($stmt->rowCount() > 0) {
        // Autenticación correcta, iniciar sesión
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $row['ID_Estudiante'];
        $_SESSION['email'] = $email;

        // Redirigir a la página de inicio o dashboard
        header("Location: /html/web1.php");
        exit();
    } else {
        // Autenticación fallida
        echo "Correo electrónico o código incorrecto.";
        header("Location: /html/web1.php"); // Redirigir a la página de login para limpiar campos
        exit();
    }
} catch (PDOException $e) {
    error_log("Error en la consulta: " . $e->getMessage());
    die("Error en la consulta: " . $e->getMessage());
}
