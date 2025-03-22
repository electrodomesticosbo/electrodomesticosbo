<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Verificar si la sesión está iniciada
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir si no hay sesión activa
    exit();
}

// Obtener el ID del usuario autenticado
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener SOLO los clientes asociados al usuario autenticado
$sql = "SELECT id, nombre_completo FROM clientes WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Clientes</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ruta a los nuevos estilos -->
</head>

<body class="estado-body">

<div class="estado-container">
    <h1 class="estado-titulo">Estado de Clientes</h1>

    <?php if ($result->num_rows > 0): ?>
        <table class="estado-tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre Completo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $numero = 1; // Para enumerar los clientes
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $numero++; ?></td>
                        <td>
                            <a href="ver_estado_cliente.php?id_cliente=<?php echo $row['id']; ?>" 
                               class="estado-enlace">
                               <?php echo htmlspecialchars($row['nombre_completo']); ?>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="estado-vacio">No hay clientes disponibles.</p>
    <?php endif; ?>

    <a href="menu.php" class="estado-btn-volver">Volver al menú principal</a>
</div>

</body>
</html>
