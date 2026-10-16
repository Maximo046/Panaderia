<?php
// ============================================================
// formulario_ayuda.php — Guarda un mensaje de contacto
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';
require_once '../Libreria/PHPMailer/src/Exception.php';
require_once '../Libreria/PHPMailer/src/PHPMailer.php';
require_once '../Libreria/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if (empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['asunto'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Completá nombre, correo y asunto.']);
    exit;
}

$id_asunto_seleccionado = mysqli_real_escape_string($conexion, $_POST['asunto']);
$nombre_remitente       = mysqli_real_escape_string($conexion, $_POST['nombre']);
$correo_remitente       = mysqli_real_escape_string($conexion, $_POST['correo']);
$texto_mensaje          = mysqli_real_escape_string($conexion, $_POST['mensaje'] ?? '');

$consulta_asunto = mysqli_query($conexion, "
    SELECT nombre FROM asunto WHERE idasunto = '$id_asunto_seleccionado' LIMIT 1
");
$datos_asunto = $consulta_asunto ? mysqli_fetch_assoc($consulta_asunto) : null;
$nombre_asunto = $datos_asunto['nombre'] ?? 'Consulta general';

$mensaje_guardado = mysqli_query($conexion, "
    INSERT INTO contacto (id_asunto, nombre, correo, mensaje)
    VALUES ('$id_asunto_seleccionado', '$nombre_remitente', '$correo_remitente', '$texto_mensaje')
");

if (!$mensaje_guardado) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al enviar: ' . mysqli_error($conexion)]);
    exit;
}

$consulta_administrador = mysqli_query($conexion, "
    SELECT u.correo
    FROM usuarios u
    INNER JOIN roles r ON r.idroles = u.id_rol
    WHERE r.nombre = 'Administrador' AND u.activo = 1
    LIMIT 1
");
$administrador = $consulta_administrador ? mysqli_fetch_assoc($consulta_administrador) : null;
$correo_administrador = $administrador['correo'] ?? '';

if (!filter_var($correo_administrador, FILTER_VALIDATE_EMAIL)) {
    mysqli_close($conexion);
    echo json_encode([
        'ok' => false,
        'mensaje' => 'El mensaje se guardó, pero no se encontró un correo válido para el administrador.'
    ]);
    exit;
}

$config_smtp = require __DIR__ . '/config_panaderia.php';
$smtp_usuario = $config_smtp['smtp_user'] ?? '';
$smtp_contrasena = $config_smtp['smtp_password'] ?? '';

if (!$smtp_usuario || !$smtp_contrasena) {
    mysqli_close($conexion);
    error_log('PHPMailer: faltan PANADERIA_SMTP_USER y PANADERIA_SMTP_PASSWORD.');
    echo json_encode([
        'ok' => false,
        'mensaje' => 'El mensaje se guardó, pero no se pudo enviar el correo. Falta configurar SMTP.'
    ]);
    exit;
}

try {
    $correo = new PHPMailer(true);
    $correo->isSMTP();
    $correo->Host = 'smtp.gmail.com';
    $correo->SMTPAuth = true;
    $correo->Username = $smtp_usuario;
    $correo->Password = $smtp_contrasena;
    $correo->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $correo->Port = 587;
    $correo->CharSet = 'UTF-8';

    $correo->setFrom($smtp_usuario, 'Formulario de ayuda - Panadería Normal 10');
    $correo->addAddress($correo_administrador);
    $correo->addReplyTo($_POST['correo'], $_POST['nombre']);
    $correo->isHTML(true);
    $correo->Subject = 'Nueva consulta: ' . $nombre_asunto;
    $correo->Body = '<h2>Nueva consulta desde el sistema</h2>'
        . '<p><strong>Nombre:</strong> ' . htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Correo:</strong> ' . htmlspecialchars($_POST['correo'], ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Asunto:</strong> ' . htmlspecialchars($nombre_asunto, ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Mensaje:</strong></p>'
        . '<p>' . nl2br(htmlspecialchars($_POST['mensaje'] ?? '', ENT_QUOTES, 'UTF-8')) . '</p>';
    $correo->AltBody = "Nueva consulta\n\n"
        . "Nombre: {$_POST['nombre']}\n"
        . "Correo: {$_POST['correo']}\n"
        . "Asunto: {$nombre_asunto}\n\n"
        . "Mensaje:\n" . ($_POST['mensaje'] ?? '');
    $correo->send();
} catch (Exception $excepcion) {
    mysqli_close($conexion);
    error_log('PHPMailer: ' . $excepcion->getMessage());
    echo json_encode([
        'ok' => false,
        'mensaje' => 'El mensaje se guardó, pero no se pudo enviar el correo.'
    ]);
    exit;
}

mysqli_close($conexion);
echo json_encode(['ok' => true, 'mensaje' => '¡Mensaje enviado correctamente!']);
