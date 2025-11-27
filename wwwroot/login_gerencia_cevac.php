<?php
session_start();

$host = "localhost";
$db = "cevac_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$error = "";
$ahora = time();

// Verificar bloqueo por intentos
if (isset($_SESSION['bloqueo'])) {
    if ($ahora < $_SESSION['bloqueo']) {
        $tiempoRestante = $_SESSION['bloqueo'] - $ahora;
        $error = "Has excedido el número de intentos. Intenta de nuevo en $tiempoRestante segundos.";
    } else {
        unset($_SESSION['bloqueo']);
        $_SESSION['intentos'] = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($error)) {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['intentos'] = 0;
        unset($_SESSION['bloqueo']);
        $_SESSION["usuario"] = $usuario['username'];
        $_SESSION["rol"] = $usuario['rol'];
        header("Location: app/index.php");
        exit();
    } else {
        if (!isset($_SESSION['intentos'])) {
            $_SESSION['intentos'] = 0;
        }
        $_SESSION['intentos']++;

        $maxIntentos = 3;
        $intentosRestantes = $maxIntentos - $_SESSION['intentos'];

        if ($_SESSION['intentos'] >= $maxIntentos) {
            $_SESSION['bloqueo'] = $ahora + 10; // BLOQUEO DE 10 SEGUNDOS
            $error = "Has excedido el número de intentos. Intenta de nuevo en 10 segundos.";
        } else {
            $error = "Usuario o contraseña incorrectos. Te quedan $intentosRestantes intento" . ($intentosRestantes == 1 ? "" : "s") . ".";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login Dashboard</title>
    <link rel="icon" href="app/img/logo-cevac.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="app/CSS/estilo_login.css" rel="stylesheet">
</head>

<body>
    <div class="overlay">
        <div class="card shadow-lg">
            <h3 class="text-center mb-3">Iniciar Sesión</h3>
            <div class="text-center mb-4">
                <img src="app/img/logo-cevac.png" alt="Logo CEVAC" class="logo-cevac">
            </div>

            <?php if (!empty($error) && !isset($_SESSION['bloqueo'])): ?>
                <div id="mensajeError" class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php else: ?>
                <div id="mensajeError"></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" <?php echo (isset($_SESSION['bloqueo']) && time() < $_SESSION['bloqueo']) ? 'style="display:none;"' : ''; ?>>
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="username" class="form-control" required autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary-custom">Ingresar</button>
            </form>

            <div id="countdown" class="text-center mt-3" style="font-weight:bold; color:#fff;"></div>

            <div class="mt-3 text-center">
                <a href="recuperar.php" class="enlace-azul d-block mb-2">¿Olvidaste tu usuario o contraseña?</a>
                <a href="cambiar_usuario_contraseña.php" class="enlace-azul d-block">Cambiar usuario o contraseña</a>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['bloqueo']) && time() < $_SESSION['bloqueo']): 
        $tiempoRestante = $_SESSION['bloqueo'] - time();
    ?>
    <script>
        let tiempo = <?php echo $tiempoRestante; ?>;
        const countdown = document.getElementById('countdown');
        const loginForm = document.getElementById('loginForm');

        function actualizarContador() {
            if (tiempo <= 0) {
                countdown.textContent = '';
                loginForm.style.display = 'block'; // Mostrar formulario cuando termine el bloqueo
                clearInterval(intervalo);
            } else {
                countdown.textContent = 'Por favor espera ' + tiempo + ' segundos antes de intentar de nuevo.';
                tiempo--;
            }
        }

        // Ejecutar cada segundo
        actualizarContador(); // Ejecutar inmediatamente para que no haya 1 seg de retraso
        const intervalo = setInterval(actualizarContador, 1000);
    </script>
    <?php endif; ?>

</body>

</html>
