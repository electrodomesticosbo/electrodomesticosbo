<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Verificar si se ha proporcionado un ID de cliente
if (isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']); // Convertir a entero para evitar problemas de seguridad

    // Obtener los datos del cliente desde la base de datos
    $sql = "SELECT nombre_completo, articulo, valor, monto_restante, estado_pago, fecha_compra FROM clientes WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_cliente); // 'i' para un entero
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Asignar los datos del cliente a la variable $cliente
            $cliente = $result->fetch_assoc();

            // Calcular la fecha límite de pago (por ejemplo, 30 días después de la compra)
            $fecha_limite_pago = date("d/m/Y", strtotime($cliente['fecha_compra'] . ' + 30 days'));
        } else {
            echo "No se encontró al cliente con el ID proporcionado.";
            exit;
        }

        $stmt->close();
    } else {
        echo "Error en la consulta SQL: " . $conn->error;
        exit;
    }
} else {
    echo "ID de cliente no proporcionado en la URL.";
    exit;
}

// Procesar el pago si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $monto_cobrado = floatval($_POST['monto_cobrado']);

    // Verificar que los datos sean válidos
    if ($monto_cobrado > 0) {
        // Obtener el monto restante actual del cliente
        $monto_restante = $cliente['monto_restante'];

        // Calcular el nuevo monto restante
        $nuevo_monto_restante = $monto_restante - $monto_cobrado;
        if ($nuevo_monto_restante < 0) {
            echo "El monto cobrado no puede exceder el monto restante.";
            exit;
        }

        // Iniciar una transacción para mantener consistencia
        $conn->begin_transaction();

        try {
            // Actualizar el monto restante y el estado de pago en la tabla clientes
            $estado_pago = ($nuevo_monto_restante == 0) ? 'CANCELADO' : 'PENDIENTE';
            $sql_actualizar = "UPDATE clientes SET monto_restante = ?, estado_pago = ? WHERE id = ?";
            $stmt_actualizar = $conn->prepare($sql_actualizar);
            $stmt_actualizar->bind_param("dsi", $nuevo_monto_restante, $estado_pago, $id_cliente);
            $stmt_actualizar->execute();
            $stmt_actualizar->close();

            // Insertar el registro del pago en la tabla pagos
            $sql_insertar = "INSERT INTO pagos (id_cliente, monto_pagado, fecha_pago) VALUES (?, ?, NOW())";
            $stmt_insertar = $conn->prepare($sql_insertar);
            $stmt_insertar->bind_param("id", $id_cliente, $monto_cobrado);
            $stmt_insertar->execute();
            $stmt_insertar->close();

            // Confirmar la transacción
            $conn->commit();

            // Actualizar la información del cliente después del pago
            $cliente['monto_restante'] = $nuevo_monto_restante;
            $cliente['estado_pago'] = $estado_pago;

            echo "<script>alert('Pago realizado exitosamente');</script>";
        } catch (Exception $e) {
            // Deshacer la transacción en caso de error
            $conn->rollback();
            echo "Error al realizar el pago: " . $e->getMessage();
        }
    } else {
        echo "Monto inválido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Cliente</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .circulos-container { display: grid; grid-template-columns: repeat(5, 50px); gap: 10px; }
        .circulo { width: 50px; height: 50px; border-radius: 50%; background-color: #FF7F32; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; cursor: pointer; }
        .circulo:hover { background-color: #cc6600; }
        .cancelado { color: green; }
    </style>
</head>

<body>
<div class="container">
    <h1>Estado de Cliente</h1>
    <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($cliente['nombre_completo']); ?></p>
    <p><strong>Artículo:</strong> <?php echo htmlspecialchars($cliente['articulo']); ?></p>
    <p><strong>Valor Original:</strong> <?php echo number_format($cliente['valor'], 2); ?> Bs.</p>
    <p><strong>Monto Restante:</strong> <span id="monto_restante" class="<?php echo $cliente['monto_restante'] == 0 ? 'cancelado' : ''; ?>">
        <?php echo number_format($cliente['monto_restante'], 2); ?></span> Bs.</p>
    <p><strong>Estado de Pago:</strong> <span class="<?php echo $cliente['estado_pago'] == 'CANCELADO' ? 'cancelado' : ''; ?>">
        <?php echo htmlspecialchars($cliente['estado_pago']); ?></span></p>
    <p><strong>Fecha de Compra:</strong> <?php echo date("d/m/Y", strtotime($cliente['fecha_compra'])); ?></p>
    <p><strong>Fecha Límite de Pago:</strong> <?php echo $fecha_limite_pago; ?></p>

    <!-- Formulario para realizar pagos -->
    <form method="POST">
        <label for="monto_cobrado">Monto a pagar (Bs.):</label>
        <input type="number" name="monto_cobrado" step="0.01" min="0" max="<?php echo $cliente['monto_restante']; ?>" required>
        <button type="submit">Realizar Pago</button>
    </form>

    <a href="estado_cliente.php">Volver a la lista de clientes</a>
</div>
</body>
</html>
