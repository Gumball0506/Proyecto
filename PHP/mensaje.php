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
    if (!empty($_POST['asunto']) && !empty($_POST['mensaje'])) {
        $asunto = $_POST['asunto'];
        $mensaje = $_POST['mensaje'];
        $id_usuario = $_SESSION['estudiante_id']; // ID del usuario autenticado

        try {
            $sql = "INSERT INTO mensajes (ID_Usuario, Asunto, Mensaje, Estado) VALUES (:id_usuario, :asunto, :mensaje, 'Por Leer')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':asunto', $asunto, PDO::PARAM_STR);
            $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt->execute();

            echo "<script>alert('Mensaje enviado con éxito.'); window.location.href='/html/web1.php';</script>";
        } catch (PDOException $e) {
            die("Error al enviar el mensaje: " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Por favor, completa todos los campos.'); window.location.href='enviar_mensaje.php';</script>";
    }
} else {
    echo "Método de solicitud no válido. Por favor, use el método POST.";
}
