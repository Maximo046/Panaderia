<?php
// ============================================================
// ventas.php — Registro y listado de ventas
// ============================================================

$pagina_actual = 'ventas';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Ventas</title>
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
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>

  </aside>

  <main class="main-content">

    <header class="page-header">
      <h1 class="page-title">Ventas</h1>
    </header>

    <section class="card" aria-label="Formulario de nueva venta">

      <header class="card-header">
        <h2>Nueva Venta</h2>
      </header>

      <form id="form-venta" method="POST">
        <div class="encabezado-venta" aria-hidden="true">
          <span>Producto</span>
          <span>Precio</span>
          <span>Cantidad</span>
        </div>
        <?php
        $lista_productos = mysqli_query($conexion, "
            SELECT p.idproductos, p.nombre AS nombre_producto, u.abreviatura,
                   COALESCE(s.cantidad, 0) AS stock_disponible
            FROM productos p
            INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
            LEFT  JOIN stock_productos   s ON s.id_producto = p.idproductos
            ORDER BY p.nombre
        ");
        while ($producto = mysqli_fetch_assoc($lista_productos)):
            $sin_stock        = $producto['stock_disponible'] <= 0;
            $stock_formateado = rtrim(rtrim($producto['stock_disponible'], '0'), '.') ?: '0';
            $solo_lectura     = $sin_stock ? 'readonly' : '';
            $clase_fila       = $sin_stock ? 'fila-produccion fila-sin-stock' : 'fila-produccion';
        ?>

          <div class="<?= $clase_fila ?> fila-venta">

            <span class="nombre-producto">
              <?= htmlspecialchars($producto['nombre_producto']) ?>
              <small>(<?= $stock_formateado ?> <?= $producto['abreviatura'] ?>)</small>:
            </span>

            <input type="hidden" name="id_producto[]" value="<?= $producto['idproductos'] ?>">
            <input type="number" name="precio[]"    step="0.01"  min="0" value="0" class="input-cantidad" <?= $solo_lectura ?>>
            <input type="number" name="cantidad[]"  step="0.001" min="0" max="<?= $producto['stock_disponible'] ?>" value="0" class="input-cantidad" <?= $solo_lectura ?>>
            <span class="unidad"><?= $producto['abreviatura'] ?></span>

          </div>

        <?php endwhile; ?>

        <div class="fila-produccion" style="margin-top:12px">

          <span class="nombre-producto">Método de pago:</span>

          <div class="select-wrapper">
            <select name="id_metodopago" required>
              <option value="">— Seleccioná —</option>
              <?php
              $lista_metodos_pago = mysqli_query($conexion, "SELECT idmetodos_pago, nombre FROM metodos_pago");
              while ($metodo = mysqli_fetch_assoc($lista_metodos_pago)):
              ?>
                <option value="<?= $metodo['idmetodos_pago'] ?>"><?= $metodo['nombre'] ?></option>
              <?php endwhile; ?>
            </select>

          </div>

          <button type="submit" class="btn-primary">Confirmar Venta</button>

          <a href="PHP/PDF/tiquet_venta.php" class="btn-primary" target="_blank">Imprimir Ticket</a>


        </div>
      </form>
    </section>

<!-- Tabla para ver las ventas del dia  nueva  -->

    <section class="card" aria-label="Historial de ventas">

      <header class="card-header">
        <h2>Ventas del Día</h2>
      </header>

      <div class="lista-productos">

        <?php
        $ventas_hoy = mysqli_query($conexion, "
            SELECT v.idventas, v.fecha, v.total, mp.nombre AS metodo_pago
            FROM ventas v
            INNER JOIN metodos_pago mp ON v.id_metodopago = mp.idmetodos_pago
            WHERE DATE(fecha) = CURDATE()
            ORDER BY fecha DESC
        ");

        if (mysqli_num_rows($ventas_hoy) === 0): ?>
          <p class="sin-datos">No hay ventas registrados.</p>
        <?php else: ?>
          <table id="tabla-ventas" class="display" style="width:100%">
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Productos</th>
                <th>Precio Unitario</th>
                <th>Método</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($venta = mysqli_fetch_assoc($ventas_hoy)): ?>
                <?php
                $detalle = mysqli_query($conexion, "
                    SELECT p.nombre AS nombre_producto, dv.cantidad, dv.precio_unitario, u.abreviatura
                    FROM detalle_venta dv
                    INNER JOIN productos p ON dv.id_producto = p.idproductos
                    INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
                    WHERE dv.id_venta = {$venta['idventas']}
                ");

                $productos = [];
                $precios = [];
                while ($linea = mysqli_fetch_assoc($detalle)) {
                    $cantidad = rtrim(rtrim(number_format($linea['cantidad'], 3, '.', ''), '0'), '.');
                    $productos[] = $linea['nombre_producto'] . ' : ' . $cantidad . ' ' . $linea['abreviatura'];
                    $precios[] = '$' . number_format($linea['precio_unitario'], 2, ',', '.');
                }
                ?>
                <tr>
                  <td><?= $venta['idventas'] ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></td>
                  <td><?= implode('<br>', $productos) ?></td>
                  <td><?= implode('<br>', $precios) ?></td>
                  <td><?= htmlspecialchars($venta['metodo_pago']) ?></td>
                  <td>$ <?= number_format($venta['total'], 2, ',', '.') ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php endif; ?>

      </div>
    </section>

  </main>

  <script>

    document.getElementById('form-venta').addEventListener('submit', function(evento) {
        evento.preventDefault();
        var peticion = new XMLHttpRequest();
        peticion.open('POST', 'PHP/registrar_venta.php', true);
        peticion.onreadystatechange = function() {
            if (peticion.readyState == 4) {
                var respuesta = JSON.parse(peticion.responseText);
                alert(respuesta.mensaje);
                if (respuesta.ok) location.reload();
            }
        };
        peticion.send(new FormData(document.getElementById('form-venta')));
    });

    document.addEventListener('DOMContentLoaded', function () {
      if (window.DataTable) {
        new DataTable('#tabla-ventas', {
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
