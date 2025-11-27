function actualizarContador(segundos) {
  const dias = Math.floor(segundos / (60 * 60 * 24)); /*este es para calcular los seg en un dia*/
  const horas = Math.floor((segundos % (60 * 60 * 24)) / (60 * 60)); /*calcula los seg restantes de los 86400 seg del dia y lo multiplica por 3600 para sacar las horas restantes, ya que 1 hora = 60 minutos * 60 seg*/
  const minutos = Math.floor((segundos % (60 * 60)) / 60); /*con la operacion de residuo (%) se calculan los minutos restantes en segundos, y luego se multiplica por 60 para obtener los minutos restantes*/
  const segs = segundos % 60; /*facilito, aqui es lo mismo, pero obtiene los seg restantes */

  return `${dias}:${horas}:${minutos}:${segs}`;
  /*template string para mostrar el contador, papa*/
}

let tiempoRestante = parseInt(document.getElementById('contador-actualizacion').textContent);
document.getElementById('contador-actualizacion').textContent = actualizarContador(tiempoRestante);
/* aqui toma desde el index con el <span> la porcion en donde se mostrara el contador, y ese valor lo reemplaza por la variable tiempoRestante que está en actualizacion_semanal.php*/

setInterval(function () {
  tiempoRestante--;
  document.getElementById('contador-actualizacion').textContent = actualizarContador(tiempoRestante);
}, 1000); /*aqui resta 1 segundo cada 1 segundo y actualiza todo el conteo*/
