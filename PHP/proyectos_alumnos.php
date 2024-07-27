<?php
require 'conexion.php';

try {
    // Validar y sanitizar entradas
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $titulo = filter_input(INPUT_POST, 'titulo_proyecto', FILTER_SANITIZE_STRING);
    $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_NUMBER_INT);
    $facultad = filter_input(INPUT_POST, 'facultad', FILTER_SANITIZE_NUMBER_INT);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
    $archivo = $_FILES['archivo'];

    if (!$nombre || !$codigo || !$titulo || !$facultad || !$telefono || !$descripcion || !$correo || !$archivo) {
        throw new Exception('Datos inválidos.');
    }

    // Obtener tipo de archivo
    $fileExtension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $stmt = $pdo->prepare('SELECT ID_Tipo_Archivo FROM tipo_archivo WHERE Tipo LIKE :extension');
    $stmt->execute([':extension' => '%' . $fileExtension . '%']);
    $tipoArchivo = $stmt->fetchColumn();

    if (!$tipoArchivo) {
        throw new Exception('Tipo de archivo no permitido.');
    }

    // Leer contenido del archivo
    $archivoContenido = file_get_contents($archivo['tmp_name']);
    if ($archivoContenido === false) {
        throw new Exception('Error al leer el archivo.');
    }

    // Comenzar transacción
    $pdo->beginTransaction();

    // Insertar datos en la tabla proyectos_alumnos
    $stmt = $pdo->prepare('
        INSERT INTO proyectos_alumnos (
            Nombres_Apellidos,
            Titulo_Proyecto,
            Codigo_alumno, 
            Descripcion, 
            Correo_Electronico, 
            Archivo_Proyecto, 
            ID_Tipo_Archivo, 
            ID_Facultad, 
            Numero_telefono, 
            Proceso
        ) VALUES (
            :nombre,
            :titulo_proyecto,
            :codigo, 
            :descripcion, 
            :correo, 
            :archivo, 
            :tipoArchivo, 
            :facultad, 
            :telefono, 
            "Proceso"
        )
    ');

    $stmt->execute([
        ':nombre' => $nombre,
        ':titulo_proyecto' => $titulo,
        ':codigo' => $codigo,
        ':descripcion' => $descripcion,
        ':correo' => $correo,
        ':archivo' => $archivoContenido,
        ':tipoArchivo' => $tipoArchivo,
        ':facultad' => $facultad,
        ':telefono' => $telefono
    ]);

    // Commit de la transacción
    $pdo->commit();

    echo 'Datos guardados exitosamente.';
} catch (Exception $e) {
    // Rollback en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo 'Error: ' . $e->getMessage();
}
