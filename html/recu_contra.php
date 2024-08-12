<?php
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
?>
<!DOCTYPE html>
<html>

<head>
    <title>Recuperación de Contraseña</title>

</head>

<body>
    <h2>RECUPERAR CONTRASEÑA</h2>
    <form action="send_recovery_email.php" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">Enviar</button>
    </form>
</body>

</html>