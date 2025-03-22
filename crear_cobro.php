<!-- crear_cobro.php -->
<form action="añadir_cobro.php" method="POST">
    <label for="id_cliente">ID Cliente:</label>
    <input type="number" name="id_cliente" required>
    
    <label for="monto">Monto a cobrar:</label>
    <input type="number" step="0.01" name="monto" required>
    
    <button type="submit">Añadir cobro</button>
</form>
