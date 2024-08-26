<?php
session_start();
$sessionActive = isset($_SESSION['username']);
$isLoggedIn = isset($_SESSION['estudiante_id']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propuesta Proyectos RSU</title>
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="/css/updated_style.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/proyectos_alumnos.css">
</head>

<body>
    <script>
        var sessionActive = <?php echo json_encode($sessionActive); ?>;
        var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    </script>
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-light navbar-light shadow-lg py-3 py-lg-0 pl-3 pl-lg-5">
                <a href="" class="navbar-brand">
                    <h1 class="m-0 text-primary"><span class="text-dark"></span>RSU</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="/html/web1.php" class="nav-item nav-link active">Inicio</a>
                        <a href="/html/equipo.php" class="nav-item nav-link">Equipo</a>
                        <a href="/html/Nosotros.php" class="nav-item nav-link">Nosotros</a>
                        <?php if ($isLoggedIn): ?>
                            <a href="/html/Proceso_proyecto.php" class="nav-item nav-link">Proceso_Proyecto</a>
                            <a href="/chat.php" class="nav-item nav-link">Comunicacion</a>
                        <?php endif; ?>
                        <a href="/html/calendario.php" class="nav-item nav-link">Calendario</a>
                        <?php if ($sessionActive): ?>
                            <a href="/html/Contenido_Registros.php" class="nav-item nav-link">Informes</a>
                        <?php endif; ?>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Proyectos</a>
                            <div class="dropdown-menu border-0 rounded-0 m-0">
                                <a href="/html/publicaciones_public.php" class="dropdown-item">Proyectos actuales</a>
                                <a href="/html/publicacionesAntiguas_public.php" class="dropdown-item">Proyectos realizados</a>
                                <?php if ($isLoggedIn): ?>
                                    <a href="/html/propuesta_proyectos_rsu.php" class="dropdown-item">Proyectos de estudiantes</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="https://forms.gle/JJ9c7M57P7y81Qsu7" class="nav-item nav-link">Contactos</a>
                        <a href="/home.php" class="nav-item nav-link" id="stat" id="stat">Panel administrador</a>
                        <div class="nav-item dropdown">
                            <?php if (!$isLoggedIn && !$sessionActive): ?>
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Ingresar</a>
                                <div class="dropdown-menu border-0 rounded-0 m-0">
                                    <a href="/html/inicio_de_sesion.php" class="dropdown-item">Administrador</a>
                                    <a href="/html/registro.html" class="dropdown-item">Registro</a>
                                    <a href="/html/login.html" class="dropdown-item">Ingreso</a>
                                </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($isLoggedIn || $sessionActive): ?>
                        <a href="#" class="nav-item nav-link" onclick="confirmLogout(event)">Salir</a>
                    <?php endif; ?>
                    <script>
                        function confirmLogout(event) {
                            event.preventDefault(); // Evita que el enlace se ejecute inmediatamente
                            const userConfirmed = confirm('¿Seguro de cerrar sesión?');

                            if (userConfirmed) {
                                window.location.href = '/PHP/cierre_sesion.php'; // Redirige a la página de cierre de sesión si se acepta
                            }
                        }
                    </script>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="container">
        <h1>Bienvenido a Propuesta de proyectos RSU</h1>
        <h2>Aquí podrás preparar tu documento de proyecto para la presentación</h2>

        <div class="form-group">
            <h4>Modelo de presentación de propuesta</h4>
            <a href="/pdf/Modelo_proyecto.pdf" class="button" download>Descargar Modelo</a>
        </div>

        <div class="form-group">
            <h4>Presentación de proyecto</h4>
            <a href="#formulario" class="button">Ir al Formulario</a>
        </div>
    </div>

    <div class="container" id="formulario">
        <form id="proyecto-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titulo_proyecto">Título del Proyecto:</label>
                <textarea id="titulo_proyecto" name="titulo_proyecto" maxlength="200" required></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción (máximo 200 palabras):</label>
                <textarea id="descripcion" name="descripcion" maxlength="200" required></textarea>
            </div>
            <div class="form-group">
                <label for="telefono">Número de teléfono (solo números):</label>
                <input type="text" id="telefono" name="telefono" pattern="\d{9}" title="Debe contener exactamente 9 dígitos" maxlength="9" required>
            </div>
            <div class="form-group">
                <label for="archivo">Archivo de proyecto:</label>
                <input type="file" id="archivo" name="archivo" accept=".pdf,.docx,.doc,.pptx,.ppt,.txt,.jpg,.png" required>
            </div>
            <button type="submit" class="button">Enviar</button>
        </form>
    </div>

    <script>
        document.getElementById('proyecto-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario

            const formData = new FormData(this);

            fetch('/PHP/proyectos_alumnos.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    document.getElementById('proyecto-form').reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/easing.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</body>

</html>