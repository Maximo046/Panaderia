<?php
// ============================================================
// insumos.php — Registro de entradas de insumos y alta de nuevos
// ============================================================

$pagina_actual = 'insumos';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Materias Primas</title>
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
    
    <header class="page-header">
        <h1 class="page-title">Materias <em>Primas</em></h1>
    </header>

    <div class="content-grid">
<!-- Formulario para añadir insumos  -->
      <section class="card form-card" aria-label="Registrar entrada de insumo">
        <header class="card-header"><h2>Registrar Entrada de Insumo:</h2></header>

        <div id="notif-compra" class="notif" role="status"></div>

        <form id="form-compra" method="POST">
          <div class="form-grid">

            <div class="form-group">
              <label for="id_insumo">Tipo de Insumo</label>
              <div class="select-wrapper">
                <select id="id_insumo" name="id_insumo" required>
                  <option value="">— Seleccioná un insumo —</option>
                  <?php
                  $lista_insumos = mysqli_query($conexion, "SELECT idinsumos, nombre FROM insumos ORDER BY nombre");
                  while ($insumo = mysqli_fetch_assoc($lista_insumos)):
                  ?>
                    <option value="<?= (int) $insumo['idinsumos'] ?>"><?= htmlspecialchars($insumo['nombre']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="cantidad">Cantidad</label>
              <input type="number" id="cantidad" name="cantidad" placeholder="0" min="1" required/>
            </div>

            <div class="form-group">
              <label for="precio">Precio de compra ($)</label>
              <input type="number" id="precio" name="precio" placeholder="0.00" min="0" step="0.01"/>
            </div>

            <div class="form-group">
              <label for="fecha_entrada">Fecha de Entrada</label>
              <input type="date" id="fecha_entrada" name="fecha_entrada" required/>
            </div>

            <div class="form-group">
              <label for="id_proveedor">Proveedor (opcional)</label>
              <div class="select-wrapper">
                <select id="id_proveedor" name="id_proveedor">
                  <option value="">— Seleccioná un proveedor —</option>
                  <?php
                  $lista_proveedores = mysqli_query($conexion, "SELECT idproveedores, nombre FROM proveedores ORDER BY nombre");
                  while ($proveedor = mysqli_fetch_assoc($lista_proveedores)):
                  ?>
                    <option value="<?= (int) $proveedor['idproveedores'] ?>"><?= htmlspecialchars($proveedor['nombre']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="id_metodopago">Método de Pago</label>
              <div class="select-wrapper">

                <select id="id_metodopago" name="id_metodopago" required>
                  <option value="">— Seleccioná un método de pago —</option>
                  <?php
                  $lista_metodos_pago = mysqli_query($conexion, "SELECT idmetodos_pago, nombre FROM metodos_pago");
                  while ($metodo = mysqli_fetch_assoc($lista_metodos_pago)):
                  ?>
                    <option value="<?= (int) $metodo['idmetodos_pago'] ?>"><?= htmlspecialchars($metodo['nombre']) ?></option>
                  <?php endwhile; ?>
                </select>

              </div>
            </div>

            <div class="form-group">
              <label for="numero_factura">Número de Factura (opcional)</label>
              <input type="text" id="numero_factura" name="numero_factura" placeholder="Ej: 0001-00012345"/>
            </div>

            <div class="form-full">
              <button type="submit" class="btn-primary">Confirmar Entrada</button>
            </div>

          </div>
        </form>
      </section>
      <!-- Formulario para añadir un nuevo tipo de insumo  -->
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
                    <option value="<?= (int) $unidad['idunidad_de_medida'] ?>"><?= htmlspecialchars($unidad['nombre']) ?></option>
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
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      window.enviarFormulario(
        document.getElementById('form-compra'),
        'PHP/registrar_compra.php',
        { notificacion: 'notif-compra', reset: true }
      );

      window.enviarFormulario(
        document.getElementById('form-insumo'),
        'PHP/registrar_insumo.php',
        { notificacion: 'notif-insumo', reset: true }
      );
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
