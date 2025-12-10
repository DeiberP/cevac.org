<!-- Gráfico estadístico -->
<?php
// Para que se actualice cada 7 días (604800 segundos)
$cache_file = 'config/estadisticas_dias_cache.json';
$cache_time = 604800; // 7 días en segundos

// Verificar si existe caché válido
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    $datos = json_decode(file_get_contents($cache_file), true);
} else {

    // Consulta para obtener el total de aprendices por día de la semana
    $sql = "SELECT DAYOFWEEK(fecha_registro) as dia_semana, COUNT(*) as cantidad 
        FROM aprendices 
        GROUP BY DAYOFWEEK(fecha_registro)";

    $resultado = $conection->query($sql);
    $datos = array_fill(1, 7, 0); //1 es el valor de domingo, 7 de sabado y 0 donde inicializa el array

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            // Ajustar para que 1=lunes, 7=domingo
            $dia_ajustado = $row['dia_semana'] - 1;
            if ($dia_ajustado == 0) //como el domingo queda en 0...
                $dia_ajustado = 7;  //Aqui lo convierte en el ultimo dia de la semana, el 7
            $datos[$dia_ajustado] = $row['cantidad'];
        }
    }

    // Guardar en caché
    file_put_contents($cache_file, json_encode($datos));

    $conection->close();
}

$max_val = max($datos) ?: 1; // El 1 es para evitar división por cero en posibles casos 
$dias_semana = [
    1 => "Lunes",
    2 => "Martes",
    3 => "Miércoles",
    4 => "Jueves",
    5 => "Viernes",
    6 => "Sábado",
    7 => "Domingo"
];

// Esta es la logica para las barras en los diferentes dias
$total_aprendices = array_sum($datos);
for ($i = 1; $i <= 7; $i++):
    $porcentaje = ($datos[$i] / $total_aprendices) * 100; //el numero de aprendices en un dia dividido por el total en la semana y multiplicado por 100 para obtener el porcentaje
    $clase = $i % 2 == 0 ? 'bar-fill' : 'bar-fill2'; //Aqui se alternan las clases para el color rojo y azul de las barras
    ?>
    <div class="bar">
        <div class="bar-label"><?= $dias_semana[$i] ?></div>
        <!-- El bar label es el nombre de los dias de la semana en la parte izquierda del grafico -->
        <div class="bar-progress-container">
            <div class="<?= $clase ?>" style="width: <?= $porcentaje ?>%;">
                <span class="percentage-text"><?= number_format($porcentaje, 1) ?>%</span>
                <!-- number format es el que permite que haya 1 solo decimal -->
            </div>
        </div>
        <div class="bar-count"><?= $datos[$i] ?></div>
        <!-- bar count es el numero de aprendices en un dia -->
    </div>

<?php endfor; ?>