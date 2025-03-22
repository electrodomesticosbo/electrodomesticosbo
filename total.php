<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Consulta para obtener la suma total de los pagos ya realizados
$sql_total_pagos = "SELECT SUM(monto_pagado) AS total_pagos FROM pagos";
$result_pagos = $conn->query($sql_total_pagos);
$total_pagos = $result_pagos && $result_pagos->num_rows > 0 
    ? $result_pagos->fetch_assoc()['total_pagos'] 
    : 0;

// Consulta para obtener la suma total de los gastos de artículos a crédito
$sql_total_credito = "SELECT SUM(valor) AS total_credito FROM gastos_articulo_credito";
$result_credito = $conn->query($sql_total_credito);
$total_credito = $result_credito && $result_credito->num_rows > 0 
    ? $result_credito->fetch_assoc()['total_credito'] 
    : 0;

// Consulta para obtener la suma total de los gastos internos
$sql_total_gastos_internos = "SELECT SUM(monto_gasto) AS total_internos FROM gastos_internos"; 
$result_internos = $conn->query($sql_total_gastos_internos);
$total_internos = $result_internos && $result_internos->num_rows > 0 
    ? $result_internos->fetch_assoc()['total_internos'] 
    : 0;

// Calcular el total de los gastos del lado izquierdo
$total_gastos = $total_credito + $total_internos;

// Calcular la resta (total de pagos menos total de gastos)
$total_final = $total_pagos - $total_gastos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen Financiero</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="total-body">

<div class="total-container">
    <!-- Lado izquierdo: Gastos -->
    <div class="total-box">
        <h2>Gastos Totales</h2>
        <p>Suma de Gastos de Artículos a Crédito: <span class="total-result"><?php echo number_format($total_credito, 2); ?> Bs.</span></p>
        <p>Suma de Gastos Internos: <span class="total-result"><?php echo number_format($total_internos, 2); ?> Bs.</span></p>
        <hr>
        <p>Total Gastos: <span class="total-result"><?php echo number_format($total_gastos, 2); ?> Bs.</span></p>
    </div>

    <!-- Lado derecho: Pagos -->
    <div class="total-box">
        <h2>Total de Pagos Realizados</h2>
        <p>Suma de Pagos Realizados: <span class="total-result"><?php echo number_format($total_pagos, 2); ?> Bs.</span></p>
        <hr>
        <p>Resta Total (Pagos - Gastos): <span class="total-result"><?php echo number_format($total_final, 2); ?> Bs.</span></p>
    </div>
</div>

<!-- Enlace al menú principal en la parte inferior -->
<div class="total-footer">
    <a href="menu.php" class="total-btn-volver">Volver al Menú Principal</a>
</div>

</body>
</html>
