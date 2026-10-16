<?php
// ============================================================
// stock.php — Panel principal con stock de insumos y productos
// ============================================================

$pagina_actual = 'stock';

session_start();

require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/alerta_stock.php';

enviar_alerta_stock($conexion);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Panel Principal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
</head>
<body class="con-sidebar">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon"><img src="CSS/Imagenes/Logo.png" alt="Logo panadería"></div>
      <div class="brand-text">
        <span class="brand-name">Panadería Normal 10</span>
        <span class="brand-sub">Gestión de Panadería</span>
      </div>
    </div>
    <ul class="nav-links">
      <?php include 'sidebar.php'; ?>
    </ul>
    <div class="sidebar-footer">
      <span>Escuela Normal N°10 · 2026</span>
      <?php include 'admin_registro_link.php'; ?>
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </aside>

  <main class="main-content">
    <header class="page-header page-header-stock">
      <div class="header-left">
        <h1 class="page-title">Buenos días, <em>Panadero</em></h1>
      </div>
      <div class="header-badge">
        <span class="badge-dot"></span> Sistema activo
      </div>
    </header>



    <div class="bottom-grid">
      <section class="card" aria-label="Stock de insumos y productos">
          <?php
          $stock_insumos = mysqli_query($conexion, "
              SELECT i.nombre AS nombre_insumo, s.cantidad, u.abreviatura
              FROM stock_insumos s
              INNER JOIN insumos i          ON s.id_insumo = i.idinsumos
              INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
              ORDER BY i.nombre
          ");
          ?>

          <section class="card insumos-reporte" aria-label="Stock de insumos">
            <header class="card-header"><h2>Insumos: Stock</h2></header>

            <?php if (mysqli_num_rows($stock_insumos) === 0): ?>
              <p class="sin-datos">No hay consumos registrados.</p>
            <?php else: ?>

              <table id="tabla-stock_productos" class="display" style="width:100%">

                <thead>
                  <tr>
                    <th>Insumo</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>

                <tbody>

                  <?php while ($fila = mysqli_fetch_assoc($stock_insumos)): ?>

                    <tr>
                      <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                      <td><?= htmlspecialchars($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    </tr>

                  <?php endwhile; ?>

                </tbody>
              </table>

            <?php endif; ?>

          </section>

          <?php
          $stock_productos = mysqli_query($conexion, "
              SELECT p.nombre AS nombre_producto, u.abreviatura,
                    COALESCE(s.cantidad, 0) AS cantidad
              FROM productos p
              INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
              LEFT  JOIN stock_productos   s ON s.id_producto = p.idproductos
              ORDER BY p.nombre
          ");
          ?>

          <section class="card insumos-reporte" aria-label="Stock de productos">
            <header class="card-header"><h2>Productos: Stock</h2></header>

            <?php if (mysqli_num_rows($stock_productos) === 0): ?>
              <p class="sin-datos">No hay consumos registrados.</p>
            <?php else: ?>

              <table id="tabla-stock_productos" class="display" style="width:100%">

                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                  </tr>
                </thead>

                <tbody>

                  <?php while ($fila = mysqli_fetch_assoc($stock_productos)): ?>

                    <tr>
                      <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                      <td><?= htmlspecialchars($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    </tr>

                  <?php endwhile; ?>

                </tbody>
              </table>

            <?php endif; ?>

          </section>



      </section>

      <aside class="side-panel" aria-label="Alertas de stock">
        <section class="card alertas-card">
          <header class="card-header"><h2>Alertas de Stock de Insumos</h2></header>
          <ul class="alertas-list">
            <?php
            $alertas_stock = mysqli_query($conexion, "
                SELECT i.nombre AS nombre_insumo, s.cantidad, s.stock_minimo
                FROM stock_insumos s
                INNER JOIN insumos i ON s.id_insumo = i.idinsumos
                WHERE s.cantidad <= s.stock_minimo
            ");
            $hay_alertas = false;
            while ($alerta = mysqli_fetch_assoc($alertas_stock)):
                $hay_alertas = true;
                $cantidad_actual = str_replace('.', ',', rtrim(rtrim($alerta['cantidad'], '0'), '.'));
                $cantidad_minima = str_replace('.', ',', rtrim(rtrim($alerta['stock_minimo'], '0'), '.'));
            ?>
              <li><?= htmlspecialchars($alerta['nombre_insumo']) ?>: <?= $cantidad_actual ?> (mínimo <?= $cantidad_minima ?>)</li>
            <?php endwhile; ?>
            <?php if (!$hay_alertas): ?>
              <li style="color:var(--green);background:#eaf6e4;border-left-color:var(--green)">Sin alertas de stock</li>
            <?php endif; ?>
          </ul>
        </section>

        <section class="card alertas-card">
          <header class="card-header"><h2>Alertas de Stock de Productos</h2></header>
          <ul class="alertas-list">
            <?php
            $stock_productos_tiene_minimo = false;
            $resultado_campos = mysqli_query($conexion, "SHOW COLUMNS FROM stock_productos LIKE 'stock_minimo'");
            if ($resultado_campos && mysqli_num_rows($resultado_campos) > 0) {
                $stock_productos_tiene_minimo = true;
            }

            $alertas_stock = mysqli_query($conexion, $stock_productos_tiene_minimo ? "
                SELECT p.nombre AS nombre_producto, s.cantidad, s.stock_minimo
                FROM stock_productos s
                INNER JOIN productos p ON s.id_producto = p.idproductos
                WHERE s.cantidad <= s.stock_minimo
            " : "
                SELECT p.nombre AS nombre_producto, s.cantidad, 10 AS stock_minimo
                FROM stock_productos s
                INNER JOIN productos p ON s.id_producto = p.idproductos
                WHERE s.cantidad <= 10
            ");
            $hay_alertas = false;
            while ($alerta = mysqli_fetch_assoc($alertas_stock)):
                $hay_alertas = true;
                $cantidad_actual = str_replace('.', ',', rtrim(rtrim($alerta['cantidad'], '0'), '.'));
                $cantidad_minima = str_replace('.', ',', rtrim(rtrim($alerta['stock_minimo'], '0'), '.'));
            ?>
              <li><?= htmlspecialchars($alerta['nombre_producto']) ?>: <?= $cantidad_actual ?> (mínimo <?= $cantidad_minima ?>)</li>
            <?php endwhile; ?>
            <?php if (!$hay_alertas): ?>
              <li style="color:var(--green);background:#eaf6e4;border-left-color:var(--green)">Sin alertas de stock</li>
            <?php endif; ?>
          </ul>
        </section>
      </aside>
    </div>
  </main>



  <script>
      document.addEventListener('DOMContentLoaded', function () {
      if (window.DataTable) {
        new DataTable('#tabla-stock_insumos', {
          paging: true,
          searching: true,
          ordering: true,
          info: true,
          lengthChange: true,
          pageLength: 10,
          language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json'
          }
        });
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
      if (window.DataTable) {
        new DataTable('#tabla-stock_productos', {
          paging: true,
          searching: true,
          ordering: true,
          info: true,
          lengthChange: true,
          pageLength: 10,
          language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json'
          }
        });
      }
    });

  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
