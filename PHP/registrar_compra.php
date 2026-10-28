<?php
// ============================================================
// registrar_compra.php — Registra entradas múltiples de insumos
// ============================================================

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function responder_compra(array $datos, int $codigo_http = 200): void
{
    http_response_code($codigo_http);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// Protección CSRF
$token_recibido = $_POST['csrf_token'] ?? '';
$token_sesion   = $_SESSION['token_compra'] ?? '';
if ($token_sesion !== '' && $token_recibido !== '') {
    if (!hash_equals($token_sesion, $token_recibido)) {
        responder_compra([
            'ok'      => false,
            'mensaje' => 'El formulario expiró. Recargá la página e intentá nuevamente.',
        ], 403);
    }
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once '../conexion.php';
require_once __DIR__ . '/alerta_stock.php';
require_once __DIR__ . '/utilidades.php';

$fecha_entrada = trim($_POST['fecha_entrada'] ?? '');
if ($fecha_entrada === '') {
    $fecha_entrada = date('Y-m-d');
}
$fecha_valida  = DateTime::createFromFormat('Y-m-d', $fecha_entrada);
if (!$fecha_valida || $fecha_valida->format('Y-m-d') !== $fecha_entrada) {
    $fecha_entrada = date('Y-m-d');
}

$ids_insumo   = $_POST['id_insumo'] ?? [];
$cantidades   = $_POST['cantidad'] ?? [];

if (!is_array($ids_insumo)) {
    $ids_insumo = [];
}
if (!is_array($cantidades)) {
    $cantidades = [];
}

if (empty($ids_insumo)) {
    $raw = file_get_contents('php://input');
    if ($raw) {
        parse_str($raw, $parsed);
        if (isset($parsed['id_insumo'])) {
            $ids_insumo = is_array($parsed['id_insumo']) ? $parsed['id_insumo'] : [$parsed['id_insumo']];
        }
        if (isset($parsed['cantidad'])) {
            $cantidades = is_array($parsed['cantidad']) ? $parsed['cantidad'] : [$parsed['cantidad']];
        }
    }
}

$entradas = [];

foreach ($ids_insumo as $indice => $id_insumo_raw) {
    $cantidad_raw = $cantidades[$indice] ?? null;
    if ($cantidad_raw === null || $cantidad_raw === '') {
        continue;
    }

    $cantidad_trim = trim((string)$cantidad_raw);
    if ($cantidad_trim === '' || $cantidad_trim === '0' || $cantidad_trim === '0.000') {
        continue;
    }

    $id_insumo = filter_var($id_insumo_raw, FILTER_VALIDATE_INT);
    if ($id_insumo === false || $id_insumo <= 0) {
        continue;
    }

    $cantidad_texto = str_replace(',', '.', $cantidad_trim);
    $cantidad = filter_var($cantidad_texto, FILTER_VALIDATE_FLOAT);
    if ($cantidad === false || $cantidad <= 0) {
        continue;
    }

    $entradas[] = [
        'id_insumo' => $id_insumo,
        'cantidad'  => $cantidad,
    ];
}

if (empty($entradas)) {
    responder_compra(['ok' => false, 'mensaje' => 'Ingresá al menos una cantidad mayor a cero.'], 400);
}

$en_transaccion = false;

// ==================== Registro transaccional de entradas y stock ====================
try {

    $conexion->begin_transaction();
    $en_transaccion = true;

    $insertar_compra = $conexion->prepare("
        INSERT INTO entrada_insumos
            (id_insumos, cantidad, fecha)
        VALUES
            (?, ?, ?)
    ");

    $actualizar_stock = $conexion->prepare("
        INSERT INTO stock_insumos (id_insumo, cantidad)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
    ");

    $ids_compras = [];
    $total_entradas = count($entradas);

    foreach ($entradas as $entrada) {
        $id_insumo_ent = $entrada['id_insumo'];
        $cantidad_ent  = $entrada['cantidad'];

        $consulta_insumo = $conexion->prepare("SELECT idinsumos FROM insumos WHERE idinsumos = ?");
        $consulta_insumo->bind_param('i', $id_insumo_ent);
        $consulta_insumo->execute();
        $consulta_insumo->store_result();
        if ($consulta_insumo->num_rows === 0) {
            $consulta_insumo->close();
            throw new Exception('Insumo no encontrado');
        }
        $consulta_insumo->close();

        $insertar_compra->bind_param(
            'ids',
            $id_insumo_ent,
            $cantidad_ent,
            $fecha_entrada
        );
        $insertar_compra->execute();
        $ids_compras[] = $conexion->insert_id;

        $actualizar_stock->bind_param('id', $id_insumo_ent, $cantidad_ent);
        $actualizar_stock->execute();
    }

    $insertar_compra->close();
    $actualizar_stock->close();
    $conexion->commit();
    $en_transaccion = false;
} catch (Throwable $excepcion) {
    if ($en_transaccion) {
        try {
            $conexion->rollback();
        } catch (Throwable $error_rollback) {
            error_log('Error al revertir la compra múltiple: ' . $error_rollback->getMessage());
        }
    }

    responder_compra([
        'ok'      => false,
        'mensaje' => 'No se pudieron registrar las entradas. Intentá nuevamente.',
    ], 500);
}

// ==================== Confirmación, alerta y respuesta final ====================
$mensaje_ok = $total_entradas === 1
    ? '1 entrada de insumo registrada correctamente.'
    : $total_entradas . ' entradas de insumo registradas correctamente.';

$_SESSION['compra_ok'] = $mensaje_ok;

try {
    enviar_alerta_stock($conexion);
} catch (Throwable $excepcion_alerta) {
    error_log('Error al enviar alerta de stock: ' . $excepcion_alerta->getMessage());
}

$conexion->close();

responder_compra([
    'ok'         => true,
    'mensaje'    => $mensaje_ok,
    'total'      => $total_entradas,
    'compras'    => $ids_compras,
]);
