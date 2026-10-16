<?php
// ============================================================
// auth.php — Control de acceso por rol
// Uso: definir $pagina_actual antes de incluir este archivo
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_rol'])) {
    header('Location: index.php');
    exit;
}

$rol_del_usuario = $_SESSION['usuario_rol'];

$permisos_por_rol = [
    'Vendedor'      => ['ventas', 'ayuda'],
    'Panadero'      => ['stock', 'insumos', 'produccion', 'productos', 'nuevo_producto', 'nueva_receta', 'ayuda'],
    'Administrador' => ['stock', 'insumos', 'produccion', 'productos', 'nuevo_producto', 'nueva_receta', 'ventas', 'reportes', 'ayuda', 'registro'],
];

$paginas_del_rol = $permisos_por_rol[$rol_del_usuario] ?? [];

if (!in_array($pagina_actual, $paginas_del_rol, true)) {
    $pagina_principal_por_rol = [
        'Vendedor'      => 'ventas.php',
        'Panadero'      => 'stock.php',
        'Administrador' => 'stock.php',
    ];

    $redireccion = $pagina_principal_por_rol[$rol_del_usuario] ?? 'index.php';
    header("Location: $redireccion");
    exit;
}
