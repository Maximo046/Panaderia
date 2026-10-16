<?php
// ============================================================
// registrar_insumo.php — Da de alta un nuevo insumo
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';

if (empty($_POST['nombre_insumo']) || empty($_POST['idunidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Completá todos los campos.']);
    exit;
}

$nombre_insumo    = mysqli_real_escape_string($conexion, $_POST['nombre_insumo']);
$id_unidad_medida = mysqli_real_escape_string($conexion, $_POST['idunidad']);

$insumo_creado = mysqli_query($conexion, "
    INSERT INTO insumos (idunidad_de_medida, nombre)
    VALUES ('$id_unidad_medida', '$nombre_insumo')
");

if (!$insumo_creado) {
    echo json_encode(['ok' => false, 'mensaje' => mysqli_error($conexion)]);
    exit;
}

$id_nuevo_insumo = mysqli_insert_id($conexion);
mysqli_close($conexion);

echo json_encode([
    'ok'      => true,
    'mensaje' => "Insumo \"$nombre_insumo\" agregado correctamente.",
    'id'      => $id_nuevo_insumo,
    'nombre'  => htmlspecialchars($nombre_insumo),
]);
