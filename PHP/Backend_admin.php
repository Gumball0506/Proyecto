<?php
// Backend_admin.php

include 'conexion.php';

$accion = $_POST['accion'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

if ($accion === 'verificar_contrasena') {
    // Consulta para verificar la contraseña del administrador
    $stmt = $pdo->prepare("SELECT * FROM administradores WHERE contrasena = :contrasena");
    $stmt->execute(['contrasena' => md5($contrasena)]); // Asume que las contraseñas están almacenadas de forma segura
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        echo json_encode(['autenticado' => true]);
    } else {
        echo json_encode(['autenticado' => false]);
    }
}
