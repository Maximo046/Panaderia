<?php
// ============================================================
// sidebar.php — Navegación principal
// Barra superior (móvil) + offcanvas lateral responsive
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
?>

<!-- ==================== Barra superior (solo móvil) ==================== -->
<nav class="navbar topbar sticky-top d-lg-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#menuLateral"
            aria-controls="menuLateral" aria-label="Abrir menú de navegación">
      <span class="navbar-toggler-icon"></span>
    </button>
    <span class="navbar-brand mb-0">Panadería Normal 10</span>
  </div>
</nav>

<!-- ==================== Menú lateral (offcanvas en móvil) ==================== -->
<aside class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="menuLateral" aria-label="Menú principal">
  <div class="offcanvas-header d-lg-none">
    <button type="button" class="btn-close btn-close-white ms-auto"
            data-bs-dismiss="offcanvas" data-bs-target="#menuLateral" aria-label="Cerrar menú"></button>
  </div>

  <div class="offcanvas-body">
    <div class="sidebar-brand">
      <div class="brand-icon"><img src="CSS/Imagenes/Logo.png" alt="Logo panadería"></div>
      <div class="brand-text">
        <span class="brand-name">Panadería Normal 10</span>
        <span class="brand-sub">Gestión de Panadería</span>
      </div>
    </div>

    <nav class="nav flex-column nav-links" aria-label="Secciones del sistema">
      <?php foreach ($items_del_menu as $clave => $item): ?>
        <?php if (!in_array($rol_del_usuario, $item['roles'], true)) continue; ?>
        <?php $activo = ($pagina_actual === $clave); ?>
        <a class="nav-link<?= $activo ? ' active' : '' ?>" href="<?= htmlspecialchars($item['enlace'], ENT_QUOTES, 'UTF-8') ?>"
           <?= $activo ? 'aria-current="page"' : '' ?>>
          <?= htmlspecialchars($item['etiqueta'], ENT_QUOTES, 'UTF-8') ?>
        </a>
      <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
      <span>Escuela Normal N°10 · 2026</span>
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </div>
</aside>
