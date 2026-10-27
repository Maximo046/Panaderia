<?php
// ============================================================
// produccion.php — Registro de producción de productos
// ============================================================

$pagina_actual = 'produccion';
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
  <title>Panadería - Producción</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
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
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </aside>

  <main class="main-content">
    <!-- ==================== Encabezado de producción ==================== -->
    <header class="page-header">
      <h1 class="page-title">Producción</h1>
    </header>

    <!-- ==================== Formularios por producto ==================== -->
    <section class="card" aria-label="Registro de producción">
      <header class="card-header"><h2>Producción</h2></header>

      <div id="notif-produccion" class="notif" role="status"></div>

      <?php
      $lista_productos = mysqli_query($conexion, "
          SELECT p.idproductos, p.nombre AS nombre_producto, u.abreviatura
          FROM productos p
          INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
          ORDER BY p.nombre
      ");
      while ($producto = mysqli_fetch_assoc($lista_productos)):
      ?>
        <form class="fila-produccion form-produccion" method="POST">
          <span class="nombre-producto"><?= htmlspecialchars($producto['nombre_producto']) ?>:</span>
          <input type="hidden" name="id_producto" value="<?= (int) $producto['idproductos'] ?>">
          <input type="number" step="0.001" min="0.001" name="cantidad" class="input-cantidad" required>
          <span class="unidad"><?= htmlspecialchars($producto['abreviatura']) ?></span>
          <button type="submit" class="btn-primary">Producir</button>
        </form>
      <?php endwhile; ?>
    </section>
  </main>

  <!-- ==================== Envío AJAX de la producción ==================== -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.form-produccion').forEach(function (formulario) {
        window.enviarFormulario(formulario, 'PHP/registrar_produccion.php', {
          notificacion: 'notif-produccion',
          onSuccess: function (datos, form) {
            form.querySelector('[name="cantidad"]').value = '';
          }
        });
      });
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
