<?php
// ============================================================
// registrar_produccion.php — Registra una producción y actualiza stock
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';
require_once __DIR__ . '/alerta_stock.php';
require_once __DIR__ . '/utilidades.php';

if (empty($_POST['id_producto']) || empty($_POST['cantidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Completá todos los campos.']);
    exit;
}

$id_producto_a_producir = mysqli_real_escape_string($conexion, $_POST['id_producto']);
$cantidad_producida      = mysqli_real_escape_string($conexion, $_POST['cantidad']);

// Verificar que el producto tiene receta definida
$receta_del_producto = mysqli_query($conexion, "
    SELECT id_insumo, cantidad AS cantidad_por_unidad
    FROM receta
    WHERE id_producto = '$id_producto_a_producir'
");

if (mysqli_num_rows($receta_del_producto) === 0) {
    echo json_encode(['ok' => false, 'mensaje' => 'Este producto no tiene receta definida. Agregá los insumos en la sección Productos.']);
    exit;
}

// Guardar la receta en un array para usarla dos veces (verificación + descuento)
$lineas_receta = [];
while ($linea = mysqli_fetch_assoc($receta_del_producto)) {
    $lineas_receta[] = $linea;
}

// Verificar que hay suficiente stock de cada insumo antes de producir
foreach ($lineas_receta as $linea_receta) {
    $id_insumo_necesario   = $linea_receta['id_insumo'];
    $cantidad_a_consumir   = $linea_receta['cantidad_por_unidad'] * $cantidad_producida;

    $stock_insumo = mysqli_query($conexion, "
        SELECT s.cantidad AS stock_actual, i.nombre AS nombre_insumo
        FROM stock_insumos s
        INNER JOIN insumos i ON s.id_insumo = i.idinsumos
        WHERE s.id_insumo = '$id_insumo_necesario'
    ");

    if (mysqli_num_rows($stock_insumo) === 0) {
        echo json_encode(['ok' => false, 'mensaje' => 'No hay stock registrado para uno de los insumos requeridos.']);
        exit;
    }

    $fila_stock = mysqli_fetch_assoc($stock_insumo);
    if ($fila_stock['stock_actual'] < $cantidad_a_consumir) {
        $disponible = formatear_cantidad($fila_stock['stock_actual']);
        $necesario  = formatear_cantidad($cantidad_a_consumir);
        echo json_encode([
            'ok'      => false,
            'mensaje' => "Stock insuficiente de \"{$fila_stock['nombre_insumo']}\": necesitás {$necesario}, tenés {$disponible}.",
        ]);
        exit;
    }
}

// Registrar la producción en la tabla
$produccion_guardada = mysqli_query($conexion, "
    INSERT INTO produccion (id_producto, cantidad)
    VALUES ('$id_producto_a_producir', '$cantidad_producida')
");

if (!$produccion_guardada) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error al registrar la producción: ' . mysqli_error($conexion)]);
    exit;
}

// Descontar del stock de insumos lo que se consumió
foreach ($lineas_receta as $linea_receta) {
    $id_insumo_a_descontar = $linea_receta['id_insumo'];
    $cantidad_a_descontar  = $linea_receta['cantidad_por_unidad'] * $cantidad_producida;

    mysqli_query($conexion, "
        UPDATE stock_insumos
        SET cantidad = cantidad - $cantidad_a_descontar
        WHERE id_insumo = '$id_insumo_a_descontar'
    ");
}

// Sumar al stock de productos lo que se produjo
mysqli_query($conexion, "
    INSERT INTO stock_productos (id_producto, cantidad)
    VALUES ('$id_producto_a_producir', '$cantidad_producida')
    ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
");

enviar_alerta_stock($conexion);

// Obtener nombre del producto para la respuesta
$datos_producto = mysqli_fetch_assoc(mysqli_query($conexion, "
    SELECT p.nombre AS nombre_producto, u.abreviatura
    FROM productos p
    INNER JOIN unidad_de_medida u ON p.idunidad_de_medida = u.idunidad_de_medida
    WHERE p.idproductos = '$id_producto_a_producir'
"));

mysqli_close($conexion);

echo json_encode([
    'ok'              => true,
    'mensaje'         => 'Producción registrada correctamente.',
    'nombre_producto' => htmlspecialchars($datos_producto['nombre_producto']),
    'cantidad'        => $cantidad_producida,
    'unidad'          => htmlspecialchars($datos_producto['abreviatura']),
]);
