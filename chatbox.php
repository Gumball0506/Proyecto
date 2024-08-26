<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$servidor = "localhost";
$usuario = "RSUFIEI";
$password = "Bicicleta123*";
$base_datos = "Responsabilidad_Social";

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$base_datos", $usuario, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

function formatearFecha($fecha)
{
    return date('g:i a', strtotime($fecha));
}

// Obtener el ID del estudiante desde la URL
$estudiante_id = isset($_GET['estudiante_id']) ? intval($_GET['estudiante_id']) : null;

if (!$estudiante_id) {
    die("ID de estudiante no válido.");
}

// Definir el ID del administrador
$idAdmin = 1;

// Asegúrate de que el ID del estudiante sea válido
$consulta = "SELECT COUNT(*) FROM estudiantes WHERE ID_Estudiante = :estudiante_id";
$stmt = $conexion->prepare($consulta);
$stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
$stmt->execute();
if ($stmt->fetchColumn() == 0) {
    die("ID de estudiante no encontrado.");
}

// Verificar si ya existe una conversación entre este usuario y el estudiante
$consulta = "SELECT ID_Conversacion FROM conversaciones 
             WHERE (ID_Usuario = :idUsuario AND ID_Admin = :idAdmin)
                OR (ID_Usuario = :estudiante_id AND ID_Admin = :idAdmin)";
$stmt = $conexion->prepare($consulta);
$stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
$stmt->bindParam(':estudiante_id', $estudiante_id, PDO::PARAM_INT);
$stmt->bindParam(':idAdmin', $idAdmin, PDO::PARAM_INT);
$stmt->execute();

$idConversacion = $stmt->fetchColumn();

