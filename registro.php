<?php
// ============================================================
// registro.php — Formulario de registro de usuarios para el administrador
// ============================================================

session_start();
$pagina_actual = 'registro';
require_once 'conexion.php';
require_once 'auth.php';

// ==================== Consulta de roles disponibles ====================
$lista_roles = mysqli_query($conexion, "
  SELECT idroles, nombre
  FROM roles
  WHERE nombre <> 'Administrador'
  ORDER BY idroles
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Registro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
  <script src="JS/datatables_config.js" defer></script>
  <script src="JS/AJAX.js"></script>
</head>
<body class="pagina-auth">
  <main class="auth-wrapper">
    <!-- ==================== Identidad del sistema ==================== -->
    <header class="auth-brand" aria-label="Branding de la panadería">
      <img src="CSS/Imagenes/Logo.png" alt="Logo panadería">
      <h1>Panadería Normal <em>10</em></h1>
      <p>Alta de usuarios del sistema</p>
    </header>

    <!-- ==================== Formulario de registro ==================== -->
    <section class="auth-card" aria-label="Formulario de registro">
      <header class="auth-card-header">
        <h2>Registro de usuario</h2>
      </header>

      <div id="notificacion-registro" class="notif"></div>

      <form id="form-registro" class="auth-form" method="POST">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
        </div>

        <div class="form-group">
          <label for="apellido">Apellido</label>
          <input type="text" id="apellido" name="apellido" required placeholder="Tu apellido">
        </div>

        <div class="form-group">
          <label for="correo">Correo electrónico</label>
          <input type="email" id="correo" name="correo" required placeholder="tu@correo.com">
        </div>

        <div class="form-group">
          <label for="id_rol">Rol</label>
          <div class="select-wrapper">
            <select id="id_rol" name="id_rol" required>
              <option value="">Seleccionar rol</option>
              <?php while ($rol = mysqli_fetch_assoc($lista_roles)): ?>
                <option value="<?= (int) $rol['idroles'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
              <?php endwhile; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="contrasena">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena" required
                 minlength="6" placeholder="Mínimo 6 caracteres">
        </div>

        <div class="form-group">
          <label for="confirmar_contrasena">Confirmar contraseña</label>
          <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required
                 minlength="6" placeholder="Repetí tu contraseña">
        </div>

        <button type="submit" class="btn-primary" id="boton-registro">Crear cuenta</button>
      </form>

      <p class="auth-footer">
        <a href="stock.php">Volver</a>
      </p>
    </section>

    <footer class="auth-school">Escuela Normal N°10 · 2026</footer>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      enviarFormularioAjax(document.getElementById('form-registro'), 'PHP/envio_registro.php', {
        onSuccess: function (datos, formulario) {
          setTimeout(function () {
            window.location.href = 'index.php';
          }, 600);
        }
      });
    });
  </script>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>