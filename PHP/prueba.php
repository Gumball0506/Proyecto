<?php
// conectar.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/*
    ----------------------------------------------------
    Anti-Copyright
    ----------------------------------------------------
    Este trabajo es realizado por:
    - Harold Ortiz Abra Loza
    - William Vega
    - Sergio Vidal
    - Elizabeth Campos
    - Lily Roque
    ----------------------------------------------------
    © 2024 Responsabilidad Social Universitaria. 
    Todos los derechos reservados.
    ----------------------------------------------------
*/

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'conexion.php'; // Asegúrate de que este archivo tenga tu configuración de conexión a la base de datos

// Recibir la solicitud AJAX
$data = json_decode(file_get_contents("php://input"), true);
$idProyecto = $data['id'];
$estado = $data['estado'];

try {
    // Cambiar el estado en la base de datos
    $stmt = $pdo->prepare("UPDATE proyectos_alumnos SET Proceso = :estado WHERE ID_ProyectoA = :id");
    $stmt->execute(['estado' => $estado, 'id' => $idProyecto]);

    // Si el estado es "Aceptado", enviar el correo
    if ($estado === 'Aceptado') {
        $stmtCorreo = $pdo->prepare("SELECT Correo_Electronico, Titulo_Proyecto, Nombres_Apellidos FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
        $stmtCorreo->execute(['id' => $idProyecto]);
        $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {
            $mail = new PHPMailer(true);
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'haroldortiz@outlook.es';
            $mail->Password = 'bicicleta123';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configurar el correo
            $mail->setFrom('haroldortiz@outlook.es', 'Harold Ortiz');
            $mail->addAddress($proyecto['Correo_Electronico'], $proyecto['Nombres_Apellidos']);
            $mail->isHTML(true);
            $mail->Subject = 'Su proyecto ha sido aceptado';
            $mail->Body    = '<h1>Su proyecto ha sido aceptado</h1><p>Su proyecto "' . $proyecto['Titulo_Proyecto'] . '" ha sido aceptado y será gestionado en estos días. Muchas gracias por su participación.</p>';
            $mail->AltBody = 'Su proyecto "' . $proyecto['Titulo_Proyecto'] . '" ha sido aceptado y será gestionado en estos días. Muchas gracias por su participación.';

            // Enviar el correo
            $mail->send();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Estado cambiado exitosamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
