<!-- recovery.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <h2>Recuperar Contraseña</h2>
    <form action="send_recovery_email.php" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
