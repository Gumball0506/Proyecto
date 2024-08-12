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
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="/css/recuperar_contraseña.css">
</head>

<body>
    <form action="update_password.php" method="post">
        <h2>Restablecer Contraseña</h2>
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br><br>
        <button type="submit">Restablecer</button>
    </form>
</body>

</html>