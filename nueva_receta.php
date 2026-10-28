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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="JS/datatables_config.js" defer></script>
  <script src="JS/AJAX.js"></script>
</head>
<body>

  <nav class="sidebar">
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
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </nav>

  <main class="main-content">
    <!-- ==================== Encabezado y navegación de regreso ==================== -->
    <header class="page-header">
      <h1 class="page-title">Nueva Receta</h1>
      <div class="acciones-productos">
      <a href="productos.php" class="btn-primary">Volver</a>
      </div>
    </header>


    <!-- ==================== Formulario de receta ==================== -->
    <section class="card form-card">
      <div class="card-header"><h2>Agregar Insumos a Receta</h2></div>

      <div id="notif-receta" class="notif" role="status"></div>

      <form id="form-receta" method="POST">
        <div class="form-group">
          <label>Producto</label>
          <select name="id_producto" required>
            <option value="">Seleccionar producto</option>
            <?php
            $lista_productos = mysqli_query($conexion, "SELECT idproductos, nombre FROM productos ORDER BY nombre");
            while ($producto = mysqli_fetch_assoc($lista_productos)):
            ?>
              <option value="<?= (int) $producto['idproductos'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group">
          <label>¿Cuántos ingredientes lleva esta receta?</label>
          <div class="generador-receta">
            <input type="number" id="cantidad-ingredientes" min="1" max="30" value="1" class="input-cantidad">
            <button type="button" class="btn-primary" id="generar-filas">Generar filas</button>
          </div>
        </div>

        <div id="contenedor-ingredientes" class="lista-receta"></div>

        <div class="form-full" style="margin-top: 20px;">
          <button type="submit" class="btn-primary" id="guardar-receta" style="display: none;">Guardar receta completa</button>
        </div>
      </form>
    </section>
  </main>

  <!-- ==================== Envío AJAX de la receta ==================== -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btnGenerar = document.getElementById('generar-filas');
      const inputCantidad = document.getElementById('cantidad-ingredientes');
      const contenedor = document.getElementById('contenedor-ingredientes');
      const btnGuardar = document.getElementById('guardar-receta');

      btnGenerar.addEventListener('click', function () {
        contenedor.innerHTML = '';
        const cantidad = parseInt(inputCantidad.value);

        if (isNaN(cantidad) || cantidad < 1) {
          alert('Ingresá una cantidad válida.');
          return;
        }

        for (let i = 0; i < cantidad; i++) {
          const fila = document.createElement('div');
          fila.className = 'fila-receta';

          fila.innerHTML = `
            <div class="form-group">
              <select name="id_insumo[]" required>
                <option value="">Seleccionar insumo</option>
                <?php
                $lista_insumos = mysqli_query($conexion, "SELECT idinsumos, nombre FROM insumos ORDER BY nombre");
                while ($insumo = mysqli_fetch_assoc($lista_insumos)):
                ?>
                  <option value="<?= (int) $insumo['idinsumos'] ?>"><?= htmlspecialchars($insumo['nombre']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <input type="number" name="cantidad[]" step="0.0001" min="0.0001" placeholder="Cantidad" class="input-cantidad" required>
            </div>
          `;
          contenedor.appendChild(fila);
        }

        if (cantidad > 0) {
          btnGuardar.style.display = 'inline-block';
        }
      });

      enviarFormularioAjax(document.getElementById('form-receta'), 'PHP/registrar_receta.php', {
        onSuccess: function (datos, formulario) {
          formulario.reset();
          contenedor.innerHTML = '';
          btnGuardar.style.display = 'none';
          inputCantidad.value = 1;
        }
      });
    });
  </script>

  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
