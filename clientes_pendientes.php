<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Verificar si la sesión está iniciada
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir si no hay sesión activa
    exit();
}

// Obtener los clientes con estado de pago "PENDIENTE"
$sql = "SELECT id, nombre_completo, articulo, monto_restante FROM clientes WHERE estado_pago = 'PENDIENTE'";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta SQL: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes Pendientes</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Estilos unificados -->
</head>
<body class="clientes-pendientes-body">

<div class="clientes-pendientes-container">
    <h1 class="clientes-pendientes-titulo">Clientes Pendientes</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="clientes-pendientes-tarjetas">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="cliente-card">
                    <p class="cliente-nombre">
                        <a href="ver_estado_cliente.php?id_cliente=<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nombre_completo']); ?>
                        </a>
                    </p>
                    <p class="cliente-articulo"><strong>Artículo:</strong> <?php echo $row['articulo']; ?></p>
                    <p class="cliente-monto"><strong>Monto Restante:</strong> <?php echo number_format($row['monto_restante'], 2); ?> Bs.</p>
                    <p class="cliente-estado">⏳ <strong>Estado:</strong> PENDIENTE</p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="clientes-pendientes-vacio">No hay clientes pendientes de pago.</p>
    <?php endif; ?>

    <a href="menu.php" class="clientes-pendientes-btn-volver">Volver al Menú Principal</a>
</div>

</body>
</html>
