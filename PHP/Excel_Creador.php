<?php
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

// Obtener todos los proyectos
$sql = "
    SELECT p.ID_Proyecto, p.Titulo, p.Fecha_Publicacion, p.Fecha_Inicio
    FROM proyectos p
    ORDER BY p.Fecha_Publicacion DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Definir los encabezados para la descarga del archivo
header("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=datos_proyectos.xls");

// Generar el archivo Excel con HTML
echo "<html>";
echo "<head>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; }";
echo "table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }";
echo "th, td { border: 1px solid #000; padding: 8px; text-align: left; }";
echo "th { background-color: #f2f2f2; color: #333; }";
echo "tr:nth-child(even) { background-color: #f9f9f9; }";
echo "tr:hover { background-color: #f1f1f1; }";
echo ".section-title { background-color: #d9edf7; font-weight: bold; padding: 10px; text-align: center; font-size: 1.2em; }";
echo ".subtable { border: 1px solid #ddd; margin-top: 10px; }";
echo ".subtable th { background-color: #e9ecef; }";
echo "</style>";
echo "</head>";
echo "<body>";

foreach ($proyectos as $proyecto) {
    // Mostrar el título del proyecto
    echo "<div class='section-title'>Proyecto: {$proyecto['Titulo']}</div>";

    // Obtener registrantes para el proyecto actual
    $sql_registrantes = "
        SELECT r.ID_Registro, r.Nombre, r.Apellido, r.Correo, r.Telefono, r.Edad, r.Genero, r.Identificacion, r.Institucion, r.Disponibilidad, r.Fecha_Registro
        FROM registro_de_proyectos_voluntarios r
        WHERE r.ID_Proyecto = :id_proyecto
    ";
    $stmt_registrantes = $pdo->prepare($sql_registrantes);
    $stmt_registrantes->execute(['id_proyecto' => $proyecto['ID_Proyecto']]);
    $registrantes = $stmt_registrantes->fetchAll(PDO::FETCH_ASSOC);

    // Mostrar tabla de registrantes
    echo "<table class='subtable'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID_Registro</th>";
    echo "<th>Nombre</th>";
    echo "<th>Apellido</th>";
    echo "<th>Correo</th>";
    echo "<th>Telefono</th>";
    echo "<th>Edad</th>";
    echo "<th>Genero</th>";
    echo "<th>Identificacion</th>";
    echo "<th>Institucion</th>";
    echo "<th>Disponibilidad</th>";
    echo "<th>Fecha_Registro</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    foreach ($registrantes as $registrante) {
        echo "<tr>";
        echo "<td>{$registrante['ID_Registro']}</td>";
        echo "<td>{$registrante['Nombre']}</td>";
        echo "<td>{$registrante['Apellido']}</td>";
        echo "<td>{$registrante['Correo']}</td>";
        echo "<td>{$registrante['Telefono']}</td>";
        echo "<td>{$registrante['Edad']}</td>";
        echo "<td>{$registrante['Genero']}</td>";
        echo "<td>{$registrante['Identificacion']}</td>";
        echo "<td>{$registrante['Institucion']}</td>";
        echo "<td>{$registrante['Disponibilidad']}</td>";
        echo "<td>" . date('d-m-Y', strtotime($registrante['Fecha_Registro'])) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
}

echo "</body>";
echo "</html>";
