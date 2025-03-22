<?php
include 'includes/conexion.php';

$mensaje = ""; // Variable para almacenar mensajes de error/éxito

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmar_password = $_POST['confirm_password'];
    $rol = $_POST['rol'];

    // Validar que las contraseñas coincidan
    if ($password !== $confirmar_password) {
        $mensaje = '<div class="registro-error">❌ <strong>Error:</strong> Las contraseñas no coinciden.</div>';
    } else {
        // Verificar si el correo ya está registrado
        $sql_verificar = "SELECT id FROM usuarios WHERE email = ?";
        $stmt_verificar = $conn->prepare($sql_verificar);
        $stmt_verificar->bind_param("s", $email);
        $stmt_verificar->execute();
        $stmt_verificar->store_result();

        if ($stmt_verificar->num_rows > 0) {
            $mensaje = '<div class="registro-error">❌ <strong>Error:</strong> El correo ya está registrado.</div>';
        } else {
            // Encriptar la contraseña
            $password_encriptado = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            $sql_insertar = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
            $stmt_insertar = $conn->prepare($sql_insertar);
            $stmt_insertar->bind_param("ssss", $nombre, $email, $password_encriptado, $rol);

            if ($stmt_insertar->execute()) {
              $mensaje = '
              <div class="registro-exito">
                  ✅ <strong>¡Registro exitoso!</strong> El usuario fue registrado correctamente.
                  <br>Serás redirigido en 3 segundos...
              </div>
              
              <script>
                  setTimeout(function() {
                      window.location.href = "login.html";  // Redirige al login después de 3 segundos
                  }, 3000);  // Tiempo en milisegundos (3000ms = 3 segundos)
              </script>';
          } else {
              $mensaje = '
              <div class="registro-error">
                  ❌ <strong>Error:</strong> No se pudo registrar el usuario.
                  <br>Por favor, intenta nuevamente.
              </div>';
          }
          

            $stmt_insertar->close();
        }

        $stmt_verificar->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="registro-body">

    <div class="registro-container">
        <h2 class="registro-titulo">Registro de Usuario</h2>

        <!-- Mostrar Mensajes -->
        <?php if (!empty($mensaje)) echo $mensaje; ?>

        <form action="registro.php" method="POST" class="registro-form">
            <label for="nombre" class="registro-label">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" class="registro-input" required>

            <label for="email" class="registro-label">Correo Electrónico:</label>
            <input type="email" id="email" name="email" class="registro-input" required>

            <label for="password" class="registro-label">Contraseña:</label>
            <input type="password" id="password" name="password" class="registro-input" required>

            <label for="confirm_password" class="registro-label">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="registro-input" required>

            <input type="hidden" name="rol" value="usuario">

            <input type="submit" value="Registrarse" name="submit" class="registro-btn">
        </form>

        <p class="registro-texto">¿Ya tienes cuenta? <a href="login.html" class="registro-enlace">Inicia sesión aquí</a></p>
    </div>

</body>
</html>
