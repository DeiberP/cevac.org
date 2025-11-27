<?php
session_start();

// Si no hay sesión, redirigir al login
if (!isset($_SESSION["usuario"])) {
    header("Location: ../login_gerencia_cevac.php");
    exit();
}

echo "<h1>Bienvenido, " . $_SESSION["usuario"] . "</h1>";
echo "<p>Has iniciado sesión correctamente.</p>";
echo '<a href="logout.php">Cerrar sesión</a>';
