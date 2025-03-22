<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Procesar el formulario de gastos internos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_gasto = htmlspecialchars(trim($_POST['nombre_gasto']));
    $monto_gasto = floatval($_POST['monto_gasto']);
    $descripcion = htmlspecialchars(trim($_POST['descripcion']));
    $fecha = date('Y-m-d H:i:s');

    // Validar que los campos no estén vacíos
    if (!empty($nombre_gasto) && $monto_gasto > 0 && !empty($descripcion)) {
        // Insertar el gasto en la base de datos
        $sql = "INSERT INTO gastos (nombre_gasto, costo, descripcion, fecha) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sdss", $nombre_gasto, $monto_gasto, $descripcion, $fecha);
            if ($stmt->execute()) {
                $mensaje_exito = "✅ Gasto interno registrado con éxito.";
            } else {
                $mensaje_error = "❌ Error al registrar el gasto interno.";
            }
            $stmt->close();
        } else {
            $mensaje_error = "❌ Error en la consulta: " . $conn->error;
        }
    } else {
        $mensaje_error = "❌ Todos los campos son obligatorios y el monto debe ser mayor a 0.";
    }
}

// Obtener el historial de gastos internos
$sql_historial = "SELECT nombre_gasto, costo, descripcion, fecha FROM gastos ORDER BY fecha DESC";
$result = $conn->query($sql_historial);

// Verificar si la consulta se realizó correctamente
if (!$result) {
    $mensaje_error = "❌ Error al obtener el historial de gastos: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gasto Interno</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="gastos-internos-body">

<div class="gastos-internos-container">
    <h1 class="gastos-internos-titulo">Gasto Interno</h1>

    <!-- Mensajes -->
    <?php if (isset($mensaje_exito)): ?>
        <div class="mensaje-exito"><?php echo $mensaje_exito; ?></div>
    <?php elseif (isset($mensaje_error)): ?>
        <div class="mensaje-error"><?php echo $mensaje_error; ?></div>
    <?php endif; ?>

    <!-- Formulario para registrar gasto interno -->
    <h2 class="gastos-internos-subtitulo">Registrar Gasto Interno</h2>
    <form action="gastos_internos.php" method="POST" class="gastos-internos-form">
        <label for="nombre_gasto">Nombre del Gasto:</label>
        <input type="text" name="nombre_gasto" required>

        <label for="monto_gasto">Monto (en Bs.):</label>
        <input type="number" name="monto_gasto" step="0.01" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label for="fecha">Fecha:</label>
        <input type="text" name="fecha" value="<?php echo date('Y-m-d'); ?>" readonly>

        <button type="submit" class="gastos-internos-btn-guardar">Guardar Gasto Interno</button>
    </form>

    <hr>

    <!-- Historial de gastos internos -->
    <h3 class="gastos-internos-subtitulo">Historial de Gastos Internos</h3>
    <table class="gastos-internos-tabla">
        <thead>
            <tr>
                <th>Nombre del Gasto</th>
                <th>Monto (Bs.)</th>
                <th>Descripción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre_gasto']); ?></td>
                        <td><?php echo number_format($row['costo'], 2); ?> Bs.</td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="gastos-internos-vacio">No hay gastos internos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="menu.php" class="gastos-internos-btn-volver">Volver al Menú Principal</a>
</div>

</body>
</html>
