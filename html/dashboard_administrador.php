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
session_start();

$isLoggedIn = isset($_SESSION['estudiante_id']);
$sessionActive = isset($_SESSION['username']);

?>
<!DOCTYPE html>
<html lang="es">
<style>
    .estado-buttons {
        display: flex;
        flex-wrap: wrap;
        /* Permite que los botones se envuelvan en una nueva línea si no hay suficiente espacio */
        gap: 5px;
        justify-content: center;
        /* Centra los botones horizontalmente */
        margin: 10px 0;
        /* Añade un margen superior e inferior */
    }

    .estado-buttons button {
        padding: 6px 12px;
        /* Ajusta el padding para mayor claridad en pantallas grandes */
        border: none;
        border-radius: 5px;
        /* Bordes más redondeados para un aspecto más moderno */
        cursor: pointer;
        color: white;
        background-color: gray;
        /* Color predeterminado para los botones inactivos */
        font-size: 0.9em;
        /* Tamaño de fuente más pequeño para adaptarse a pantallas pequeñas */
        transition: background-color 0.3s ease, font-weight 0.3s ease;
        /* Transiciones suaves para cambios de color y peso de fuente */
    }

    .estado-buttons button.active {
        background-color: blue;
        /* Color para el botón activo */
        font-weight: bold;
    }

    .estado-buttons button:nth-child(1):not(.active) {
        background-color: orange;
        /* Color para el primer botón no activo */
    }

    .estado-buttons button:nth-child(2):not(.active) {
        background-color: lightgreen;
        /* Color para el segundo botón no activo */
    }

    .estado-buttons button:nth-child(3):not(.active) {
        background-color: lightcoral;
        /* Color para el tercer botón no activo */
    }

    @media (max-width: 600px) {
        .estado-buttons button {
            padding: 4px 8px;
            /* Menor padding para pantallas pequeñas */
            font-size: 0.8em;
            /* Tamaño de fuente más pequeño para pantallas pequeñas */
        }
    }

    @media (max-width: 400px) {
        .estado-buttons {
            flex-direction: column;
            /* Cambia la dirección a columna en pantallas muy pequeñas */
            align-items: center;
            /* Centra los botones verticalmente en pantallas muy pequeñas */
        }

        .estado-buttons button {
            width: 100%;
            /* Hacer que los botones ocupen todo el ancho disponible */
            max-width: 300px;
            /* Ancho máximo para evitar botones demasiado grandes */
        }
    }

    /* Botón "Respondido" */
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Información de Responsabilidad Social</title>
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="/css/equipostyle.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="/css/updated_style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/daskboard.js" defer></script>
    <link rel="stylesheet" href="/css/daskboard_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <script>
        var sessionActive = <?php echo json_encode($sessionActive); ?>;
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
                        <a href="/html/dashboard_administrador.php" class="nav-item nav-link" id="stat" id="stat">Estadisticas</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Sesiones</a>
                            <div class="dropdown-menu border-0 rounded-0 m-0">
                                <?php if (!$isLoggedIn && !$sessionActive): ?>
                                    <a href="/html/inicio_de_sesion.php" class="dropdown-item">Administrador</a>
                                    <a href="/html/registro.html" class="dropdown-item">Registro</a>
                                    <a href="/html/login.html" class="dropdown-item">Ingreso</a>
                                <?php endif; ?>
                            </div>
                        </div>
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
    <div class="container1">
        <aside class="sidebar">
            <div class="profile">
                <img src="/imagenes/admin.png" alt="Foto de Perfil" class="profile-pic">
                <h2>Admin</h2>
            </div>
            <nav class="nav">
                <ul>
                    <li><i class="fas fa-qrcode"></i>
                        <a href="#" id="principal">Inicio</a>
                    </li>
                    <li><i class="fas fa-link"></i>
                        <a href="#" id="vistas">Vistas</a>
                    </li>
                    <li><i class="fas fa-stream"></i>
                        <a href="#" id="registros">Estadisticas</a>
                    </li>
                    <li><i class="fas fa-calendar-week"></i>
                        <a href="#" id="solicitudes">Solicitudes de Proyectos</a>

                    </li>
                    <li><i class="fas fa-calendar-week"></i>
                        <a href="#" id="mensajes">Gestion de mensajes</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Sistema de Información de Responsabilidad Social</h1>
            </header>
            <section class="content" id="content">
                <h2>Bienvenido al Sistema</h2>
                <p>Seleccione una opción del menú para comenzar.</p>
                <!-- Aquí se insertará el contenido dinámico, como la tabla de solicitudes -->
            </section>
        </main>

    </div>

    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner"></div>
        <h2 id="loadingMessage">Cargando...</h2>
    </div>
    <div id="informarForm" class="informar-formulario" style="display: none;">
        <h3>Enviar Notificación</h3>
        <textarea id="informarMessage" rows="4" placeholder="Escribe tu mensaje aquí..."></textarea>
        <button class="enviar" onclick="enviarNotificacion()">Enviar</button>
        <button class="cancelar" onclick="cerrarFormulario()">Cancelar</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/easing.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</body>

</html>