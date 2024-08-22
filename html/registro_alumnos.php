<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Voluntarios</title>
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
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .project-details {
            background-color: #f7f7f7;
            padding: 15px;
            border-left: 4px solid #007BFF;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .project-details strong {
            color: #007BFF;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            background-color: #007BFF;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            color: #5bc0de;
            background-color: #d9edf7;
            border: 1px solid #bce8f1;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .button-container {
            display: flex;

            justify-content: space-between;
            margin-left: 45%;
        }

        .error {
            color: #d9534f;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Media Queries para mejorar la responsividad */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
                /* Menor padding en pantallas medianas */
            }

            button {
                padding: 12px;
                /* Menor padding en pantallas medianas */
                font-size: 14px;
                /* Tamaño de fuente más pequeño */
            }

            .button-container {
                flex-direction: column;
                /* Apila los botones verticalmente en pantallas pequeñas */
                margin-left: 0;
                /* Elimina el margen izquierdo */
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 10px;
                /* Menor padding en pantallas muy pequeñas */
            }

            button {
                padding: 10px;
                /* Menor padding en pantallas muy pequeñas */
                font-size: 12px;
                /* Tamaño de fuente más pequeño */
            }

            .project-details {
                padding: 10px;
                /* Menor padding en pantallas muy pequeñas */
            }

            .button-container {
                flex-direction: column;
                /* Apila los botones verticalmente en pantallas muy pequeñas */
                margin-left: 0;
                /* Elimina el margen izquierdo */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        // Conectar a la base de datos
        $host = 'localhost';
        $dbname = 'Responsabilidad_Social';
        $username = 'RSUFIEI';
        $password = 'Bicicleta123*';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Obtener el ID_Proyecto desde la URL
            $idProyecto = isset($_GET['ID_Proyecto']) ? $_GET['ID_Proyecto'] : null;

            if ($idProyecto) {
                // Preparar y ejecutar la consulta
                $stmt = $pdo->prepare("SELECT ID_Proyecto, Titulo FROM proyectos WHERE ID_Proyecto = :ID_Proyecto");
                $stmt->bindParam(':ID_Proyecto', $idProyecto, PDO::PARAM_INT);
                $stmt->execute();

                // Obtener el resultado
                $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                echo "ID del proyecto no proporcionado.";
                exit;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
        ?>

        <?php
        if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
            <div class="error">
                ⚠️ El correo electrónico o el número de identificación ya están registrados para este proyecto. Por favor, utilice otro correo electrónico o número de identificación.
            </div>
        <?php endif; ?>

        <h1>Registro de Voluntarios</h1>

        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
            <div class="alert">
                🎉 ¡Muchas gracias por registrarte y querer participar en nuestro proyecto! 🌟 Esperamos que sea de tu agrado y te esperamos para más proyectos. 🚀
            </div>
        <?php endif; ?>

        <?php if ($proyecto): ?>
            <div class="project-details">
                <strong>Código del Proyecto:</strong> <?php echo htmlspecialchars($proyecto['ID_Proyecto']); ?><br>
                <strong>Nombre del Proyecto:</strong> <?php echo htmlspecialchars($proyecto['Titulo']); ?>
            </div>
        <?php endif; ?>

        <form id="registroForm" action="/PHP/procesar_registro.php" method="POST" onsubmit="return validarFormulario()">
            <input type="hidden" id="ID_Proyecto" name="ID_Proyecto" value="<?php echo htmlspecialchars($proyecto['ID_Proyecto']); ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ ]+">

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ ]+">

            <label for="correo">Correo institucional:</label>
            <input type="email" id="correo" name="correo" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" pattern="\d{9}" title="Debe contener exactamente 9 dígitos" maxlength="9" required>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" min="16" max="120" required>

            <label for="genero">Género:</label>
            <select id="genero" name="genero">
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>

            <label for="identificacion">Identificación(DNI):</label>
            <input type="text" id="identificacion" name="identificacion" required pattern="[0-9A-Za-z]+">

            <label for="institucion">Institución O Universidad:</label>
            <input type="text" id="institucion" name="institucion" pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ ]*">

            <label for="disponibilidad">Disponibilidad:</label>
            <textarea id="disponibilidad" name="disponibilidad" pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ0-9., ]*"></textarea>

            <button type="submit">Registrar</button>

        </form>

        <div class="button-container">
            <a href="publicaciones_public.php">
                <button type="button_salir">Salir</button>
            </a>
        </div>
    </div>

    <script>
        function validarFormulario() {
            const correo = document.getElementById('correo').value;
            const correoRegex = /^[a-zA-Z0-9._%+-]+@unfv\.edu\.pe$/;

            if (!correoRegex.test(correo)) {
                alert('Por favor, ingrese un correo válido con el formato example@unfv.edu.pe');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>