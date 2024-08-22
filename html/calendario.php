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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <link rel="stylesheet" href="/css/calendario.css">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="/css/updated_style.css" rel="stylesheet">
    <link href="/css/owl.carousel.min.css" rel="stylesheet">
    <link href="/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
</head>
<style>
    .watermark {
        position: relative;
        /* Fija la huella en la pantalla */
        left: 50%;
        /* Centra horizontalmente */
        bottom: 10px;
        /* Posición desde el fondo */
        transform: translateX(-50%);
        /* Ajusta la posición para centrar */
        opacity: 0.5;
        /* Opacidad */
        z-index: 1000;
        /* Asegúrate de que esté por encima de otros elementos */
        text-align: center;
        /* Centra el texto */
        font-size: 14px;
        /* Ajusta el tamaño de fuente según tus necesidades */
        color: #fff;
        /* Cambia el color del texto si es necesario */
    }
</style>

<body>
    <?php
    session_start();
    $sessionActive = isset($_SESSION['username']);
    $isLoggedIn = isset($_SESSION['estudiante_id']);
    ?>
    <script>
        var sessionActive = <?php echo json_encode($sessionActive); ?>;
    </script>

    <div class="container-fluid bg-light pt-3 d-none d-lg-block">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center">
                        <p><i class="fa fa-envelope mr-2"></i></p>
                        <p class="text-body px-3">|</p>
                        <p><i class="fa fa-phone-alt mr-2"></i></p>
                    </div>
                </div>
                <div class="col-lg-6 text-center text-lg-right">
                    <div class="d-inline-flex align-items-center">
                        <a class="text-primary px-3" href="https://www.facebook.com/FIEIOficial?locale=es_LA">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div id="calendar">
        <div id="calendar-header">
            <button id="prev-month">&lt;</button>
            <div id="month-year"></div>
            <button id="next-month">&gt;</button>
        </div>
        <div id="calendar-body">
            <div id="days-of-week">
                <div>Dom</div>
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sáb</div>
            </div>
            <div id="days"></div>
        </div>
    </div>
    <div id="event-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="event-details"></div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-white-50 py-5 px-sm-3 px-lg-5" style="margin-top: 90px;">
        <div class="row pt-5">
            <div class="col-lg-3 col-md-6 mb-5">
                <a href="" class="navbar-brand">
                    <h1 class="text-primary"><span class="text-white"></span>RSU</h1>
                </a>
                <p>Únete a nuestra comunidad de viajeros apasionados! Explora el mundo con nosotros mientras compartimos experiencias únicas y descubrimos nuevos destinos juntos</p>
                <h6 class="text-white text-uppercase mt-4 mb-3" style="letter-spacing: 5px;">Siguenos</h6>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-outline-primary btn-square mr-2" href="https://www.facebook.com/FIEIOficial?locale=es_LA"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Servicios</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white-50 mb-2" href="https://web.unfv.edu.pe/facultades/fiei/"><i class="fa fa-angle-right mr-2"></i>Nosotros</a>
                    <a class="text-white-50 mb-2" href="/html/contacto.html"><i class="fa fa-angle-right mr-2"></i>Soporte</a>
                    <a class="text-white-50 mb-2" href="/html/web1.php"><i class="fa fa-angle-right mr-2"></i>Mision</a>
                    <a class="text-white-50 mb-2" href="/html/equipo.php"><i class="fa fa-angle-right mr-2"></i>Equipo</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Enlaces</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white-50 mb-2" href="https://web.unfv.edu.pe/facultades/fiei/"><i class="fa fa-angle-right mr-2"></i>Nosotros</a>
                    <a class="text-white-50 mb-2" href="/html/contacto.html"><i class="fa fa-angle-right mr-2"></i>Soporte</a>
                    <a class="text-white-50 mb-2" href="/html/inicio_de_sesion.php"><i class="fa fa-angle-right mr-2"></i>Administrador</a>
                    <a class="text-white-50 mb-2" href="/html/equipo.php"><i class="fa fa-angle-right mr-2"></i>Equipo</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Contactanos</h5>
                <p><i class="fa fa-map-marker-alt mr-2"></i> Jr. Iquique Nº 127 - Breña - Lima</p>
                <p><i class="fa fa-phone-alt mr-2"></i> (+51) 748-0888 Anexo: 9871 - 9866</p>
            </div>
            <div class="watermark">
                © 2024 Responsabilidad Social Universitaria. Todos los derechos reservados.
            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="/js/calendario.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/easing.min.js"></script>
    <script src="/js/owl.carousel.min.js"></script>
    <script src="/js/main.js"></script>
</body>

</html>