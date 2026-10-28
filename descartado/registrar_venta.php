<?php
// ============================================================
// registrar_venta.php — Registra una venta y descuenta el stock
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';
require_once __DIR__ . '/alerta_stock.php';

if (empty($_POST['id_metodopago'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Seleccioná un método de pago.']);
    exit;
}

$id_metodo_pago    = (int) $_POST['id_metodopago'];
$ids_productos     = $_POST['id_producto'] ?? [];
$cantidades        = $_POST['cantidad']    ?? [];
$precios_unitarios = $_POST['precio']      ?? [];

// Filtrar solo los productos con cantidad y precio mayores a 0
$lineas_venta = [];
foreach ($ids_productos as $indice => $id_producto) {
    $cantidad_vendida  = isset($cantidades[$indice])        ? (float) $cantidades[$indice]        : 0;
    $precio_por_unidad = isset($precios_unitarios[$indice]) ? (float) $precios_unitarios[$indice] : 0;

    if ($cantidad_vendida > 0 && $precio_por_unidad > 0) {
        $lineas_venta[] = [
            'id_producto'      => (int) $id_producto,
            'cantidad_vendida' => $cantidad_vendida,
            'precio_unitario'  => $precio_por_unidad,
        ];
    }
}

if (empty($lineas_venta)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Ingresá cantidad y precio en al menos un producto.']);
    exit;
}

// Verificar stock suficiente para cada producto de la venta
foreach ($lineas_venta as $linea) {
    $stock_disponible = mysqli_query($conexion, "
        SELECT s.cantidad AS stock_actual, p.nombre AS nombre_producto
        FROM stock_productos s
        INNER JOIN productos p ON p.idproductos = s.id_producto
        WHERE s.id_producto = {$linea['id_producto']}
    ");

    if (!$stock_disponible || mysqli_num_rows($stock_disponible) === 0) {
        echo json_encode(['ok' => false, 'mensaje' => 'Sin stock registrado para uno de los productos.']);
        exit;
    }

    $fila_stock = mysqli_fetch_assoc($stock_disponible);
    if ($fila_stock['stock_actual'] < $linea['cantidad_vendida']) {
        echo json_encode([
            'ok'      => false,
            'mensaje' => "Stock insuficiente de \"{$fila_stock['nombre_producto']}\": tenés {$fila_stock['stock_actual']}, necesitás {$linea['cantidad_vendida']}.",
        ]);
        exit;
    }
}

// Calcular el total de la venta
$total_venta = array_sum(array_map(
    fn($linea) => round($linea['cantidad_vendida'] * $linea['precio_unitario'], 2),
    $lineas_venta
));

// Insertar la venta en la tabla principal
mysqli_query($conexion, "INSERT INTO ventas (id_metodopago, total) VALUES ($id_metodo_pago, $total_venta)");
$id_venta_nueva = mysqli_insert_id($conexion);

// Insertar el detalle y descontar stock de cada producto vendido
foreach ($lineas_venta as $linea) {
    $subtotal_linea = round($linea['cantidad_vendida'] * $linea['precio_unitario'], 2);

    mysqli_query($conexion, "
        INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal)
        VALUES ($id_venta_nueva, {$linea['id_producto']}, {$linea['cantidad_vendida']}, {$linea['precio_unitario']}, $subtotal_linea)
    ");

    mysqli_query($conexion, "
        UPDATE stock_productos
        SET cantidad = cantidad - {$linea['cantidad_vendida']}
        WHERE id_producto = {$linea['id_producto']}
    ");
}

enviar_alerta_stock($conexion);

mysqli_close($conexion);
echo json_encode([
    'ok'      => true,
    'mensaje' => "Venta #{$id_venta_nueva} registrada. Total: $" . number_format($total_venta, 2, ',', '.'),
]);
