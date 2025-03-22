<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
</head>
<body>
    <h1>Bienvenido al Panel de Administración, <?php echo $_SESSION['usuario']; ?>!</h1>
    <p>Aquí puedes gestionar usuarios y realizar otras tareas de administrador.</p>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>