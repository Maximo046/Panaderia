<?php
// ============================================================
// nueva_receta.php — Asociar insumos a un producto (receta)
// ============================================================

$pagina_actual = 'nueva_receta';
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
  <title>Panadería - Añadir Receta</title>
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
        <h1 class="page-title">Nueva Receta</h1>
        <p class="page-subtitle">Asociá los insumos y cantidades que requiere un producto.</p>
      </div>
      <a href="productos.php" class="btn btn-outline-secondary">Volver</a>
    </header>

    <!-- ==================== Formulario de receta ==================== -->
    <section class="card form-card">
      <div class="card-header"><h2>Agregar Insumos a Receta</h2></div>
      <div class="card-body">
        <div id="notif-receta" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-receta" method="POST">
          <div class="row g-3">
            <div class="col-12">
              <label for="id_producto" class="form-label">Producto</label>
              <select class="form-select" id="id_producto" name="id_producto" required>
                <option value="">Seleccionar producto</option>
                <?php
                $lista_productos = mysqli_query($conexion, "SELECT idproductos, nombre FROM productos ORDER BY nombre");
                while ($producto = mysqli_fetch_assoc($lista_productos)):
                ?>
                  <option value="<?= (int) $producto['idproductos'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>

            <div class="col-12">
              <label for="cantidad-ingredientes" class="form-label">¿Cuántos ingredientes lleva esta receta?</label>
              <div class="d-flex flex-wrap gap-2">
                <input type="number" id="cantidad-ingredientes" min="1" max="30" value="1"
                       class="form-control" style="max-width: 120px;">
                <button type="button" class="btn btn-outline-secondary" id="generar-filas">Generar filas</button>
              </div>
            </div>

            <div class="col-12">
              <div id="contenedor-ingredientes" class="d-flex flex-column gap-2"></div>
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary d-none" id="guardar-receta">Guardar receta completa</button>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btnGenerar = document.getElementById('generar-filas');
      const inputCantidad = document.getElementById('cantidad-ingredientes');
      const contenedor = document.getElementById('contenedor-ingredientes');
      const btnGuardar = document.getElementById('guardar-receta');

      btnGenerar.addEventListener('click', function () {
        contenedor.innerHTML = '';
        const cantidad = parseInt(inputCantidad.value, 10);

        if (isNaN(cantidad) || cantidad < 1) {
          alert('Ingresá una cantidad válida.');
          return;
        }

        for (let i = 0; i < cantidad; i++) {
          const fila = document.createElement('div');
          fila.className = 'row g-2 align-items-center';

          fila.innerHTML = `
            <div class="col-12 col-md-7">
              <select name="id_insumo[]" class="form-select" required>
                <option value="">Seleccionar insumo</option>
                <?php
                $lista_insumos = mysqli_query($conexion, "SELECT idinsumos, nombre FROM insumos ORDER BY nombre");
                while ($insumo = mysqli_fetch_assoc($lista_insumos)):
                ?>
                  <option value="<?= (int) $insumo['idinsumos'] ?>"><?= htmlspecialchars($insumo['nombre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-12 col-md-5">
              <input type="number" name="cantidad[]" step="0.0001" min="0.0001"
                     placeholder="Cantidad" class="form-control" required>
            </div>
          `;
          contenedor.appendChild(fila);
        }

        if (cantidad > 0) {
          btnGuardar.classList.remove('d-none');
        }
      });

      enviarFormularioAjax(document.getElementById('form-receta'), 'PHP/registrar_receta.php', {
        onSuccess: function (datos, formulario) {
          formulario.reset();
          contenedor.innerHTML = '';
          btnGuardar.classList.add('d-none');
          inputCantidad.value = 1;
        }
      });
    });
  </script>

  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
