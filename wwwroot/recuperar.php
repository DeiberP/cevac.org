<?php

$usuarios = [
    "Gerente" => [
        "password" => "admin",
        "preguntas" => [
            [
                "pregunta" => "¿En que año fue creado el Cevac?",
                "respuesta" => "1993"
            ],
            [
                "pregunta" => "¿quien es la coordinadora del Cevac?",
                "respuesta" => "monterola"
            ],
            [
                "pregunta" => "¿En qué ciudad naciste?",
                "respuesta" => "caracas"
            ]
        ]
    ],
    "Secretaria" => [
        "password" => "admin",
        "preguntas" => [
            [
                "pregunta" => "¿En qué ciudad naciste?",
                "respuesta" => "caracas"
            ],
            [
                "pregunta" => "¿En que año fue creado el Cevac?",
                "respuesta" => "1993"
            ],
            [
                "pregunta" => "¿quien es la coordinadora del Cevac?",
                "respuesta" => "monterola"
            ]
        ]
    ]
];

$preguntasDisponibles = [];
foreach ($usuarios as $user) {
    foreach ($user["preguntas"] as $pq) {
        if (!in_array($pq["pregunta"], $preguntasDisponibles)) {
            $preguntasDisponibles[] = $pq["pregunta"];
        }
    }
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userRec = trim($_POST["userRec"] ?? "");
    $preguntaSeleccionada = $_POST["pregunta"] ?? "";
    $respuestaIngresada = strtolower(trim($_POST["respuesta"] ?? ""));

    if ($userRec !== "") {
        if (isset($usuarios[$userRec])) {
            $preguntasUsuario = $usuarios[$userRec]["preguntas"];
            $respuestaCorrecta = null;

            foreach ($preguntasUsuario as $pq) {
                if ($pq["pregunta"] === $preguntaSeleccionada) {
                    $respuestaCorrecta = strtolower($pq["respuesta"]);
                    break;
                }
            }

            if ($respuestaCorrecta !== null && $respuestaIngresada === $respuestaCorrecta) {
                $mensaje = "<div class='alert alert-success'>La contraseña para <b>$userRec</b> es: <b>" . $usuarios[$userRec]["password"] . "</b></div>";
            } else {
                $mensaje = "<div class='alert alert-danger'>Pregunta o respuesta incorrecta para el usuario especificado.</div>";
            }
        } else {
            $mensaje = "<div class='alert alert-warning'>El usuario no existe.</div>";
        }
    } else {
        $usuarioEncontrado = null;

        foreach ($usuarios as $nombreUsuario => $dataUsuario) {
            foreach ($dataUsuario["preguntas"] as $pq) {
                if ($pq["pregunta"] === $preguntaSeleccionada && strtolower($pq["respuesta"]) === $respuestaIngresada) {
                    $usuarioEncontrado = $nombreUsuario;
                    break 2;
                }
            }
        }

        if ($usuarioEncontrado !== null) {
            $mensaje = "<div class='alert alert-success'>El usuario asociado a esa respuesta es: <b>$usuarioEncontrado</b></div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>No se encontró ningún usuario con esa pregunta y respuesta.</div>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Usuario o Contraseña</title>
    <link rel="icon" href="app/img/logo-cevac.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="app/CSS/estilo_recuperar.css" rel="stylesheet">
</head>

<body>

    <div class="overlay">
        <div class="card shadow-lg">
            <h3>Recuperar Usuario o Contraseña</h3>

            <img src="app/img/logo-cevac.png" alt="Logo CEVAC" class="logo-cevac">

            <?php if (!empty($mensaje)) echo $mensaje; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Usuario (opcional para recuperar contraseña)</label>
                    <input type="text" name="userRec" class="form-control" placeholder="Deja vacío para recuperar usuario">
                </div>
                <div class="mb-3">
                    <label class="form-label">Selecciona tu pregunta de seguridad</label>
                    <select name="pregunta" class="form-select" required>
                        <option value="">-- Selecciona una pregunta --</option>
                        <?php foreach ($preguntasDisponibles as $pregunta): ?>
                            <option value="<?php echo htmlspecialchars($pregunta); ?>">
                                <?php echo htmlspecialchars($pregunta); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Respuesta</label>
                    <input type="text" name="respuesta" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary-custom">Recuperar</button>
            </form>

            <div class="mt-3 text-center">
                <a href="login_gerencia_cevac.php" class="enlace-azul">Volver al Login</a>
            </div>
        </div>
    </div>

</body>

</html>
