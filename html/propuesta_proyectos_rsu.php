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
<?php
    session_start();
    $sessionActive = isset($_SESSION['username']);
    ?>
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
                        <a href="/html/nosotros.php" class="nav-item nav-link">Nosotros</a>
                        <a href="/html/calendario.php" class="nav-item nav-link">Calendario</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Proyectos</a>
                            <div class="dropdown-menu border-0 rounded-0 m-0">
                                <a href="/html/publicaciones_public.php" class="dropdown-item">Proyectos actuales</a>
                                <a href="/html/publicacionesAntiguas_public.php" class="dropdown-item">Proyectos realizados</a>
                                <a href="/html/publicaciones_futuras_public.html" class="dropdown-item">Proyectos futuros</a>
                                <a href="/html/propuesta_proyectos_rsu.php" class="dropdown-item">Proyectos de estudiantes</a>
                            </div>
                        </div>
                        <a href="link del forms" class="nav-item nav-link">Contactos</a>
                        <a href="/html/dashboard_administrador.php" class="nav-item nav-link" id="stat">Estadisticas</a>
                        <a href="inicio_de_sesion.php" class="nav-item nav-link">Administrador</a>

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
            <a href="/pdf/Currículum Vitae LozaBenites (1) (1).pdf" class="button" download>Descargar Modelo</a>
        </div>

        <div class="form-group">
            <h4>Presentación de proyecto</h4>
            <a href="#formulario" class="button">Ir al Formulario</a>
        </div>
    </div>

    <div class="container" id="formulario">
        <form id="proyecto-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombres y apellidos:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="codigo">Código de alumno:</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="facultad">Facultad:</label>
                <select id="facultad" name="facultad" required>
                    <!-- Opciones de facultades aquí -->
                    <option value="1">Facultad de Administración</option>
                    <option value="2">Facultad de Ciencias Económicas</option>
                    <option value="3">Facultad de Ciencias Financieras y Contables</option>
                    <option value="4">Facultad de Ciencias Naturales y Matemática</option>
                    <option value="5">Facultad de Ciencias Sociales</option>
                    <option value="6">Facultad de Derecho y Ciencia Política</option>
                    <option value="7">Facultad de Educación</option>
                    <option value="8">Facultad de Enfermería</option>
                    <option value="9">Facultad de Humanidades</option>
                    <option value="10">Facultad de Ingeniería Civil</option>
                    <option value="11">Facultad de Ingeniería de Sistemas y Computación</option>
                    <option value="12">Facultad de Ingeniería Electrónica e Informática</option>
                    <option value="13">Facultad de Ingeniería Geográfica, Ambiental y Ecología</option>
                    <option value="14">Facultad de Ingeniería Industrial y de Sistemas</option>
                    <option value="15">Facultad de Medicina</option>
                    <option value="16">Facultad de Odontología</option>
                    <option value="17">Facultad de Oceanografía, Pesquería, Ciencias Alimentarias y Biotecnología</option>
                    <option value="18">Facultad de Psicología</option>
                    <option value="19">Facultad de Arquitectura y Urbanismo</option>
                    <option value="20">Facultad de Tecnología Médica</option>
                </select>
            </div>
            <div class="form-group">
                <label for="telefono">Número de teléfono (solo números):</label>
                <input type="text" id="telefono" name="telefono" pattern="[0-9]*" maxlength="9" required>
            </div>
            <div class="form-group">
                <label for="titulo_proyecto">Titulo del Proyecto:</label>
                <textarea id="titulo_proyecto" name="titulo_proyecto" maxlength="200" required></textarea>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción (máximo 200 palabras):</label>
                <textarea id="descripcion" name="descripcion" maxlength="200" required></textarea>
            </div>
            <div class="form-group">
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
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

            fetch('/php/proyectos_alumnos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                // Limpiar el formulario después de enviar los datos
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
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</body>
</html>