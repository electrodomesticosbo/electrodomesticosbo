<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'usuario') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['usuario']; ?>!</h1>
    <p>Este es tu panel de usuario. Aquí puedes ver tus datos, historial, etc.</p>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>