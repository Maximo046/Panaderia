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
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
  <script src="JS/app.js" defer></script>
</head>

<body>

  <!-- ==================== Navegación principal ==================== -->
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

    <!-- ==================== Encabezado y accesos de productos ==================== -->
    <header class="page-header">
      <h1 class="page-title">Productos</h1>
    </header>

    <div class="acciones-productos">
      <a href="nuevo_producto.php" class="btn-primary">Añadir Producto</a>
      <a href="nueva_receta.php" class="btn-primary">Añadir Receta</a>
    </div>

    <!-- ==================== Tabla de productos y sus recetas ==================== -->
    <section class="card" aria-label="Productos Registrados">
      <header class="card-header"><h2>Productos</h2></header>

      <div class="lista-productos">

        <?php
        $lista_productos = mysqli_query($conexion, "
            SELECT idproductos, nombre AS nombre_producto
            FROM productos
            ORDER BY nombre
        ");

        if (mysqli_num_rows($lista_productos) === 0): ?>
          <p class="sin-datos">No hay productos registrados.</p>
        <?php else: ?>
          <table id="tabla-productos" class="display">
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
        <?php endif; ?>

      </div>
    </section>

  </main>


  <!-- ==================== Inicialización de la tabla ==================== -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.iniciarTabla('#tabla-productos');
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
