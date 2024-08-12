<?php
function cifrar($datos)
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
{
    $password = 'ABCD-1234.aer'; // Contraseña de cifrado
    $metodo = 'AES-256-CBC'; // Método de cifrado
    $ivSize = openssl_cipher_iv_length($metodo); // Tamaño del vector de inicialización
    $iv = openssl_random_pseudo_bytes($ivSize); // Generar IV aleatorio
    $datosCifrados = openssl_encrypt($datos, $metodo, $password, OPENSSL_RAW_DATA, $iv); // Cifrar los datos
    return base64_encode($iv . $datosCifrados); // Retornar IV + datos cifrados en base64
}

function descifrar($datos)
{
    $password = 'ABCD-1234.aer'; // Contraseña de cifrado
    $metodo = 'AES-256-CBC'; // Método de cifrado
    $datos = base64_decode($datos); // Decodificar base64
    $ivSize = openssl_cipher_iv_length($metodo); // Tamaño del vector de inicialización
    $iv = substr($datos, 0, $ivSize); // Obtener IV
    $datosCifrados = substr($datos, $ivSize); // Obtener datos cifrados
    return openssl_decrypt($datosCifrados, $metodo, $password, OPENSSL_RAW_DATA, $iv); // Desencriptar
}

// Ejemplo de uso
$correoOriginal = "correo@example.com";
$correoCifrado = cifrar($correoOriginal);
echo "Correo cifrado: " . $correoCifrado . PHP_EOL;

$correoDesencriptado = descifrar($correoCifrado);
echo "Correo desencriptado: " . htmlspecialchars($correoDesencriptado) . PHP_EOL;
