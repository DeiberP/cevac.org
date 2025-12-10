<?php
include("config/conection.php");

$aprendices_total = "Error";
$instructores_total = "Error";

// Aprendices
$sql = "SELECT COUNT(*) as total FROM aprendices";
$resultado = $conection->query($sql);
if ($resultado) {
    $row = $resultado->fetch_assoc();
    $aprendices_total = $row['total'];
}

// Profesores
$sql = "SELECT COUNT(*) as total FROM instructores";
$resultado = $conection->query($sql);
if ($resultado) {
    $row = $resultado->fetch_assoc();
    $instructores_total = $row['total'];
}
?>