if (!$idConversacion) {
    // Si no existe una conversación, crear una nueva
    $idConversacion = uniqid('conv_', true);

    $consulta = "INSERT INTO conversaciones (ID_Conversacion, ID_Usuario, ID_Admin, Fecha_Creacion) 
                 VALUES (:idConversacion, :idUsuario, :idAdmin, NOW())";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':idConversacion', $idConversacion, PDO::PARAM_STR);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':idAdmin', $idAdmin, PDO::PARAM_INT);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['enviar'])) {
        $mensaje = $_POST['mensaje'];

        if (!empty($mensaje) && $idConversacion) {
            $consulta = "INSERT INTO mensajes (ID_Usuario, ID_Admin, Mensaje, Fecha, ID_Conversacion) 
                        VALUES (:idUsuario, :idAdmin, :mensaje, NOW(), :idConversacion)";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT); // Usa $idUsuario aquí
            $stmt->bindParam(':idAdmin', $idAdmin, PDO::PARAM_INT);
            $stmt->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt->bindParam(':idConversacion', $idConversacion, PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?estudiante_id=" . $estudiante_id);
                exit;
            } else {
                echo "Error al enviar el mensaje.";
            }
        }
    } elseif (isset($_POST['editar'])) {
        $mensajeId = $_POST['mensaje_id'];
        $nuevoMensaje = $_POST['mensaje'];

        $consulta = "UPDATE mensajes SET Mensaje = :mensaje WHERE ID_Mensaje = :mensajeId";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':mensaje', $nuevoMensaje, PDO::PARAM_STR);
        $stmt->bindParam(':mensajeId', $mensajeId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?estudiante_id=" . $estudiante_id);
            exit;
        } else {
            echo "Error al editar el mensaje.";
        }
    } elseif (isset($_POST['eliminar'])) {
        $mensajeId = $_POST['mensaje_id'];

        $consulta = "DELETE FROM mensajes WHERE ID_Mensaje = :mensajeId";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':mensajeId', $mensajeId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?estudiante_id=" . $estudiante_id);
            exit;
        } else {
            echo "Error al eliminar el mensaje.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            margin: 0;
            border: 0;
            box-sizing: border-box;
        }

        body {
            background: #972247;
            font-family: 'Lora', serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        #contenedor {
            width: 80%;
            max-width: 900px;
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 80vh;
            overflow: hidden;
        }

        #caja-chat {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        #datos-chat {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            background: #f9f9f9;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            display: flex;
            flex-direction: column;
        }

        #datos-chat span {
            display: block;
        }

        #datos-chat .username {
            color: #1c62c4;
            margin-bottom: 5px;
        }

        #datos-chat .message-text {
            color: #333;
            margin-bottom: 5px;
        }

        #datos-chat .message-time {
            color: #848484;
            font-size: 0.85rem;
            text-align: right;
        }

        textarea {
            width: calc(100% - 22px);
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            margin-bottom: 10px;
            resize: none;
        }

        input[type='submit'] {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 5px;
            background: #1c62c4;
            color: #fff;
            cursor: pointer;
            font-size: 1rem;
        }

        input[type='submit']:hover {
            background: #155a8a;
        }

        .btn-editar,
        .btn-eliminar {
            width: 9%;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
            font-size: 0.9rem;
            color: #fff;
            display: inline-flex;
            align-items: right;
        }

        .btn-editar {
            background: #28a745;
        }

        .btn-editar i {
            margin-right: 5px;
        }

        .btn-eliminar {
            background: #dc3545;
        }

        .btn-eliminar i {
            margin-right: 5px;
        }

        #formulario {
            display: flex;
            margin-top: 10px;
        }

        #formulario textarea {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
            font-size: 16px;
        }

        #formulario button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        #editModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none;
        }

        #editModal div {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }

        #editModal h2 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        #editModal textarea {
            width: 100%;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        #editModal button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        #editModal button.close {
            background: #dc3545;
        }

        #editModal button.close:hover {
            background: #c82333;
        }

        #editModal button:hover {
            background: #0056b3;
        }

        .message-user {
            background: #e1f5fe;
            align-self: flex-end;
        }

        .message-admin {
            background: #f1f1f1;
            align-self: flex-start;
        }
    </style>
    <script>
        function ajax() {
            var req = new XMLHttpRequest();
            req.onreadystatechange = function() {
                if (req.readyState == 4 && req.status == 200) {
                    document.getElementById('chat').innerHTML = req.responseText;
                }
            }
            req.open('GET', 'get_messages.php?id_conversacion=<?php echo $idConversacion; ?>', true);
            req.send();
        }

        function openEditModal(mensajeId, mensajeTexto) {
            document.getElementById('editModal').style.display = 'flex';
            document.getElementById('editMensajeId').value = mensajeId;
            document.getElementById('editMensajeTexto').value = mensajeTexto;
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        setInterval(function() {
            ajax();
        }, 1000);
    </script>
</head>

<body onload="ajax();">
    <div id="contenedor">
        <div id="caja-chat">
            <?php
            $consulta = "SELECT * FROM mensajes WHERE ID_Conversacion = :idConversacion ORDER BY Fecha DESC";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':idConversacion', $idConversacion, PDO::PARAM_STR);
            $stmt->execute();
            $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($mensajes as $mensaje) {
                $isUser = $mensaje['ID_Usuario'] == $idUsuario;
                $class = $isUser ? 'message-user' : 'message-admin';
                $formattedDate = formatearFecha($mensaje['Fecha']);
                $nombre = $isUser ? 'Yo' : 'Usuario';

                echo '<div id="datos-chat">';
                echo '<span class="username">' . $nombre . '</span>';
                echo '<span class="message-text">' . htmlspecialchars($mensaje['Mensaje']) . '</span>';
                echo '<span class="message-time">' . $formattedDate . '</span>';

                // Mostrar botones solo si no es el usuario actual
                if ($isUser) {
                    echo '<button type="button" class="btn-editar" onclick="abrirModal(' . $mensaje['ID_Mensaje'] . ', \'' . htmlspecialchars($mensaje['Mensaje']) . '\')"><i class="fas fa-edit"></i> Editar</button>';
                    echo '<form method="post" style="display: inline;">
                              <input type="hidden" name="mensaje_id" value="' . htmlspecialchars($mensaje['ID_Mensaje']) . '">
                              <button type="submit" name="eliminar" class="btn-eliminar" value="' . htmlspecialchars($mensaje['ID_Mensaje']) . '"><i class="fas fa-trash-alt"></i> Eliminar</button>
                          </form>';
                }

                echo '</div>';
            }
            ?>
        </div>
        <form method="POST">
            <textarea name="mensaje" placeholder="Escribe tu mensaje aquí..." required></textarea>
            <input type="submit" name="enviar" value="Enviar">
        </form>
    </div>

    <div id="editModal">
        <div>
            <h2>Editar Mensaje</h2>
            <form method="POST">
                <textarea id="editMessage" name="mensaje" required></textarea>
                <input type="hidden" id="mensaje_id" name="mensaje_id">
                <button type="submit" name="editar">Guardar Cambios</button>
                <button type="button" class="close" onclick="cerrarModal()">Cerrar</button>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id, mensaje) {
            document.getElementById('mensaje_id').value = id;
            document.getElementById('editMessage').value = mensaje;
            document.getElementById('editModal').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>

</html>