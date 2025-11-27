<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../login_gerencia_cevac.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="img/logo-cevac.png" type="image/x-icon" />
    <title>Instructores Postulados</title>
    <!-- Boostrap !-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="CSS/EstiloOfficial.css" /><!-- CSS !-->
</head>

<body>

    <!-- Menu Izquierdo !-->
    <aside>
        <div class="sidebar">
            <!-- Aqui falta la redireccion al index publico -->
            <a href="../index.html">
                <div class="sidebar-logo">
                    <img src="img/logo-cevac.png" alt="Logo CEVAC" id="logocevac" />
                </div>
            </a>

            <!-- Iconos !-->
            <div class="icons">
                <a href="index.php">
                    <div class="bar-icon">
                        <img src="img/house-iconwhite.png" alt="Icono de Home" />
                        <span class="tooltip">Inicio</span>
                    </div>
                </a>

                <a href="tabla_aprendices_principal.php">
                    <div class="bar-icon">
                        <img src="img/Userimagewhite.png" alt="Icono de Aprendiz" />
                        <span class="tooltip">Aprendices Postulados</span>
                    </div>
                </a>

                <a href="tabla_instructores_principal.php">
                    <div class="bar-icon">
                        <img src="img/instructorwhite.png" alt="Icono de Instructor" />
                        <span class="tooltip">Instructores Postulados</span>
                    </div>
                </a>

                <a href="ayuda.php">
                    <div class="bar-icon">
                        <img src="img/interrogacionwhite.png" alt="Icono de Ayuda" />
                        <span class="tooltip">Ayuda</span>
                    </div>
                </a>
            </div>
            <div class="sidebar-bottom">
                <img src="img/Gerenciaicon.png" alt="Avatar" width="55" />
            </div>
        </div>
    </aside>
    <main>
        <div class="table_title">
            <h1>INSTRUCTORES</h1>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include("config/tabla_instructores.php"); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- script de bootstrap para todo el proceso de descarga -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <footer style="margin-top: 400px;">
        © 2025 cevac.org
    </footer>
</body>