<?php
// ============================================================
// reportes.php — Historial de consumos, ingresos, producción y salidas
// ============================================================

$pagina_actual = 'reportes';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/utilidades.php';

// ==================== Estado del filtro por período ====================
$mes_seleccionado = '';
$filtro_produccion = '';
$filtro_compras    = '';
$filtro_salidas    = '';

$mes_solicitado = $_GET['mes'] ?? '';

if (is_string($mes_solicitado) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes_solicitado)) {
    $mes_seleccionado  = $mes_solicitado;
    $filtro_produccion = "WHERE DATE_FORMAT(p.fecha, '%Y-%m') = '$mes_seleccionado'";
    $filtro_compras    = "WHERE DATE_FORMAT(c.fecha, '%Y-%m') = '$mes_seleccionado'";
    $filtro_salidas    = "WHERE DATE_FORMAT(s.fecha, '%Y-%m') = '$mes_seleccionado'";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Reportes</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.3.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.bootstrap5.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
  <script src="Libreria/DataTables/datatables.bootstrap5.min.js" defer></script>
  <script src="JS/datatables_config.js" defer></script>
</head>
<body>

  <?php include 'sidebar.php'; ?>

  <main class="main-content">
    <!-- ==================== Encabezado, filtro y exportación PDF ==================== -->
    <header class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
      <div>
        <h1 class="page-title">Reportes de <em>Movimientos</em></h1>
        <p class="page-subtitle">Consultá el historial y filtrá por mes.</p>
      </div>

      <div class="d-flex flex-wrap align-items-end gap-2">
        <form method="GET" action="reportes.php" class="d-flex flex-wrap align-items-end gap-2">
          <div>
            <label for="mes" class="form-label">Mes del reporte</label>
            <input type="month" class="form-control" id="mes" name="mes" value="<?= htmlspecialchars($mes_seleccionado) ?>">
          </div>
          <button type="submit" class="btn btn-primary">Filtrar</button>
          <?php if ($mes_seleccionado): ?>
            <a href="reportes.php" class="btn btn-outline-secondary">Ver todo</a>
          <?php endif; ?>
        </form>

        <a href="PHP/PDF/reportes_pdf.php<?= $mes_seleccionado ? '?mes=' . urlencode($mes_seleccionado) : '' ?>"
           class="btn btn-outline-primary" target="_blank" rel="noopener">Imprimir Reportes</a>
      </div>
    </header>

    <?php $sufijo_vacio = $mes_seleccionado ? ' para este mes' : ''; ?>

    <!-- ==================== Consumos de insumos ==================== -->
    <?php
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

    <section class="card mb-4" aria-label="Consumos de insumos">
      <div class="card-header"><h2>Insumos: Consumos</h2></div>
      <div class="card-body">
        <?php if (mysqli_num_rows($consumos_insumos) === 0): ?>
          <p class="sin-datos">No hay consumos registrados<?= $sufijo_vacio ?>.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table id="tabla-consumos" class="table table-striped table-hover align-middle mb-0">
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
                    <td><?= formatear_cantidad($fila['cantidad_consumida']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- ==================== Ingresos de insumos ==================== -->
    <?php
    $ingresos_insumos = mysqli_query($conexion, "
        SELECT c.fecha, i.nombre AS nombre_insumo, c.cantidad, u.abreviatura
        FROM entrada_insumos c
        INNER JOIN insumos i          ON c.id_insumos = i.idinsumos
        INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
        $filtro_compras
        ORDER BY c.fecha DESC, c.idcompras_insumos DESC
    ");
    ?>

    <section class="card mb-4" aria-label="Ingresos de insumos">
      <div class="card-header"><h2>Insumos: Ingresos</h2></div>
      <div class="card-body">
        <?php if (mysqli_num_rows($ingresos_insumos) === 0): ?>
          <p class="sin-datos">No hay ingresos registrados<?= $sufijo_vacio ?>.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table id="tabla-ingresos" class="table table-striped table-hover align-middle mb-0">
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
                    <td><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- ==================== Producción registrada ==================== -->
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

    <section class="card mb-4" aria-label="Producción registrada">
      <div class="card-header"><h2>Productos: Producción</h2></div>
      <div class="card-body">
        <?php if (mysqli_num_rows($produccion_registrada) === 0): ?>
          <p class="sin-datos">No hay producciones registradas<?= $sufijo_vacio ?>.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table id="tabla-produccion" class="table table-striped table-hover align-middle mb-0">
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
                    <td><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- ==================== Salidas de productos ==================== -->
    <?php
    $salidas_registradas = mysqli_query($conexion, "
        SELECT s.fecha, pr.nombre AS nombre_producto, s.cantidad,
               s.motivo, s.observacion, u.abreviatura,
               CONCAT_WS(' ', us.nombre, us.apellido) AS nombre_usuario
        FROM salidas_productos s
        INNER JOIN productos pr        ON s.id_producto = pr.idproductos
        INNER JOIN unidad_de_medida u  ON pr.idunidad_de_medida = u.idunidad_de_medida
        LEFT  JOIN usuarios us         ON s.id_usuario = us.idusuarios
        $filtro_salidas
        ORDER BY s.fecha DESC
    ");
    ?>

    <section class="card" aria-label="Salidas registradas">
      <div class="card-header"><h2>Productos: Salidas</h2></div>
      <div class="card-body">
        <?php if (mysqli_num_rows($salidas_registradas) === 0): ?>
          <p class="sin-datos">No hay salidas registradas<?= $sufijo_vacio ?>.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table id="tabla-salidas" class="table table-striped table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Motivo</th>
                  <th>Observación</th>
                  <th>Registró</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($fila = mysqli_fetch_assoc($salidas_registradas)): ?>
                  <tr>
                    <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                    <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                    <td><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    <td><?= htmlspecialchars($fila['motivo']) ?></td>
                    <td><?= $fila['observacion'] !== null && $fila['observacion'] !== '' ? htmlspecialchars($fila['observacion']) : '—' ?></td>
                    <td><?= htmlspecialchars($fila['nombre_usuario'] ?: '—') ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof window.iniciarTabla === 'function') {
        window.iniciarTabla('#tabla-consumos');
        window.iniciarTabla('#tabla-ingresos');
        window.iniciarTabla('#tabla-produccion');
        window.iniciarTabla('#tabla-salidas');
      }
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
