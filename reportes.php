<?php
// ============================================================
// reportes.php — Historial de consumos, ingresos, producción y ventas
// ============================================================

$pagina_actual = 'reportes';
session_start();
require_once 'conexion.php';
require_once 'auth.php';

// Filtro por mes (formato YYYY-MM)
$mes_seleccionado = '';
$filtro_produccion = '';
$filtro_compras    = '';
$filtro_ventas     = '';

if (!empty($_GET['mes']) && preg_match('/^\d{4}-\d{2}$/', $_GET['mes'])) {
    $mes_seleccionado  = $_GET['mes'];
    $filtro_produccion = "WHERE DATE_FORMAT(p.fecha, '%Y-%m') = '$mes_seleccionado'";
    $filtro_compras    = "WHERE DATE_FORMAT(c.fecha, '%Y-%m') = '$mes_seleccionado'";
    $filtro_ventas     = "WHERE DATE_FORMAT(v.fecha, '%Y-%m') = '$mes_seleccionado'";
}

// Función auxiliar para formatear cantidades (limpia ceros y usa coma decimal)
function formatearCantidad($numero) {
    return str_replace('.', ',', rtrim(rtrim($numero, '0'), '.')) ?: '0';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Reportes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
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
    <header class="page-header">
      <div>
        <h1 class="page-title">Reportes de <em>Consumo</em></h1>
      </div>
      <form method="GET" action="reportes.php" class="filtro-top">
        <label>Mes del reporte:</label>
        <input type="month" name="mes" value="<?= htmlspecialchars($mes_seleccionado) ?>">
        <button type="submit" class="btn-filtrar">Filtrar</button>
        <?php if ($mes_seleccionado): ?>
          <a href="reportes.php" class="btn-limpiar">Ver todo</a>
        <?php endif; ?>
      </form>
      
      <div>
        <a href="PHP/PDF/reportes_pdf.php" class="btn-pdf"  target="_blank">Imprimir Reportes</a>
      </div>
    </header>

    <?php
    $sufijo_vacio = $mes_seleccionado ? ' para este mes' : '';

    $consumos_insumos = mysqli_query($conexion, "
        SELECT p.fecha, i.nombre AS nombre_insumo,
               p.cantidad * r.cantidad AS cantidad_consumida, u.abreviatura
        FROM produccion p
        INNER JOIN receta r              ON p.id_producto = r.id_producto
        INNER JOIN insumos i             ON r.id_insumo = i.idinsumos
        INNER JOIN unidad_de_medida u    ON i.idunidad_de_medida = u.idunidad_de_medida
        $filtro_produccion
        ORDER BY p.fecha DESC
    ");
    ?>

    <section class="card insumos-reporte" aria-label="Consumos de insumos">
      <header class="card-header"><h2>Insumos: Consumos</h2></header>

      <?php if (mysqli_num_rows($consumos_insumos) === 0): ?>
        <p class="sin-datos">No hay consumos registrados<?= $sufijo_vacio ?>.</p>
      <?php else: ?>

        <table id="tabla-consumos" class="display" style="width:100%">

          <thead>
            <tr>
              <th>Fecha</th>
              <th>Insumo</th>
              <th>Cantidad consumida</th>
            </tr>
          </thead>

          <tbody>

            <?php while ($fila = mysqli_fetch_assoc($consumos_insumos)): ?>

              <tr>
                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                <td><?= formatearCantidad($fila['cantidad_consumida']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
              </tr>

            <?php endwhile; ?>

          </tbody>
        </table>

      <?php endif; ?>

    </section>

    <?php
    $ingresos_insumos = mysqli_query($conexion, "
        SELECT c.fecha, i.nombre AS nombre_insumo, c.cantidad, u.abreviatura
        FROM compras_insumos c
        INNER JOIN insumos i          ON c.id_insumos = i.idinsumos
        INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
        $filtro_compras
        ORDER BY c.fecha DESC
    ");
    ?>

    <section class="card insumos-reporte" aria-label="Ingresos de insumos">
      <header class="card-header"><h2>Insumos: Ingresos</h2></header>


      <?php if (mysqli_num_rows($ingresos_insumos) === 0): ?>
        <p class="sin-datos">No hay ingresos registrados<?= $sufijo_vacio ?>.</p>
      <?php else: ?>

        <table id="tabla-ingresos" class="display" style="width:100%">

          <thead>
            <tr>
              <th>Fecha</th>
              <th>Insumo</th>
              <th>Cantidad ingresada</th>
            </tr>
          </thead>

          <tbody>
            <?php while ($fila = mysqli_fetch_assoc($ingresos_insumos)): ?>

              <tr>
                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                <td><?= formatearCantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
              </tr>

            <?php endwhile; ?>

          </tbody>
        </table>

      <?php endif; ?>

    </section>

    <?php
    $produccion_registrada = mysqli_query($conexion, "
        SELECT p.fecha, pr.nombre AS nombre_producto, p.cantidad, u.abreviatura
        FROM produccion p
        INNER JOIN productos pr       ON p.id_producto = pr.idproductos
        INNER JOIN unidad_de_medida u ON pr.idunidad_de_medida = u.idunidad_de_medida
        $filtro_produccion
        ORDER BY p.fecha DESC
    ");
    ?>

    <section class="card insumos-reporte" aria-label="Producción registrada">
      <header class="card-header"><h2>Productos: Producción</h2></header>

      <?php if (mysqli_num_rows($produccion_registrada) === 0): ?>
        <p class="sin-datos">No hay producciones registradas<?= $sufijo_vacio ?>.</p>
      <?php else: ?>

        <table id="tabla-produccion" class="display" style="width:100%">

          <thead>
            <tr>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Cantidad Producida</th>
            </tr>
          </thead>

          <tbody>

            <?php while ($fila = mysqli_fetch_assoc($produccion_registrada)): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                <td><?= formatearCantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
              </tr>
            <?php endwhile; ?>

          </tbody>
        </table>

      <?php endif; ?>

    </section>

    <?php
    $ventas_registradas = mysqli_query($conexion, "
        SELECT v.fecha, pr.nombre AS nombre_producto,
               dv.cantidad, dv.precio_unitario, dv.subtotal, u.abreviatura
        FROM detalle_venta dv
        INNER JOIN ventas v            ON dv.id_venta = v.idventas
        INNER JOIN productos pr        ON dv.id_producto = pr.idproductos
        INNER JOIN unidad_de_medida u  ON pr.idunidad_de_medida = u.idunidad_de_medida
        $filtro_ventas
        ORDER BY v.fecha DESC
    ");
    ?>

    <section class="card insumos-reporte" aria-label="Ventas registradas">
      <header class="card-header"><h2>Productos: Ventas</h2></header>

      <?php if (mysqli_num_rows($ventas_registradas) === 0): ?>
        <p class="sin-datos">No hay producciones registradas<?= $sufijo_vacio ?>.</p>
      <?php else: ?>

        <table id="tabla-ventas" class="display" style="width:100%">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Cantidad vendida</th>
              <th>Precio Unitario</th>
              <th>Subtotal</th>
            </tr>
          </thead>

          <tbody>
            <?php while ($fila = mysqli_fetch_assoc($ventas_registradas)): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                <td><?= formatearCantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                <td>$<?= number_format($fila['precio_unitario'], 2, ',', '.') ?></td>
                <td>$<?= number_format($fila['subtotal'], 2, ',', '.') ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.DataTable) {
        new DataTable('#tabla-consumos', {
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
        new DataTable('#tabla-ingresos', {
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
        new DataTable('#tabla-produccion', {
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
        new DataTable('#tabla-ventas', {
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
