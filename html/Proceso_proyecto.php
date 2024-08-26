<?php
session_start();

// Verifica si el estudiante está logueado
$isLoggedIn = isset($_SESSION['estudiante_id']);

if (!$isLoggedIn) {
    header("Location: login.php");
    exit();
}

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

// Obtiene el ID del estudiante logueado
$estudiante_id = $_SESSION['estudiante_id'];

// Consulta para obtener los proyectos del estudiante
$query = "SELECT * FROM proyectos_alumnos WHERE ID_Estudiante = :estudiante_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['estudiante_id' => $estudiante_id]);
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Proyectos</title>
    <link rel="stylesheet" href="styles.css"> <!-- Tu archivo CSS -->
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #ddd;
    }

    .container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        color: #333;
        margin-bottom: 20px;
    }

    .button-container {
        margin-bottom: 20px;
        text-align: right;
    }

    .button-container a {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    .button-container a:hover {
        background-color: #45a049;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .progress-container {
        width: 100%;
        background-color: #f3f3f3;
        border-radius: 5px;
        overflow: hidden;
        margin: 5px 0;
    }

    .progress-bar {
        height: 20px;
        background-color: #4caf50;
        color: #333;
        text-align: center;
        line-height: 20px;
        border-radius: 5px;
        white-space: nowrap;
    }

    a {
        color: #1e90ff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .contact-btn {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .contact-btn:hover {
        background-color: #0056b3;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
    }

    .modal-header,
    .modal-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
    }

    .close-btn {
        background-color: #f44336;
        color: white;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        border-radius: 5px;
    }

    .close-btn:hover {
        background-color: #d32f2f;
    }

    .modal-body {
        margin-top: 10px;
    }

    .modal-body p {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        resize: vertical;
    }

    .modal-footer .send-btn {
        background-color: #4CAF50;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-footer .send-btn:hover {
        background-color: #45a049;
    }
</style>

<body>
    <div class="container">
        <div class="button-container">
            <a href="/html/web1.php">Inicio</a>
        </div>
        <h1>Mis Proyectos</h1>
        <?php if (empty($proyectos)): ?>
            <p>No tienes proyectos en proceso.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Teléfono</th>
                        <th>Proceso</th>
                        <th>Fecha de Envío</th>
                        <th>Estado</th>
                        <th>Contactanos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proyecto['Titulo_Proyecto']); ?></td>
                            <td><?php echo htmlspecialchars($proyecto['Descripcion']); ?></td>
                            <td><?php echo htmlspecialchars($proyecto['Numero_telefono']); ?></td>
                            <td>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?php echo getProgressWidth($proyecto['Proceso']); ?>;">
                                        <?php echo getProgressText($proyecto['Proceso']); ?>
                                    </div>
                                </div>
                                <?php if ($proyecto['Proceso'] === 'Etapa4'): ?>
                                    <p>Este proyecto ya está siendo gestionado por la escuela. Comuníquese con ella lo más pronto posible.</p>
                                <?php elseif ($proyecto['Proceso'] === 'Rechazado'): ?>
                                    <button class="contact-btn" onclick="openModal('<?php echo htmlspecialchars($proyecto['Titulo_Proyecto']); ?>')">Importante</button>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($proyecto['Fecha_Envio']); ?></td>
                            <td><?php echo htmlspecialchars($proyecto['visible'] ? 'Visible' : 'No Visible'); ?></td>
                            <td><button class="contact-btn" onclick="window.location.href='/chat.php'">Contacto</button></td>

                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div id="infoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Información Adicional</h2>
                <button class="close-btn" onclick="closeModal()">Cerrar</button>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
        </div>
    </div>
    <script>
        function openModal(titulo) {
            const message = `Lamentamos informarle que el proyecto '${titulo}' ha sido rechazado por los administradores y el jefe del área de Responsabilidad Social Universitaria. Uno de los motivos puede haber sido el contexto del proyecto, la forma en que fue presentado, o la falta de explicación. Si desea más detalles sobre el rechazo, comuníquese con el encargado del area de responsabilidad social universitario Jose Martin Gil Lopez su correo es 'jgill@unfv.edu.pe' para obtener más información.`;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('infoModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('infoModal').style.display = 'none';
        }
    </script>
</body>

</html>

<?php
// Funciones auxiliares
function getProgressWidth($etapa)
{
    switch ($etapa) {
        case 'Etapa1':
            return '25%';
        case 'Etapa2':
            return '50%';
        case 'Etapa3':
            return '75%';
        case 'Etapa4':
            return '100%';

        default:
            return '0%';
    }
}

function getProgressText($etapa)
{
    switch ($etapa) {
        case 'Etapa1':
            return 'Etapa 1 - 25%';
        case 'Etapa2':
            return 'Etapa 2 - 50%';
        case 'Etapa3':
            return 'Etapa 3 - 75%';
        case 'Etapa4':
            return 'Etapa 4 - 100%';
        default:
            return 'No disponible';
    }
}
?>