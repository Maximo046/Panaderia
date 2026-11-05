<?php
// ============================================================
// produccion.php — Registro de producción de productos
// ============================================================

$pagina_actual = 'produccion';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/utilidades.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Producción</title>
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
    <!-- ==================== Encabezado de producción ==================== -->
    <header class="page-header">
      <h1 class="page-title">Producción</h1>
      <p class="page-subtitle">Registrá la cantidad elaborada y el sistema descuenta los insumos de la receta.</p>
    </header>

    <!-- ==================== Formularios por producto ==================== -->
    <section class="card" aria-label="Registro de producción">
      <div class="card-header"><h2>Producción</h2></div>
      <div class="card-body">
        <div id="notif-produccion" class="notif d-none" role="status" aria-live="polite"></div>

        <?php
        $lista_productos = mysqli_query($conexion, "
            SELECT p.idproductos, p.nombre AS nombre_producto, u.abreviatura,
                   COALESCE(sp.cantidad, 0) AS stock_actual
            FROM productos p
            INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
            LEFT JOIN stock_productos sp ON sp.id_producto = p.idproductos
            ORDER BY p.nombre
        ");
        ?>

        <?php if (mysqli_num_rows($lista_productos) === 0): ?>
          <p class="sin-datos">No hay productos registrados.</p>
        <?php else: ?>
          <div class="d-flex flex-column gap-2">
            <?php while ($producto = mysqli_fetch_assoc($lista_productos)): ?>
              <form class="form-produccion row g-2 align-items-center border-bottom py-2" method="POST">
                <div class="col-12 col-md-4">
                  <span class="fw-bold text-dark"><?= htmlspecialchars($producto['nombre_producto']) ?></span>
                </div>
                <div class="col-6 col-md-3 text-md-center text-muted small">
                  Stock: <?= formatear_cantidad($producto['stock_actual']) ?> <?= htmlspecialchars($producto['abreviatura']) ?>
                </div>
                <div class="col-6 col-md-2">
                  <input type="hidden" name="id_producto" value="<?= (int) $producto['idproductos'] ?>">
                  <input type="number" step="0.1" min="0.1" name="cantidad" class="form-control" required
                         aria-label="Cantidad a producir de <?= htmlspecialchars($producto['nombre_producto']) ?>">
                </div>
                <div class="col-3 col-md-1 text-muted small">
                  <?= htmlspecialchars($producto['abreviatura']) ?>
                </div>
                <div class="col-9 col-md-2 text-end">
                  <button type="submit" class="btn btn-primary btn-sm w-100">Producir</button>
                </div>
              </form>
            <?php endwhile; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.form-produccion').forEach(function (formulario) {
        enviarFormularioAjax(formulario, 'PHP/registrar_produccion.php', {
          onSuccess: function (datos, form) {
            var cant = form.querySelector('[name="cantidad"]');
            if (cant) cant.value = '';
            setTimeout(function () { window.location.reload(); }, 500);
          }
        });
      });
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
