<?php
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';
session_start();

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

$idProyecto = $_GET['id'] ?? null;

if ($idProyecto) {
    try {
        $stmt = $pdo->prepare('
            SELECT
                p.Titulo_Proyecto,
                p.Descripcion,
                p.Fecha_Envio,
                e.Nombre,
                e.Apellido,
                e.Email
            FROM
                proyectos_alumnos p
            JOIN
                estudiantes e ON p.ID_Estudiante = e.ID_Estudiante
            WHERE
                p.ID_ProyectoA = :idProyecto
        ');

        $stmt->bindParam(':idProyecto', $idProyecto, PDO::PARAM_INT);
        $stmt->execute();
        $detalleProyecto = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        $detalleProyecto = [];
    }
} else {
    echo 'ID de proyecto no proporcionado.';
    exit;
}
?>
<p><strong>Título:</strong> <?php echo htmlspecialchars($detalleProyecto['Titulo_Proyecto']); ?></p>
<p><strong>Descripción:</strong> <?php echo htmlspecialchars($detalleProyecto['Descripcion']); ?></p>
<p><strong>Fecha de Envío:</strong> <?php echo htmlspecialchars($detalleProyecto['Fecha_Envio']); ?></p>
<p><strong>Enviado por:</strong> <?php echo htmlspecialchars($detalleProyecto['Nombre'] . ' ' . $detalleProyecto['Apellido']); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($detalleProyecto['Email']); ?></p>