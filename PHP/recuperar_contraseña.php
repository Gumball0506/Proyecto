<?php
ini_set('SMTP', 'localhost');
ini_set('smtp_port', 25);
ini_set('sendmail_from', 'tu_email@ejemplo.com');
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

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$email = $_POST['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

// Aquí deberías verificar si el email existe en tu base de datos

// Generar un token único para la recuperación de contraseña
$token = bin2hex(random_bytes(32));

// Guardar el token en la base de datos asociado al usuario

// Construir el enlace de recuperación
$recuperationLink = "http://localhost:3000/html/cambiar_contraseña.html" . $token;

// Configurar el correo
$to = $email;
$subject = "Recuperación de contraseña";
$message = "Para restablecer tu contraseña, haz clic en el siguiente enlace: " . $recuperationLink;
$headers = "From: noreply@tudominio.com\r\n";
$headers .= "Reply-To: noreply@tudominio.com\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Enviar el correo
if (mail($to, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo']);
}
