<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['codigo'])) {
        $email = $_POST['email'];
        $codigo = $_POST['codigo'];

        try {
            $sql = "SELECT * FROM estudiantes WHERE Email = :email AND Codigo_Estudiante = :codigo";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['user_id'] = $row['ID_Estudiante'];
                $_SESSION['email'] = $row['Email'];
                $_SESSION['codigo_estudiante'] = $row['Codigo_Estudiante'];
                header("Location: /html/web1.php");
                exit();
            } else {
                echo "<script>alert('Correo o código de estudiante incorrectos'); window.location.href='login.php';</script>";
                exit();
            }
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Por favor, completa todos los campos.'); window.location.href='login.php';</script>";
    }
} else {
    echo "Método de solicitud no válido. Por favor, use el método POST.";
}
