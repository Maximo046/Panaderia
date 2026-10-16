<?php
// ============================================================
// nuevo_producto.php — Formulario para registrar un producto nuevo
// ============================================================

$pagina_actual = 'nuevo_producto';
session_start();
require_once 'conexion.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Añadir Producto</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
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
      <?php include 'admin_registro_link.php'; ?>
      <a href="logout.php" class="btn-logout">Cerrar sesión</a>
    </div>
  </nav>

  <main class="main-content">
    <header class="page-header">
      <h1 class="page-title">Nuevo Producto</h1>
      <div class="acciones-productos">
      <a href="productos.php" class="btn-primary">Volver</a>
      </div>
    </header>

    <section class="card form-card">
      <div class="card-header"><h2>Datos del Producto</h2></div>

      <form id="form-nuevo-producto" method="POST">
        <div class="form-group">
          <label>Nombre del producto</label>
          <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
          <label>Unidad de medida</label>
          <select name="idunidad_de_medida" required>
            <option value="">Seleccionar</option>
            <?php
            $lista_unidades = mysqli_query($conexion, "SELECT idunidad_de_medida, nombre FROM unidad_de_medida");
            while ($unidad = mysqli_fetch_assoc($lista_unidades)):
            ?>
              <option value="<?= $unidad['idunidad_de_medida'] ?>"><?= $unidad['nombre'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <button type="submit" class="btn-primary">Guardar Producto</button>
      </form>
    </section>
  </main>

  <script>
  document.getElementById('form-nuevo-producto').addEventListener('submit', function(evento) {
      evento.preventDefault();
      var peticion = new XMLHttpRequest();
      peticion.open('POST', 'PHP/registrar_producto.php', true);
      peticion.onreadystatechange = function() {
          if (peticion.readyState == 4) {
              var respuesta = JSON.parse(peticion.responseText);
              alert(respuesta.mensaje);
              if (respuesta.ok) document.getElementById('form-nuevo-producto').reset();
          }
      };
      peticion.send(new FormData(document.getElementById('form-nuevo-producto')));
  });
  </script>

  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
