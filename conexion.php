<?php
// XAMPP trae PHP con Europe/Berlin por defecto; usamos la hora local.
date_default_timezone_set('America/Argentina/Buenos_Aires');

$servidor    = "localhost";
$usuario     = "root";
$contrasena  = "";
$base_datos  = "panaderia_normal10";

$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
