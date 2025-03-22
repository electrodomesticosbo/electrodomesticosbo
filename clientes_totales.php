<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Consulta para obtener todos los clientes junto con su artículo y estado de pago
$sql = "SELECT id, nombre_completo, articulo, estado_pago FROM clientes";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes Totales</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Estilos centralizados -->
</head>
<body class="clientes-totales-body">

    <div class="clientes-totales-container">
        <h1 class="clientes-totales-titulo">Lista de Clientes Totales</h1>

        <?php if ($result->num_rows > 0): ?>
            <div class="clientes-tarjetas">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="cliente-card">
                        <p class="cliente-nombre">
                            <a href="estado_cliente.php?id_cliente=<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['nombre_completo']); ?>
                            </a>
                        </p>
                        <p class="cliente-articulo"><strong>Artículo:</strong> <?php echo $row['articulo']; ?></p>
                        <p class="cliente-estado <?php echo strtolower($row['estado_pago']) === 'pendiente' ? 'estado-pendiente' : 'estado-pagado'; ?>">
                            <strong>Estado de Pago:</strong> <?php echo $row['estado_pago']; ?>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="clientes-vacio">No hay clientes disponibles.</p>
        <?php endif; ?>

        <a href="menu.php" class="clientes-btn-volver">Volver al menú principal</a>
    </div>

</body>
</html>
