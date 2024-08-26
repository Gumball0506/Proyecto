<?php
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';
session_start();
$adminID = $_SESSION['username'];
$isLoggedIn = isset($_SESSION['estudiante_id']);
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

try {
    $stmt = $pdo->prepare('
    SELECT
        e.Nombre,
        e.Apellido,
        p.Titulo_Proyecto,
        e.Codigo_Estudiante,
        e.Email,
        e.ID_Estudiante,  -- Incluye este campo para usarlo en el enlace
        p.ID_ProyectoA
    FROM
        proyectos_alumnos p
    JOIN
        estudiantes e ON p.ID_Estudiante = e.ID_Estudiante
');


    $stmt->execute();
    $proyectosAlumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    $proyectosAlumnos = [];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de proyectos</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>
        window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')
    </script>
    <script src="js/main copy.js"></script>
    <style>
        /* Estilos para el menú desplegable */
        .options-menu {
            position: relative;
            display: inline-block;
        }

        .options-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .options-content button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
        }

        .options-content button:hover {
            background-color: #ddd;
        }

        .options-menu:hover .options-content {
            display: block;
        }

        /* Estilos para la modal */
        .modal {
            display: none;
            /* Inicialmente oculto */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            opacity: 0;
            /* Oculto por defecto */
            transition: opacity 0.4s ease;
            /* Transición suave */
        }

        .modal.show {
            display: block;
            /* Mostrar cuando sea necesario */
            opacity: 1;
            /* Visible */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 600px;
            border-radius: 8px;
            /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            /* Transición para el efecto de entrada */
            transform: scale(0.9);
            /* Escala inicial */
        }

        .modal-content.show {
            transform: scale(1);
            /* Tamaño final */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- navBar -->
    <div class="full-width navBar">
        <div class="full-width navBar-options">
            <i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>
            <div class="mdl-tooltip" for="btn-menu">Menu</div>
            <nav class="navBar-options-list">
                <ul class="list-unstyle">
                </ul>
            </nav>
        </div>
    </div>
    <!-- navLateral -->
    <section class="full-width navLateral">
        <div class="full-width navLateral-bg btn-menu"></div>
        <div class="full-width navLateral-body">
            <div class="full-width navLateral-body-logo text-center tittles">
                <i class="zmdi zmdi-close btn-menu"></i>
            </div>
            <figure class="full-width" style="height: 77px;">
                <div class="navLateral-body-cl">
                    <img src="/imagenes/admin.png" alt="Avatar" class="img-responsive">
                </div>
                <figcaption class="navLateral-body-cr hide-on-tablet">
                    <span>Panel de control<br><small></small></span>
                </figcaption>
            </figure>
            <div class="full-width tittles navLateral-body-tittle-menu">
                <i class="zmdi zmdi-desktop-mac"></i><span class="hide-on-tablet">&nbsp; PANEL</span>
            </div>
            <nav class="full-width">
                <ul class="full-width list-unstyle menu-principal">
                    <!-- Navigation Links -->
                    <li class="full-width"><a href="/html/web1.php" class="full-width">
                            <div class="navLateral-body-cl"><i class="zmdi zmdi-view-dashboard"></i></div>
                            <div class="navLateral-body-cr hide-on-tablet">INICIO</div>
                        </a></li>
                    <li class="full-width"><a href="home.php" class="full-width">
                            <div class="navLateral-body-cl"><i class="zmdi zmdi-settings"></i></div>
                            <div class="navLateral-body-cr hide-on-tablet">PANEL DE GESTION</div>
                        </a></li>
                    <li class="full-width"><a href="panel_mensajes.php" class="full-width">
                            <div class="navLateral-body-cl"><i class="zmdi zmdi-email"></i></div>
                            <div class="navLateral-body-cr hide-on-tablet">MENSAJES</div>
                        </a></li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width"><a href="#!" class="full-width btn-subMenu">
                            <div class="navLateral-body-cl"><i class="zmdi zmdi-face"></i></div>
                            <div class="navLateral-body-cr hide-on-tablet">USUARIOS</div><span class="zmdi zmdi-chevron-left"></span>
                        </a>
                        <ul class="full-width menu-principal sub-menu-options">
                            <li class="full-width"><a href="/client.php" class="full-width">
                                    <div class="navLateral-body-cl"><i class="zmdi zmdi-accounts"></i></div>
                                    <div class="navLateral-body-cr hide-on-tablet">USUSARIOS REGISTRADOS</div>
                                </a></li>
                        </ul>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width"><a href="inventory.php" class="full-width">
                            <div class="navLateral-body-cl"><i class="zmdi zmdi-store"></i></div>
                            <div class="navLateral-body-cr hide-on-tablet">REGISTRO DE PROYECTOS</div>
                        </a></li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width"><a href="#!" class="full-width btn-subMenu">
                            <div class="navLateral-body-cl"><i class="zmdi zmdi-wrench"></i></div>
                            <div class="navLateral-body-cr hide-on-tablet">SETTINGS</div><span class="zmdi zmdi-chevron-left"></span>
                        </a>
                        <ul class="full-width menu-principal sub-menu-options">
                            <li class="full-width"><a href="/Reporte.php" class="full-width">
                                    <div class="navLateral-body-cl"><i class="zmdi zmdi-widgets"></i></div>
                                    <div class="navLateral-body-cr hide-on-tablet">Reportes</div>
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
    <!-- pageContent -->
    <section class="full-width pageContent">
        <section class="full-width header-well">
            <div class="full-width header-well-icon">
                <i class="zmdi zmdi-assignment"></i>
            </div>
            <div class="full-width header-well-text">
                <p class="text-condensedLight">
                    📂 En este apartado podrás visualizar los proyectos de los alumnos 📝. Explora cada propuesta 📊, revisa los archivos adjuntos 📎, y mantente al tanto del progreso 🚀.
                </p>
            </div>

        </section>
        <div class="full-width divider-menu-h"></div>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-responsive">
                    <thead>
                        <tr>
                            <th>ID_Proyectos</th>
                            <th class="mdl-data-table__cell--non-numeric">Nombres y Apellidos</th>
                            <th>Titulo del proyecto</th>
                            <th>Codigo del estudiante</th>
                            <th>Correo Electronico</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectosAlumnos as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['ID_ProyectoA']); ?></td>
                                <td class="mdl-data-table__cell--non-numeric">
                                    <?php echo htmlspecialchars($row['Nombre']) . ' ' . htmlspecialchars($row['Apellido']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['Titulo_Proyecto']); ?></td>
                                <td><?php echo htmlspecialchars($row['Codigo_Estudiante']); ?></td>
                                <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                <td>
                                    <div class="options-menu">
                                        <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                            <i class="zmdi zmdi-more"></i>
                                        </button>
                                        <div class="options-content">
                                            <!-- Enlace dinámico a la página de chat con los parámetros -->
                                            <button class="btn-detalles" data-id="<?php echo htmlspecialchars($row['ID_ProyectoA']); ?>">
                                                Detalles
                                            </button>

                                            <form action="generar_reporte.php" method="GET">
                                                <input type="hidden" name="id_proyecto" value="<?php echo htmlspecialchars($row['ID_ProyectoA']); ?>">
                                                <button type="submit">Reporte</button>
                                            </form>


                                            <a href="/PHP/ver_documento.php?id=<?php echo htmlspecialchars($row['ID_ProyectoA']); ?>" class="btn btn-primary">
                                                <button>Visualizar</button>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
        <!-- Modal para detalles del proyecto -->
        <div id="modalDetalles" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Detalles del Proyecto</h2>
                <div id="detalleContenido">
                    <!-- Los detalles se cargarán aquí -->
                </div>
            </div>
        </div>

    </section>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modalDetalles');
        var modalContent = document.querySelector('.modal-content');
        var span = document.getElementsByClassName('close')[0];

        document.querySelectorAll('.btn-detalles').forEach(function(button) {
            button.addEventListener('click', function() {
                var proyectoId = this.getAttribute('data-id');
                fetchDetallesProyecto(proyectoId);
                modal.classList.add('show');
                modalContent.classList.add('show');
            });
        });

        span.onclick = function() {
            modal.classList.remove('show');
            modalContent.classList.remove('show');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.classList.remove('show');
                modalContent.classList.remove('show');
            }
        }
    });

    function fetchDetallesProyecto(id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'detalles_proyecto.php?id=' + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('detalleContenido').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>
<script>
    document.querySelectorAll('.btn-reporte').forEach(function(button) {
        button.addEventListener('click', function() {
            var proyectoId = this.getAttribute('data-id');
            fetchreporteProyecto(proyectoId);
            modal.classList.add('show');
            modalContent.classList.add('show');
        });
    });

    function fetchreporteProyecto(id) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'reporte_proyecto_alumno?id=' + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('detalleContenido').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>

</html>