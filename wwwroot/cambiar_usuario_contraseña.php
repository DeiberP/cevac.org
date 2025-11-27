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

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userActual = $_POST["userActual"] ?? "";
    $passActual = $_POST["passActual"] ?? "";
    $nuevoUsuario = trim($_POST["nuevoUsuario"] ?? "");
    $nuevaPass = trim($_POST["nuevaPass"] ?? "");
    $confirmPass = trim($_POST["confirmPass"] ?? "");

    // 1. Buscar usuario actual
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute([$userActual]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // 2. Verificar contraseña actual
        if (password_verify($passActual, $usuario['password'])) {

            // 3. Validar nueva contraseña y confirmación
            if ($nuevaPass !== "" && $nuevaPass !== $confirmPass) {
                $mensaje = "<div class='alert alert-danger'>La nueva contraseña y su confirmación no coinciden.</div>";
            } else {
                // 4. Cambiar nombre de usuario (si aplica)
                if ($nuevoUsuario !== "" && $nuevoUsuario !== $userActual) {
                    // Verificar que no exista nuevo usuario
                    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
                    $stmtCheck->execute([$nuevoUsuario]);
                    if ($stmtCheck->fetchColumn() > 0) {
                        $mensaje = "<div class='alert alert-warning'>El nuevo nombre de usuario ya existe.</div>";
                    } else {
                        // Actualizar nombre de usuario
                        $stmtUpdate = $pdo->prepare("UPDATE usuarios SET username = ? WHERE username = ?");
                        $stmtUpdate->execute([$nuevoUsuario, $userActual]);
                        $userActual = $nuevoUsuario; // actualizar variable para siguiente paso
                        $mensaje = "<div class='alert alert-success'>Usuario cambiado correctamente.</div>";
                    }
                }

                // 5. Cambiar contraseña (si aplica)
                if ($nuevaPass !== "") {
                    $hashPass = password_hash($nuevaPass, PASSWORD_DEFAULT);
                    $stmtPass = $pdo->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
                    $stmtPass->execute([$hashPass, $userActual]);
                    $mensaje = "<div class='alert alert-success'>Contraseña cambiada correctamente.</div>";
                }

                // 6. Si no se cambió nada
                if ($nuevoUsuario === "" && $nuevaPass === "") {
                    $mensaje = "<div class='alert alert-info'>No se realizó ningún cambio.</div>";
                }
            }
        } else {
            $mensaje = "<div class='alert alert-danger'>Contraseña actual incorrecta.</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-warning'>El usuario actual no existe.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cambiar Usuario o Contraseña</title>
    <link rel="icon" href="app/img/logo-cevac.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="app/CSS/estilo_cambiar.css">
</head>

<body>

    <div class="overlay">
        <div class="card shadow-lg">
            <h3>Cambiar Usuario o Contraseña</h3>

            <img src="app/img/logo-cevac.png" alt="Logo CEVAC" class="logo-cevac">

            <?php if (!empty($mensaje)) echo $mensaje; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuario Actual</label>
                    <input type="text" name="userActual" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña Actual</label>
                    <input type="password" name="passActual" class="form-control" required>
                </div>
                <hr style="border-color: #666;">
                <div class="mb-3">
                    <label class="form-label">Nuevo Usuario</label>
                    <input type="text" name="nuevoUsuario" class="form-control" placeholder="Nuevo usuario">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" name="nuevaPass" class="form-control" placeholder="Nueva contraseña">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmar Nueva Contraseña</label>
                    <input type="password" name="confirmPass" class="form-control" placeholder="Confirma la nueva contraseña">
                </div>
                <button type="submit" class="btn btn-primary-custom">Actualizar</button>
            </form>

            <div class="mt-3 text-center">
                <a href="login_gerencia_cevac.php" class="enlace-azul">Volver al Login</a>
            </div>
        </div>
    </div>

</body>

</html>
