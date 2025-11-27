<?php
session_start();

// Verificar si el usuario está autenticado
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
  <title>Dashboard</title>
  <!-- Bootstrap !-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="CSS/EstiloOfficial.css" /><!-- CSS !-->
</head>

<body>
  <!-- Menu Izquierdo !-->
  <aside>
    <div class="sidebar">
      <a href="../index.html">
        <div class="sidebar-logo">
          <img src="img/logo-cevac.png" alt="Logo CEVAC" id="logocevac" />
        </div>
      </a>

      <!-- Iconos !-->
      <!-- Para ir al index de la pagina publica -->
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
    <div class="derecha">
      <div class="header d-flex justify-content-between align-items-center">
        <h6>Bienvenido, Administrador</h6>
        <a href="logout.php" class="btn btn-danger btn-sm">Cerrar sesión</a>
      </div>

      <?php include("config/conteos.php"); ?>

      <div class="top_container">
        <div class="postulados">
          <div class="pos-profesores">
            <p class="titulo">Aprendices Postulados</p>
            <img class="logopostulado" src="img/User_image.png" alt="Aprendices" />
            <p class="contador"><?php echo $aprendices_total; ?></p>
          </div>

          <div class="pos-profesores">
            <p class="titulo">Instructores Postulados</p>
            <img class="logopostulado" src="img/instructor_image.png" alt="Instructores" />
            <p class="contador"><?php echo $instructores_total; ?></p>
          </div>
        </div>

        <!-- Aqui empieza el grafico -->
        <div class="chart-container">
          <h6>Total Aprendices Postulados Esta Semana</h6>

          <?php include("config/grafico.php"); ?>

          <p>Se actualiza en <span id="contador-actualizacion">
              <?php include("config/actualizacion_semanal.php"); ?>
            </span>
          </p>

          <script src="config/actualizador_semanal2.js"></script>
        </div>
        <!-- Aqui termina el grafico -->
      </div>

      <div class="tables">
        <div class="table_title">
          <h1>APRENDICES</h1>
          <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombres</th>
                  <th>Apellidos</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php include('config/tabla_aprendices.php'); ?>
              </tbody>
            </table>
          </div>
        </div>

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
                  <th>Teléfono</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php include("config/tabla_instructores.php"); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer style="position: static;" >
    © 2025 cevac.org
  </footer>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Alerta Toast</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    /* Para asegurar que el toast esté bien centrado arriba */
    #alerta-inactividad {
      position: fixed;
      top: 1rem;
      left: 50%;
      transform: translateX(-50%);
      min-width: 320px;
      z-index: 1060;
      display: none; /* oculto inicialmente */
    }
    /* Personalización fondo rojo y texto blanco */
    #alerta-inactividad.toast {
      background-color: #ff0000 !important;
      color: white !important;
    }
    /* Botón cerrar blanco */
    #alerta-inactividad .btn-close {
      filter: invert(1);
      -webkit-filter: invert(1);
    }
  </style>
</head>
<body>

  <!-- Toast alerta fija arriba-centro -->
  <div 
    id="alerta-inactividad" 
    class="toast align-items-center border-0" 
    role="alert" 
    aria-live="assertive" 
    aria-atomic="true"
  >
    <div class="d-flex">
      <div class="toast-body">
        ⚠️ Has estado inactivo. Serás redirigido pronto por seguridad.
      </div>
      <button type="button" class="btn-close me-2 m-auto" aria-label="Cerrar" onclick="ocultarAlerta()"></button>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let timeout;
    let warningTimeout;

    const toastEl = document.getElementById('alerta-inactividad');
    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });

    function showWarning() {
      toastEl.style.display = 'block';
      toast.show();
    }

    function ocultarAlerta() {
      toast.hide();
    }

    toastEl.addEventListener('hidden.bs.toast', () => {
      toastEl.style.display = 'none';
    });

    function resetTimer() {
      clearTimeout(timeout);
      clearTimeout(warningTimeout);

      // Mostrar advertencia después de 3 minutos (180000 ms)
      warningTimeout = setTimeout(() => {
        showWarning();
      }, 180000);

      // Redirigir después de 3 minutos y 10 segundos (190000 ms)
      timeout = setTimeout(() => {
        window.location.href = '../login_gerencia_cevac.php';
      }, 190000);
    }

    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onkeypress = resetTimer;
    window.onscroll = resetTimer;
    window.onclick = resetTimer;
  </script>
</body>
</html>

</body>
</html>

</body>
</html>


</html>