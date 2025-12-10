<?php
include("veraprendizlogic.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="../img/logo-cevac.png" type="image/x-icon">
    <title>Detalles del Registro</title>
    <!-- Boostrap !-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../CSS/Ver_Styleee.css">
    <!-- Viewer.js para vista previa de PDF -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.5/viewer.min.css">
</head>

<body>
    <!-- Menu Izquierdo !-->
    <aside>
        <div class="sidebar">
            <!-- Aqui falta la redireccion al index publico -->
            <a href="../../index.html">
                <div class="sidebar-logo">
                    <img src="../img/logo-cevac.png" alt="Logo CEVAC" id="logocevac">
                </div>
            </a>

            <!-- Iconos !-->
            <div class="icons">
                <a href="../index.php">
                    <div class="bar-icon">
                        <img src="../img/house-iconwhite.png" alt="Icono de Home">
                        <span class="tooltip">Inicio</span>
                    </div>
                </a>

                <a href="../tabla_aprendices_principal.php">
                    <div class="bar-icon">
                        <img src="../img/Userimagewhite.png" alt="Icono de Aprendiz">
                        <span class="tooltip">Aprendices Postulados</span>
                    </div>
                </a>

                <a href="../tabla_instructores_principal.php">
                    <div class="bar-icon">
                        <img src="../img/instructorwhite.png" alt="Icono de Instructor">
                        <span class="tooltip">Instructores Postulados</span>
                    </div>
                </a>

                <a href="../ayuda.php">
                    <div class="bar-icon">
                        <img src="../img/interrogacionwhite.png" alt="Icono de Ayuda">
                        <span class="tooltip">Ayuda</span>
                    </div>
                </a>
            </div>
            <div class="sidebar-bottom">
                <img src="../img/Gerenciaicon.png" alt="Avatar" width="55">
            </div>

        </div>

    </aside>
    <main>
        <div class="detalle-container">
            <div class="lef-container">
                <?php if ($registro): ?>
                    <h1>Detalles de: <?= htmlspecialchars($registro['nombres'] . ' ' . $registro['apellidos']) ?></h1>

                    <div class="campo">
                        <label>Identificador:</label>
                        <p><?= htmlspecialchars($registro['id']) ?></p>
                    </div>

                    <div class="campo">
                        <label>Nombre Completo:</label>
                        <p><?= htmlspecialchars($registro['nombres'] . ' ' .$registro['apellidos']) ?></p>
                    </div>

                    <div class="campo">
                        <label>Número de C.I:</label>
                        <p><?= htmlspecialchars($registro['tipo_documento'] . '-' . $registro['documento']) ?></p>
                    </div>

                    <div class="campo">
                        <label>Email:</label>
                        <p><?= htmlspecialchars($registro['email']) ?></p>
                    </div>

                    <div class="campo">
                        <label>Teléfono:</label>
                        <p><?php echo "(" . $registro['codigo_telefono'] . ") " . $registro['telefono']; ?></p>
                    </div>

                    <div class="campo">
                        <label>Fecha de nacimiento:</label>
                        <p><?= htmlspecialchars($registro['fecha_nacimiento']) ?></p>
                    </div>

                    <div class="campo">
                        <label>Motivación</label>
                        <p><?= htmlspecialchars($registro['motivacion']) ?></p>
                    </div>

                    <div class="campo">
                        <label>Documentos:</label>
                        <div class="documentos-container">
                            <div class="documento-item">
                                <h5>Cédula:</h5>
                                <?php if ($registro['foto_cedula']): ?>
                                    <div class="vista-previa">
                                        <img src="data:<?= $registro['foto_cedula_mime']; ?>;base64,<?= base64_encode($registro['foto_cedula']); ?>"
                                            alt="Vista previa de cédula" class="img-preview">
                                    </div>
                                <?php endif; ?>
                                <a href="../config/descargar_archivo_aprendices.php?id=<?= $registro['id'] ?>&tipo=cedula"
                                    class="btn btn-primary">
                                    <i class="bi bi-download"></i> Descargar Cédula
                                </a>
                            </div>


                            <?php if ($registro['tiene_bachiller'] && $registro['foto_titulo']): ?>
                                <div class="documento-item">
                                    <h5>Título de Bachiller:</h5>
                                    <div class="vista-previa">
                                        <img src="data:<?= $registro['foto_titulo_mime']; ?>;base64,<?= base64_encode($registro['foto_titulo']); ?>"
                                            alt="Vista previa de título" class="img-preview">
                                    </div>
                                    <a href="../config/descargar_archivo_aprendices.php?id=<?= $registro['id'] ?>&tipo=componentes"
                                        class="btn btn-primary">
                                        <i class="bi bi-file-earmark-arrow-down"></i> Descargar Título
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <a href="javascript:history.back()" class="btn-volver">Volver</a>
                <?php else: ?>
                    <h2>Registro no encontrado</h2>
                    <p>No se encontró ningún registro con ID: <?= $id ?></p>
                    <p>Consulta ejecutada: <?= $sql ?> con ID: <?= $id ?></p>
                    <a href="index.php" class="btn-volver">Volver a la lista</a>
                <?php endif; ?>
            </div>

            <div class="right-container">
                <!-- CV MODIFICACION -->
                <div class="documento-item">
                    <h5>C.V / Curriculum:</h5>
                    <?php if ($registro['foto_cv']): ?>
                        <?php if ($registro['foto_cv_mime'] == 'application/pdf'): ?>
                            <div class="vista-previa" style="padding: 0;">
                                <div class="vista-previa-pdf">
                                    <iframe
                                        src="data:application/pdf;base64,<?= base64_encode($registro['foto_cv']); ?>#toolbar=0&navpanes=0"
                                        style="width:100%; height:43.4rem; border:none;"></iframe>
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="width:118rem;" class="vista-previa">
                                <img src="data:<?= $registro['foto_cv_mime']; ?>;base64,<?= base64_encode($registro['foto_cv']); ?>"
                                    alt="Vista previa del C.V" class="img-preview">
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="../config/descargar_archivo_aprendices.php?id=<?= $registro['id'] ?>&tipo=cv"
                        class="btn btn-primary">
                        <i class="bi bi-download"></i> Descargar CV
                    </a>
                </div>
            </div>
        </div>


    </main>
</body>

</html>