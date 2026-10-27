<?php
// ============================================================
// registrar_compra.php — Registra entradas múltiples de insumos
// ============================================================

header('Content-Type: application/json; charset=utf-8');

// ==================== Sesión y respuesta JSON ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function responder_compra(array $datos, int $codigo_http = 200): void
{
    http_response_code($codigo_http);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// ==================== Autorización y protección CSRF ====================
if (!isset($_SESSION['usuario_rol'])) {
    responder_compra([
        'ok'      => false,
        'mensaje' => 'Tu sesión expiró. Volvé a iniciar sesión.',
    ], 401);
}

$token_recibido = $_POST['csrf_token'] ?? '';
$token_sesion   = $_SESSION['token_compra'] ?? '';
if ($token_sesion === '' || !hash_equals($token_sesion, $token_recibido)) {
    responder_compra([
        'ok'      => false,
        'mensaje' => 'El formulario expiró. Recargá la página e intentá nuevamente.',
    ], 403);
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once '../conexion.php';
require_once __DIR__ . '/alerta_stock.php';
require_once __DIR__ . '/utilidades.php';

// ==================== Fecha y valores predeterminados ====================
$fecha_entrada = trim($_POST['fecha_entrada'] ?? '');
if ($fecha_entrada === '') {
    $fecha_entrada = date('Y-m-d');
}
$fecha_valida  = DateTime::createFromFormat('Y-m-d', $fecha_entrada);
if (!$fecha_valida || $fecha_valida->format('Y-m-d') !== $fecha_entrada) {
    $fecha_entrada = date('Y-m-d');
}

$id_metodo_pago = null;
$consulta_metodo_por_defecto = $conexion->query("SELECT idmetodos_pago FROM metodos_pago ORDER BY idmetodos_pago ASC LIMIT 1");
if ($consulta_metodo_por_defecto && ($fila_metodo = $consulta_metodo_por_defecto->fetch_assoc())) {
    $id_metodo_pago = (int) $fila_metodo['idmetodos_pago'];
} else {
    responder_compra(['ok' => false, 'mensaje' => 'No hay métodos de pago configurados.'], 500);
}
$consulta_metodo_por_defecto->free();

$id_proveedor = null;
$numero_factura_bd = null;

// ==================== Validación de insumos y cantidades ====================
$ids_insumo   = $_POST['id_insumo'] ?? [];
$cantidades   = $_POST['cantidad'] ?? [];

if (!is_array($ids_insumo)) {
    responder_compra(['ok' => false, 'mensaje' => 'No se enviaron insumos para registrar.'], 400);
}

$entradas = [];
$total_entradas = 0;

foreach ($ids_insumo as $indice => $id_insumo_raw) {
    $cantidad_raw = $cantidades[$indice] ?? '';

    $cantidad_trim = trim($cantidad_raw);
    if ($cantidad_trim === '') {
        continue;
    }

    $id_insumo = filter_var($id_insumo_raw, FILTER_VALIDATE_INT);
    if ($id_insumo === false || $id_insumo <= 0) {
        responder_compra(['ok' => false, 'mensaje' => 'Hay un insumo inválido en la lista.'], 400);
    }

    $cantidad_texto = str_replace(',', '.', $cantidad_trim);
    $cantidad = filter_var($cantidad_texto, FILTER_VALIDATE_FLOAT);
    if ($cantidad === false || $cantidad <= 0) {
        responder_compra(['ok' => false, 'mensaje' => 'Las cantidades deben ser mayores a cero.'], 400);
    }

    $entradas[] = [
        'id_insumo' => $id_insumo,
        'cantidad'  => $cantidad,
    ];
    $total_entradas++;
}

if (empty($entradas)) {
    responder_compra(['ok' => false, 'mensaje' => 'Ingresá al menos una cantidad.'], 400);
}

$en_transaccion = false;

// ==================== Registro transaccional de entradas y stock ====================
try {

    if ($id_proveedor !== null) {
        $consulta_prov = $conexion->prepare("SELECT idproveedores FROM proveedores WHERE idproveedores = ?");
        $consulta_prov->bind_param('i', $id_proveedor);
        $consulta_prov->execute();
        $consulta_prov->store_result();
        if ($consulta_prov->num_rows === 0) {
            $consulta_prov->close();
            responder_compra(['ok' => false, 'mensaje' => 'El proveedor seleccionado no existe.'], 400);
        }
        $consulta_prov->close();
    }

    $conexion->begin_transaction();
    $en_transaccion = true;

    $insertar_compra = $conexion->prepare("
        INSERT INTO compras_insumos
            (id_metodopago, id_proveedor, id_insumos, numero_factura, cantidad, precio, fecha)
        VALUES
            (?, ?, ?, ?, ?, ?, ?)
    ");

    $actualizar_stock = $conexion->prepare("
        INSERT INTO stock_insumos (id_insumo, cantidad)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
    ");

    $ids_compras = [];

    foreach ($entradas as $entrada) {
        $consulta_insumo = $conexion->prepare("SELECT idinsumos FROM insumos WHERE idinsumos = ?");
        $consulta_insumo->bind_param('i', $entrada['id_insumo']);
        $consulta_insumo->execute();
        $consulta_insumo->store_result();
        if ($consulta_insumo->num_rows === 0) {
            $consulta_insumo->close();
            throw new Exception('Insumo no encontrado');
        }
        $consulta_insumo->close();

        $precio_cero = 0.00;
        $insertar_compra->bind_param(
            'iiisdds',
            $id_metodo_pago,
            $id_proveedor,
            $entrada['id_insumo'],
            $numero_factura_bd,
            $entrada['cantidad'],
            $precio_cero,
            $fecha_entrada
        );
        $insertar_compra->execute();
        $ids_compras[] = $conexion->insert_id;

        $actualizar_stock->bind_param('id', $entrada['id_insumo'], $entrada['cantidad']);
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

    error_log('Error al registrar compra múltiple: ' . $excepcion->getMessage());
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
