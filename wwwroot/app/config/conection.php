<?php
$server = 'localhost';
$user = 'usr_cevag';
$password = 'filtBdmkncuvxabq2[ye';
$dbname = 'db_cevag_postu';

$conection = new mysqli($server, $user, $password, $dbname);
if ($conection->connect_error) {
    echo 'Conexion Fallida';
}
?>