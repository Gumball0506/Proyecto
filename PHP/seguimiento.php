<!-- seguimiento.php -->
<?php
include 'conexion.php'; 
session_start();
$estudiante_id = $_SESSION['estudiante_id']; 
$sql = "SELECT Titulo_Proyecto, Proceso, Fecha_Envio FROM proyectos_alumnos WHERE ID_Estudiante = :estudiante_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
$stmt->execute();

$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Proyectos</title>
    <style>
        .timeline {
            display: flex;
            justify-content: space-between;
            margin: 20px auto;
            max-width: 900px;
            position: relative;
            padding: 10px 0;
        }
        .state {
            width: 30%;
            text-align: center;
            position: relative;
        }
        .state::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 6px;
            background-color: #e9e9e9;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
        }
        .state h3 {
            margin-bottom: 20px;
        }
        .project {
            position: relative;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Seguimiento de Proyectos</h2>
    <div class="timeline">
        <div class="state">
            <h3>En Proceso</h3>
            <?php foreach ($proyectos as $proyecto): ?>
                <?php if ($proyecto['Proceso'] == 'Proceso'): ?>
                    <div class="project">
                        <h4><?php echo htmlspecialchars($proyecto['Titulo_Proyecto']); ?></h4>
                        <p>Enviado el: <?php echo htmlspecialchars($proyecto['Fecha_Envio']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="state">
            <h3>Aceptado</h3>
            <?php foreach ($proyectos as $proyecto): ?>
                <?php if ($proyecto['Proceso'] == 'Aceptado'): ?>
                    <div class="project">
                        <h4><?php echo htmlspecialchars($proyecto['Titulo_Proyecto']); ?></h4>
                        <p>Enviado el: <?php echo htmlspecialchars($proyecto['Fecha_Envio']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="state">
            <h3>Rechazado</h3>
            <?php foreach ($proyectos as $proyecto): ?>
                <?php if ($proyecto['Proceso'] == 'Rechazado'): ?>
                    <div class="project">
                        <h4><?php echo htmlspecialchars($proyecto['Titulo_Proyecto']); ?></h4>
                        <p>Enviado el: <?php echo htmlspecialchars($proyecto['Fecha_Envio']); ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
