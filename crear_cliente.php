<?php
session_start(); // Asegúrate de que la sesión esté iniciada correctamente

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir al login si no hay sesión activa
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Ruta a tu archivo de estilos -->
</head>
<body class="crear-cliente-body">

<div class="crear-cliente-container">
    <h1 class="crear-cliente-titulo">Crear Cliente</h1>
    <form action="guardar_cliente.php" method="post" class="crear-cliente-form">

        <label for="nombre_completo" class="crear-cliente-label">Nombre Completo:</label>
        <input type="text" id="nombre_completo" name="nombre_completo" class="crear-cliente-input" required>

        <label for="celular" class="crear-cliente-label">Celular:</label>
        <input type="tel" id="celular" name="celular" class="crear-cliente-input" placeholder="Ej: 70123456" required>

        <label for="mercaderia" class="crear-cliente-label">Mercadería:</label>
        <select id="mercaderia" name="mercaderia" class="crear-cliente-input" required>
            <option value="Crédito">Crédito</option>
        </select>

        <label for="articulo" class="crear-cliente-label">Artículo:</label>
        <select id="articulo" name="articulo" class="crear-cliente-input" onchange="cambiarPrecios()" required>
            <option value="Plancha">Plancha</option>
            <option value="Licuadora">Licuadora</option>
            <option value="Lavadora">Lavadora</option>
            <option value="TV">TV</option>
            <option value="Otro">Otro</option>
        </select>

        <label for="valor" class="crear-cliente-label">Valor:</label>
        <select id="valor" name="valor" class="crear-cliente-input" required>
        </select>

        <label for="monto_restante" class="crear-cliente-label">Monto Restante a Cobrar (con 20% adicional):</label>
        <input type="text" id="monto_restante" name="monto_restante" class="crear-cliente-input" readonly>

        <label for="fecha_compra" class="crear-cliente-label">Fecha de Compra:</label>
        <input type="date" id="fecha_compra" name="fecha_compra" class="crear-cliente-input" required>

        <!-- Campo oculto con el ID del usuario -->
        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['id_usuario']; ?>">

        <input type="submit" value="Guardar Cliente" class="crear-cliente-btn">
    </form>
</div>

<script>
    function cambiarPrecios() {
        const articulo = document.getElementById("articulo").value;
        const valor = document.getElementById("valor");
        const montoRestante = document.getElementById("monto_restante");

        valor.innerHTML = '';

        if (articulo === "Plancha") {
            valor.innerHTML = '<option value="500">500 Bs</option><option value="800">800 Bs</option><option value="900">900 Bs</option>';
        } else if (articulo === "Licuadora") {
            valor.innerHTML = '<option value="1000">1000 Bs</option><option value="1500">1500 Bs</option>';
        } else if (articulo === "Lavadora") {
            valor.innerHTML = '<option value="2000">2000 Bs</option><option value="2500">2500 Bs</option>';
        } else if (articulo === "TV") {
            valor.innerHTML = '<option value="3000">3000 Bs</option><option value="3500">3500 Bs</option>';
        } else {
            valor.innerHTML = '<option value="0">Otro</option>';
        }

        valor.onchange = function() {
            let valorSeleccionado = parseFloat(valor.value);
            let valorConRecargo = valorSeleccionado + (valorSeleccionado * 0.20);
            montoRestante.value = valorConRecargo.toFixed(2) + " Bs";
        };

        let valorInicial = parseFloat(valor.value);
        let valorConRecargoInicial = valorInicial + (valorInicial * 0.20);
        montoRestante.value = valorConRecargoInicial.toFixed(2) + " Bs";
    }

    function setFechaActual() {
        const today = new Date();
        const day = String(today.getDate()).padStart(2, '0');
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const year = today.getFullYear();

        const currentDate = `${year}-${month}-${day}`;
        document.getElementById('fecha_compra').value = currentDate;
    }

    window.onload = function() {
        cambiarPrecios();
        setFechaActual();
    };
</script>

</body>
</html>
