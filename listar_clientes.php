<?php
// Incluir la conexión a la base de datos
include 'includes/conexion.php';

// Consulta SQL para obtener todos los clientes
$sql = "SELECT id, nombre_completo FROM clientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<div class="container">
    <h1>Lista de Clientes</h1>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Comprobar si hay resultados
        if ($result->num_rows > 0) {
            // Iterar sobre cada fila de resultados y mostrar en la tabla
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                // Enlace en el nombre del cliente
                echo "<td><a href='estado_cliente.php?id=" . $row['id'] . "'>" . $row['nombre_completo'] . "</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No hay clientes disponibles</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
