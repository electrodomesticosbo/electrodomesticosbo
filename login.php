<?php
session_start();

// Asegúrate de tener el código de conexión a la base de datos
include 'includes/conexion.php'; 

$error = ""; // Inicializar variable para manejar el error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Consulta para verificar las credenciales
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado, verificamos la contraseña
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            // Iniciar sesión y almacenar la información del usuario
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            // Después de verificar que la contraseña es correcta
            $_SESSION['id_usuario'] = $usuario['id']; // Guarda el ID del usuario en la sesión

            header("Location: menu.php"); // Redirigir al menú principal
            exit();
        } else {
            $error = "❌ Contraseña incorrecta.";
        }
    } else {
        $error = "❌ Correo no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    
    <!-- Enlace a Bootstrap desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Tu archivo CSS personalizado -->
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="login-container p-4 border rounded shadow" style="width: 350px;">
            <!-- Logo -->
            <div class="logo text-center mb-4">
                <img src="img/logoelectro.png" alt="Logo de la Empresa" width="150">
            </div>

            <h2 class="text-center mb-4">Iniciar Sesión</h2>

            <!-- Mensaje de error si hay problema en el login -->
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario de inicio de sesión -->
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="d-grid gap-2">
                    <input type="submit" name="submit" value="Iniciar sesión" class="btn btn-primary">
                </div>
            </form>

            <p class="mt-3 text-center">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </div>

    <!-- Enlace a los scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
