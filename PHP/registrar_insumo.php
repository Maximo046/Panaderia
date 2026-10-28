<?php
// ============================================================
// registrar_insumo.php — Da de alta un nuevo insumo
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';

// ==================== Validación del formulario ====================
if (empty($_POST['nombre_insumo']) || empty($_POST['idunidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Completá todos los campos.']);
    exit;
}

$nombre_insumo    = mysqli_real_escape_string($conexion, $_POST['nombre_insumo']);
$id_unidad_medida = mysqli_real_escape_string($conexion, $_POST['idunidad']);

// ==================== Alta del insumo y su stock inicial ====================
mysqli_begin_transaction($conexion);

$insumo_creado = mysqli_query($conexion, "
    INSERT INTO insumos (idunidad_de_medida, nombre)
    VALUES ('$id_unidad_medida', '$nombre_insumo')
");

if (!$insumo_creado) {
    mysqli_rollback($conexion);
    echo json_encode(['ok' => false, 'mensaje' => mysqli_error($conexion)]);
    exit;
}

$id_nuevo_insumo = mysqli_insert_id($conexion);

// Todo insumo nuevo debe tener su fila de stock desde el primer momento.
$stock_inicial = mysqli_query($conexion, "
    INSERT INTO stock_insumos (id_insumo, cantidad, stock_minimo)
    VALUES ('$id_nuevo_insumo', 0, 10)
");

if (!$stock_inicial) {
    mysqli_rollback($conexion);
    echo json_encode(['ok' => false, 'mensaje' => mysqli_error($conexion)]);
    exit;
}

mysqli_commit($conexion);
mysqli_close($conexion);

// ==================== Respuesta para la interfaz ====================
echo json_encode([
    'ok'      => true,
    'mensaje' => "Insumo \"$nombre_insumo\" agregado correctamente.",
    'id'      => $id_nuevo_insumo,
    'nombre'  => htmlspecialchars($nombre_insumo),
]);
