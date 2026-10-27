<?php
// ============================================================
// auth.php — Control de acceso por rol
// Uso: definir $pagina_actual antes de incluir este archivo
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== Contexto de navegación ====================
// Permite que los archivos dentro de subcarpetas indiquen cómo volver a la raíz.
$prefijo_redireccion = $prefijo_redireccion ?? '';

// ==================== Validación de sesión ====================
if (!isset($_SESSION['usuario_rol'])) {
    header('Location: ' . $prefijo_redireccion . 'index.php');
    exit;
}

$rol_del_usuario = $_SESSION['usuario_rol'];

// ==================== Permisos por rol ====================
$permisos_por_rol = [
    'Panadero'      => ['stock', 'insumos', 'produccion', 'productos', 'nuevo_producto', 'nueva_receta', 'salidas', 'ayuda'],
    'Administrador' => ['stock', 'insumos', 'produccion', 'productos', 'nuevo_producto', 'nueva_receta', 'salidas', 'reportes', 'ayuda', 'registro'],
];

$paginas_del_rol = $permisos_por_rol[$rol_del_usuario] ?? [];

// ==================== Validación de acceso a la página ====================
if (!in_array($pagina_actual, $paginas_del_rol, true)) {
    $pagina_principal_por_rol = [
        'Panadero'      => 'stock.php',
        'Administrador' => 'stock.php',
    ];

    $redireccion = $pagina_principal_por_rol[$rol_del_usuario] ?? 'index.php';
    header('Location: ' . $prefijo_redireccion . $redireccion);
    exit;
}
