<!DOCTYPE html>
<html lang="es">
<head>
    <style>
        /* Estilo para ocultar el cuadro de texto */
        #admin {
            display: none;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Publicaciones</title>
    <link rel="stylesheet" href="/css/publicaciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/comentarios.css">
        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Web Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> 
    
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    
        <!-- Customized Bootstrap Stylesheet -->
        <link href="/css/updated_style.css" rel="stylesheet">
        
</head>
<body>
<?php
    session_start();
    if (isset($_SESSION['username'])) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { document.getElementById('admin').style.display = 'block'; });</script>";
    }
    ?>

        <!-- Navbar Start -->
        <div class="container-fluid position-relative nav-bar p-0">
            <div class="container-lg position-relative p-0 px-lg-3" style="z-index: 9;">
                <nav class="navbar navbar-expand-lg bg-light navbar-light shadow-lg py-3 py-lg-0 pl-3 pl-lg-5">
                    <a href="" class="navbar-brand">
                        <h1 class="m-0 text-primary"><span class="text-dark">UNI</span>RSE</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                        <div class="navbar-nav ml-auto py-0">
                            <a href="/html/web1.html" class="nav-item nav-link active">Inicio</a>
                            <a href="/html/equipo.html" class="nav-item nav-link">Equipo</a>
                            <a href="/html/nosotros.html" class="nav-item nav-link">Nosotros</a>
                            <a href="/html/calendario.html" class="nav-item nav-link">Calendario</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Proyectos</a>
                                <div class="dropdown-menu border-0 rounded-0 m-0">
                                    <a href="/html/publicaciones_public.html" class="dropdown-item">Proyectos actuales</a>
                                    <a href="/html/publicacionesAntiguas_public.html" class="dropdown-item">Proyectos realizados</a>
                                    <a href="/html/publicaciones_futuras_public.html" class="dropdown-item">Proyectos futuros</a>
                                    <a href="/html/proyecto_estudiantes.html" class="dropdown-item">Proyectos de estudiantes</a>
                                </div>
                            </div>
                            <a href="/html/contacto.html" class="nav-item nav-link">Contactos</a>
                            <a href="inicio_de_sesion.php" class="nav-item nav-link">Administrador</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->
        
        <div class="fondo">
            <div id="admin">
                <h1>Sistema de Publicaciones</h1>
                <form id="postForm" class="form">
                    <input type="text" id="title" placeholder="Título" required>
                    <textarea id="description" placeholder="Descripción" required></textarea>
                    <input type="text" id="registrationLink" name="registrationLink" placeholder="URL de registro" required>
                    <div class="input-container">
                        <input type="date" id="eventDate" placeholder="Fecha del evento" required>
                        <div>
                            <label for="projectStatus">Estado del Proyecto:</label>
                            <input type="checkbox" id="projectStatus" name="projectStatus" checked>
                            <label for="projectStatus">Actual</label>
                        </div>
                    </div>
                    <input type="file" id="image" accept="image/*" required>
                    <button type="submit">Publicar</button>
                </form>
            </div>
            <div id="posts" class="posts-container">
                <!-- Ejemplo de proyecto publicado -->
            </div>
        </div>
    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white-50 py-5 px-sm-3 px-lg-5" style="margin-top: 90px;">
        <div class="row pt-5">
            <div class="col-lg-3 col-md-6 mb-5">
                <a href="" class="navbar-brand">
                    <h1 class="text-primary"><span class="text-white">UNI</span>RSE</h1>
                </a>
                <p>Únete a nuestra comunidad de viajeros apasionados! Explora el mundo con nosotros mientras compartimos experiencias únicas y descubrimos nuevos destinos juntos</p>
                <h6 class="text-white text-uppercase mt-4 mb-3" style="letter-spacing: 5px;">Siguenos</h6>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-outline-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Servicios</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Nosotros</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Soporte</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Mision</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Equipo</a>
                    <a class="text-white-50" href="#"><i class="fa fa-angle-right mr-2"></i>Blog</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Enlaces</h5>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Nosotros</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Soporte</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Administrador</a>
                    <a class="text-white-50 mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Equipo</a>
                    <a class="text-white-50" href="#"><i class="fa fa-angle-right mr-2"></i>Blog</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h5 class="text-white text-uppercase mb-4" style="letter-spacing: 5px;">Contactanos</h5>
                <p><i class="fa fa-map-marker-alt mr-2"></i>Direccion de la Fiei</p>
                <p><i class="fa fa-phone-alt mr-2"></i>Numero</p>
                <p><i class="fa fa-envelope mr-2"></i>correo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/easing.min.js"></script>
    <script src="/js/owl.carousel.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/publicacion.js"></script>
    

</body>
</html>
