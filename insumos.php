<?php
// ============================================================
// insumos.php — Entrada de insumos
// ============================================================

// ==================== Sesión, permisos y dependencias ====================
$pagina_actual = 'insumos';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
require_once 'PHP/utilidades.php';

if (empty($_SESSION['token_compra'])) {
    $_SESSION['token_compra'] = bin2hex(random_bytes(32));
}
$token_compra = $_SESSION['token_compra'];

// ==================== Mensaje de operación exitosa ====================
$mensaje_compra = $_SESSION['compra_ok'] ?? '';
unset($_SESSION['compra_ok']);

// ==================== Consulta de insumos y existencias ====================
$lista_insumos = mysqli_query($conexion, "
    SELECT i.idinsumos,
           i.nombre,
           u.abreviatura,
           COALESCE(s.cantidad, 0) AS stock_actual,
           COALESCE(s.stock_minimo, 10) AS stock_minimo
    FROM insumos i
    INNER JOIN unidad_de_medida u ON u.idunidad_de_medida = i.idunidad_de_medida
    LEFT JOIN stock_insumos s ON s.id_insumo = i.idinsumos
    ORDER BY i.nombre
");


?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Materias Primas</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="Libreria/DataTables/datatables.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
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
    <!-- ==================== Encabezado de la página ==================== -->
    <header class="page-header">
      <h1 class="page-title">Materias <em>Primas</em></h1>
    </header>

    <!-- ==================== Formulario de entrada de insumos ==================== -->
    <section class="card" aria-label="Registrar entrada de insumos">
      <header class="card-header">
        <h2>Registrar Entrada de Insumos</h2>
      </header>

      <div id="notif-compra" class="notif" role="status"></div>

      <form id="form-compra" method="POST" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token_compra, ENT_QUOTES, 'UTF-8') ?>"/>
        <input type="hidden" name="fecha_entrada" value="<?= date('Y-m-d') ?>"/>

        <div class="encabezado-salida" aria-hidden="true">
          <span>Insumo</span>
          <span>Stock</span>
          <span>Cantidad</span>
        </div>

        <!-- ==================== Tabla de insumos y cantidades a ingresar ==================== -->
        <?php while ($insumo = mysqli_fetch_assoc($lista_insumos)): ?>
          <?php
          $stock = (float) $insumo['stock_actual'];
          $clase_fila = $stock < (float)$insumo['stock_minimo'] ? 'fila-salida fila-sin-stock' : 'fila-salida';
          ?>
          <div class="<?= $clase_fila ?>">
            <span class="nombre-producto"><?= htmlspecialchars($insumo['nombre'], ENT_QUOTES, 'UTF-8') ?></span>

            <span class="stock-disponible <?= $stock < (float)$insumo['stock_minimo'] ? 'stock-bajo' : '' ?>">
              <?= formatear_cantidad($stock) ?> <?= htmlspecialchars($insumo['abreviatura'], ENT_QUOTES, 'UTF-8') ?>
            </span>

            <input type="hidden" name="id_insumo[]" value="<?= (int) $insumo['idinsumos'] ?>">
            <input type="number" name="cantidad[]" step="0.001" min="0" value="0"
                   class="input-cantidad"
                   aria-label="Cantidad a ingresar de <?= htmlspecialchars($insumo['nombre'], ENT_QUOTES, 'UTF-8') ?>">
          </div>
        <?php endwhile; ?>

        <div class="opciones-salida">
          <div class="form-full">
            <button type="submit" class="btn-primary">Registrar Entrada</button>
          </div>
        </div>
      </form>
    </section>

    <!-- ==================== Formulario para añadir un insumo ==================== -->
    <section class="card form-card" aria-label="Añadir nuevo insumo">
      <header class="card-header"><h2>Añadir Nuevo Insumo:</h2></header>
      <div id="notif-insumo" class="notif" role="status"></div>
      <form id="form-insumo" method="POST">
        <div class="form-grid">
          <div class="form-group">
            <label for="nombre_insumo">Nombre del Insumo</label>
            <input type="text" id="nombre_insumo" name="nombre_insumo" placeholder="Ej: Harina 000" required/>
          </div>
          <div class="form-group">
            <label for="idunidad">Unidad de Medida</label>
            <div class="select-wrapper">
              <select id="idunidad" name="idunidad" required>
                <option value="">— Seleccioná una unidad —</option>
                <?php
                $lista_unidades = mysqli_query($conexion, "SELECT idunidad_de_medida, nombre FROM unidad_de_medida");
                while ($unidad = mysqli_fetch_assoc($lista_unidades)):
                ?>
                  <option value="<?= (int) $unidad['idunidad_de_medida'] ?>"><?= htmlspecialchars($unidad['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-full">
            <button type="submit" class="btn-primary">Añadir Insumo</button>
          </div>
        </div>
      </form>
    </section>


  </main>

  <!-- ==================== Envío AJAX de entradas y altas ==================== -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var formularioCompra = document.getElementById('form-compra');

      window.enviarFormulario(formularioCompra, 'PHP/registrar_compra.php', {
        notificacion: 'notif-compra',
        onSuccess: function () {
          window.location.reload();
        }
      });

      window.enviarFormulario(document.getElementById('form-insumo'), 'PHP/registrar_insumo.php', {
        notificacion: 'notif-insumo',
        reset: true
      });
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
