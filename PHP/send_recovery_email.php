<?php
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Generar un token único para la recuperación
    $token = bin2hex(random_bytes(50));
    
    // Guardar el token en un archivo asociado al email
    if (!file_exists('tokens')) {
        mkdir('tokens', 0777, true); // Crear el directorio si no existe
    }
    file_put_contents("tokens/$token.txt", $email);

    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'brsocial.fiei.online';
        $mail->SMTPAuth = true;
        $mail->Username = 'rsufiei@brsocial.fiei.online';
        $mail->Password = 'W4ht7xxoP^eX';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 465;

        $mail->setFrom('rsufiei@brsocial.fiei.online', 'Recuperacion de Contraseña');
        $mail->addAddress($email);          
        
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de Contraseña';
        $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: 
                       <a href='https://brsocial.fiei.online/PHP/reset_password.php?token=$token'>Recuperar Contraseña</a>";
        
        $mail->send();
        echo 'El mensaje ha sido enviado';
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Error: {$mail->ErrorInfo}";
    }
}
?>
