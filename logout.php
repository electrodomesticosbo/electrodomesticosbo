<?php
// Iniciar la sesión
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al inicio de sesión
header("Location: login.php"); // Redirige a login.php
exit(); // Asegura que no se ejecute ningún código posterior
?>

