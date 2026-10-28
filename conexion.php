<?php
// ==================== Configuración regional ====================
// XAMPP trae PHP con Europe/Berlin por defecto; usamos la hora local.
date_default_timezone_set('America/Argentina/Buenos_Aires');

// ==================== Credenciales de base de datos ====================
$servidor    = "localhost";
$usuario     = "root";
$contrasena  = "";
$base_datos  = "panaderia";

// ==================== Apertura de conexión ====================
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// ==================== Manejo de errores de conexión ====================
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
