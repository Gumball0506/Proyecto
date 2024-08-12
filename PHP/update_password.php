<?php
require 'admin.php'; // Asegúrate de que la conexión a la base de datos esté configurada
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];

    // Verificar el token y obtener el email asociado
    $token_file = "tokens/$token.txt";
    if (file_exists($token_file)) {
        $email = file_get_contents($token_file);

        // Actualizar la contraseña en la base de datos
        $stmt = $conn->prepare("UPDATE administrador SET Contraseña = ? WHERE Correo = ?");
        $stmt->bind_param('ss', $new_password, $email);

        if ($stmt->execute()) {
            echo 'Tu contraseña ha sido actualizada';

            // Eliminar el token después de usarlo
            unlink($token_file);
        } else {
            echo 'Error al actualizar la contraseña: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo 'Token inválido o expirado';
    }
}

$conn->close();
