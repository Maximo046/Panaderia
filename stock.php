<?php
// ============================================================
// stock.php — Panel principal con stock de insumos y productos
// ============================================================

$pagina_actual = 'stock';

session_start();

require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/alerta_stock.php';
require_once 'PHP/utilidades.php';

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
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
  <script src="JS/app.js" defer></script>
</head>
<body>
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
      <div>
        <?php
        $hora_actual  = (int) date('G');
        $saludo       = $hora_actual < 12 ? 'Buenos días' : ($hora_actual < 20 ? 'Buenas tardes' : 'Buenas noches');
        $nombre_sesion = $_SESSION['usuario_nombre'] ?? 'Panadero';
        ?>
        <h1 class="page-title"><?= htmlspecialchars($saludo) ?>, <em><?= htmlspecialchars($nombre_sesion) ?></em></h1>
      </div>
      <div class="header-badge">
        <span class="badge-dot"></span> Sistema activo
      </div>
    </header>



    <div class="bottom-grid">
      <section class="stock-panel" aria-label="Stock de insumos y productos">
          <?php
          $stock_insumos = mysqli_query($conexion, "
              SELECT i.nombre AS nombre_insumo,
                     COALESCE(s.cantidad, 0) AS cantidad,
                     u.abreviatura
              FROM insumos i
              INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
              LEFT  JOIN stock_insumos s    ON s.id_insumo = i.idinsumos
              ORDER BY i.nombre
          ");
          ?>

          <section class="card" aria-label="Stock de insumos">
            <header class="card-header"><h2>Insumos: Stock</h2></header>

            <?php if (mysqli_num_rows($stock_insumos) === 0): ?>
              <p class="sin-datos">No hay insumos registrados.</p>
            <?php else: ?>

              <table id="tabla-stock-insumos" class="display">

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
                      <td><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
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

          <section class="card" aria-label="Stock de productos">
            <header class="card-header"><h2>Productos: Stock</h2></header>

            <?php if (mysqli_num_rows($stock_productos) === 0): ?>
              <p class="sin-datos">No hay productos registrados.</p>
            <?php else: ?>

              <table id="tabla-stock-productos" class="display">

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
                      <td><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    </tr>

                  <?php endwhile; ?>

                </tbody>
              </table>

            <?php endif; ?>

          </section>



      </section>

      <aside class="side-panel" aria-label="Alertas de stock">
        <section class="card">
          <header class="card-header"><h2>Alertas de Stock de Insumos</h2></header>
          <ul class="alertas-list">
            <?php
            $alertas_stock = mysqli_query($conexion, "
                SELECT i.nombre AS nombre_insumo,
                       COALESCE(s.cantidad, 0) AS cantidad,
                       COALESCE(s.stock_minimo, 10) AS stock_minimo
                FROM insumos i
                LEFT  JOIN stock_insumos s ON s.id_insumo = i.idinsumos
                WHERE COALESCE(s.cantidad, 0) < COALESCE(s.stock_minimo, 10)
            ");
            $hay_alertas = false;
            while ($alerta = mysqli_fetch_assoc($alertas_stock)):
                $hay_alertas = true;
                $cantidad_actual = formatear_cantidad($alerta['cantidad']);
                $cantidad_minima = formatear_cantidad($alerta['stock_minimo']);
            ?>
              <li><?= htmlspecialchars($alerta['nombre_insumo']) ?>: <?= $cantidad_actual ?> (mínimo <?= $cantidad_minima ?>)</li>
            <?php endwhile; ?>
            <?php if (!$hay_alertas): ?>
              <li class="alerta-ok">Sin alertas de stock</li>
            <?php endif; ?>
          </ul>
        </section>

        <section class="card">
          <header class="card-header"><h2>Alertas de Stock de Productos</h2></header>
          <ul class="alertas-list">
            <?php
            $alertas_stock = mysqli_query($conexion, "
                SELECT p.nombre AS nombre_producto,
                       COALESCE(s.cantidad, 0) AS cantidad,
                       COALESCE(s.stock_minimo, 10) AS stock_minimo
                FROM productos p
                LEFT  JOIN stock_productos s ON s.id_producto = p.idproductos
                WHERE COALESCE(s.cantidad, 0) < COALESCE(s.stock_minimo, 10)
            ");
            $hay_alertas = false;
            while ($alerta = mysqli_fetch_assoc($alertas_stock)):
                $hay_alertas = true;
                $cantidad_actual = formatear_cantidad($alerta['cantidad']);
                $cantidad_minima = formatear_cantidad($alerta['stock_minimo']);
            ?>
              <li><?= htmlspecialchars($alerta['nombre_producto']) ?>: <?= $cantidad_actual ?> (mínimo <?= $cantidad_minima ?>)</li>
            <?php endwhile; ?>
            <?php if (!$hay_alertas): ?>
              <li class="alerta-ok">Sin alertas de stock</li>
            <?php endif; ?>
          </ul>
        </section>
      </aside>
    </div>
  </main>



  <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.iniciarTabla('#tabla-stock-insumos');
      window.iniciarTabla('#tabla-stock-productos');
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
