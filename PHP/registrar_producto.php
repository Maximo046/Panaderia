<?php
// ============================================================
// registrar_producto.php — Da de alta un nuevo producto
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';

if (empty($_POST['nombre']) || empty($_POST['idunidad_de_medida'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Completá todos los campos.']);
    exit;
}

$nombre_producto  = mysqli_real_escape_string($conexion, $_POST['nombre']);
$id_unidad_medida = mysqli_real_escape_string($conexion, $_POST['idunidad_de_medida']);

mysqli_begin_transaction($conexion);

$producto_creado = mysqli_query($conexion, "
    INSERT INTO productos (idunidad_de_medida, nombre)
    VALUES ('$id_unidad_medida', '$nombre_producto')
");

if (!$producto_creado) {
    mysqli_rollback($conexion);
    echo json_encode(['ok' => false, 'mensaje' => 'Error: ' . mysqli_error($conexion)]);
    exit;
}

$id_nuevo_producto = mysqli_insert_id($conexion);

// Todo producto nuevo debe tener su fila de stock desde el primer momento.
$stock_inicial = mysqli_query($conexion, "
    INSERT INTO stock_productos (id_producto, cantidad, stock_minimo)
    VALUES ('$id_nuevo_producto', 0, 10)
");

if (!$stock_inicial) {
    mysqli_rollback($conexion);
    echo json_encode(['ok' => false, 'mensaje' => 'Error: ' . mysqli_error($conexion)]);
    exit;
}

mysqli_commit($conexion);
mysqli_close($conexion);

echo json_encode([
    'ok'      => true,
    'mensaje' => "Producto \"$nombre_producto\" registrado correctamente.",
    'id'      => $id_nuevo_producto,
    'nombre'  => htmlspecialchars($nombre_producto),
]);
