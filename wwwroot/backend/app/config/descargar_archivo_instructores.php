<?php
include "conection.php";

// Verificar conexión primero
if (!$conection || $conection->connect_error) {
    die("Error de conexión a la base de datos.");
}

// ESTO ES PARA INSTRUCTORES PRIMERO

// Validar parámetros
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['tipo'])) {
    die("Parámetros no válidos.");
}

$id = (int)$_GET['id'];
$tipo = $_GET['tipo'];

// Validar tipo de archivo
$campos_validos = [
    'cedula' => [
        'campo' => 'foto_cedula',
        'nombre' => 'Cédula'
    ],
    'componentes' => [
        'campo' => 'foto_componentes',
        'nombre' => 'Componentes'
    ],
    'cv' => [
        'campo' => 'foto_cv',
        'nombre' => 'cv'
    ]
];

if (!array_key_exists($tipo, $campos_validos)) {
    die("Tipo de archivo no válido.");
}

$campo = $campos_validos[$tipo]['campo'];
$nombre_archivo = $campos_validos[$tipo]['nombre'] . '_' . $id;

// Consulta preparada
$sql = "SELECT $campo FROM instructores WHERE id = ?";
$stmt = $conection->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $conection->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("No se encontró registro con ese ID.");
}

$stmt->bind_result($archivoBlob);
$stmt->fetch();

if (empty($archivoBlob)) {
    die("No hay archivo para descargar.");
}

// Detectar tipo de imagen automáticamente
$tipo_mime = 'application/octet-stream';
$extension = 'dat';

// JPEG
if (strpos($archivoBlob, "\xFF\xD8\xFF") === 0) {
    $tipo_mime = 'image/jpeg';
    $extension = 'jpg';
} 
// PNG
elseif (strpos($archivoBlob, "\x89PNG\r\n\x1a\n") === 0) {
    $tipo_mime = 'image/png';
    $extension = 'png';
}
// PDF
elseif (strpos($archivoBlob, "%PDF-") === 0) {
    $tipo_mime = 'application/pdf';
    $extension = 'pdf';
}

// Configurar headers
header("Content-Type: $tipo_mime");
header("Content-Disposition: attachment; filename=\"{$nombre_archivo}.{$extension}\"");
header("Content-Length: " . strlen($archivoBlob));

echo $archivoBlob;
exit;
?>