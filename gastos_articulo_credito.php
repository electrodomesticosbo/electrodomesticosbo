<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Obtener todos los registros de artículos a crédito
$sql_historial_credito = "SELECT nombre_cliente, articulo, valor, monto_restante, fecha_compra 
                          FROM gastos_articulo_credito 
                          ORDER BY fecha_compra DESC";
$result = $conn->query($sql_historial_credito);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Artículos a Crédito</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="credito-body">

<div class="credito-container">
    <h1 class="credito-titulo">Historial de Artículos a Crédito</h1>

    <table class="credito-tabla">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Artículo</th>
                <th>Valor (Bs.)</th>
                <th>Monto Restante (Bs.)</th>
                <th>Fecha de Compra</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($row['articulo']); ?></td>
                    <td><?php echo number_format($row['valor'], 2); ?> Bs.</td>
                    <td><?php echo number_format($row['monto_restante'], 2); ?> Bs.</td>
                    <td><?php echo date("d/m/Y", strtotime($row['fecha_compra'])); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan='5' class="credito-vacio">No hay artículos a crédito registrados.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="menu.php" class="credito-btn-volver">Volver al Menú Principal</a>
</div>

</body>
</html>
