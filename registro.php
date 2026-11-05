<?php
// ============================================================
// registro.php — Formulario de registro de usuarios para el administrador
// ============================================================

session_start();
$pagina_actual = 'registro';
require_once 'conexion.php';
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- ==================== Metadatos y recursos ==================== -->
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Registro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.3.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
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
    <section class="card auth-card" aria-label="Formulario de registro">
      <div class="card-body">
        <header class="auth-card-header">
          <h2>Registro de usuario</h2>
        </header>

        <div id="notificacion-registro" class="notif d-none" role="status" aria-live="polite"></div>

        <form id="form-registro" class="auth-form" method="POST">
          <div>
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Tu nombre">
          </div>

          <div>
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellido" name="apellido" required placeholder="Tu apellido">
          </div>

          <div>
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required placeholder="tu@correo.com">
          </div>

          <input type="hidden" name="id_rol" value="2">

          <div>
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required
                   minlength="6" placeholder="Mínimo 6 caracteres">
          </div>

          <div>
            <label for="confirmar_contrasena" class="form-label">Confirmar contraseña</label>
            <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required
                   minlength="6" placeholder="Repetí tu contraseña">
          </div>

          <button type="submit" class="btn btn-primary w-100" id="boton-registro">Crear cuenta</button>
        </form>

        <p class="auth-footer">
          <a href="stock.php">Volver</a>
        </p>
      </div>
    </section>

    <footer class="auth-school">Escuela Normal N°10 · 2026</footer>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      enviarFormularioAjax(document.getElementById('form-registro'), 'PHP/envio_registro.php', {
        onSuccess: function () {
          setTimeout(function () {
            window.location.href = 'index.php';
          }, 600);
        }
      });
    });
  </script>
  <script src="Framework/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
