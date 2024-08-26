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
<?php
session_start();

$isLoggedIn = isset($_SESSION['estudiante_id']);
$sessionActive = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Proyecto de RSU</title>
    <link href="img/favicon.ico" rel="icon">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="/css/updated_style.css" rel="stylesheet">
    <link href="/css/equipostyle.css" rel="stylesheet">
    <link href="/css/proyec.reail.css" rel="stylesheet">
    <link href="/css/textito.css" rel="stylesheet">
    <link href="/css/pilaresprin.css" rel="stylesheet">
    <link href="/css/ejes.css" rel="stylesheet">
    <link href="/css/misivi.css" rel="stylesheet">
    <link href="/css/agradecimiento.css" rel="stylesheet">
    <link href="/css/panel.css" rel="stylesheet">
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

    .contact-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #007BFF;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 1000;
    }

    .contact-button img {
        width: 30px;
        height: 30px;
        color: white;
    }

    .contact-button:hover {
        background-color: #0056b3;
    }

    /* Estilos del chat flotante */
    .chat-box {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 350px;
        max-height: 500px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        z-index: 1000;
        opacity: 0;
        transform: translateY(100%);
        margin-left: 100%;
    }

    .chat-box.show {
        display: flex;
        opacity: 1;
        transform: scale(1);
        width: 30%;
        /* Ancho expandido */
        max-height: 80%;
        /* Altura expandida para adaptarse al viewport */
        bottom: 10px;
        /* Ajustar la distancia desde el borde inferior cuando está expandido */
        right: 10px;
        /* Ajustar la distancia desde el borde derecho cuando está expandido */
        margin-left: 50%;
        /* Ajustar la posición horizontal */
        margin-bottom: 6%;
        /* Eliminar margen inferior para expansión completa */
        margin-right: 60px;
    }

    .chat-box header {
        background: #007BFF;
        color: #fff;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .chat-box .close {
        cursor: pointer;
        font-size: 20px;
        transition: color 0.3s;
    }

    .chat-box .close:hover {
        color: #0056b3;
    }

    .chat-box .content1 {
        padding: 20px;
        flex: 1;
        overflow-y: auto;
    }

    .chat-box .content1 label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .chat-box .content1 input[type="text"],
    .chat-box .content1 textarea {
        width: calc(100% - 20px);
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .chat-box .content1 textarea {
        resize: vertical;
    }

    .warning-message {
        background-color: #ffdddd;
        border: 1px solid #dd0000;
        color: #dd0000;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .chat-box .buttons {
        display: flex;
        justify-content: space-between;
        padding: 15px;
        background: #f9f9f9;
        border-top: 1px solid #ddd;
    }

    .chat-box .buttons button {
        width: 48%;
        padding: 10px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        color: #fff;
        transition: background 0.3s;
    }

    .chat-box .buttons .send {
        background: #007BFF;
    }

    .chat-box .buttons .send:hover {
        background: #0056b3;
    }

    .chat-box .buttons .cancel {
        background: #ddd;
        color: #333;
    }

    .chat-box .buttons .cancel:hover {
        background: #ccc;
    }

    label {
        color: #333;
    }
</style>
<div id="notificacion-evento">
</div>

<body>

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
    <div class="container-fluid p-0">
        <div id="header-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="/imagenes/prueba1.png" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h4 class="text-white text-uppercase mb-md-3">Responsabilidad Social Universitaria</h4>
                            <h1 class="display-3 text-white mb-md-4">Unidos por un Futuro Mejor</h1>
                            <a href="/html/queesrsu.html" class="btn btn-primary py-md-3 px-md-5 mt-2">Conócenos Más</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="/imagenes/prueba2.png" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h4 class="text-white text-uppercase mb-md-3">Responsabilidad Social Universitaria</h4>
                            <h1 class="display-3 text-white mb-md-4">Unidos por un Futuro Mejor</h1>
                            <a href="/html/normasrsu.html" class="btn btn-primary py-md-3 px-md-5 mt-2">Conócenos Más</a>
                        </div>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
                <div class="btn btn-dark" style="width: 45px; height: 45px;">
                    <span class="carousel-control-prev-icon mb-n2"></span>
                </div>
            </a>
            <a class="carousel-control-next" href="#header-carousel" data-slide="next">
                <div class="btn btn-dark" style="width: 45px; height: 45px;">
                    <span class="carousel-control-next-icon mb-n2"></span>
                </div>
            </a>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container pt-5">
            <div class="row">
                <div class="col-lg-6" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="/imagenes/profesor-decano.png" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 pt-5 pb-lg-5">
                    <div class="about-text bg-white p-4 p-lg-5 my-lg-5">
                        <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Nuestra Identidad</h6>
                        <h1 class="mb-3">"Acerca de Nosotros"</h1>
                        <p>Bienvenidos a la página de Responsabilidad Social de la Universidad Nacional Federico Villarreal (UNFV). Somos un equipo comprometido con el desarrollo integral de nuestra comunidad universitaria y la sociedad en general, guiados por los principios de ética, sostenibilidad y compromiso social. Bajo la dirección del Mg. José Martin Gil Lopez, encargado del área de Responsabilidad Social de la UNFV, trabajamos incansablemente para fomentar una educación de calidad que vaya más allá de las aulas, promoviendo iniciativas que generen un impacto positivo en el entorno social, económico y ambiental.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="container-fluid pb-5">
        <div class="container pb-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                            <i class="fa fa-2x fa-money-check-alt text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">Somos</h5>
                            <p class="m-0">COLABORATIVOS</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                            <i class="fa fa-2x fa-award text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">Somos</h5>
                            <p class="m-0">INNOVADORES</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                            <i class="fa fa-2x fa-globe text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">Somos</h5>
                            <p class="m-0">COMPROMETIDOS</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="containerpilares">
        <div class="titulopilares">
            <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Pilares importantes en la RSU</h6>
            <h1>Responsabilidad Social UNFV</h1>
            <p>No solo se enfoquen en la formación académica, sino también en formar ciudadanos conscientes y comprometidos con su entorno.</p>
        </div>
        <div class="contentpilares">
            <div class="boxpilares card">
                <figure>
                    <img src="/imagenes/Sostenibilidad.jpg">
                </figure>
                <div class="namepilares">
                    <h12>Sostenibilidad Ambiental</h12>
                    <span>Implementar prácticas sostenibles en el campus universitario, como la gestión eficiente de recursos, la reducción de residuos y la promoción de la conciencia ambiental entre estudiantes y personal.</span>
                </div>
            </div>
            <div class="boxpilares card">
                <figure>
                    <img src="/imagenes/Educacion.jpg">
                </figure>
                <div class="namepilares">
                    <h12>Educación de Calidad</h12>
                    <span>Es el pilar fundamental de la universidad. Asegurando que los estudiantes reciban una formación académica integral y relevante, que no solo les prepare para el mercado laboral, sino que también los capacite para contribuir positivamente a la sociedad.</span>
                </div>
            </div>
            <div class="boxpilares card">
                <figure>
                    <img src="/imagenes/Investigacion.jpg">
                </figure>
                <div class="namepilares">
                    <h12>Investigación con Impacto Social</h12>
                    <span>La investigación universitaria debe abordar y encontrar soluciones a problemas sociales, económicos y ambientales. Para garantizar el conocimiento y asi genere tenga un impacto real y beneficioso en la comunidad.</span>
                </div>
            </div>
            <div class="boxpilares card">
                <figure>
                    <img src="/imagenes/Compromiso.jpg">
                </figure>
                <div class="namepilares">
                    <h12>Compromiso con la Comunidad</h12>
                    <span>La participación activa en proyectos comunitarios y el apoyo a iniciativas locales o globales ayudan a fortalecer la relación entre la universidad y la sociedad, generando beneficios mutuos.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Proyectos</h6>
                <h1>Proyectos Realizados</h1>
            </div>
            <main>
                <div class="slider">
                    <div class="slide-row" id="slide-row">
                        <div class="slide-col">
                            <div class="content">
                                <p>El "Concurso de Esculturas con RAEE", los estudiantes aprovecharon estos materiales reciclables para transformarlos en obras de arte ecológico. Demuestrando sus habilidades artísticas y contribuyendo al cuidado del #MedioAmbiente.
                                </p>
                                <h2>ESCULTURAS CON RAEE EN LA FIEI</h2>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/proyectosR1.png" alt="avatar">
                            </div>
                        </div>
                        <div class="slide-col">
                            <div class="content">
                                <p>Estudiantes voluntarios y una comitiva de la #FIEI, llevaron alegría a cientos de niños y niñas de la comunidad shipibo-konibo instalada en la Asociación de Viviendas de Cantagallo, con quienes compartieron una deliciosa chocolatada y entregaron regalos.</p>
                                <h2>NAVIDAD VILLAREILINA</h2>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/proyectosR5.png.jpg" alt="avatar">
                            </div>
                        </div>
                        <div class="slide-col">
                            <div class="content">
                                <p>Descubrieron los fundamentos esenciales para garantizar un entorno laboral seguro y saludable, aprendiendo a identificar riesgos y aplicar medidas preventivas. Por ultimo, conocieron las mejores prácticas en seguridad laboral.</p>
                                <h2>CHARLAS LABORALES</h2>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/proyectosR3.png" alt="avatar">
                            </div>
                        </div>
                        <div class="slide-col">
                            <div class="content">
                                <p>Esta capacitación estuvo dirigida a todo el personal docente, administrativo y funcionarios de la #ComunidadFIEI.En la cual aprendieron habilidades vitales para responder de manera efectiva ante situaciones de emergencia en el entorno laboral.</p>
                                <h2>CAPACITACION FIEI</h2>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/proyectosR4.png" alt="avatar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="indicador">
                    <span class="botones active"></span>
                    <span class="botones"></span>
                    <span class="botones"></span>
                    <span class="botones"></span>
                </div>
            </main>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Actividades RSU</h6>
                <h1>Formacion de las Personas</h1>
                <p>Es esencial para crear una comunidad académica comprometida con los valores éticos, sociales y ambientales.</p>
            </div>
            <div class="containerformacion">
                <div class="cardformacion">
                    <div class="imgBxformacion">
                        <img src="/imagenes/voluntariadosRSU.png">
                    </div>
                    <div class="contentformacion">
                        <h13>PROGRAMA DE VOLUNTARIADO</h13>
                        <p>Nuestros programas de voluntariado brindan a los estudiantes la oportunidad de participar activamente en causas sociales y comunitarias.
                            que permiten el desarrollo de habilidades de liderazgo, trabajo en equipo y empatía.</p>
                    </div>
                </div>

                <div class="cardformacion">
                    <div class="imgBxformacion">
                        <img src="/imagenes/charlasRSU.png">
                    </div>
                    <div class="contentformacion">
                        <h13>CHARLAS Y CONFERENCIAS</h13>
                        <p>Estos son organizadas por la universidad que expresan espacios de reflexión y aprendizaje, donde expertos y profesionales comparten sus conocimientos y experiencias con los estudiantes.
                        </p>
                    </div>
                </div>

                <div class="cardformacion">
                    <div class="imgBxformacion">
                        <img src="/imagenes/participacionRSU.png">
                    </div>
                    <div class="contentformacion">
                        <h13>CONCURSO SOBRE RSU</h13>
                        <p>La participación es una herramienta clave para la formación práctica y aplicada de nuestros estudiantes.
                            A través de estos proyectos, se promueve la investigación, la creatividad y la innovación, permitiendo a los participantes trabajar en soluciones concretas a problemas reales. </p>
                    </div>
                </div>

                <div class="cardformacion">
                    <div class="imgBxformacion">
                        <img src="/imagenes/campañas.png">
                    </div>
                    <div class="contentformacion">
                        <h13>EVENTOS Y CAMPAÑAS</h13>
                        <p> Nuestra universidad organiza una variedad de campañas y eventos diseñados para aumentar la conciencia sobre temas sociales y ambientales entre los estudiantes y la comunidad universitaria
                            que tienen como objetivo promover el compromiso social y la participación activa en causas relevantes. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Villarealinos</h6>
                <h1>Nuestros Ejes Fundamentales</h1>
                <p>En la Universidad Nacional Federico Villarreal, los VILLAREILINOS se destacan por su compromiso con el desarrollo integral y el bienestar social. Asi
                    orientamos nuestras acciones hacia la construcción de un futuro más justo, equitativo y sostenible.
                </p>
                <div class="containerejes">
                    <div class="boxejes">
                        <span></span>
                        <div class="contentejes">
                            <h14>Formación Integral de los Estudiantes</h14>
                            <p>Garantiza que los estudiantes reciban una educación que no solo se centra en el conocimiento académico, sino también en el desarrollo de habilidades éticas, sociales y cívicas, fomentando su compromiso con la sociedad y el medio ambiente.</p>
                        </div>
                    </div>
                    <div class="boxejes">
                        <span></span>
                        <div class="contentejes">
                            <h14>Formación Integral de los Estudiantes</h14>
                            <p>Se centra en proporcionar a los estudiantes una educación que no solo aborde aspectos académicos, sino también éticos y sociales, preparándolos para ser ciudadanos responsables y comprometidos.</p>
                        </div>
                    </div>
                    <div class="boxejes">
                        <span></span>
                        <div class="contentejes">
                            <h14>Gestión Ambiental Sostenible</h14>
                            <p>Promueve la adopción de prácticas que reduzcan el impacto ambiental de las actividades universitarias y fomenten la sostenibilidad tanto en el campus como en la comunidad en general.</p>
                        </div>
                    </div>
                    <div class="boxejes">
                        <span></span>
                        <div class="contentejes">
                            <h14>Ética y Gobernanza</h14>
                            <p>Implica la adopción de prácticas transparentes y éticas en la gestión universitaria, así como el desarrollo de políticas y directrices que refuercen la integridad y la rendición de cuentas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Equipo</h6>
                <h1>Formacion del equipo</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 pb-2">
                    <div class="team-item bg-white mb-4">
                        <div class="team-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="img/team-1.jpg" alt="">
                            <div class="team-social">
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <h5 class="text-truncate">Miembro1</h5>
                            <p class="m-0">Corta descripcion</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 pb-2">
                    <div class="team-item bg-white mb-4">
                        <div class="team-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="img/team-2.jpg" alt="">
                            <div class="team-social">
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <h5 class="text-truncate">Miembro2</h5>
                            <p class="m-0">Corta descripcion</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 pb-2">
                    <div class="team-item bg-white mb-4">
                        <div class="team-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="img/team-3.jpg" alt="">
                            <div class="team-social">
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <h5 class="text-truncate">Miembro3</h5>
                            <p class="m-0">corta descripcion</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 pb-2">
                    <div class="team-item bg-white mb-4">
                        <div class="team-img position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="img/team-4.jpg" alt="">
                            <div class="team-social">
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-outline-primary btn-square" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                        <div class="text-center py-4">
                            <h5 class="text-truncate">Miembro4</h5>
                            <p class="m-0">corta descripcion</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">principales alcances</h6>
                <h1>Compromiso Villarealino</h1>
                <p>Este compromiso se refleja en la firme convicción de que la educación superior debe ser un motor de cambio social, orientado a la mejora continua de la sociedad en su conjunto.
                    A través de la Responsabilidad Social Universitaria (RSU), la UNFV articula sus objetivos, misión y visión para canalizar este compromiso en acciones concretas y sostenibles.</p>
                <article>
                    <div class="blog__image-box">
                        <div class="blog__image-box_img">
                            <img src="/imagenes/objet3.png" alt="image" />
                        </div>
                    </div>
                    <div class="blog-content">
                        <h2 class="blog-content__title">
                            OBJETIVOS
                        </h2>
                        <ul class="blog-content__footer">
                            <li class="blog-content__footer-text">
                                Promover la responsabilidad social: Fomentar una cultura de responsabilidad social dentro de la comunidad universitaria, involucrando a estudiantes, docentes y administrativos.<br>
                                Desarrollar proyectos sostenibles: Implementar y apoyar proyectos que aborden problemáticas sociales. <br>
                            </li>
                        </ul>
                    </div>

                </article>
                <article>
                    <div class="blog__image-box">
                        <div class="blog__image-box_img">
                            <img src="/imagenes/objet1.png" alt="image" />
                        </div>
                    </div>
                    <div class="blog-content">
                        <h2 class="blog-content__title">
                            MISIÓN
                        </h2>
                        <ul class="blog-content__footer">
                            <li class="blog-content__footer-text">
                                La misión de la Responsabilidad Social Universitaria de la Universidad Nacional Federico Villarreal es formar profesionales competentes, comprometidos con la ética, la sostenibilidad y el bienestar social,
                                a través de la integración de la responsabilidad social en todos los aspectos de la vida universitaria, promoviendo el desarrollo sostenible y el mejoramiento de la calidad de vida de la comunidad.
                            </li>
                        </ul>
                    </div>
                </article>
                <article>
                    <div class="blog__image-box">
                        <div class="blog__image-box_img">
                            <img src="/imagenes/objet2.png" alt="image" />
                        </div>
                    </div>
                    <div class="blog-content">
                        <h2 class="blog-content__title">
                            VISIÓN
                        </h2>
                        <ul class="blog-content__footer">
                            <li class="blog-content__footer-text">
                                La visión de la Responsabilidad Social Universitaria de la Universidad Nacional Federico Villarreal es ser reconocida como una institución líder en la promoción de la responsabilidad social,
                                destacándose por su contribución significativa al desarrollo sostenible, la justicia social y la protección del medio ambiente, y por formar profesionales con una sólida conciencia social y ambiental.


                            </li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container pt-5 pb-3">
            <div class="text-center mb-3 pb-3">
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Equipo</h6>
                <h1>De Desarrolladores</h1>
            </div>
            <div id="particles-js" class="particles"></div>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="/imagenes/abra (2).png" alt="" />
                        <div class="title">
                            <span>Abraham Loza</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/lily.png" alt="" />
                        <div class="title">
                            <span>Lily Roque</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/harold.jpg" alt="" />
                        <div class="title">
                            <span>Harold Ortiz</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/willy.jpg" alt="" />
                        <div class="title">
                            <span>William Vega</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/nieves.jpg" alt="" />
                        <div class="title">
                            <span>Elizabeth Nieves</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/vidal.png" alt="" />
                        <div class="title">
                            <span>Sergio Vidal</span>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="container-fluid py-5">
            <div class="container pt-5 pb-3">
                <div class="text-center mb-3 pb-3">
                    <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Villarealinos</h6>
                    <h1>Agradecimiento</h1>

                    <div class="blog-slider">
                        <div class="blog-slider__img">
                            <img src="/imagenes/Sostenibilidad.jpg" alt="">
                        </div>
                        <div class="blog-slider__text">Queremos expresar nuestro más sincero agradecimiento a cada uno de ustedes por su dedicación y compromiso con la responsabilidad social universitaria.
                            Gracias a su participación en voluntariados, proyectos y charlas, estamos construyendo una comunidad más solidaria y consciente.
                            Su entusiasmo y esfuerzo son fundamentales para generar un impacto positivo y sostenible en nuestra sociedad.</div>
                    </div>
                </div>
            </div>
        </div>
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
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/easing.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="/js/equipojs.js"></script>
    <script src="/js/proy.rlz.js"></script>
    <script src="/js/panel.js"></script>
</body>

</html>