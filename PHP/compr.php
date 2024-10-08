    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    /*
        ----------------------------------------------------
        Anti-Copyright
        ----------------------------------------------------
        Este trabajo es realizado por:
        - Harold Ortiz Abra Loza
        - William Vega
        - Sergio Vidal
        - Elizabeth Campos
        - Lily Roque
        ----------------------------------------------------
        © 2024 Responsabilidad Social Universitaria. 
        Todos los derechos reservados.
        ----------------------------------------------------
    */


    $host = 'localhost';
    $dbname = 'Responsabilidad_Social';
    $username = 'RSUFIEI';
    $password = 'Bicicleta123*';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Conexión exitosa";
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }

    // Iniciar sesión
    session_start();

    // Verificar que el formulario fue enviado con el método POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener datos del formulario
        $user = $_POST['username'];
        $pass = $_POST['password'];

        try {
            // Preparar la consulta SQL
            $sql = "SELECT * FROM administrador WHERE Nombre = :username AND Contraseña = :password";
            $stmt = $pdo->prepare($sql);

            // Enlazar parámetros
            $stmt->bindParam(':username', $user, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pass, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si el usuario existe
            if ($stmt->rowCount() > 0) {
                $_SESSION['username'] = $user; // Almacenar el nombre de usuario en la sesión
                echo "<script>
        window.location.href = '/home.php';
    </script>";
            } else {
                echo "<script>
        alert('Usuario o contraseña incorrectos');
        window.location.href = '/html/inicio_de_sesion.php';
    </script>";
            }
        } catch (PDOException $e) {
            // Manejar el error de la consulta
            error_log("Error en la consulta: " . $e->getMessage()); // Registra el error en el archivo de log del servidor
            die("Error en la consulta: " . $e->getMessage());
        }
    } else {
        die("Método de solicitud no válido. Por favor, use el método POST.");
    }

    // Cerrar la conexión (PDO se cierra automáticamente al finalizar el script)