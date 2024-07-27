<?php
$archivo = "archivo.txt";
$periodo_tiempo = 3600; // Tiempo en segundos (por ejemplo, 3600 segundos = 1 hora)

// Verificar si se ha establecido una cookie de visita
if (!isset($_COOKIE['visitado'])) {
    // Incrementar el contador solo si no se ha establecido la cookie
    if (file_exists($archivo)) {
        $contador = intval(trim(file_get_contents($archivo)));
        $contador++;
        file_put_contents($archivo, $contador . PHP_EOL);
    } else {
        // Si el archivo no existe, crearlo e inicializar el contador a 0
        file_put_contents($archivo, "0\n");
        $contador = 0;
    }

    // Establecer la cookie de visita con un tiempo de expiración
    setcookie('visitado', 'true', time() + $periodo_tiempo, '/');
} else {
    // Si la cookie de visita ya está establecida, no incrementar el contador
    $contador = intval(trim(file_get_contents($archivo)));
}

// Mostrar el contador actualizado
echo "<p>Visitas $contador veces.</p>";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitas prueba</title>
</head>

<body>
    <h1>Willy de mrd</h1>
    <p>TIRAME TU GAAAAAAAAA.</p>
    <p>La fecha y hora:
        <?php
        date_default_timezone_set('America/Mexico_City'); // Ajusta esto a tu zona horaria
        echo date('Y-m-d H:i:s');
        ?>
    </p>
</body>

</html>