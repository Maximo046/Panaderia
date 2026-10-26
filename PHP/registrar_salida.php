<?php
// ============================================================
// registrar_salida.php — Registra una salida de productos
// y descuenta el stock disponible.
// ============================================================

header('Content-Type: application/json; charset=UTF-8');

session_start();
require_once '../conexion.php';
require_once __DIR__ . '/alerta_stock.php';
require_once __DIR__ . '/utilidades.php';

if (!isset($_SESSION['usuario_rol']) || !in_array($_SESSION['usuario_rol'], ['Administrador', 'Panadero'], true)) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'mensaje' => 'No tenés permiso para registrar salidas.']);
    exit;
}

$motivos_validos = ['Venta mostrador', 'Consumo interno', 'Merma/vencido', 'Rotura', 'Donación', 'Otro'];

$motivo_salida    = trim($_POST['motivo'] ?? '');
$observacion      = trim($_POST['observacion'] ?? '');
$ids_productos    = $_POST['id_producto'] ?? [];
$cantidades       = $_POST['cantidad']    ?? [];

if (!in_array($motivo_salida, $motivos_validos, true)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Seleccioná un motivo válido.']);
    exit;
}

if (mb_strlen($observacion) > 255) {
    echo json_encode(['ok' => false, 'mensaje' => 'La observación no puede superar los 255 caracteres.']);
    exit;
}

$lineas_salida = [];
foreach ($ids_productos as $indice => $id_producto) {
    $cantidad = isset($cantidades[$indice]) ? (float) str_replace(',', '.', $cantidades[$indice]) : 0;

    if ($cantidad > 0) {
        $lineas_salida[] = [
            'id_producto' => (int) $id_producto,
            'cantidad'    => $cantidad,
        ];
    }
}

if (empty($lineas_salida)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Ingresá una cantidad mayor a cero en al menos un producto.']);
    exit;
}

$id_usuario = (int) ($_SESSION['usuario_id'] ?? 0) ?: null;

mysqli_begin_transaction($conexion);

try {
    $consulta_producto = $conexion->prepare("
        SELECT p.nombre AS nombre_producto, u.abreviatura
        FROM productos p
        INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
        WHERE p.idproductos = ?
        LIMIT 1
    ");
    $consulta_stock = $conexion->prepare("
        SELECT cantidad FROM stock_productos WHERE id_producto = ? FOR UPDATE
    ");
    $insertar_salida = $conexion->prepare("
        INSERT INTO salidas_productos (id_producto, id_usuario, cantidad, motivo, observacion)
        VALUES (?, ?, ?, ?, ?)
    ");
    $descontar_stock = $conexion->prepare("
        UPDATE stock_productos SET cantidad = cantidad - ? WHERE id_producto = ?
    ");

    if (!$consulta_producto || !$consulta_stock || !$insertar_salida || !$descontar_stock) {
        throw new RuntimeException('No se pudieron preparar las consultas.');
    }

    foreach ($lineas_salida as $linea) {
        $id_producto      = $linea['id_producto'];
        $cantidad_retirada = $linea['cantidad'];

        $consulta_producto->bind_param('i', $id_producto);
        $consulta_producto->execute();
        $producto = $consulta_producto->get_result()->fetch_assoc();
        $consulta_producto->free_result();

        if (!$producto) {
            throw new RuntimeException('Uno de los productos seleccionados no existe.');
        }

        $consulta_stock->bind_param('i', $id_producto);
        $consulta_stock->execute();
        $fila_stock = $consulta_stock->get_result()->fetch_assoc();
        $consulta_stock->free_result();

        $stock_actual = $fila_stock ? (float) $fila_stock['cantidad'] : 0.0;

        if ($stock_actual < $cantidad_retirada) {
            $disponible = formatear_cantidad($stock_actual);
            $pedido     = formatear_cantidad($cantidad_retirada);
            throw new RuntimeException(
                "Stock insuficiente de \"{$producto['nombre_producto']}\": hay {$disponible} {$producto['abreviatura']} y se intentan retirar {$pedido} {$producto['abreviatura']}."
            );
        }

        $insertar_salida->bind_param('iidss', $id_producto, $id_usuario, $cantidad_retirada, $motivo_salida, $observacion);
        if (!$insertar_salida->execute()) {
            throw new RuntimeException('No se pudo registrar la salida.');
        }

        $descontar_stock->bind_param('di', $cantidad_retirada, $id_producto);
        if (!$descontar_stock->execute()) {
            throw new RuntimeException('No se pudo actualizar el stock.');
        }
    }

    $consulta_producto->close();
    $consulta_stock->close();
    $insertar_salida->close();
    $descontar_stock->close();

    mysqli_commit($conexion);
} catch (Throwable $error) {
    mysqli_rollback($conexion);
    error_log('Registrar salida: ' . $error->getMessage());
    echo json_encode([
        'ok'      => false,
        'mensaje' => $error->getMessage(),
    ]);
    exit;
}

enviar_alerta_stock($conexion);

$cantidad_productos = count($lineas_salida);
echo json_encode([
    'ok'      => true,
    'mensaje' => "Salida registrada correctamente ({$cantidad_productos} producto" . ($cantidad_productos === 1 ? '' : 's') . ').',
]);
