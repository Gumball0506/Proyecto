<?php
session_start();
session_unset();
session_destroy();
header("Location: /html/inicio_de_sesion.php");
exit();
?>