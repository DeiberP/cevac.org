<?php
if (file_exists($cache_file))  /*aqui comprueba si el archivo del cache existe*/ {
  $tiempo_restante = $cache_time - (time() - filemtime($cache_file));
  // tiempo restante va a ser igual a los 7 dias menos el tiempo que ha pasado desde que se modificó el archivo  
  echo $tiempo_restante;
} else {
  echo "604800"; // 7 días en segundos
}
?>
