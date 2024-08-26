<?php
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
	// Conexión a la base de datos usando PDO
	$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	// Registrar el error y detener la ejecución
	error_log("Error de conexión: " . $e->getMessage());
	die("Error de conexión: " . $e->getMessage());
}

// Contar el número de conversaciones
$queryConversaciones = "SELECT COUNT(*) as total_conversaciones FROM conversaciones";
$resultConversaciones = $pdo->query($queryConversaciones);
$totalConversaciones = $resultConversaciones->fetch(PDO::FETCH_ASSOC)['total_conversaciones'];

// Contar el número total de mensajes
$queryMensajes = "SELECT COUNT(*) as total_mensajes FROM mensajes";
$resultMensajes = $pdo->query($queryMensajes);
$totalMensajes = $resultMensajes->fetch(PDO::FETCH_ASSOC)['total_mensajes'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/material.min.css">
	<link rel="stylesheet" href="css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
	<link rel="stylesheet" href="css/main.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/material.min.js"></script>
	<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
	<script src="js/main copy.js"></script>
	<script src="progreso.js" defer></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<style>
	/* styles.css */
	.container {
		width: 80%;
		margin: 0 auto;
		padding: 20px;
	}

	#progressContainer {
		margin-top: 20px;
	}

	.stage {
		font-weight: bold;
		margin-bottom: 5px;
	}

	.button-container {
		margin-top: 10px;
	}

	.button-container button {
		padding: 10px 20px;
		margin-right: 10px;
		border: none;
		color: white;
		background-color: #007bff;
		border-radius: 5px;
		cursor: pointer;
	}

	.button-container button:hover {
		background-color: #0056b3;
	}

	/* styles.css */
	/* styles.css */

	.progress-bar {
		width: 100%;
		background-color: #e0e0e0;
		border-radius: 4px;
		overflow: hidden;
		margin-bottom: 10px;
		/* Agregar margen si es necesario */
	}

	.progress-bar-inner {
		height: 30px;
		line-height: 30px;
		color: white;
		text-align: center;
		background-color: #0000FF;
		/* Azul para todas las etapas */
		transition: width 1s;
		/* Transición suave de 1 segundo */
	}

	/* Debug CSS */
	.progress-bar-inner {
		background-color: #00FF00 !important;
		/* Verde brillante para depurar */
	}
</style>

<body>
	<!-- navBar -->
	<div class="full-width navBar">
		<div class="full-width navBar-options">
			<i class="zmdi zmdi-more-vert btn-menu" id="btn-menu"></i>
			<div class="mdl-tooltip" for="btn-menu">Menu</div>
			<nav class="navBar-options-list">
				<ul class="list-unstyle"></ul>
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
	<section class="full-width pageContent">
		<section class="full-width text-center" style="padding: 40px 0;">
			<h3 class="text-center tittles">SISTEMA DE GESTION</h3>
			<!-- Tile para mostrar el número de conversaciones -->
			<article class="full-width tile">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $totalConversaciones; ?><br>
						<small>Total de Conversaciones</small>
					</span>
				</div>
				<i class="zmdi zmdi-comment-list tile-icon"></i>
			</article>
			<!-- Tile para mostrar el número de mensajes -->
			<article class="full-width tile">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $totalMensajes; ?><br>
						<small>Total de Mensajes</small>
					</span>
				</div>
				<i class="zmdi zmdi-email tile-icon"></i>
			</article>
			<section class="container">
				<h3 class="text-center tittles">Proceso de los proyectos</h3>
				<div id="progressContainer">
					<!-- Aquí se inyectarán las barras de progreso dinámicamente -->
				</div>
			</section>
		</section>
	</section>
</body>

</html>