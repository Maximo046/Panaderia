<?php
// ============================================================
// logout.php — Cierra la sesión y redirige al login
// ============================================================

session_start();
session_destroy();
header('Location: index.php');
exit;
