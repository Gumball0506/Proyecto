<?php
require 'conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar PHPMailer
require 'src/PHPMailer.php';
require 'src/SMTP.php';

header('Content-Type: application/json');

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !isset($data['mensaje'])) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $idProyecto = $data['id'];
    $mensaje = $data['mensaje'];

    try {
        // Obtener los datos del proyecto
        $stmtCorreo = $pdo->prepare("SELECT Correo_Electronico, Titulo_Proyecto, Nombres_Apellidos FROM proyectos_alumnos WHERE ID_ProyectoA = :id");
        $stmtCorreo->execute(['id' => $idProyecto]);
        $proyecto = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {
            $correoDesencriptado = descifrar($proyecto['Correo_Electronico']);

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'brsocial.fiei.online';
            $mail->SMTPAuth = true;
            $mail->Username = 'rsufiei@brsocial.fiei.online';
            $mail->Password = 'W4ht7xxoP^eX';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('rsufiei@brsocial.fiei.online', 'Soporte_Tecnico');
            $mail->addAddress($correoDesencriptado, $proyecto['Nombres_Apellidos']);
            $mail->isHTML(true);
            $mail->Subject = 'Notificación sobre su Proyecto';

            // Cuerpo del correo
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
                <div class="header"><h1>Notificación de Proyecto</h1></div>
                <div class="content">
                    <p>Estimado/a <strong>' . $proyecto['Nombres_Apellidos'] . '</strong>,</p>
                    <p>' . nl2br(htmlspecialchars($mensaje)) . '</p>
                    <p>Atentamente,<br>El equipo de gestión</p>
                </div>
                <div class="footer">
                    <p>&copy; ' . date("Y") . ' Responsabilidad Social Universitaria. Todos los derechos reservados.</p>
                </div>
            </body>
            </html>';

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Mensaje enviado exitosamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Proyecto no encontrado.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no soportado']);
}
