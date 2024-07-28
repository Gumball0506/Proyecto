<?php
session_start();
session_unset();
session_destroy();
echo "Sesión cerrada.";
header("Location: /html/inicio_de_sesion.php");
exit();
?>
