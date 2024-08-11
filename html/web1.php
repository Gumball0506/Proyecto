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

</head>

<body>
    <?php
    session_start();
    $sessionActive = isset($_SESSION['username']);
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
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Proyectos</a>
                            <div class="dropdown-menu border-0 rounded-0 m-0">
                                <a href="/html/publicaciones_public.php" class="dropdown-item">Proyectos actuales</a>
                                <a href="/html/publicacionesAntiguas_public.php" class="dropdown-item">Proyectos realizados</a>
                                <a href="/html/propuesta_proyectos_rsu.php" class="dropdown-item">Proyectos de estudiantes</a>
                            </div>
                        </div>
                        <a href="link del forms" class="nav-item nav-link">Contactos</a>
                        <a href="/html/dashboard_administrador.php" class="nav-item nav-link" id="stat" id="stat">Estadisticas</a>
                        <a href="inicio_de_sesion.php" class="nav-item nav-link">Administrador</a>

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
                            <h5 class="">EN VERSE</h5>
                            <p class="m-0">En verse</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                            <i class="fa fa-2x fa-award text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">EN VERSE</h5>
                            <p class="m-0">EN VERSE</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex mb-4 mb-lg-0">
                        <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-primary mr-3" style="height: 100px; width: 100px;">
                            <i class="fa fa-2x fa-globe text-white"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <h5 class="">EN VERSE</h5>
                            <p class="m-0">EN VERSE</p>
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
                <img src="https://cdn-icons-png.flaticon.com/128/2327/2327640.png">
            </figure>
            <div class="namepilares">
                <h12>Sostenibilidad Ambiental</h12>
                <span>Implementar prácticas sostenibles en el campus universitario, como la gestión eficiente de recursos, la reducción de residuos y la promoción de la conciencia ambiental entre estudiantes y personal.</span>
            </div>
        </div>
        <div class="boxpilares card">
            <figure>
                <img src="https://cdn-icons-png.flaticon.com/128/13104/13104539.png">
            </figure>
            <div class="namepilares">
                <h12>Educación de Calidad</h12>
                <span>Es el pilar fundamental de la universidad. Asegurando que los estudiantes reciban una formación académica integral y relevante, que no solo les prepare para el mercado laboral, sino que también los capacite para contribuir positivamente a la sociedad.</span>
            </div>
        </div>
        <div class="boxpilares card">
            <figure>
                <img src="https://cdn-icons-png.flaticon.com/128/2436/2436890.png">
            </figure>
            <div class="namepilares">
                <h12>Investigación con Impacto Social</h12>
                <span>La investigación universitaria debe abordar y encontrar soluciones a problemas sociales, económicos y ambientales. Para garantizar el conocimiento y asi genere tenga un impacto real y beneficioso en la comunidad.</span>
            </div>
        </div>
        <div class="boxpilares card">
            <figure>
                <img src="https://cdn-icons-png.flaticon.com/128/10930/10930925.png">
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
                                <p>Una conferencia, congreso o encuentro es una reunión de gente con un interés o un antecedente común.</p>
                                <h2>PUBLICACIONES 1</h2>
                                <button class="btn-small">Leer más</button>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/publi.1.png" alt="avatar">
                            </div>
                        </div>
                        <div class="slide-col">
                            <div class="content">
                                <p>Una conferencia, congreso o encuentro es una reunión de gente con un interés o un antecedente común.</p>
                                <h2>PUBLICACIONES 2</h2>
                                <button class="btn-small">Leer más</button>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/publi.2.png" alt="avatar">
                            </div>
                        </div>
                        <div class="slide-col">
                            <div class="content">
                                <p>Una conferencia, congreso o encuentro es una reunión de gente con un interés o un antecedente común.</p>
                                <h2>PUBLICACIONES 3</h2>
                                <button class="btn-small">Leer más</button>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/publi.3.png" alt="avatar">
                            </div>
                        </div>
                        <div class="slide-col">
                            <div class="content">
                                <p>Una conferencia, congreso o encuentro es una reunión de gente con un interés o un antecedente común.</p>
                                <h2>PUBLICACIONES 4</h2>
                                <button class="btn-small">Leer más</button>
                            </div>
                            <div class="hero">
                                <img src="/imagenes/publi.4.png" alt="avatar">
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
            <img src="/imagenes/charlasRSU.png">
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
                <h6 class="text-primary text-uppercase" style="letter-spacing: 5px;">Villareilinos</h6>
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
                        <img src="/imagenes/lily.foto.png" alt="" />
                        <div class="title">
                            <span>Lily Roque</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/harold (2).png" alt="" />
                        <div class="title">
                            <span>Harold Ortiz</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/sergio1.foto.png" alt="" />
                        <div class="title">
                            <span>Sergio Vidal</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/Nieves.foto.png" alt="" />
                        <div class="title">
                            <span>Elizabeth Nieves</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <img src="/imagenes/william.png" alt="" />
                        <div class="title">
                            <span>William Vegu</span>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="contentito">
            <h1>Agradecimiento</h1>
            <h6>
                Queremos expresar nuestro más sincero agradecimiento a cada uno de ustedes por su dedicación y compromiso con la responsabilidad social universitaria.
                Gracias a su participación en voluntariados, proyectos y charlas, estamos construyendo una comunidad más solidaria y consciente.
                Su entusiasmo y esfuerzo son fundamentales para generar un impacto positivo y sostenible en nuestra sociedad.
            </h6>
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
</body>

</html>