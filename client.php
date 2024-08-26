<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
	$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	// echo "Conexión exitosa a la base de datos."; // Comenta o elimina esta línea en producción
} catch (PDOException $e) {
	error_log("Error de conexión: " . $e->getMessage()); // Registra el error en el archivo de log del servidor
	die("Error de conexión. Por favor, intente más tarde.");
}

$query = "
    SELECT 
        e.ID_Estudiante,
        e.Nombre,
        e.Apellido,
        e.Codigo_Estudiante,
        IFNULL(p.Titulo, 'No se registró en ningún proyecto') AS Proyecto
    FROM 
        estudiantes e
    LEFT JOIN 
        registro_de_proyectos_voluntarios r ON e.ID_Estudiante = r.ID_Registro
    LEFT JOIN 
        proyectos p ON r.ID_Proyecto = p.ID_Proyecto
    ORDER BY 
        e.Apellido, e.Nombre;
";

try {
	$result = $pdo->query($query);
	if ($result === false) {
		throw new Exception('Error en la consulta.');
	}
	$estudiantes = $result->fetchAll(PDO::FETCH_ASSOC);
	// var_dump($estudiantes); // Para depuración
} catch (Exception $e) {
	error_log("Error en la consulta: " . $e->getMessage());
	die("Error al obtener los datos. Por favor, intente más tarde.");
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
					<span>
						Panel de control<br>
						<small></small>
					</span>
				</figcaption>
			</figure>
			<div class="full-width tittles navLateral-body-tittle-menu">
				<i class="zmdi zmdi-desktop-mac"></i><span class="hide-on-tablet">&nbsp; PANEL</span>
			</div>
			<nav class="full-width">
				<ul class="full-width list-unstyle menu-principal">
					<li class="full-width">
						<a href="/html/web1.php" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-view-dashboard"></i>
							</div>
							<div class="navLateral-body-cr hide-on-tablet">
								INICIO
							</div>
						</a>
					</li>
					<li class="full-width">
						<a href="home.php" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-settings"></i>
							</div>
							<div class="navLateral-body-cr hide-on-tablet">
								PANEL DE GESTION
							</div>
						</a>
					</li>
					<li class="full-width">
						<a href="panel_mensajes.php" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-email"></i>
							</div>
							<div class="navLateral-body-cr hide-on-tablet">
								MENSAJES
							</div>
						</a>
					</li>

					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="#!" class="full-width btn-subMenu">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-face"></i>
							</div>
							<div class="navLateral-body-cr hide-on-tablet">
								USUARIOS
							</div>
							<span class="zmdi zmdi-chevron-left"></span>
						</a>
						<ul class="full-width menu-principal sub-menu-options">
							<li class="full-width">
								<a href="/client.php" class="full-width">
									<div class="navLateral-body-cl">
										<i class="zmdi zmdi-accounts"></i>
									</div>
									<div class="navLateral-body-cr hide-on-tablet">
										USUSARIOS REGISTRADOS
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="full-width divider-menu-h"></li>
					<li class="full-width">
						<a href="inventory.php" class="full-width">
							<div class="navLateral-body-cl">
								<i class="zmdi zmdi-store"></i>
							</div>
							<div class="navLateral-body-cr hide-on-tablet">
								REGISTRO DE PROYECTOS
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
	<section class="full-width pageContent">
		<section class="full-width header-well">
			<div class="full-width header-well-icon">
				<i class="zmdi zmdi-accounts"></i>
			</div>
			<div class="full-width header-well-text">
				<p class="text-condensedLight">
					👩‍🎓👨‍🎓 Alumnos en Nuestra Comunidad 🌐

					Aquí podrás ver el total de alumnos registrados en nuestra página, así como aquellos que están participando en proyectos específicos. 🚀📊


				</p>
			</div>
		</section>
		<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
			<div class="mdl-tabs__panel" id="tabListClient">
				<div class="mdl-grid">
					<div class="mdl-cell mdl-cell--4-col-phone mdl-cell--8-col-tablet mdl-cell--8-col-desktop mdl-cell--2-offset-desktop">
						<div class="full-width panel mdl-shadow--2dp">
							<div class="full-width panel-tittle bg-success text-center tittles">
								🎓 Alumnos en esta Comunidad 🌟
								<div class="full-width">
									<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width">
										<thead>
											<tr>
												<th class="text-center">ID Estudiante</th>
												<th class="text-center">Nombre</th>
												<th class="text-center">Apellido</th>
												<th class="text-center">Código de Estudiante</th>
												<th class="text-center">Proyecto</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($estudiantes as $estudiante): ?>
												<tr>
													<th class="text-center"><?php echo htmlspecialchars($estudiante['ID_Estudiante']); ?></th>
													<th class="text-center"><?php echo htmlspecialchars($estudiante['Nombre']); ?></th>
													<th class="text-center"><?php echo htmlspecialchars($estudiante['Apellido']); ?></th>
													<th class="text-center"><?php echo htmlspecialchars($estudiante['Codigo_Estudiante']); ?></th>
													<th class="text-center"><?php echo htmlspecialchars($estudiante['Proyecto']); ?></th>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		</div>
	</section>
</body>

</html>