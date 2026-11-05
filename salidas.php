<?php
// ============================================================
// salidas.php — Registro y listado de salidas de productos
// ============================================================

$pagina_actual = 'salidas';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/utilidades.php';

$motivos_salida = [
    'Venta mostrador',
    'Consumo interno',
    'Merma/vencido',
    'Rotura',
    'Donación',
    'Otro',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Salidas de Productos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.3.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="JS/AJAX.js"></script>
</head>

<body>

  <?php include 'sidebar.php'; ?>

  <main class="main-content">
    <!-- ==================== Encabezado de la página ==================== -->
    <header class="page-header">
      <h1 class="page-title">Salidas de <em>Productos</em></h1>
      <p class="page-subtitle">Registrá las salidas del depósito y el sistema descuenta el stock.</p>
    </header>

    <!-- ==================== Formulario de salida de productos ==================== -->
    <section class="card" aria-label="Registrar salida de productos">
      <div class="card-header">
        <h2>Nueva Salida</h2>
      </div>
      <div class="card-body">
        <div id="notif-salida" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-salida" method="POST">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th class="text-center">Stock disponible</th>
                  <th style="width: 180px;">Cantidad a retirar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $lista_productos = mysqli_query($conexion, "
                    SELECT p.idproductos, p.nombre AS nombre_producto, u.abreviatura,
                           COALESCE(s.cantidad, 0) AS stock_disponible
                    FROM productos p
                    INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
                    LEFT  JOIN stock_productos   s ON s.id_producto = p.idproductos
                    ORDER BY p.nombre
                ");

                while ($producto = mysqli_fetch_assoc($lista_productos)):
                    $sin_stock        = $producto['stock_disponible'] <= 0;
                    $stock_formateado = formatear_cantidad($producto['stock_disponible']);
                    $solo_lectura     = $sin_stock ? 'readonly' : '';
                    $clase_fila       = $sin_stock ? 'fila-sin-stock' : '';
                ?>
                  <tr class="<?= $clase_fila ?>">
                    <td class="fw-semibold"><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                    <td class="text-center">
                      <?= $stock_formateado ?> <?= htmlspecialchars($producto['abreviatura']) ?>
                    </td>
                    <td>
                      <input type="hidden" name="id_producto[]" value="<?= (int) $producto['idproductos'] ?>">
                      <input type="number" name="cantidad[]" step="0.1" min="0"
                             max="<?= $producto['stock_disponible'] ?>" value="0"
                             class="form-control text-end" <?= $solo_lectura ?>
                             aria-label="Cantidad a retirar de <?= htmlspecialchars($producto['nombre_producto']) ?>">
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label for="motivo" class="form-label">Motivo</label>
              <select class="form-select" id="motivo" name="motivo" required>
                <option value="">— Seleccioná un motivo —</option>
                <?php foreach ($motivos_salida as $motivo): ?>
                  <option value="<?= htmlspecialchars($motivo) ?>"><?= htmlspecialchars($motivo) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label for="observacion" class="form-label">Observación (opcional)</label>
              <input type="text" class="form-control" id="observacion" name="observacion" maxlength="255"
                     placeholder="Detalle de la salida">
            </div>

            <div class="col-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Registrar Salida</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      enviarFormularioAjax(document.getElementById('form-salida'), 'PHP/registrar_salida.php', {
        recargar: true
      });
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
