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
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Añadir Receta</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="JS/app.js" defer></script>
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
    <header class="page-header">
      <h1 class="page-title">Nueva Receta</h1>
      <div class="acciones-productos">
      <a href="productos.php" class="btn-primary">Volver</a>
      </div>
    </header>



    <section class="card form-card">
      <div class="card-header"><h2>Agregar Insumo a Receta</h2></div>

      <div id="notif-receta" class="notif" role="status"></div>

      <form id="form-receta" method="POST">
        <div class="form-group">
          <label>Producto</label>
          <select name="id_producto" required>
            <option value="">Seleccionar</option>
            <?php
            $lista_productos = mysqli_query($conexion, "SELECT idproductos, nombre FROM productos ORDER BY nombre");
            while ($producto = mysqli_fetch_assoc($lista_productos)):
            ?>
              <option value="<?= (int) $producto['idproductos'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Insumo</label>
          <select name="id_insumo" required>
            <option value="">Seleccionar</option>
            <?php
            $lista_insumos = mysqli_query($conexion, "SELECT idinsumos, nombre FROM insumos ORDER BY nombre");
            while ($insumo = mysqli_fetch_assoc($lista_insumos)):
            ?>
              <option value="<?= (int) $insumo['idinsumos'] ?>"><?= htmlspecialchars($insumo['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Cantidad necesaria</label>
          <input type="number" name="cantidad" step="0.0001" min="0.0001" required>
        </div>
        <button type="submit" class="btn-primary">Agregar a la Receta</button>
      </form>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.enviarFormulario(document.getElementById('form-receta'), 'PHP/registrar_receta.php', {
        notificacion: 'notif-receta',
        onSuccess: function (datos, formulario) {
          formulario.querySelector('[name="cantidad"]').value = '';
        }
      });
    });
  </script>

  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
