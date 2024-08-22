<?php
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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT PA.Titulo_Proyecto, TA.Tipo AS Tipo_Archivo, PA.Archivo_Proyecto 
                               FROM proyectos_alumnos PA 
                               INNER JOIN tipo_archivo TA ON PA.ID_Tipo_Archivo = TA.ID_Tipo_Archivo 
                               WHERE PA.ID_ProyectoA = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $documento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($documento) {
            $tipoArchivo = strtolower($documento['Tipo_Archivo']); // Convertir a minúsculas
            $nombreArchivo = $documento['Titulo_Proyecto'];
            $archivoBinario = $documento['Archivo_Proyecto'];

            switch ($tipoArchivo) {
                case 'pdf':
                case 'application/pdf':
                    header("Content-Type: application/pdf");
                    header("Content-Disposition: inline; filename=\"" . $nombreArchivo . ".pdf\"");
                    break;
                case 'doc':
                case 'application/msword':
                case 'docx':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    header("Content-Type: " . $tipoArchivo);
                    header("Content-Disposition: inline; filename=\"" . $nombreArchivo . ".docx\"");
                    break;
                default:
                    header("Content-Type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=\"" . $nombreArchivo . "\"");
                    break;
            }

            echo $archivoBinario;
            exit();
        } else {
            echo "Documento no encontrado.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID no especificado.";
}
