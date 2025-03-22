<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Obtener el id del cliente desde la URL
$id_cliente = $_GET['id'];


// Consulta SQL para obtener la información del cliente
$sql = "SELECT nombre_completo, articulo, valor, monto_restante, estado_pago FROM clientes WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $stmt->bind_result($nombre_completo, $articulo, $valor, $monto_restante, $estado_pago);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error en la consulta: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Cliente</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <h1>Estado del Cliente</h1>
    <p><strong>Nombre:</strong> <?php echo $nombre_completo; ?></p>
    <p><strong>Artículo:</strong> <?php echo $articulo; ?></p>
    <p><strong>Valor Original:</strong> <?php echo $valor; ?> Bs.</p>
    <p><strong>Monto Restante a Cobrar:</strong> <?php echo $monto_restante; ?> Bs.</p>
    <p><strong>Estado de Pago:</strong> <?php echo $estado_pago; ?></p>
    
    <h3>Añadir Cobro</h3>
    <!-- Formulario para añadir cobro -->
    <form action="añadir_cobro.php" method="POST">
        <input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>"> <!-- Pasamos el id del cliente al formulario -->
        
        <label for="monto">Monto a cobrar:</label>
        <input type="number" step="0.01" name="monto" required>
        
        <button type="submit">Añadir cobro</button>
    </form>
</div>

</body>
</html>




