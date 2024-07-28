<!-- recovery.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
    <link rel="stylesheet" href="/css/recuperar_contraseña.css">
</head>
<body>
    
    <form action="send_recovery_email.php" method="post">
    <h2>Recuperar Contraseña</h2>
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Enviar enlace de recuperacion</button>
    </form> 
</body>
</html>
