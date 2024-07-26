<?php
require 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Manejar solicitud GET para obtener datos
    try {
        $stmt = $pdo->query('SELECT ID_ProyectoA, Nombres_Apellidos, Titulo_Proyecto, Codigo_alumno, Proceso FROM proyectos_alumnos');
        $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'solicitudes' => $solicitudes]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al obtener solicitudes: ' . $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejar solicitud POST para actualizar estado
    $data = json_decode(file_get_contents('php://input'), true);
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
                $mail->Subject = 'Notificacion de Aceptacion de Proyecto';

                // Cuerpo del correo en formato HTML
                $mail->Body = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333;
                    }
                    .header {
                        background-color: #f2f2f2;
                        padding: 10px;
                        text-align: center;
                    }
                    .content {
                        padding: 20px;
                    }
                    .footer {
                        font-size: 0.8em;
                        color: #777;
                        text-align: center;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Notificacion de Aceptacion</h1>
                </div>
                <div class="content">
                    <p>Estimado/a <strong>' . $proyecto['Nombres_Apellidos'] . '</strong>,</p>
                    <p>Nos complace informarle que su proyecto "<strong>' . $proyecto['Titulo_Proyecto'] . '</strong>" ha sido aceptado.</p>
                    <p>El proyecto sera gestionado en los proximos dias. Agradecemos su participacion y compromiso con la responsabilidad social.</p>
                    <p>Atentamente,<br>El equipo de gestion</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date("Y") . ' Responsabilidad Social Universitaria. Todos los derechos reservados.</p>
                </div>
            </body>
            </html>
        ';

                // Enviar el correo
                $mail->send();
            }
        }

        echo json_encode(['success' => true, 'message' => 'Estado cambiado exitosamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado']);
}
