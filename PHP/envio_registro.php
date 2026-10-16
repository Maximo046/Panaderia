<?php
// ============================================================
// envio_registro.php — Procesa el formulario de registro
// ============================================================

header('Content-Type: application/json');
session_start();
require_once '../conexion.php';

if (($_SESSION['usuario_rol'] ?? '') !== 'Administrador') {
    echo json_encode(['ok' => false, 'mensaje' => 'Solo el administrador puede registrar usuarios.']);
    exit;
}

// Verificar que todos los campos estén presentes
$campos_requeridos = ['nombre', 'apellido', 'correo', 'id_rol', 'contrasena', 'confirmar_contrasena'];
foreach ($campos_requeridos as $campo) {
    if (empty($_POST[$campo])) {
        echo json_encode(['ok' => false, 'mensaje' => 'Completá todos los campos.']);
        exit;
    }
}

$nombre_usuario       = trim($_POST['nombre']);
$apellido_usuario     = trim($_POST['apellido']);
$correo_usuario       = trim($_POST['correo']);
$id_rol_seleccionado  = (int) $_POST['id_rol'];
$contrasena_nueva     = $_POST['contrasena'];
$contrasena_repetida  = $_POST['confirmar_contrasena'];

// Validar formato de correo
if (!filter_var($correo_usuario, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'mensaje' => 'El correo electrónico no es válido.']);
    exit;
}

// Validar longitud mínima de contraseña
if (strlen($contrasena_nueva) < 6) {
    echo json_encode(['ok' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.']);
    exit;
}

// Verificar que ambas contraseñas coincidan
if ($contrasena_nueva !== $contrasena_repetida) {
    echo json_encode(['ok' => false, 'mensaje' => 'Las contraseñas no coinciden.']);
    exit;
}

// Verificar que el rol existe y evitar crear otro administrador
$rol_encontrado = mysqli_query($conexion, "SELECT nombre FROM roles WHERE idroles = $id_rol_seleccionado LIMIT 1");
$datos_rol = $rol_encontrado ? mysqli_fetch_assoc($rol_encontrado) : null;
if (!$datos_rol) {
    echo json_encode(['ok' => false, 'mensaje' => 'El rol seleccionado no es válido.']);
    exit;
}

if ($datos_rol['nombre'] === 'Administrador') {
    echo json_encode(['ok' => false, 'mensaje' => 'Solo puede existir un administrador en el sistema.']);
    exit;
}

// Escapar datos para la consulta
$nombre_seguro   = mysqli_real_escape_string($conexion, $nombre_usuario);
$apellido_seguro = mysqli_real_escape_string($conexion, $apellido_usuario);
$correo_seguro   = mysqli_real_escape_string($conexion, $correo_usuario);

// Verificar que el correo no esté ya registrado
$correo_duplicado = mysqli_query($conexion, "SELECT idusuarios FROM usuarios WHERE correo = '$correo_seguro' LIMIT 1");
if ($correo_duplicado && mysqli_num_rows($correo_duplicado) > 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Ya existe una cuenta con ese correo.']);
    exit;
}

// Hashear la contraseña de forma segura
$contrasena_hasheada = password_hash($contrasena_nueva, PASSWORD_DEFAULT);

// Insertar el nuevo usuario
$registro_exitoso = mysqli_query($conexion, "
    INSERT INTO usuarios (id_rol, nombre, apellido, correo, contrasena)
    VALUES ($id_rol_seleccionado, '$nombre_seguro', '$apellido_seguro', '$correo_seguro', '$contrasena_hasheada')
");

if (!$registro_exitoso) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al registrar: ' . mysqli_error($conexion)]);
    exit;
}

mysqli_close($conexion);
echo json_encode(['ok' => true, 'mensaje' => '¡Cuenta creada correctamente! Redirigiendo al login...']);
