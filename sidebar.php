<?php
// ============================================================
// sidebar.php — Menú de navegación lateral según rol
// Requiere: $pagina_actual y $_SESSION['usuario_rol']
// ============================================================

$rol_del_usuario = $_SESSION['usuario_rol'] ?? '';

// ==================== Opciones de navegación y permisos ====================
$items_del_menu = [
    'stock'      => ['etiqueta' => 'Stock',             'enlace' => 'stock.php',      'roles' => ['Administrador', 'Panadero']],
    'insumos'    => ['etiqueta' => 'Insumos',           'enlace' => 'insumos.php',    'roles' => ['Administrador', 'Panadero']],
    'produccion' => ['etiqueta' => 'Producción',        'enlace' => 'produccion.php', 'roles' => ['Administrador', 'Panadero']],
    'productos'  => ['etiqueta' => 'Productos',         'enlace' => 'productos.php',  'roles' => ['Administrador', 'Panadero']],
    'salidas'    => ['etiqueta' => 'Salidas',           'enlace' => 'salidas.php',    'roles' => ['Administrador', 'Panadero']],
    'reportes'   => ['etiqueta' => 'Reportes',          'enlace' => 'reportes.php',   'roles' => ['Administrador']],
    'registro'   => ['etiqueta' => 'Registrar usuario', 'enlace' => 'registro.php',   'roles' => ['Administrador']],
    'ayuda'      => ['etiqueta' => 'Ayuda',             'enlace' => 'ayuda.php',      'roles' => ['Administrador', 'Panadero']],
];

// ==================== Renderizado del menú visible ====================
foreach ($items_del_menu as $clave => $item) {
    if (!in_array($rol_del_usuario, $item['roles'])) {
        continue;
    }

    $clase_activa = ($pagina_actual === $clave) ? ' class="active"' : '';
    echo "<li{$clase_activa}><a href=\"{$item['enlace']}\">{$item['etiqueta']}</a></li>\n";
}
