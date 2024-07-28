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
                <input type="submit" class="pu_ac" id="pu_ac" value="publicaciones actuales">   
                <input type="submit" class="pu_an" id="pu_an" value="publicaciones antiguas">
                <input type="submit" class="pu_fu" id="pu_fu" value="publicaciones futuras">
            </div>
            <div class="">
                <button id="logoutButton">Cerrar Sesión</button>
            </div>
        </div>
    
    <script>
        // Obtenemos una referencia al botón
        const pu_ac = document.getElementById('pu_ac');
        const pu_an = document.getElementById('pu_an');
        const pu_fu = document.getElementById('pu_fu');
        const invitado = document.getElementById('invitado');
        const logoutButton = document.getElementById('logoutButton');
        
        // Agregamos un evento de click al botón
        pu_ac.addEventListener('click', function() {
            // Redirigimos a la página deseada
            window.location.href = 'publicaciones_admin.html'; 
        });

        pu_an.addEventListener('click', function() {
            // Redirigimos a la página deseada
            window.location.href = 'publicacionesAntiguas_admin.html'; 
        });

        pu_fu.addEventListener('click', function() {
            // Redirigimos a la página deseada
            window.location.href = 'publicaciones_futuras_admin.html'; 
        });
        invitado.addEventListener('click', function() {
            // Redirigimos a la página deseada
            window.location.href = 'web1.html'; 
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
