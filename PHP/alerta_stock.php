<?php
// Envia una alerta una sola vez por cada estado diferente de stock bajo.

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../Libreria/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../Libreria/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Libreria/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/utilidades.php';

function enviar_alerta_stock(mysqli $conexion): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $correo_destinatario = trim($_SESSION['usuario_correo'] ?? '');
    if (!filter_var($correo_destinatario, FILTER_VALIDATE_EMAIL)) {
        error_log('PHPMailer: no se encontró un correo válido en la sesión del usuario.');
        return false;
    }

    $alertas = [];

    $insumos = mysqli_query($conexion, "
        SELECT i.nombre,
               COALESCE(s.cantidad, 0) AS cantidad,
               COALESCE(s.stock_minimo, 10) AS stock_minimo,
               u.abreviatura
        FROM insumos i
        INNER JOIN unidad_de_medida u ON u.idunidad_de_medida = i.idunidad_de_medida
        LEFT  JOIN stock_insumos s    ON s.id_insumo = i.idinsumos
        WHERE COALESCE(s.cantidad, 0) < COALESCE(s.stock_minimo, 10)
        ORDER BY i.nombre
    ");

    if ($insumos) {
        while ($fila = mysqli_fetch_assoc($insumos)) {
            $alertas[] = [
                'tipo' => 'Insumo',
                'nombre' => $fila['nombre'],
                'cantidad' => (float) $fila['cantidad'],
                'minimo' => (float) $fila['stock_minimo'],
                'unidad' => $fila['abreviatura'],
            ];
        }
    }

    $productos = mysqli_query($conexion, "
        SELECT p.nombre,
               COALESCE(s.cantidad, 0) AS cantidad,
               COALESCE(s.stock_minimo, 10) AS stock_minimo,
               u.abreviatura
        FROM productos p
        INNER JOIN unidad_de_medida u ON u.idunidad_de_medida = p.idunidad_de_medida
        LEFT  JOIN stock_productos s  ON s.id_producto = p.idproductos
        WHERE COALESCE(s.cantidad, 0) < COALESCE(s.stock_minimo, 10)
        ORDER BY p.nombre
    ");

    if (!$productos) {
        error_log('PHPMailer: no se pudieron consultar alertas de productos: ' . mysqli_error($conexion));
        return false;
    }

    while ($fila = mysqli_fetch_assoc($productos)) {
        $alertas[] = [
            'tipo' => 'Producto',
            'nombre' => $fila['nombre'],
            'cantidad' => (float) $fila['cantidad'],
            'minimo' => (float) $fila['stock_minimo'],
            'unidad' => $fila['abreviatura'],
        ];
    }

    $archivo_estado = 'C:/xampp/stock_alerta_estado.json';
    if (empty($alertas)) {
        if (is_file($archivo_estado)) {
            unlink($archivo_estado);
        }
        return true;
    }

    $estado_actual = json_encode([
        'destinatario' => $correo_destinatario,
        'alertas' => $alertas,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $estado_anterior = is_file($archivo_estado) ? file_get_contents($archivo_estado) : '';
    if ($estado_actual === $estado_anterior) {
        return true;
    }

    $config_smtp = require __DIR__ . '/config_panaderia.php';
    $smtp_usuario = $config_smtp['smtp_user'] ?? '';
    $smtp_contrasena = $config_smtp['smtp_password'] ?? '';
    if (!$smtp_usuario || !$smtp_contrasena) {
        error_log('PHPMailer: no hay configuración SMTP para alertas de stock.');
        return false;
    }

    $filas_html = '';
    $filas_texto = '';
    foreach ($alertas as $alerta) {
        $cantidad = formatear_cantidad($alerta['cantidad']);
        $minimo = formatear_cantidad($alerta['minimo']);
        $nombre = htmlspecialchars($alerta['nombre'], ENT_QUOTES, 'UTF-8');
        $tipo = htmlspecialchars($alerta['tipo'], ENT_QUOTES, 'UTF-8');
        $unidad = htmlspecialchars($alerta['unidad'], ENT_QUOTES, 'UTF-8');
        $filas_html .= "<li><strong>{$tipo}:</strong> {$nombre} - actual: {$cantidad} {$unidad}; mínimo: {$minimo} {$unidad}</li>";
        $filas_texto .= "- {$alerta['tipo']}: {$alerta['nombre']} - actual: {$cantidad} {$alerta['unidad']}; mínimo: {$minimo} {$alerta['unidad']}\n";
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
        $correo->setFrom($smtp_usuario, 'Alertas de stock - Panadería Normal 10');
        $correo->addAddress($correo_destinatario);
        $correo->isHTML(true);
        $correo->Subject = 'Alerta de stock bajo - Panadería Normal 10';
        $correo->Body = '<h2>Alerta de stock bajo</h2>'
            . '<p>Los siguientes artículos están por debajo del stock mínimo:</p>'
            . '<ul>' . $filas_html . '</ul>';
        $correo->AltBody = "Alerta de stock bajo\n\n{$filas_texto}";
        $correo->send();
        file_put_contents($archivo_estado, $estado_actual, LOCK_EX);
        return true;
    } catch (Exception $excepcion) {
        error_log('PHPMailer alerta de stock: ' . $excepcion->getMessage());
        return false;
    }
}
