<?php
// ============================================================
// salidas.php — Registro y listado de salidas de productos
// ============================================================

$pagina_actual = 'salidas';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/utilidades.php';

$motivos_salida = [
    'Venta mostrador',
    'Consumo interno',
    'Merma/vencido',
    'Rotura',
    'Donación',
    'Otro',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Salidas de Productos</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="Libreria/DataTables/datatables.min.js" defer></script>
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
      <?php include 'admin_registro_link.php'; ?>
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </aside>

  <main class="main-content">
    <header class="page-header">
      <div>
        <h1 class="page-title">Salidas de <em>Productos</em></h1>
        <p class="page-subtitle">Registrá la mercadería que sale del depósito. El stock se descuenta automáticamente.</p>
      </div>
    </header>

    <section class="card" aria-label="Registrar salida de productos">
      <header class="card-header">
        <h2>Nueva Salida</h2>
      </header>

      <div id="notif-salida" class="notif" role="status"></div>

      <form id="form-salida" method="POST">
        <div class="encabezado-salida" aria-hidden="true">
          <span>Producto</span>
          <span>Stock disponible</span>
          <span>Cantidad a retirar</span>
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
            $stock_formateado = formatear_cantidad($producto['stock_disponible']);
            $solo_lectura     = $sin_stock ? 'readonly' : '';
            $clase_fila       = $sin_stock ? 'fila-salida fila-sin-stock' : 'fila-salida';
        ?>
          <div class="<?= $clase_fila ?>">
            <span class="nombre-producto"><?= htmlspecialchars($producto['nombre_producto']) ?></span>

            <span class="stock-disponible">
              <?= $stock_formateado ?> <?= htmlspecialchars($producto['abreviatura']) ?>
            </span>

            <input type="hidden" name="id_producto[]" value="<?= (int) $producto['idproductos'] ?>">
            <input type="number" name="cantidad[]" step="0.001" min="0"
                   max="<?= $producto['stock_disponible'] ?>" value="0"
                   class="input-cantidad" <?= $solo_lectura ?>
                   aria-label="Cantidad a retirar de <?= htmlspecialchars($producto['nombre_producto']) ?>">
          </div>
        <?php endwhile; ?>

        <div class="opciones-salida">
          <div class="form-group">
            <label for="motivo">Motivo</label>
            <div class="select-wrapper">
              <select id="motivo" name="motivo" required>
                <option value="">— Seleccioná un motivo —</option>
                <?php foreach ($motivos_salida as $motivo): ?>
                  <option value="<?= htmlspecialchars($motivo) ?>"><?= htmlspecialchars($motivo) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="observacion">Observación (opcional)</label>
            <input type="text" id="observacion" name="observacion" maxlength="255"
                   placeholder="Detalle de la salida">
          </div>

          <div class="form-full">
            <button type="submit" class="btn-primary">Registrar Salida</button>
          </div>
        </div>
      </form>
    </section>

    <section class="card" aria-label="Salidas del día">
      <header class="card-header">
        <h2>Salidas del Día</h2>
      </header>

      <?php
      $salidas_hoy = mysqli_query($conexion, "
          SELECT s.idsalida, s.fecha, s.cantidad, s.motivo, s.observacion,
                 p.nombre AS nombre_producto, u.abreviatura,
                 CONCAT_WS(' ', us.nombre, us.apellido) AS nombre_usuario
          FROM salidas_productos s
          INNER JOIN productos p          ON s.id_producto = p.idproductos
          INNER JOIN unidad_de_medida u   ON p.idunidad_de_medida = u.idunidad_de_medida
          LEFT  JOIN usuarios us          ON s.id_usuario = us.idusuarios
          WHERE DATE(s.fecha) = CURDATE()
          ORDER BY s.fecha DESC
      ");

      if (mysqli_num_rows($salidas_hoy) === 0): ?>
        <p class="sin-datos">No hay salidas registradas hoy.</p>
      <?php else: ?>
        <table id="tabla-salidas" class="display">
          <thead>
            <tr>
              <th>#</th>
              <th>Hora</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Motivo</th>
              <th>Observación</th>
              <th>Registró</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($salida = mysqli_fetch_assoc($salidas_hoy)): ?>
              <tr>
                <td><?= (int) $salida['idsalida'] ?></td>
                <td><?= date('H:i', strtotime($salida['fecha'])) ?></td>
                <td><?= htmlspecialchars($salida['nombre_producto']) ?></td>
                <td><?= htmlspecialchars(formatear_cantidad($salida['cantidad'])) ?> <?= htmlspecialchars($salida['abreviatura']) ?></td>
                <td><?= htmlspecialchars($salida['motivo']) ?></td>
                <td><?= $salida['observacion'] !== null && $salida['observacion'] !== '' ? htmlspecialchars($salida['observacion']) : '—' ?></td>
                <td><?= htmlspecialchars($salida['nombre_usuario'] ?: '—') ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.iniciarTabla('#tabla-salidas');

      window.enviarFormulario(
        document.getElementById('form-salida'),
        'PHP/registrar_salida.php',
        {
          notificacion: 'notif-salida',
          onSuccess: function (datos, formulario) {
            window.setTimeout(function () {
              window.location.reload();
            }, 700);
          }
        }
      );
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
