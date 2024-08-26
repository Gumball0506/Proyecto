<?php
// Conectar a la base de datos
$host = 'localhost';
$dbname = 'Responsabilidad_Social';
$username = 'RSUFIEI';
$password = 'Bicicleta123*';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del formulario
    $idProyecto = $_POST['ID_Proyecto'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $identificacion = $_POST['identificacion'];
    $institucion = $_POST['institucion'];
    $disponibilidad = $_POST['disponibilidad'];


    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM registro_de_proyectos_voluntarios 
        WHERE (correo = :correo OR identificacion = :identificacion) AND ID_Proyecto = :ID_Proyecto
    ");
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':identificacion', $identificacion);
    $stmt->bindParam(':ID_Proyecto', $idProyecto, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {

        header('Location: /HTML/registro_alumnos.php?ID_Proyecto=' . $idProyecto . '&error=1');
        exit;
    }


    $stmt = $pdo->prepare("
        INSERT INTO registro_de_proyectos_voluntarios 
        (ID_Proyecto, Nombre, Apellido, Correo, Telefono, Edad, Genero, Identificacion,
        Institucion, Disponibilidad)
        VALUES (:ID_Proyecto, :nombre, :apellido, :correo, :telefono, :edad, :genero, :identificacion, :institucion, :disponibilidad)
    ");
    $stmt->bindParam(':ID_Proyecto', $idProyecto, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->bindParam(':edad', $edad, PDO::PARAM_INT);
    $stmt->bindParam(':genero', $genero);
    $stmt->bindParam(':identificacion', $identificacion);
    $stmt->bindParam(':institucion', $institucion);
    $stmt->bindParam(':disponibilidad', $disponibilidad);
    $stmt->execute();


    header('Location: /html/registro_alumnos.php?ID_Proyecto=' . $idProyecto . '&success=1');
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
