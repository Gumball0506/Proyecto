<?php
require 'fpdf/fpdf.php';  // Asumiendo que `fpdf` está en el mismo directorio que `Reporte.php`


$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

// Obtener el ID del proyecto de la URL
$id_proyecto = isset($_GET['id_proyecto']) ? $_GET['id_proyecto'] : '';

if ($id_proyecto) {
    // Preparar y ejecutar la consulta para obtener los detalles del proyecto
    try {
        $stmt = $pdo->prepare('
            SELECT
                e.Nombre,
                e.Apellido,
                p.Titulo_Proyecto,
                e.Codigo_Estudiante,
                e.Email
            FROM
                proyectos_alumnos p
            JOIN
                estudiantes e ON p.ID_Estudiante = e.ID_Estudiante
            WHERE
                p.ID_ProyectoA = :id_proyecto
        ');
        $stmt->bindParam(':id_proyecto', $id_proyecto, PDO::PARAM_INT);
        $stmt->execute();
        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($proyecto) {
            // Crear el PDF con FPDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetMargins(25, 20, 25);

            // Agregar logo de la universidad
            $pdf->Image('imagenes/Logo_UNFV.png', 10, 10, 30); // Ruta del logo de la universidad

            // Título de la universidad
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Universidad Nacional Federico Villarreal', 0, 1, 'C');
            $pdf->SetFont('Arial', 'I', 12);
            $pdf->Cell(0, 10, 'Facultad de Ingieneria Electronica e Informatica', 0, 1, 'C');
            $pdf->Ln(20);

            // Introducción formal
            $pdf->SetFont('Arial', '', 12);
            $intro = "Este documento contiene un reporte detallado del proyecto academico desarrollado por el estudiante, que ha sido sometido a revision y evaluacion por parte del comite academico de la Universisda Nacional Federico Villarreal. A continuacion, se presentan los detalles del estudiante y del proyecto.";
            $pdf->MultiCell(0, 10, $intro, 0, 'J');
            $pdf->Ln(10);

            // Título del documento
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Reporte de Proyecto', 0, 1, 'C');
            $pdf->Ln(15);

            // Detalles del proyecto
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 10, 'Nombre del Estudiante: ' . ucwords(strtolower($proyecto['Nombre'])) . ' ' . ucwords(strtolower($proyecto['Apellido'])), 0, 1, 'C');
            $pdf->Cell(0, 10, 'Titulo del Proyecto: ' . ucwords(strtolower($proyecto['Titulo_Proyecto'])), 0, 1, 'C');
            $pdf->Cell(0, 10, 'Codigo del Estudiante: ' . $proyecto['Codigo_Estudiante'], 0, 1, 'C');
            $pdf->Cell(0, 10, 'Correo Electronico: ' . strtolower($proyecto['Email']), 0, 1, 'C');
            $pdf->Ln(20);

            // Cierre del documento
            $pdf->SetFont('Arial', '', 12);
            $cierre = "Este reporte se emite para su uso exclusivo en procesos academicos y administrativos. El contenido aqui presentado ha sido preparado con la maxima precision posible, con la finalidad de servir como registro oficial del proyecto desarrollado por el estudiante.";
            $pdf->MultiCell(0, 10, $cierre, 0, 'J');
            $pdf->Ln(20);

            // Pie de página
            $pdf->SetY(-30);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->Cell(0, 10, 'Fecha de emision: ' . date('d/m/Y'), 0, 0, 'L');
            $pdf->Cell(0, 10, 'Pagina ' . $pdf->PageNo(), 0, 0, 'R');

            // Salida del PDF
            $pdf->Output();
        } else {
            echo "No se encontró el proyecto.";
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo "ID de proyecto no proporcionado.";
}
