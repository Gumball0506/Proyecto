<?php
session_start();
$host = 'localhost';
$dbname = 'proyecto_integrador';
$username = 'root';
$password = '';

// Verifica si el estudiante está conectado
if (!isset($_SESSION['estudiante_id'])) {
    echo 'No estás conectado.';
    exit;
}

$estudiante_id = $_SESSION['estudiante_id'];

// Consulta para obtener el nombre y apellido del estudiante
$query = $pdo->prepare("
    SELECT nombre, apellido 
    FROM estudiantes 
    WHERE ID_Estudiante = :estudiante_id
");
$query->execute(['estudiante_id' => $estudiante_id]);
$estudiante = $query->fetch(PDO::FETCH_ASSOC);

if (!$estudiante) {
    echo 'Estudiante no encontrado.';
    exit;
}

// Extrae nombre y apellido
$nombre = $estudiante['nombre'];
$apellido = $estudiante['apellido'];

// Consulta para obtener proyectos enviados por el estudiante
$query = $pdo->prepare("
    SELECT p.ID_Proyecto, p.Titulo, p.Descripcion, p.Fecha_Envio 
    FROM proyectos_alumnos pa
    JOIN proyectos p ON pa.ID_Proyecto = p.ID_Proyecto
    JOIN estudiantes e ON pa.ID_Estudiante = e.ID_Estudiante
    WHERE e.nombre = :nombre AND e.apellido = :apellido
");
$query->execute(['nombre' => $nombre, 'apellido' => $apellido]);
$proyectos = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos del Estudiante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .project {
            background-color: #f7f7f7;
            padding: 15px;
            border-left: 4px solid #007BFF;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .project strong {
            color: #007BFF;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Proyectos Enviados por <?php echo htmlspecialchars($nombre . ' ' . $apellido); ?></h1>

        <?php if (empty($proyectos)): ?>
            <p>No se encontraron proyectos enviados.</p>
        <?php else: ?>
            <?php foreach ($proyectos as $proyecto): ?>
                <div class="project">
                    <strong>Título:</strong> <?php echo htmlspecialchars($proyecto['Titulo']); ?><br>
                    <strong>Descripción:</strong> <?php echo htmlspecialchars($proyecto['Descripcion']); ?><br>
                    <strong>Fecha de Envío:</strong> <?php echo htmlspecialchars($proyecto['Fecha_Envio']); ?><br>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

</html>