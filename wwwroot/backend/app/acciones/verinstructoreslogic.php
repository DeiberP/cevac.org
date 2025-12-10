<?php
include("../config/conection.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("ID no válido");
}

$sql = "SELECT id, nombres, apellidos, tipo_documento, documento, foto_cedula, foto_cedula_mime, foto_cv, foto_cv_mime, email, codigo_telefono, telefono, fecha_nacimiento, experiencia, foto_componentes, foto_componentes_mime FROM instructores WHERE id = ?";
$stmt = $conection->prepare($sql);

if (!$stmt) {
    die("Error preparando la consulta: " . $conection->error);
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error ejecutando la consulta: " . $stmt->error);
}

$resultado = $stmt->get_result();
$registro = $resultado->fetch_assoc();

if (!$registro) {
    die("No se encontró ningún registro con ID $id");
}