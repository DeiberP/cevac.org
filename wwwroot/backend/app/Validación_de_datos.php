<?php
// Habilitar todos los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir conexión a la base de datos
include("config/conection.php");

// Verificar conexión
if (!$conection || $conection->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos: ' . $conection->connect_error
    ]));
}

// 1. Recibir todos los datos del formulario
$tipo_usuario = $_POST['tipo_usuario'] ?? '';
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$tipo_documento = trim($_POST['tipo_documento'] ?? '');
$documento = trim($_POST['documento'] ?? '');
$email = trim($_POST['email'] ?? '');
$codigo_telefono = trim($_POST['codigo_telefono'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$fecha_nacimiento = ($_POST['fecha_nacimiento']);
$motivacion = trim($_POST['motivacion'] ?? '');
$tiene_bachiller = isset($_POST['tiene_bachiller']) ? 1 : 0;
$experiencia = trim($_POST['experiencia'] ?? '');



// Obtener la fecha para el funcionamiento del grafico
$fecha_actual = date('Y-m-d');

// 2. Validar campos obligatorios
$errores = [];

// Validaciones comunes
if (empty($nombres)) {
    $errores[] = "El campo Nombres es obligatorio";
}
if (empty($apellidos)) {
    $errores[] = "El campo Apellidos es obligatorio";
}
if (empty($tipo_documento)) {
    $errores[] = "El campo Tipo de Documento es obligatorio";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El email no es válido";
}
if (empty($telefono)) {
    $errores[] = "El teléfono es obligatorio";
}
if (empty($fecha_nacimiento)) {
    $errores[] = "La fecha de nacimiento es obligatoria";
}

// Validar formato de cédula
if (!preg_match('/^\d{2}\.\d{3}\.\d{3}$/', $documento)) {
    $errores[] = "El formato de la cédula no es válido (debe ser XX.XXX.XXX)";
}

// Validar archivos obligatorios
if ($_FILES['foto_cedula']['error'] !== UPLOAD_ERR_OK) {
    $errores[] = "Debe subir una foto de la cédula";
} else {
    $permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($_FILES['foto_cedula']['type'], $permitidos)) {
        $errores[] = "La foto de cédula debe ser JPEG, PDF o PNG";
    } elseif ($_FILES['foto_cedula']['size'] > $max_size) {
        $errores[] = "La foto de cédula excede el tamaño máximo de 5MB";
    }
}

// Validaciones específicas por tipo de usuario
if ($tipo_usuario == 'aprendiz') {
    if (empty($motivacion)) {
        $errores[] = "La motivación es obligatoria para aprendices";
    }

    if ($tiene_bachiller && $_FILES['foto_titulo']['error'] !== UPLOAD_ERR_OK) {
        $errores[] = "Debe subir foto del título de bachiller";
    }
} elseif ($tipo_usuario == 'instructor') {
    if (empty($experiencia)) {
        $errores[] = "La experiencia es obligatoria para instructores";
    }
    if ($_FILES['foto_componentes']['error'] !== UPLOAD_ERR_OK) {
        $errores[] = "Debe subir foto de los componentes";
    }
}

// 3. Si hay errores, retornarlos
if (!empty($errores)) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => implode('<br>', $errores)
    ]);
    exit;
}

// 4. Si todo está bien, procesar los archivos y guardar en la base de datos
try {

    // Función para leer el contenido del archivo
    function leerArchivo($archivo)
    {
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        return file_get_contents($archivo['tmp_name']);
    }

    // Guardar datos
    $foto_cedula = leerArchivo($_FILES['foto_cedula']);
    $foto_cedula_mime = $_FILES['foto_cedula']['type'];

    // Guardar datos CV
    $foto_cv = leerArchivo($_FILES['foto_cv']);
    $foto_cv_mime = $_FILES['foto_cv']['type'];

    if ($tipo_usuario == 'aprendiz') {
        $foto_titulo = $tiene_bachiller ? leerArchivo($_FILES['foto_titulo']) : null;
        $foto_titulo_mime = $tiene_bachiller ? $_FILES['foto_titulo']['type'] : null;

        // Para evitar duplicidad de datos:
        $stmt = $conection->prepare("SELECT * FROM aprendices WHERE 
        nombres = ? AND 
        apellidos = ? AND 
        tipo_documento = ? AND
        documento = ? AND 
        email = ? AND 
        codigo_telefono = ? AND 
        telefono = ?");
        $stmt->bind_param(
            "sssssss",
            $nombres,
            $apellidos,
            $tipo_documento,
            $documento,
            $email,
            $codigo_telefono,
            $telefono
        );
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Usted ya ha enviado sus datos anteriormente'
            ]);
            exit();
        }

        $sql = "INSERT INTO aprendices 
    (nombres, apellidos, tipo_documento, documento, foto_cedula, foto_cedula_mime, foto_cv, foto_cv_mime, email, codigo_telefono, telefono, 
    fecha_nacimiento, tiene_bachiller, foto_titulo, foto_titulo_mime, motivacion, fecha_registro) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conection->prepare($sql);
        $stmt->bind_param(
            "sssssssssssssssss",
            $nombres,
            $apellidos,
            $tipo_documento,
            $documento,
            $foto_cedula,
            $foto_cedula_mime,
            $foto_cv,
            $foto_cv_mime,
            $email,
            $codigo_telefono,
            $telefono,
            $fecha_nacimiento,
            $tiene_bachiller,
            $foto_titulo,
            $foto_titulo_mime,
            $motivacion,
            $fecha_actual
        );
    } else {
        $foto_componentes = leerArchivo($_FILES['foto_componentes']);
        $foto_componentes_mime = $_FILES['foto_componentes']['type'];

        // Para evitar duplicidad de datos:
        $stmt = $conection->prepare("SELECT * FROM instructores WHERE 
        nombres = ? AND 
        apellidos = ? AND 
        tipo_documento = ? AND
        documento = ? AND 
        email = ? AND 
        codigo_telefono = ? AND 
        telefono = ?");
        $stmt->bind_param(
            "sssssss",
            $nombres,
            $apellidos,
            $tipo_documento,
            $documento,
            $email,
            $codigo_telefono,
            $telefono
        );
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Usted ya ha enviado sus datos anteriormente'
            ]);
            exit();
        }
        $sql = "INSERT INTO instructores 
    (nombres, apellidos, tipo_documento, documento, foto_cedula, foto_cedula_mime, foto_cv, foto_cv_mime, email, codigo_telefono, telefono, 
    fecha_nacimiento, experiencia, foto_componentes, foto_componentes_mime, fecha_registro) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conection->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssss",
            $nombres,
            $apellidos,
            $tipo_documento,
            $documento,
            $foto_cedula,
            $foto_cedula_mime,
            $foto_cv,
            $foto_cv_mime,
            $email,
            $codigo_telefono,
            $telefono,
            $fecha_nacimiento,
            $experiencia,
            $foto_componentes,
            $foto_componentes_mime,
            $fecha_actual
        );
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registro guardado correctamente'
        ]);
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

} catch (Exception $e) {
    error_log("Error en Validación_de_datos.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
    ]);
}
?>