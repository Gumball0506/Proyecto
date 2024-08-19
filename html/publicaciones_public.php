<?php
session_start();
$sessionActive = isset($_SESSION['username']);
$isLoggedIn = isset($_SESSION['estudiante_id']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        #admin {
            display: none;
        }

        #stat {
            display: none;
        }

        .vistas-container {
            display: none;
        }

        .eliminar-proyecto-btn {
            display: none;
        }

        .project-status-container {
            display: none;
        }
        .editar-proyecto-btn{
            display: none;
        }

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

        .star-rating {
            display: flex;
            direction: rtl;
            /* Muestra las estrellas en orden inverso */
            font-size: 24px;
            /* Tamaño de las estrellas */
        }

        .star-rating input[type="radio"] {
            display: none;
            /* Ocultar los botones de radio */
        }

        .star-rating label {
            color: #ddd;
            /* Color por defecto */
            cursor: pointer;
            /* Hacer que las estrellas sean clicables */
        }

        .star-rating input[type="radio"]:checked~label {
            color: #ffc107;
            /* Color de las estrellas seleccionadas */
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* Cambia el color al pasar el mouse por encima */
        }

        /* Estilos para el contenedor del título y el botón */
        .titulo-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .editar-proyecto-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .editar-proyecto-btn i {
            margin-right: 5px;
        }

        .editar-proyecto-btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Estilos para el contenedor del formulario de edición */
        #editFormContainer {
            display: none;
            /* Oculto por defecto */
            position: fixed;
            /* Posiciona el cuadro en relación a la ventana del navegador */
            top: 50%;
            /* Centra verticalmente */
            left: 50%;
            /* Centra horizontalmente */
            transform: translate(-50%, -50%);
            /* Ajusta la posición para centrar */
            background-color: #fff;
            /* Fondo blanco */
            padding: 20px;
            /* Espaciado interno */
            border: 1px solid #ddd;
            /* Borde gris claro */
            border-radius: 8px;
            /* Bordes redondeados */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            /* Sombra para destacar */
            z-index: 1000;
            /* Asegura que esté por encima de otros elementos */
            max-width: 600px;
            /* Ancho máximo del formulario */
            width: 100%;
            /* Ancho completo en pantallas pequeñas */
            box-sizing: border-box;
            /* Incluye el padding en el ancho total */
        }

        /* Estilos para el formulario dentro del contenedor */
        #editFormContainer form {
            display: flex;
            flex-direction: column;
        }

        #editFormContainer label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        #editFormContainer input,
        #editFormContainer textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        #editFormContainer button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        #editFormContainer button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Estilos para el botón de cancelar */
        .cancelar-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-right: 10px;
        }

        .cancelar-btn:hover {
            background-color: #e53935;
            transform: scale(1.05);
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            /* Asegura que esté encima de otros elementos */
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Publicaciones</title>
    <link rel="stylesheet" href="/css/publicaciones.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <script>
        var sessionActive = <?php echo json_encode($sessionActive); ?>;
    </script>
    <script src="/js/publicacion.js"></script>


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
        <div id="overlay"></div>
        <div id="editFormContainer">
            <form id="editForm">
                <input type="hidden" id="editProjectId">
                <label for="editTitle">Título:</label>
                <input type="text" id="editTitle" required>
                <label for="editDescription">Descripción:</label>
                <textarea id="editDescription" required></textarea>
                <label for="editRegistrationLink">Enlace de Registro:</label>
                <input type="url" id="editRegistrationLink" required>
                <label for="editEventDate">Fecha de Evento:</label>
                <input type="date" id="editEventDate" required>
                <button type="submit">Guardar</button>
                <button type="button" id="cancelEdit">Cancelar</button>
            </form>
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="/js/easing.min.js"></script>
    <script src="/js/owl.carousel.min.js"></script>
    <script src="/js/main.js"></script>
    <script src="/js/publicacion.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Llamada a la función cuando la página está completamente cargada
            incrementViewCount();
        });

        function incrementViewCount() {
            fetch('/PHP/increment_view_count.php', {
                    method: 'POST', // O 'GET' si solo estás recuperando datos sin modificar
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                    // body: 'page_name=pagina_publicaciones' // Solo si necesitas enviar datos en POST
                })
                .then(response => response.json())
                .then(data => {
                    // Aquí puedes manejar la respuesta si es necesario
                    console.log('Vista actualizada:', data);
                })
                .catch(error => {
                    console.error('Error al actualizar la vista:', error);
                });
        }
    </script>

</body>

</html>