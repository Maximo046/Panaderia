<?php
// ============================================================
// index.php — Inicio de sesión
// ============================================================

session_start();
require_once 'conexion.php';

$mensaje_error = '';

// Redirigir destinos según el rol
$pagina_por_rol = [
    'Vendedor'      => 'ventas.php',
    'Panadero'      => 'insumos.php',
    'Administrador' => 'stock.php',
];

// Si ya hay sesión activa, redirigir directamente
if (isset($_SESSION['usuario_id'])) {
    $rol_actual = $_SESSION['usuario_rol'] ?? '';
    header('Location: ' . ($pagina_por_rol[$rol_actual] ?? 'ventas.php'));
    exit;
}

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo_ingresado    = trim($_POST['correo']    ?? '');
    $contrasena_ingresada = $_POST['contrasena'] ?? '';

    if ($correo_ingresado === '' || $contrasena_ingresada === '') {
        $mensaje_error = 'Completá correo y contraseña.';
    } else {
        $correo_seguro = mysqli_real_escape_string($conexion, $correo_ingresado);

        $resultado = mysqli_query($conexion, "
            SELECT u.idusuarios, u.nombre, u.apellido, u.correo, u.contrasena, u.activo,
                   r.nombre AS rol
            FROM usuarios u
            INNER JOIN roles r ON u.id_rol = r.idroles
            WHERE u.correo = '$correo_seguro'
            LIMIT 1
        ");

        $usuario_encontrado = $resultado ? mysqli_fetch_assoc($resultado) : null;

        if (!$usuario_encontrado) {
            $mensaje_error = 'Correo o contraseña incorrectos.';
        } elseif (!$usuario_encontrado['activo']) {
            $mensaje_error = 'Tu cuenta está desactivada. Contactá al administrador.';
        } elseif (!password_verify($contrasena_ingresada, $usuario_encontrado['contrasena'])) {
            $mensaje_error = 'Correo o contraseña incorrectos.';
        } else {
            // Login exitoso: guardar datos en sesión
            $_SESSION['usuario_id']     = $usuario_encontrado['idusuarios'];
            $_SESSION['usuario_nombre'] = $usuario_encontrado['nombre'] . ' ' . $usuario_encontrado['apellido'];
            $_SESSION['usuario_correo'] = $usuario_encontrado['correo'];
            $_SESSION['usuario_rol']    = $usuario_encontrado['rol'];

            $redireccion = $pagina_por_rol[$usuario_encontrado['rol']] ?? 'ventas.php';
            header('Location: ' . $redireccion);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panadería - Iniciar Sesión</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Framework/bootstrap-5.1.3/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="CSS/styles.css"/>
</head>
<body class="pagina-auth">
  <main class="auth-wrapper">
    <header class="auth-brand" aria-label="Branding de la panadería">
      <img src="CSS/Imagenes/Logo.png" alt="Logo panadería">
      <h1>Panadería Normal <em>10</em></h1>
      <p>Sistema de gestión de panadería</p>
    </header>

    <section class="auth-card" aria-label="Formulario de acceso">
      <header class="auth-card-header">
        <h2>Iniciar sesión</h2>
        <p>Ingresá con tu correo y contraseña</p>
      </header>

      <?php if ($mensaje_error !== ''): ?>
        <div class="alert alert-error"><?= htmlspecialchars($mensaje_error) ?></div>
      <?php endif; ?>

      <form class="auth-form" method="POST" action="index.php">
        <div class="form-group">
          <label for="correo">Correo electrónico</label>
          <input type="email" id="correo" name="correo" required
                 placeholder="tu@correo.com"
                 value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label for="contrasena">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena" required
                 placeholder="Tu contraseña">
        </div>

        <button type="submit" class="btn-primary">Ingresar</button>
      </form>

    </section>

    <footer class="auth-school">Escuela Normal N°10 · 2026</footer>
  </main>
  <script src="Framework/bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
