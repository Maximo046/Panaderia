<?php
// ============================================================
// productos.php — Listado de productos y sus recetas
// ============================================================
$pagina_actual = 'productos';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Productos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
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
      <?php include 'admin_registro_link.php'; ?>
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>

  </aside>

  <main class="main-content">

    <header class="page-header">
      <h1 class="page-title">Productos</h1>
    </header>

    <div class="acciones-productos">
      <a href="nuevo_producto.php" class="btn-primary">Añadir Producto</a>
      <a href="nueva_receta.php" class="btn-primary">Añadir Receta</a>
    </div>

    <section class="card insumos-reporte" aria-label="Productos Registrados">
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
          <table id="tabla-productos" class="display" style="width:100%">
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
                    $partes_receta[] = htmlspecialchars($fila_receta['nombre_insumo']) . ' : ' . htmlspecialchars($fila_receta['cantidad']) . ' ' . htmlspecialchars($fila_receta['abreviatura']);
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


  <script>
    document.addEventListener('DOMContentLoaded', function () {
    if (window.DataTable) {
      new DataTable('#tabla-productos', {
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        pageLength: 10,
        language: {
          url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json'
        }
      });
    }
    });

  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
