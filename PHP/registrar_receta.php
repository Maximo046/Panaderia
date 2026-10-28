<?php
// ============================================================
// registrar_receta.php — Registra uno o varios insumos para una receta
// ============================================================

header('Content-Type: application/json; charset=utf-8');

require_once '../conexion.php';

function responder_receta(array $datos, int $codigo_http = 200): void
{
    http_response_code($codigo_http);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($_POST['id_producto'])) {
    responder_receta(['ok' => false, 'mensaje' => 'Seleccioná un producto.'], 400);
}

$id_producto = (int) $_POST['id_producto'];
$insumos     = $_POST['id_insumo'] ?? [];
$cantidades  = $_POST['cantidad'] ?? [];

if (!is_array($insumos) || !is_array($cantidades)) {
    responder_receta(['ok' => false, 'mensaje' => 'Completá todos los insumos y sus cantidades.'], 400);
}

$entradas = [];
for ($i = 0; $i < count($insumos); $i++) {
    $id_insumo_raw = $insumos[$i] ?? null;
    $cant_raw      = $cantidades[$i] ?? null;

    if ($id_insumo_raw === null || $cant_raw === null || $cant_raw === '') {
        continue;
    }

    $id_insumo = filter_var($id_insumo_raw, FILTER_VALIDATE_INT);
    if ($id_insumo === false || $id_insumo <= 0) {
        continue;
    }

    $cant_texto = str_replace(',', '.', trim($cant_raw));
    $cantidad   = filter_var($cant_texto, FILTER_VALIDATE_FLOAT);
    if ($cantidad === false || $cantidad <= 0) {
        continue;
    }

    $entradas[] = [
        'id_insumo' => $id_insumo,
        'cantidad'  => $cantidad
    ];
}

if (empty($entradas)) {
    responder_receta(['ok' => false, 'mensaje' => 'Ingresá al menos un insumo con su cantidad.'], 400);
}

mysqli_begin_transaction($conexion);

try {
    $verificar_producto = $conexion->prepare("SELECT idproductos FROM productos WHERE idproductos = ?");
    $verificar_producto->bind_param('i', $id_producto);
    $verificar_producto->execute();
    $verificar_producto->store_result();
    if ($verificar_producto->num_rows === 0) {
        $verificar_producto->close();
        throw new Exception('Producto no encontrado.');
    }
    $verificar_producto->close();

    $stmt = $conexion->prepare("INSERT IGNORE INTO receta (id_producto, id_insumo, cantidad) VALUES (?, ?, ?)");
    $agregados = 0;

    foreach ($entradas as $entrada) {
        $verificar_insumo = $conexion->prepare("SELECT idinsumos FROM insumos WHERE idinsumos = ?");
        $verificar_insumo->bind_param('i', $entrada['id_insumo']);
        $verificar_insumo->execute();
        $verificar_insumo->store_result();
        if ($verificar_insumo->num_rows === 0) {
            $verificar_insumo->close();
            continue;
        }
        $verificar_insumo->close();

        $stmt->bind_param('iid', $id_producto, $entrada['id_insumo'], $entrada['cantidad']);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $agregados++;
        }
    }

    $stmt->close();
    mysqli_commit($conexion);
    mysqli_close($conexion);

    if ($agregados === 0) {
        responder_receta(['ok' => false, 'mensaje' => 'Esos insumos ya se encuentran cargados en la receta de este producto.'], 400);
    }

    $mensaje = $agregados === 1 ? '1 ingrediente agregado a la receta correctamente.' : $agregados . ' ingredientes agregados a la receta correctamente.';
    responder_receta(['ok' => true, 'mensaje' => $mensaje]);
} catch (Throwable $e) {
    mysqli_rollback($conexion);
    mysqli_close($conexion);
    responder_receta(['ok' => false, 'mensaje' => 'No se pudo guardar la receta. Intentá nuevamente.'], 500);
}
