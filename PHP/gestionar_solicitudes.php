<?php
require 'conexion.php';

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

require 'src/DSNConfigurator.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

header('Content-Type: application/json');

// Función para desencriptar el correo
function descifrar($datos)
{
    $password = 'ABCD-1234.aer'; // Contraseña de cifrado
    $metodo = 'AES-256-CBC'; // Método de cifrado
    $datos = base64_decode($datos); // Decodificar datos base64
    $ivSize = openssl_cipher_iv_length($metodo); // Tamaño del vector de inicialización
    $iv = substr($datos, 0, $ivSize); // Extraer IV
    $datosCifrados = substr($datos, $ivSize); // Extraer datos cifrados
    return openssl_decrypt($datosCifrados, $metodo, $password, OPENSSL_RAW_DATA, $iv); // Desencriptar
}

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
        } elseif ($estado === 'Rechazado') {
            proyecto_rechazado($idProyecto);
        } elseif ($estado === 'Proceso') {
            proyecto_proceso($idProyecto);
            proyecto_proceso_profesor($idProyecto);
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
        // Desencriptar el correo
        $correoDesencriptado = descifrar($proyecto['Correo_Electronico']);

        $mail = new PHPMailer(true);
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'brsocial.fiei.online';
        $mail->SMTPAuth = true;
        $mail->Username = 'rsufiei@brsocial.fiei.online'; // Cambiar por variable de entorno
        $mail->Password = 'W4ht7xxoP^eX'; // Cambiar por variable de entorno
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Configurar el correo
        $mail->setFrom('rsufiei@brsocial.fiei.online', 'Soporte_Tecnico');
        $mail->addAddress($correoDesencriptado, $proyecto['Nombres_Apellidos']);
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

// Función para manejar proyectos rechazados
function proyecto_rechazado($idProyecto)
{
    global $pdo;

    $stmtCorreo = $pdo->prepare("SELECT Correo_Electronico, Titulo_Proyecto, Nombres_Apellidos FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
    $stmtCorreo->execute(['id' => $idProyecto]);
    $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

    if ($proyecto) {
        // Desencriptar el correo
        $correoDesencriptado = descifrar($proyecto['Correo_Electronico']);

        $mail = new PHPMailer(true);
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'brsocial.fiei.online';
        $mail->SMTPAuth = true;
        $mail->Username = 'rsufiei@brsocial.fiei.online'; // Cambiar por variable de entorno
        $mail->Password = 'W4ht7xxoP^eX'; // Cambiar por variable de entorno
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Configurar el correo
        $mail->setFrom('rsufiei@brsocial.fiei.online', 'Soporte_Tecnico');
        $mail->addAddress($correoDesencriptado, $proyecto['Nombres_Apellidos']);
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
                <p>Lamentablemente, su proyecto "<strong>' . $proyecto['Titulo_Proyecto'] . '</strong>" ha sido rechazado.</p>
                <p>Le invitamos a realizar las mejoras necesarias y a presentarlo nuevamente en el futuro.</p>
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
function proyecto_proceso($idProyecto)
{
    global $pdo;

    $stmtCorreo = $pdo->prepare("SELECT Correo_Electronico, Titulo_Proyecto, Nombres_Apellidos FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
    $stmtCorreo->execute(['id' => $idProyecto]);
    $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

    if ($proyecto) {
        // Desencriptar el correo
        $correoDesencriptado = descifrar($proyecto['Correo_Electronico']);

        $mail = new PHPMailer(true);
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'brsocial.fiei.online';
        $mail->SMTPAuth = true;
        $mail->Username = 'rsufiei@brsocial.fiei.online'; // Cambiar por variable de entorno
        $mail->Password = 'W4ht7xxoP^eX'; // Cambiar por variable de entorno
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Configurar el correo
        $mail->setFrom('rsufiei@brsocial.fiei.online', 'Soporte_Tecnico');
        $mail->addAddress($correoDesencriptado, $proyecto['Nombres_Apellidos']);
        $mail->isHTML(true);
        $mail->Subject = 'Notificacion de Revision de su Proyecto';

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
            <div class="header"><h1>Notificacion de Revision</h1></div>
            <div class="content">
                <p>Estimado/a <strong>' . $proyecto['Nombres_Apellidos'] . '</strong>,</p>
                <p>Nos complace informarle que su proyecto "<strong>' . $proyecto['Titulo_Proyecto'] . '</strong>" esta en tramite de revision.</p>
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
function proyecto_proceso_profesor($idProyecto)
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

            $stmtCorreo = $pdo->prepare("SELECT Titulo_Proyecto, Correo_Electronico FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
            $stmtCorreo->execute(['id' => $idProyecto]);
            $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

            if ($proyecto) {
                $mail = new PHPMailer(true);
                // Configuración del servidor
                $mail->isSMTP();
                $mail->Host = 'brsocial.fiei.online';
                $mail->SMTPAuth = true;
                $mail->Username = 'rsufiei@brsocial.fiei.online'; // Cambiar por variable de entorno
                $mail->Password = 'W4ht7xxoP^eX'; // Cambiar por variable de entorno
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Configurar el correo
                $mail->setFrom('rsufiei@brsocial.fiei.online', 'Soporte_Tecnico');
                $mail->addAddress('jgill@unfv.edu.pe', 'Jose Martin Gil Lopez');
                $mail->isHTML(true);
                $mail->Subject = 'Notificacion de Proyecto para revision';

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
                    <div class="header"><h1>Notificacion de Revision</h1></div>
                    <div class="content">
                        <p>Estimado Profesor/a,</p>
                        <p>Nos complace informarle que se ha enviado el proyecto "<strong>' . $proyecto['Titulo_Proyecto'] . '</strong>".</p>
                        <p>Para su respectiva revision </p>
                        <p>Atentamente,<br>El equipo de gestion</p>
                    </div>
                    <div class="footer">
                        <p>&copy; ' . date("Y") . ' Responsabilidad Social Universitaria. Todos los derechos reservados.</p>
                    </div>
                </body>
                </html>';

                // Adjuntar el archivo
                $mail->addAttachment($rutaArchivoTemp);

                // Enviar el correo
                $mail->send();

                // Eliminar archivo temporal
                unlink($rutaArchivoTemp);
            }
        }
    }
}
