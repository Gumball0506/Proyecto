<?php
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

// Función para desencriptar el correo
function descifrar($datos)
{
    $password = 'ABCD-1234.aer'; // Contraseña de cifrado
    $metodo = 'AES-256-CBC'; // Método de cifrado
    $datos = base64_decode($datos); // Decodificar datos base64
    $ivSize = openssl_cipher_iv_length($metodo); // Tamaño del vector de inicialización
    $iv = substr($datos, 0, $ivSize); // Extraer IV
    $datosCifrados = substr($datos, $ivSize); // Extraer datos cifrados
    return openssl_decrypt($datosCifrados, $metodo, $password, OPENSSL_RAW_DATA, $iv); // Desencriptar
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión: " . $e->getMessage());
}

// Obtener los proyectos de la base de datos
$stmt = $pdo->query("SELECT PA.ID_ProyectoA, PA.Titulo_Proyecto, PA.Nombres_Apellidos, PA.Codigo_alumno, PA.Correo_Electronico, TA.Tipo AS Tipo_Archivo 
                     FROM proyectos_alumnos PA
                     INNER JOIN tipo_archivo TA ON PA.ID_Tipo_Archivo = TA.ID_Tipo_Archivo");
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Proyectos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .buttons a {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    <h1>Inventario de Proyectos</h1>

    <table>
        <thead>
            <tr>
                <th>Nombre y Apellidos</th>
                <th>Título del Proyecto</th>
                <th>Código</th>
                <th>Correo Electrónico</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($proyectos as $proyecto): ?>
                <tr>
                    <td><?php echo htmlspecialchars($proyecto['Nombres_Apellidos']); ?></td>
                    <td><?php echo htmlspecialchars($proyecto['Titulo_Proyecto']); ?></td>
                    <td><?php echo htmlspecialchars($proyecto['Codigo_alumno']); ?></td>
                    <td><?php echo htmlspecialchars(descifrar($proyecto['Correo_Electronico'])); ?></td>
                    <td class="buttons">
                        <a href="ver_documento.php?id=<?php echo $proyecto['ID_ProyectoA']; ?>" target="_blank">Vizualizar</a>
                        <a href="descargar_documento.php?id=<?php echo $proyecto['ID_ProyectoA']; ?>" download>Descargar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>