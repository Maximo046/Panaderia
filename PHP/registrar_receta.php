<?php
// ============================================================
// registrar_receta.php — Asocia un insumo a la receta de un producto
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';

// ==================== Validación de datos de la receta ====================
if (empty($_POST['id_producto']) || empty($_POST['id_insumo']) || empty($_POST['cantidad'])) {
    echo json_encode(['ok' => false, 'mensaje' => 'Completá todos los campos.']);
    exit;
}

$id_producto_receta = mysqli_real_escape_string($conexion, $_POST['id_producto']);
$id_insumo_receta   = mysqli_real_escape_string($conexion, $_POST['id_insumo']);
$cantidad_en_receta = mysqli_real_escape_string($conexion, $_POST['cantidad']);

// ==================== Guardado o actualización de la receta ====================
// Si ya existe la combinación producto-insumo, actualiza la cantidad
$receta_guardada = mysqli_query($conexion, "
    INSERT INTO receta (id_producto, id_insumo, cantidad)
    VALUES ('$id_producto_receta', '$id_insumo_receta', '$cantidad_en_receta')
    ON DUPLICATE KEY UPDATE cantidad = VALUES(cantidad)
");

if (!$receta_guardada) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error: ' . mysqli_error($conexion)]);
    exit;
}

// ==================== Datos de respuesta para la interfaz ====================
// Obtener los nombres para mostrar en la interfaz sin recargar la página
$datos_receta = mysqli_fetch_assoc(mysqli_query($conexion, "
    SELECT p.nombre AS nombre_producto, i.nombre AS nombre_insumo, u.abreviatura
    FROM receta r
    INNER JOIN productos p        ON r.id_producto = p.idproductos
    INNER JOIN insumos   i        ON r.id_insumo   = i.idinsumos
    INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
    WHERE r.id_producto = '$id_producto_receta' AND r.id_insumo = '$id_insumo_receta'
"));

mysqli_close($conexion);

echo json_encode([
    'ok'              => true,
    'mensaje'         => 'Insumo agregado a la receta correctamente.',
    'nombre_producto' => htmlspecialchars($datos_receta['nombre_producto']),
    'nombre_insumo'   => htmlspecialchars($datos_receta['nombre_insumo']),
    'cantidad'        => $cantidad_en_receta,
    'unidad'          => htmlspecialchars($datos_receta['abreviatura']),
]);
