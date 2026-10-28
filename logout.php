<?php
// ============================================================
// logout.php — Cierra la sesión y redirige al login
// ============================================================

// ==================== Cierre y redirección ====================
session_start();
session_destroy();
header('Location: index.php');
exit;
