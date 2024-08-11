<?php
include 'conexion.php';

function cifrar($datos)
{
    $password = 'ABCD-1234.aer'; // Contraseña de cifrado
    $metodo = 'AES-256-CBC'; // Método de cifrado
    $ivSize = openssl_cipher_iv_length($metodo); // Tamaño del vector de inicialización
    $iv = openssl_random_pseudo_bytes($ivSize); // Generar IV aleatorio
    $datosCifrados = openssl_encrypt($datos, $metodo, $password, OPENSSL_RAW_DATA, $iv); // Cifrar los datos
    return base64_encode($iv . $datosCifrados); // Retornar IV + datos cifrados en base64
}

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

    // Cifrar el correo antes de insertarlo
    $correoCifrado = cifrar($correo);

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
            Proceso,
            ID_Admin
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
            "Proceso",
            1
        )
    ');

    $stmt->execute([
        ':nombre' => $nombre,
        ':titulo_proyecto' => $titulo,
        ':codigo' => $codigo,
        ':descripcion' => $descripcion,
        ':correo' => $correoCifrado, // Guardar el correo cifrado
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
