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
            $tipoArchivo = strtolower($documento['Tipo_Archivo']);
            $archivoBinario = $documento['Archivo_Proyecto'];

            switch ($tipoArchivo) {
                case 'pdf':
                case 'application/pdf':
                    header("Content-Type: application/pdf");
                    header("Content-Disposition: attachment; filename=\"" . $documento['Titulo_Proyecto'] . ".pdf\"");
                    break;
                case 'doc':
                case 'application/msword':
                case 'docx':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    // Aquí se asume que la conversión a PDF ya ha sido realizada
                    // y el documento es enviado como PDF
                    header("Content-Type: application/pdf");
                    header("Content-Disposition: attachment; filename=\"" . $documento['Titulo_Proyecto'] . ".pdf\"");
                    break;
                default:
                    header("Content-Type: application/octet-stream");
                    header("Content-Disposition: attachment; filename=\"" . $documento['Titulo_Proyecto'] . "\"");
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
