<?php
// ============================================================
// productos.php — Listado de productos y sus recetas
// ============================================================

$pagina_actual = 'productos';
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
  <title>Panadería - Productos</title>
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

    <!-- ==================== Encabezado y accesos de productos ==================== -->
    <header class="page-header d-flex flex-wrap justify-content-between align-items-end gap-3">
      <div>
        <h1 class="page-title">Productos</h1>
        <p class="page-subtitle">Administrá el catálogo y las recetas de cada producto.</p>
      </div>
      <div class="d-flex flex-wrap gap-2">
        <a href="nuevo_producto.php" class="btn btn-primary">Añadir Producto</a>
        <a href="nueva_receta.php" class="btn btn-outline-secondary">Añadir Receta</a>
      </div>
    </header>

    <!-- ==================== Tabla de productos y sus recetas ==================== -->
    <section class="card" aria-label="Productos Registrados">
      <div class="card-header"><h2>Productos</h2></div>
      <div class="card-body">
        <?php
        $lista_productos = mysqli_query($conexion, "
            SELECT idproductos, nombre AS nombre_producto
            FROM productos
            ORDER BY nombre
        ");

        if (mysqli_num_rows($lista_productos) === 0): ?>
          <p class="sin-datos">No hay productos registrados.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table id="tabla-productos" class="table table-striped table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Receta</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($producto = mysqli_fetch_assoc($lista_productos)): ?>
                  <?php
                  $receta_del_producto = mysqli_query($conexion, "
                      SELECT i.nombre AS nombre_insumo, r.cantidad, u.abreviatura
                      FROM receta r
                      INNER JOIN insumos i ON r.id_insumo = i.idinsumos
                      INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
                      WHERE r.id_producto = {$producto['idproductos']}
                  ");

                  $partes_receta = [];
                  while ($fila_receta = mysqli_fetch_assoc($receta_del_producto)) {
                      $partes_receta[] = htmlspecialchars($fila_receta['nombre_insumo']) . ' : ' . formatear_cantidad($fila_receta['cantidad']) . ' ' . htmlspecialchars($fila_receta['abreviatura']);
                  }
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                    <td><?= !empty($partes_receta) ? implode('<br>', $partes_receta) : 'Sin receta definida' ?></td>
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
        window.iniciarTabla('#tabla-productos');
      }
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
