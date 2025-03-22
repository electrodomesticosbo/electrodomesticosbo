<?php
session_start(); // Verifica que la sesión esté activa

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Verificar si se ha proporcionado un ID de cliente
if (isset($_GET['id_cliente']) && !empty($_GET['id_cliente'])) {
    $id_cliente = intval($_GET['id_cliente']);

    // Obtener los datos del cliente desde la base de datos
    $sql = "SELECT nombre_completo, articulo, valor, monto_restante, estado_pago, fecha_compra FROM clientes WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $cliente = $result->fetch_assoc();
            $fecha_limite_pago = date("d/m/Y", strtotime($cliente['fecha_compra'] . ' + 28 days'));

            // Obtener pagos realizados
            $sql_pagos = "SELECT fecha_pago, monto_pagado FROM pagos WHERE id_cliente = ?";
            $stmt_pagos = $conn->prepare($sql_pagos);
            $stmt_pagos->bind_param("i", $id_cliente);
            $stmt_pagos->execute();
            $result_pagos = $stmt_pagos->get_result();

            $fechas_pagadas = [];
            $total_pagado = 0;
            while ($pago = $result_pagos->fetch_assoc()) {
                $fechas_pagadas[] = date("d/m", strtotime($pago['fecha_pago']));
                $total_pagado += floatval($pago['monto_pagado']);
            }
            $stmt_pagos->close();
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
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Cliente</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="estado-cliente-body">
<div class="estado-cliente-container">
    <h1 class="estado-cliente-titulo">Estado del Cliente</h1>

    <div class="estado-cliente-info">
        <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($cliente['nombre_completo']); ?></p>
        <p><strong>Artículo:</strong> <?php echo htmlspecialchars($cliente['articulo']); ?></p>
        <p><strong>Valor Original:</strong> <?php echo number_format($cliente['valor'], 2); ?> Bs.</p>
        <p><strong>Monto Restante:</strong> <span id="monto_restante"><?php echo number_format($cliente['monto_restante'], 2); ?></span> Bs.</p>
        <p><strong>Total de Pagos Realizados:</strong> <?php echo number_format($total_pagado, 2); ?> Bs.</p>
        <p><strong>Estado de Pago:</strong> <?php echo htmlspecialchars($cliente['estado_pago']); ?></p>
        <p><strong>Fecha de Compra:</strong> <?php echo date("d/m/Y", strtotime($cliente['fecha_compra'])); ?></p>
        <p><strong>Fecha Límite de Pago:</strong> <?php echo $fecha_limite_pago; ?></p>
    </div>


    <!-- Contenedor de Círculos de Pagos -->
    <div class="circulos-container">
        <?php 
        for ($i = 0; $i < 28; $i++) {
            $fecha = date("d/m", strtotime($cliente['fecha_compra'] . " + $i days"));
            $class = in_array($fecha, $fechas_pagadas) ? 'pago-realizado' : '';
        ?>
            <div id="circulo-<?php echo $i + 1; ?>" class="circulo <?php echo $class; ?>" onclick="mostrarModal(<?php echo $i + 1; ?>)">
                <?php echo $fecha; ?>
            </div>
        <?php } ?>
    </div>

    <a href="estado_cliente.php" class="estado-btn-volver">Volver a la lista de clientes</a>
</div>

<!-- Modal Personalizado -->
<div class="modal-container" id="modalPago">
    <div class="modal-content">
        <div class="modal-title">Ingrese el monto a cobrar para el pago número <span id="numeroPago"></span></div>
        <input type="text" class="modal-input" id="montoCobrar" placeholder="Ingrese el monto">
        <div class="modal-buttons">
            <button class="modal-btn" onclick="confirmarPago()">Aceptar</button>
            <button class="modal-btn cancelar" onclick="cerrarModal()">Cancelar</button>
        </div>
    </div>
</div>

<script>
    function mostrarModal(numeroPago) {
        document.getElementById('modalPago').style.display = 'flex';
        document.getElementById('numeroPago').innerText = numeroPago;
        document.getElementById('montoCobrar').value = '';
    }

    function cerrarModal() {
        document.getElementById('modalPago').style.display = 'none';
    }

    function confirmarPago() {
        const numeroPago = document.getElementById('numeroPago').innerText;
        const montoRestanteElem = document.getElementById('monto_restante');
        const montoRestante = parseFloat(montoRestanteElem.innerText.replace(',', ''));
        const montoCobrar = parseFloat(document.getElementById('montoCobrar').value);

        if (montoCobrar && !isNaN(montoCobrar)) {
            const nuevoMontoRestante = montoRestante - montoCobrar;

            if (nuevoMontoRestante < 0) {
                alert("El monto a cobrar excede el monto restante.");
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "actualizar_pago.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    montoRestanteElem.innerText = nuevoMontoRestante.toFixed(2);
                    alert("Pago registrado exitosamente.");
                    document.getElementById('circulo-' + numeroPago).classList.add('pago-realizado');
                    cerrarModal();
                }
            };
            xhr.send("id_cliente=<?php echo $id_cliente; ?>&monto_cobrado=" + montoCobrar + "&nuevo_monto_restante=" + nuevoMontoRestante.toFixed(2));
        } else {
            alert("Monto inválido.");
        }
    }
</script>

</body>
</html>
