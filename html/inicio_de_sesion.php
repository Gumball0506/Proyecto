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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inicio de Sesión</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/inicio_sesion.css">

</head>
<style>
    /* Estilo para ocultar el cuadro de texto */
    #opciones {
        display: none;
    }
</style>

<body>
    <div class="box">
        <div class="container">
            <div class="top-header">
                <header>Iniciar Sesión</header>
            </div>

            <form action="/PHP/compr.php" method="post">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
                <br><br>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <br><br>
                <button type="submit">Iniciar Sesión</button>
            </form>

            <div class="bottom">
                <div class="left">
                    <input type="checkbox" id="check">
                    <label for="check">Recordarme</label>
                </div>
                <div class="right">
                    <label><a href="/PHP/recovery.php" id="forgotPassword">¿Olvidaste la contraseña?</a></label>
                </div>
            </div>

            <div class="incognito-option" id="invitado">
                <button id="incognitoButton">Continuar como usuario normal</button>
            </div>
        </div>
        <div id="opciones">

            <div class="">
                <button id="logoutButton">Cerrar Sesión</button>
            </div>
        </div>

        <script>
            // Obtenemos una referencia al botón

            const invitado = document.getElementById('invitado');
            const logoutButton = document.getElementById('logoutButton');


            invitado.addEventListener('click', function() {
                // Redirigimos a la página deseada
                window.location.href = '/html/web1.php';
            });
            logoutButton.addEventListener('click', function() {
                window.location.href = '/PHP/logout.php';
            });
        </script>

        <?php
        session_start();
        if (isset($_SESSION['username'])) {
            echo "<script>document.getElementById('opciones').style.display = 'block';</script>";
        }
        ?>
</body>

</html>