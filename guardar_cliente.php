<?php
session_start(); // Iniciar sesión para obtener el ID del usuario autenticado

// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Verificar que el usuario esté autenticado y que el ID esté en la sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir al login si no hay sesión activa
    exit();
}

// Obtener el ID del usuario autenticado
$id_usuario = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : NULL;

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar que todos los campos requeridos estén presentes
    if (
        isset($_POST['nombre_completo'], $_POST['celular'], $_POST['articulo'], 
        $_POST['valor'], $_POST['fecha_compra'])
    ) {
        $nombre_completo = trim($_POST['nombre_completo']);
        $celular = trim($_POST['celular']);
        $articulo = trim($_POST['articulo']);
        $valor = floatval($_POST['valor']);
        $fecha_compra = $_POST['fecha_compra'];

        // Calcular el monto restante a cobrar (20% adicional)
        $monto_restante = $valor + ($valor * 0.20);

        // Insertar los datos en la tabla CLIENTES
        $sql_cliente = "INSERT INTO clientes (nombre_completo, celular, articulo, valor, monto_restante, fecha_compra, estado_pago, id_usuario)
                        VALUES (?, ?, ?, ?, ?, ?, 'PENDIENTE', ?)";

        if ($stmt_cliente = $conn->prepare($sql_cliente)) {
            $stmt_cliente->bind_param("sssddsi", 
                $nombre_completo, 
                $celular, 
                $articulo, 
                $valor, 
                $monto_restante, 
                $fecha_compra,
                $id_usuario
            );

            if ($stmt_cliente->execute()) {
                $id_cliente = $conn->insert_id; // Obtener el ID del cliente recién registrado

                // Insertar datos en la tabla `gastos_articulo_credito`
                $sql_historial = "INSERT INTO gastos_articulo_credito 
                                (id_cliente, nombre_cliente, articulo, valor, monto_restante, fecha_compra) 
                                VALUES (?, ?, ?, ?, ?, ?)";

                if ($stmt_historial = $conn->prepare($sql_historial)) {
                    $stmt_historial->bind_param("issdds", 
                        $id_cliente,
                        $nombre_completo,
                        $articulo,
                        $valor,
                        $monto_restante,
                        $fecha_compra
                    );

                    if ($stmt_historial->execute()) {
                        echo '
                        <div class="registro-exito">
                            ✅ Cliente registrado exitosamente y agregado al historial de crédito.
                            <br>
                            <a href="estado_cliente.php?id_cliente=' . $id_cliente . '" class="registro-btn-volver">
                                ➡️ Ver Estado del Cliente
                            </a>
                        </div>';
                    } else {
                        echo '<div class="registro-error">❌ Error al registrar el historial de artículo a crédito: ' . $stmt_historial->error . '</div>';
                    }

                    $stmt_historial->close();
                } else {
                    echo '<div class="registro-error">❌ Error en la preparación del historial: ' . $conn->error . '</div>';
                }
            } else {
                echo '<div class="registro-error">❌ Error al registrar cliente: ' . $stmt_cliente->error . '</div>';
            }

            $stmt_cliente->close();
        } else {
            echo '<div class="registro-error">❌ Error en la preparación del cliente: ' . $conn->error . '</div>';
        }
    } else {
        echo '<div class="registro-error">❌ Error: Faltan campos requeridos en el formulario.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlace al archivo de estilos -->
</head>
<body>
</body>
</html>
