<?php
// ============================================================
// nuevo_producto.php — Formulario para registrar un producto nuevo
// ============================================================

$pagina_actual = 'nuevo_producto';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Añadir Producto</title>
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
    <!-- ==================== Encabezado y navegación de regreso ==================== -->
    <header class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
      <div>
        <h1 class="page-title">Nuevo Producto</h1>
        <p class="page-subtitle">Definí el nombre y la unidad de medida del producto.</p>
      </div>
      <a href="productos.php" class="btn btn-outline-secondary">Volver</a>
    </header>

    <!-- ==================== Formulario de producto ==================== -->
    <section class="card form-card">
      <div class="card-header"><h2>Datos del Producto</h2></div>
      <div class="card-body">
        <div id="notif-producto" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-nuevo-producto" method="POST">
          <div class="row g-3">
            <div class="col-12">
              <label for="nombre" class="form-label">Nombre del producto</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="col-12">
              <label for="idunidad_de_medida" class="form-label">Unidad de medida</label>
              <select class="form-select" id="idunidad_de_medida" name="idunidad_de_medida" required>
                <option value="">Seleccionar</option>
                <?php
                $lista_unidades = mysqli_query($conexion, "SELECT idunidad_de_medida, nombre FROM unidad_de_medida");
                while ($unidad = mysqli_fetch_assoc($lista_unidades)):
                ?>
                  <option value="<?= (int) $unidad['idunidad_de_medida'] ?>"><?= htmlspecialchars($unidad['nombre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary">Guardar Producto</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      enviarFormularioAjax(document.getElementById('form-nuevo-producto'), 'PHP/registrar_producto.php', {
        reset: true
      });
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
