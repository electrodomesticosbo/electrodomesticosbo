<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Establecer codificación UTF-8 para evitar problemas con caracteres especiales
$conn->set_charset("utf8");

// Verificar si se recibieron los datos requeridos
if (isset($_POST['id_cliente']) && isset($_POST['monto_cobrado']) && isset($_POST['nuevo_monto_restante'])) {
    // Validar y sanitizar datos
    $id_cliente = filter_var($_POST['id_cliente'], FILTER_VALIDATE_INT);
    $monto_cobrado = filter_var($_POST['monto_cobrado'], FILTER_VALIDATE_FLOAT);
    $nuevo_monto_restante = filter_var($_POST['nuevo_monto_restante'], FILTER_VALIDATE_FLOAT);

    // Verificar que los datos sean válidos
    if ($id_cliente === false || $monto_cobrado === false || $nuevo_monto_restante === false) {
        echo json_encode(['error' => 'Datos inválidos. Verifique los valores ingresados.']);
        exit();
    }

    // Evitar que el monto cobrado sea negativo o mayor al monto restante
    if ($monto_cobrado <= 0 || $nuevo_monto_restante < 0) {
        echo json_encode(['error' => 'El monto cobrado no puede ser negativo o exceder el monto restante.']);
        exit();
    }

    // Iniciar una transacción para mayor seguridad
    $conn->begin_transaction();

    try {
        // Actualizar el monto restante en la tabla clientes
        $sql_update = "UPDATE clientes SET monto_restante = ?, estado_pago = ? WHERE id = ?";
        $estado_pago = ($nuevo_monto_restante == 0) ? 'CANCELADO' : 'PENDIENTE';
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('dsi', $nuevo_monto_restante, $estado_pago, $id_cliente);

        if (!$stmt_update->execute()) {
            throw new Exception("Error al actualizar el cliente: " . $stmt_update->error);
        }
        $stmt_update->close();

        // Insertar el pago en la tabla pagos
        $sql_insert = "INSERT INTO pagos (id_cliente, monto_pagado, fecha_pago) VALUES (?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param('id', $id_cliente, $monto_cobrado);

        if (!$stmt_insert->execute()) {
            throw new Exception("Error al registrar el pago: " . $stmt_insert->error);
        }
        $stmt_insert->close();

        // Confirmar la transacción
        $conn->commit();

        // Enviar respuesta en formato JSON
        echo json_encode(['success' => 'Pago registrado exitosamente.']);
    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conn->rollback();
        echo json_encode(['error' => "Error al registrar el pago: " . $e->getMessage()]);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'Datos insuficientes para procesar el pago.']);
}
?>
