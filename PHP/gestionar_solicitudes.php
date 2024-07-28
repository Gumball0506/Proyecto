<?php
require 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/DSNConfigurator.php';
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
        http_response_code(500); // Cambia el código de respuesta a 500
        echo json_encode(['success' => false, 'message' => 'Error al obtener solicitudes: ' . $e->getMessage(), 'error' => $e]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejar solicitud POST para actualizar estado
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !isset($data['estado'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $idProyecto = $data['id'];
    $estado = $data['estado'];

    try {
        // Cambiar el estado en la base de datos
        $stmt = $pdo->prepare("UPDATE proyectos_alumnos SET Proceso = :estado WHERE ID_ProyectoA = :id");
        $stmt->execute(['estado' => $estado, 'id' => $idProyecto]);

        // Enviar correos según el estado
        if ($estado === 'Aceptado') {
            enviar_alumnos($idProyecto);
            enviar_profesor($idProyecto);
        } elseif ($estado === 'Rechazado') {
            proyecto_rechazado($idProyecto);
        }

        echo json_encode(['success' => true, 'message' => 'Estado cambiado exitosamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado']);
}

// Función para enviar correo a los alumnos
function enviar_alumnos($idProyecto)
{
    global $pdo;

    $stmtCorreo = $pdo->prepare("SELECT Correo_Electronico, Titulo_Proyecto, Nombres_Apellidos FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
    $stmtCorreo->execute(['id' => $idProyecto]);
    $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

    if ($proyecto) {
        $mail = new PHPMailer(true);
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haroldortiz@outlook.es'; // Cambiar por variable de entorno
        $mail->Password = 'bicicleta123'; // Cambiar por variable de entorno
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
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background-color: #f2f2f2; padding: 10px; text-align: center; }
                .content { padding: 20px; }
                .footer { font-size: 0.8em; color: #777; text-align: center; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="header"><h1>Notificacion de Aceptacion</h1></div>
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
        </html>';

        // Enviar el correo
        $mail->send();
    }
}

// Función para enviar correo al profesor
function enviar_profesor($idProyecto)
{
    global $pdo;

    $stmtArchivo = $pdo->prepare("SELECT Archivo_Proyecto, ID_Tipo_Archivo FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
    $stmtArchivo->execute(['id' => $idProyecto]);
    $archivoData = $stmtArchivo->fetch(PDO::FETCH_ASSOC);

    if ($archivoData) {
        // Recuperar el tipo de archivo
        $stmtTipo = $pdo->prepare("SELECT Tipo FROM tipo_archivo WHERE ID_Tipo_Archivo = :id_tipo");
        $stmtTipo->execute(['id_tipo' => $archivoData['ID_Tipo_Archivo']]);
        $tipoArchivo = $stmtTipo->fetch(PDO::FETCH_ASSOC);

        if ($tipoArchivo) {
            // Determinar la extensión del archivo
            $extension = match ($tipoArchivo['Tipo']) {
                'PDF' => '.pdf',
                'Word Document (.docx)' => '.docx',
                'Word Document (.doc)' => '.doc',
                'PowerPoint Presentation (.pptx)' => '.pptx',
                'PowerPoint Presentation (.ppt)' => '.ppt',
                'Text File (.txt)' => '.txt',
                'Image File (.jpg)' => '.jpg',
                'Image File (.png)' => '.png',
                default => ''
            };

            // Guardar el archivo temporalmente
            $rutaArchivoTemp = tempnam(sys_get_temp_dir(), 'proyecto_') . $extension;
            file_put_contents($rutaArchivoTemp, $archivoData['Archivo_Proyecto']);

            $stmtCorreo = $pdo->prepare("SELECT Titulo_Proyecto FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
            $stmtCorreo->execute(['id' => $idProyecto]);
            $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

            if ($proyecto) {
                $mail = new PHPMailer(true);
                // Configuración del servidor
                $mail->isSMTP();
                $mail->Host = 'smtp.office365.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'haroldortiz@outlook.es'; // Cambiar por variable de entorno
                $mail->Password = 'bicicleta123'; // Cambiar por variable de entorno
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Configurar el correo
                $mail->setFrom('haroldortiz@outlook.es', 'Harold Ortiz');
                $mail->addAddress("2021017348@unfv.edu.pe", "Sergio Vidal"); // Cambiar por el correo del profesor
                $mail->isHTML(true);
                $mail->Subject = 'Revision de envio del profesor';

                // Cuerpo del correo en formato HTML
                $mail->Body = '
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .header { background-color: #f2f2f2; padding: 10px; text-align: center; }
                        .content { padding: 20px; }
                        .footer { font-size: 0.8em; color: #777; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header"><h1>Revisión de Proyecto Aceptado</h1></div>
                    <div class="content">
                        <p>Estimado Profesor,</p>
                        <p>El proyecto titulado "<strong>' . $proyecto['Titulo_Proyecto'] . '</strong>" ha sido aceptado por uno de los alumnos.</p>
                        <p>Adjunto encontrará el archivo correspondiente.</p>
                    </div>
                    <div class="footer">
                        <p>&copy; ' . date("Y") . ' Responsabilidad Social Universitaria. Todos los derechos reservados.</p>
                    </div>
                </body>
                </html>';

                // Adjuntar el archivo
                $mail->addAttachment($rutaArchivoTemp, 'proyecto' . $extension);

                // Enviar el correo
                $mail->send();

                // Eliminar el archivo temporal
                unlink($rutaArchivoTemp);
            }
        }
    }
}

// Función para enviar correo de rechazo
function proyecto_rechazado($idProyecto)
{
    global $pdo;

    $stmtCorreo = $pdo->prepare("SELECT Correo_Electronico, Nombres_Apellidos, Titulo_Proyecto FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
    $stmtCorreo->execute(['id' => $idProyecto]);
    $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

    if ($proyecto) {
        $mail = new PHPMailer(true);
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haroldortiz@outlook.es'; // Cambiar por variable de entorno
        $mail->Password = 'bicicleta123'; // Cambiar por variable de entorno
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurar el correo
        $mail->setFrom('haroldortiz@outlook.es', 'Harold Ortiz');
        $mail->addAddress($proyecto['Correo_Electronico'], $proyecto['Nombres_Apellidos']);
        $mail->isHTML(true);
        $mail->Subject = 'Notificacion de Rechazo de Proyecto';

        // Cuerpo del correo en formato HTML
        $mail->Body = '
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background-color: #f2f2f2; padding: 10px; text-align: center; }
                .content { padding: 20px; }
                .footer { font-size: 0.8em; color: #777; text-align: center; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class="header"><h1>Notificacion de Rechazo</h1></div>
            <div class="content">
                <p>Estimado/a <strong>' . $proyecto['Nombres_Apellidos'] . '</strong>,</p>
                <p>Le informamos que su proyecto "<strong>' . $proyecto['Titulo_Proyecto'] . '</strong>" ha sido rechazado.</p>
                <p>Agradecemos su interés y le invitamos a que lo vuelva a enviar en otra oportunidad.</p>
                <p>Atentamente,<br>El equipo de gestión</p>
            </div>
            <div class="footer">
                <p>&copy; ' . date("Y") . ' Responsabilidad Social Universitaria. Todos los derechos reservados.</p>
            </div>
        </body>
        </html>';

        // Enviar el correo
        $mail->send();
    }
}
