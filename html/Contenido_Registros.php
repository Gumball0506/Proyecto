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

// Obtener todos los proyectos y sus registrantes
$sql = "
    SELECT p.ID_Proyecto, p.Titulo, p.Fecha_Publicacion, p.Fecha_Inicio, COUNT(r.ID_Registro) AS cantidad_registrantes
    FROM proyectos p
    LEFT JOIN registro_de_proyectos_voluntarios r ON p.ID_Proyecto = r.ID_Proyecto
    GROUP BY p.ID_Proyecto, p.Titulo, p.Fecha_Publicacion, p.Fecha_Inicio
    ORDER BY p.Fecha_Publicacion DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos y Registrantes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #e0f7fa;
        }

        h1 {
            text-align: center;
            color: #FF6F00;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .project-details {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }

        .section-header {
            background-color: #FF6F00;
            color: #ffffff;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            font-size: 1.1em;
        }

        .project-details h2 {
            margin-top: 0;
            font-size: 1.2em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.9em;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #FF6F00;
            color: #ffffff;
        }

        tbody tr:nth-child(even) {
            background-color: #f1f8e9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        @media (max-width: 768px) {
            table {
                border: 0;
                border-collapse: collapse;
                width: 100%;
                overflow-x: auto;
                display: block;
                margin-bottom: 15px;
            }

            thead {
                display: none;
            }

            tr {
                display: block;
                border-bottom: 2px solid #ddd;
                margin-bottom: 10px;
            }

            td {
                display: block;
                text-align: right;
                font-size: 0.9em;
                padding: 10px;
                border-bottom: 1px solid #ddd;
                position: relative;
                background-color: #ffffff;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                font-weight: bold;
                white-space: nowrap;
                background-color: #f1f8e9;
            }

            td:last-child {
                border-bottom: 0;
            }
        }

        .btn-exportar {
            display: inline-block;
            background-color: #FF6F00;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            margin-bottom: 20px;
        }

        .btn-exportar:hover {
            background-color: #e65c00;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Informe de Proyectos y Registrantes</h1>
        <a href="/PHP/Excel_Creador.php" class="btn-exportar">Informe en Excel</a>
        <?php foreach ($proyectos as $proyecto): ?>
            <div class="project-details">
                <div class="section-header">
                    <h2><?php echo htmlspecialchars($proyecto['Titulo']); ?></h2>
                    <p><strong>Fecha de Publicación:</strong> <?php echo htmlspecialchars(date('d-m-Y', strtotime($proyecto['Fecha_Publicacion']))); ?></p>
                    <p><strong>Fecha de Inicio:</strong> <?php echo htmlspecialchars(date('d-m-Y', strtotime($proyecto['Fecha_Inicio']))); ?></p>
                    <p><strong>Cantidad de Registrantes:</strong> <?php echo htmlspecialchars($proyecto['cantidad_registrantes']); ?></p>
                </div>

                <?php
                // Obtener los registrantes para el proyecto actual
                $sql_registros = "
                    SELECT Nombre, Apellido, Correo, Telefono, Edad, Genero, Identificacion, Institucion, Fecha_Registro
                    FROM registro_de_proyectos_voluntarios
                    WHERE ID_Proyecto = :id_proyecto
                ";
                $stmt_registros = $pdo->prepare($sql_registros);
                $stmt_registros->execute(['id_proyecto' => $proyecto['ID_Proyecto']]);
                $registros = $stmt_registros->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <h3>Registrantes</h3>
                <?php if (count($registros) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Edad</th>
                                <th>Género</th>
                                <th>Identificación</th>
                                <th>Institución</th>
                                <th>Fecha de Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $registro): ?>
                                <tr>
                                    <td data-label="Nombre"><?php echo htmlspecialchars($registro['Nombre']); ?></td>
                                    <td data-label="Apellido"><?php echo htmlspecialchars($registro['Apellido']); ?></td>
                                    <td data-label="Correo"><?php echo htmlspecialchars($registro['Correo']); ?></td>
                                    <td data-label="Teléfono"><?php echo htmlspecialchars($registro['Telefono']); ?></td>
                                    <td data-label="Edad"><?php echo htmlspecialchars($registro['Edad']); ?></td>
                                    <td data-label="Género"><?php echo htmlspecialchars($registro['Genero']); ?></td>
                                    <td data-label="Identificación"><?php echo htmlspecialchars($registro['Identificacion']); ?></td>
                                    <td data-label="Institución"><?php echo htmlspecialchars($registro['Institucion']); ?></td>
                                    <td data-label="Fecha de Registro"><?php echo htmlspecialchars(date('d-m-Y', strtotime($registro['Fecha_Registro']))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay registrantes para este proyecto.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>