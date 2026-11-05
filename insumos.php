<?php
// ============================================================
// insumos.php — Entrada de insumos
// ============================================================

// ==================== Sesión, permisos y dependencias ====================
$pagina_actual = 'insumos';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/utilidades.php';

if (empty($_SESSION['token_compra'])) {
    $_SESSION['token_compra'] = bin2hex(random_bytes(32));
}
$token_compra = $_SESSION['token_compra'];

// ==================== Mensaje de operación exitosa ====================
$mensaje_compra = $_SESSION['compra_ok'] ?? '';
unset($_SESSION['compra_ok']);

// ==================== Consulta de insumos y existencias ====================
$lista_insumos = mysqli_query($conexion, "
    SELECT i.idinsumos,
           i.nombre,
           u.abreviatura,
           COALESCE(s.cantidad, 0) AS stock_actual,
           COALESCE(s.stock_minimo, 10) AS stock_minimo
    FROM insumos i
    INNER JOIN unidad_de_medida u ON u.idunidad_de_medida = i.idunidad_de_medida
    LEFT JOIN stock_insumos s ON s.id_insumo = i.idinsumos
    ORDER BY i.nombre
");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Materias Primas</title>
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
      <h1 class="page-title">Materias <em>Primas</em></h1>
      <p class="page-subtitle">Registrá las compras de insumos y dá de alta nuevas materias primas.</p>
    </header>

    <!-- ==================== Formulario de entrada de insumos ==================== -->
    <section class="card mb-4" aria-label="Registrar entrada de insumos">
      <div class="card-header">
        <h2>Registrar Entrada de Insumos</h2>
      </div>
      <div class="card-body">
        <div id="notif-compra" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-compra" method="POST" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token_compra, ENT_QUOTES, 'UTF-8') ?>"/>
          <input type="hidden" name="fecha_entrada" value="<?= date('Y-m-d') ?>"/>

          <!-- ==================== Tabla de insumos y cantidades a ingresar ==================== -->
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th>Insumo</th>
                  <th>Stock actual</th>
                  <th class="text-end" style="width: 180px;">Cantidad</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($insumo = mysqli_fetch_assoc($lista_insumos)): ?>
                  <?php $stock = (float) $insumo['stock_actual']; ?>
                  <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($insumo['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-muted">
                      <?= formatear_cantidad($stock) ?> <?= htmlspecialchars($insumo['abreviatura'], ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    <td>
                      <input type="hidden" name="id_insumo[]" value="<?= (int) $insumo['idinsumos'] ?>">
                      <input type="number" name="cantidad[]" step="0.1" min="0" value="0"
                             class="form-control text-end"
                             aria-label="Cantidad a ingresar de <?= htmlspecialchars($insumo['nombre'], ENT_QUOTES, 'UTF-8') ?>">
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

          <div class="d-flex justify-content-end mt-3">
            <button type="submit" class="btn btn-primary">Registrar Entrada</button>
          </div>
        </form>
      </div>
    </section>

    <!-- ==================== Formulario para añadir un insumo ==================== -->
    <section class="card form-card" aria-label="Añadir nuevo insumo">
      <div class="card-header"><h2>Añadir Nuevo Insumo</h2></div>
      <div class="card-body">
        <div id="notif-insumo" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-insumo" method="POST">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="nombre_insumo" class="form-label">Nombre del Insumo</label>
              <input type="text" class="form-control" id="nombre_insumo" name="nombre_insumo" placeholder="Ej: Harina 000" required/>
            </div>

            <div class="col-md-6">
              <label for="idunidad" class="form-label">Unidad de Medida</label>
              <select class="form-select" id="idunidad" name="idunidad" required>
                <option value="">— Seleccioná una unidad —</option>
                <?php
                $lista_unidades = mysqli_query($conexion, "SELECT idunidad_de_medida, nombre FROM unidad_de_medida");
                while ($unidad = mysqli_fetch_assoc($lista_unidades)):
                ?>
                  <option value="<?= (int) $unidad['idunidad_de_medida'] ?>"><?= htmlspecialchars($unidad['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary">Añadir Insumo</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      enviarFormularioAjax(document.getElementById('form-compra'), 'PHP/registrar_compra.php', {
        recargar: true
      });
      enviarFormularioAjax(document.getElementById('form-insumo'), 'PHP/registrar_insumo.php', {
        reset: true
      });
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
