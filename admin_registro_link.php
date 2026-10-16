<?php if (($_SESSION['usuario_rol'] ?? '') === 'Administrador'): ?>
  <a href="registro.php" class="btn-admin">Registrar usuario</a>
<?php endif; ?>
