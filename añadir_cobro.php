<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Verificar si se recibió el id_cliente y monto
$id_cliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : null;
$monto = isset($_POST['monto']) ? $_POST['monto'] : null;

// Validación para asegurarse de que los parámetros estén presentes
if ($id_cliente && $monto) {
    // Obtener el monto restante actual del cliente
    $sql = "SELECT monto_restante, estado_pago FROM clientes WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $stmt->bind_result($monto_restante, $estado_pago);
        $stmt->fetch();
        $stmt->close();

        // Verificar si el monto cobrado es mayor que el monto restante
        if ($monto > $monto_restante) {
            echo "El monto a cobrar no puede ser mayor que el monto restante.";
            exit;
        }

        // Restar el monto cobrado del monto restante
        $nuevo_monto_restante = $monto_restante - $monto;

        // Verificar si el monto restante es 0 y actualizar el estado de pago
        if ($nuevo_monto_restante <= 0) {
            $nuevo_estado_pago = 'CANCELADO';
            $nuevo_monto_restante = 0; // Asegurarse de que el monto restante sea 0
        } else {
            $nuevo_estado_pago = 'PENDIENTE';
        }

        // Actualizar la base de datos con el nuevo monto restante y estado de pago
        $sql = "UPDATE clientes SET monto_restante = ?, estado_pago = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("dsi", $nuevo_monto_restante, $nuevo_estado_pago, $id_cliente);
            if ($stmt->execute()) {
                $stmt->close();
            } else {
                // Si ocurre un error en la actualización
                echo "Error al actualizar el monto restante y estado de pago: " . $stmt->error;
                exit;
            }
        } else {
            // Si ocurre un error en la preparación de la consulta
            echo "Error al preparar la consulta de actualización: " . $conn->error;
            exit;
        }

        // Registrar el cobro en la tabla de cobros
        $fecha_cobro = date('Y-m-d H:i:s');
        $sql = "INSERT INTO cobros (id_cliente, monto, fecha_cobro) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ids", $id_cliente, $monto, $fecha_cobro);
            if ($stmt->execute()) {
                echo "Cobro registrado exitosamente.";
            } else {
                // Si ocurre un error en la inserción del cobro
                echo "Error al registrar el cobro: " . $stmt->error;
                exit;
            }
            $stmt->close();
        } else {
            // Si ocurre un error en la preparación de la consulta
            echo "Error al preparar la consulta para registrar el cobro: " . $conn->error;
            exit;
        }

        // Redirigir de nuevo a la página del estado del cliente
        header("Location: estado_cliente.php?id_cliente=" . $id_cliente);
        exit();
    } else {
        // Si ocurre un error al obtener los datos del cliente
        echo "Error al obtener los datos del cliente: " . $conn->error;
        exit;
    }
} else {
    // Si no se reciben los parámetros necesarios
    echo "Faltan parámetros importantes.";
    exit;
}
?>
