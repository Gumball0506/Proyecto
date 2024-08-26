<?php
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';
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
                <i class="zmdi zmdi-close btn-menu"></i> Registro de Proyectos
            </div>
            <figure class="full-width" style="height: 77px;">
                <div class="navLateral-body-cl">
                    <img src="assets/img/avatar-male.png" alt="Avatar" class="img-responsive">
                </div>
                <figcaption class="navLateral-body-cr hide-on-tablet">
                    <span>
                        Full Name Admin<br>
                        <small>Admin</small>
                    </span>
                </figcaption>
            </figure>
            <div class="full-width tittles navLateral-body-tittle-menu">
                <i class="zmdi zmdi-desktop-mac"></i><span class="hide-on-tablet">&nbsp; DASHBOARD</span>
            </div>
            <nav class="full-width">
                <ul class="full-width list-unstyle menu-principal">
                    <li class="full-width">
                        <a href="home.php" class="full-width">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-view-dashboard"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                HOME
                            </div>
                        </a>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width">
                        <a href="#!" class="full-width btn-subMenu">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-case"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                ADMINISTRATION
                            </div>
                            <span class="zmdi zmdi-chevron-left"></span>
                        </a>
                        <ul class="full-width menu-principal sub-menu-options">
                            <li class="full-width">
                                <a href="company.html" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-balance"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        COMPANY
                                    </div>
                                </a>
                            </li>
                            <li class="full-width">
                                <a href="providers.html" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-truck"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        PROVIDERS
                                    </div>
                                </a>
                            </li>
                            <li class="full-width">
                                <a href="payments.html" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-card"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        PAYMENTS
                                    </div>
                                </a>
                            </li>
                            <li class="full-width">
                                <a href="categories.html" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-label"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        CATEGORIES
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width">
                        <a href="#!" class="full-width btn-subMenu">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-face"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                USERS
                            </div>
                            <span class="zmdi zmdi-chevron-left"></span>
                        </a>
                        <ul class="full-width menu-principal sub-menu-options">
                            <li class="full-width">
                                <a href="admin.html" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-account"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        ADMINISTRATORS
                                    </div>
                                </a>
                            </li>
                            <li class="full-width">
                                <a href="client.html" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-accounts"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        CLIENT
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width">
                        <a href="products.html" class="full-width">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-washing-machine"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                PRODUCTS
                            </div>
                        </a>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width">
                        <a href="sales.html" class="full-width">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-shopping-cart"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                SALES
                            </div>
                        </a>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width">
                        <a href="inventory.php" class="full-width">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-store"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                Registro de Proyectos
                            </div>
                        </a>
                    </li>
                    <li class="full-width divider-menu-h"></li>
                    <li class="full-width">
                        <a href="#!" class="full-width btn-subMenu">
                            <div class="navLateral-body-cl">
                                <i class="zmdi zmdi-wrench"></i>
                            </div>
                            <div class="navLateral-body-cr hide-on-tablet">
                                SETTINGS
                            </div>
                            <span class="zmdi zmdi-chevron-left"></span>
                        </a>
                        <ul class="full-width menu-principal sub-menu-options">
                            <li class="full-width">
                                <a href="#!" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-widgets"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        OPTION
                                    </div>
                                </a>
                            </li>
                            <li class="full-width">
                                <a href="#!" class="full-width">
                                    <div class="navLateral-body-cl">
                                        <i class="zmdi zmdi-widgets"></i>
                                    </div>
                                    <div class="navLateral-body-cr hide-on-tablet">
                                        OPTION
                                    </div>
                                </a>
                            </li>
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
                <i class="zmdi zmdi-store"></i>
            </div>
            <div class="full-width header-well-text">
                <p class="text-condensedLight">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde aut nulla accusantium minus corporis accusamus fuga harum natus molestias necessitatibus.
                </p>
            </div>
        </section>
        <div class="full-width divider-menu-h"></div>
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--12-col-desktop">
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width table-responsive">
                    <thead>
                        <tr>
                            <th>Proyectos</th>
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
                                            <a href="chatbox.php?estudiante_id=<?php echo htmlspecialchars($row['ID_Estudiante']); ?>" class="btn btn-primary">
                                                <button>Mensajes</button>
                                            </a>

                                            <button>Reporte</button>
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
    </section>
</body>


</html>