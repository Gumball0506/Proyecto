<?php
include 'conexion.php';
session_start(); // Iniciar sesión para acceder a las variables de sesión

try {
    // Verificar y sanitizar entradas
    $titulo = filter_input(INPUT_POST, 'titulo_proyecto', FILTER_SANITIZE_STRING);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $archivo = $_FILES['archivo'];

    // Depuración: Imprimir datos recibidos
    if (empty($titulo) || empty($telefono) || empty($descripcion) || empty($archivo)) {
        throw new Exception('Datos inválidos. Verifique todos los campos.');
    }

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error en la carga del archivo.');
    }

    // Verificar si el estudiante está logueado
    if (!isset($_SESSION['estudiante_id'])) {
        throw new Exception('Estudiante no autenticado.');
    }
    $estudianteId = $_SESSION['estudiante_id']; // Obtener el ID del estudiante desde la sesión

    // Obtener tipo de archivo
    $fileExtension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['pdf', 'docx', 'doc', 'pptx', 'ppt', 'txt', 'jpg', 'png'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Tipo de archivo no permitido.');
    }

    // Leer el contenido del archivo
    $archivoContenido = file_get_contents($archivo['tmp_name']);
    if ($archivoContenido === false) {
        throw new Exception('Error al leer el archivo.');
    }

    // Obtener ID_Tipo_Archivo desde la tabla tipo_archivo
    $tipoArchivoQuery = $pdo->prepare('SELECT ID_Tipo_Archivo FROM tipo_archivo WHERE Tipo = :tipo');
    $tipoArchivoQuery->execute([':tipo' => $fileExtension]);
    $tipoArchivoResult = $tipoArchivoQuery->fetch(PDO::FETCH_ASSOC);

    if ($tipoArchivoResult) {
        $tipoArchivo = $tipoArchivoResult['ID_Tipo_Archivo'];
    } else {
        throw new Exception('Tipo de archivo no encontrado en la base de datos.');
    }

    // Preparar y ejecutar la inserción en la base de datos
    $stmt = $pdo->prepare('
        INSERT INTO proyectos_alumnos (
            Titulo_Proyecto,
            Descripcion, 
            Archivo_Proyecto, 
            ID_Tipo_Archivo, 
            Numero_telefono, 
            Proceso,
            ID_Admin,
            ID_Estudiante
        ) VALUES (
            :titulo_proyecto,
            :descripcion, 
            :archivo, 
            :tipoArchivo, 
            :telefono, 
            "Proceso",
            1,
            :estudiante_id
        )
    ');

    $stmt->execute([
        ':titulo_proyecto' => $titulo,
        ':descripcion' => $descripcion,
        ':archivo' => $archivoContenido,
        ':tipoArchivo' => $tipoArchivo,
        ':telefono' => $telefono,
        ':estudiante_id' => $estudianteId // Insertar el ID del estudiante
    ]);

    echo 'Formulario enviado exitosamente.';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
