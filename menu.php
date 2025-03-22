<?php  
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener el nombre del usuario y su rol desde la sesión
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Invitado';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Usuario';


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ruta a tu archivo de estilos -->
</head>

<body class="menu-body">

    <div class="menu-container">
        <h1 class="menu-titulo">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</h1>
        <h2 class="menu-subtitulo">Menú Principal</h2>



        <ul class="menu-lista">
            <li><a href="crear_cliente.php" class="menu-enlace">Crear Cliente</a></li>
            <li><a href="estado_cliente.php" class="menu-enlace">Estado Cliente</a></li>
            <li><a href="clientes_totales.php" class="menu-enlace">Clientes Totales</a></li>
            <li><a href="clientes_pendientes.php" class="menu-enlace">Clientes Pendientes</a></li>
            <li><a href="gastos_internos.php" class="menu-enlace">Gasto Interno</a></li>
            <li><a href="gastos_articulo_credito.php" class="menu-enlace">Gasto de Artículo a Crédito</a></li>
            <li><a href="total.php" class="menu-enlace">Total</a></li>
        </ul>

        <a href="logout.php" class="menu-logout-btn">Cerrar sesión</a>
    </div>

</body>
</html>
