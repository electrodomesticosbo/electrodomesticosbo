<?php
// Datos de conexión
$host = 'localhost';      // Servidor de la base de datos
$usuario = 'root';        // Usuario de MySQL (generalmente 'root' en XAMPP)
$password = '';           // Contraseña (deja vacío en XAMPP)
$base_de_datos = 'DB_electrodomesticos';  // Nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($host, $usuario, $password, $base_de_datos);

// Verificar si la conexión tuvo éxito
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>