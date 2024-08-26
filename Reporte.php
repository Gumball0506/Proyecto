<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye la biblioteca FPDF desde la ruta correcta
require 'fpdf/fpdf.php';  // Asumiendo que `fpdf` está en el mismo directorio que `Reporte.php`


// Conexión a la base de datos
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Consulta para obtener los proyectos con detalles adicionales
$query = "
SELECT p.ID_Proyecto, p.Titulo, p.Fecha_Inicio, e.Nombre as Estado, a.Nombre as Administrador
FROM proyectos p
JOIN estado e ON p.ID_Estado = e.ID_Estado
JOIN administrador a ON p.ID_Admin = a.ID_Admin
";
$stmt = $pdo->query($query);
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear un nuevo documento PDF
class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        // Logo
        $this->Image('imagenes/logo_unfv.jpg', 10, 6, 30); // Ruta correcta de la imagen
        $this->SetFont('Arial', 'B', 12);
        // Mover a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(30, 10, 'Reporte de Proyectos', 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Creación de un nuevo PDF usando la clase con encabezado y pie de página
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 24);

// Portada
$pdf->Cell(0, 60, '', 0, 1, 'C');
$pdf->Cell(0, 10, 'Universidad Nacional Federico Villarreal', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(0, 10, 'Facultad de Ingenieria Electronica e Informatica', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 18);
$pdf->Cell(0, 10, 'Reporte de Proyectos de Responsabilidad Social', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 16);
$pdf->Cell(0, 10, 'Fecha: ' . date('d/m/Y'), 0, 1, 'C');
$pdf->Ln(20);
$pdf->SetFont('Arial', 'I', 14);
$pdf->Cell(0, 10, 'Autor: Jose Martin Gil Lopez', 0, 1, 'C');
$pdf->Ln(50);
$pdf->Cell(0, 10, 'Documento', 0, 1, 'C');
$pdf->AddPage();

// Título de la sección
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Detalle de Proyectos', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Titulo', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fecha Inicio', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Estado', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Administrador', 1, 1, 'C', true);

// Datos de la tabla
$pdf->SetFont('Arial', '', 12);
foreach ($proyectos as $proyecto) {
    $pdf->Cell(20, 10, $proyecto['ID_Proyecto'], 1, 0, 'C');
    $pdf->Cell(50, 10, mb_convert_encoding(substr($proyecto['Titulo'], 0, 30), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L'); // Limitar el título a 30 caracteres
    $pdf->Cell(30, 10, $proyecto['Fecha_Inicio'], 1, 0, 'C');
    $pdf->Cell(30, 10, $proyecto['Estado'], 1, 0, 'C');
    $pdf->Cell(50, 10, $proyecto['Administrador'], 1, 1, 'C');
}

// Agregar más secciones si es necesario
$pdf->AddPage();

// Sección de Eventos
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Eventos de los Proyectos', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

$queryEventos = "
SELECT c.ID_Proyecto, c.titulo, c.fecha_evento
FROM calendario c
";
$stmtEventos = $pdo->query($queryEventos);
$eventos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(30, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Titulo Evento', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fecha Evento', 1, 1, 'C', true);

// Datos de la tabla
$pdf->SetFont('Arial', '', 12);
foreach ($eventos as $evento) {
    $pdf->Cell(30, 10, $evento['ID_Proyecto'], 1, 0, 'C');
    $pdf->Cell(80, 10, mb_convert_encoding(substr($evento['titulo'], 0, 30), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L'); // Limitar el título a 30 caracteres
    $pdf->Cell(30, 10, $evento['fecha_evento'], 1, 1, 'C');
}

// Agregar más secciones si es necesario
$pdf->AddPage();

// Sección de Calificaciones
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Calificaciones de Proyectos', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

$queryCalificaciones = "
SELECT c.ID_Proyecto, e.Nombre AS Estudiante, c.calificacion
FROM calificaciones c
JOIN estudiantes e ON c.ID_Estudiante = e.ID_Estudiante
";
$stmtCalificaciones = $pdo->query($queryCalificaciones);
$calificaciones = $stmtCalificaciones->fetchAll(PDO::FETCH_ASSOC);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(30, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(60, 10, 'Estudiante', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Calificacion', 1, 1, 'C', true);

// Datos de la tabla
$pdf->SetFont('Arial', '', 12);
foreach ($calificaciones as $calificacion) {
    $pdf->Cell(30, 10, $calificacion['ID_Proyecto'], 1, 0, 'C');
    $pdf->Cell(60, 10, mb_convert_encoding(substr($calificacion['Estudiante'], 0, 30), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L'); // Limitar el nombre del estudiante a 30 caracteres
    $pdf->Cell(30, 10, $calificacion['calificacion'], 1, 1, 'C');
}

// Agregar más secciones si es necesario
$pdf->AddPage();

// Sección de Proyectos Antiguos
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Proyectos Antiguos', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

$queryProyectosAntiguos = "
SELECT p.ID_Antiguos, p.Titulo, p.Fecha_Publicacion
FROM proyectos_antiguos p
";
$stmtProyectosAntiguos = $pdo->query($queryProyectosAntiguos);
$proyectosAntiguos = $stmtProyectosAntiguos->fetchAll(PDO::FETCH_ASSOC);

// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(30, 10, 'ID', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Titulo', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fecha', 1, 1, 'C', true);

// Datos de la tabla
$pdf->SetFont('Arial', '', 12);
foreach ($proyectosAntiguos as $proyectoAntiguo) {
    $pdf->Cell(30, 10, $proyectoAntiguo['ID_Antiguos'], 1, 0, 'C');
    $pdf->Cell(80, 10, mb_convert_encoding(substr($proyectoAntiguo['Titulo'], 0, 30), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L'); // Limitar el título a 30 caracteres
    $pdf->Cell(30, 10, $proyectoAntiguo['Fecha_Publicacion'], 1, 1, 'C');
}

// Cierre y salida del documento PDF
$pdf->Output();
