<?php
// ============================================================
// registrar_compra.php — Registra una entrada de insumo
// ============================================================

header('Content-Type: application/json');
require_once '../conexion.php';
require_once __DIR__ . '/alerta_stock.php';

// Verificar que todos los campos llegaron en el POST
$campos_requeridos = ['id_insumo', 'cantidad', 'precio', 'fecha_entrada', 'id_proveedor', 'numero_factura', 'id_metodopago'];
foreach ($campos_requeridos as $campo) {
    if (!isset($_POST[$campo])) {
        echo json_encode(['ok' => false, 'mensaje' => "Falta el campo: $campo"]);
        exit;
    }
}

// Escapar todos los valores para prevenir inyección SQL
$id_insumo_seleccionado  = mysqli_real_escape_string($conexion, $_POST['id_insumo']);
$cantidad_comprada       = mysqli_real_escape_string($conexion, $_POST['cantidad']);
$precio_compra           = mysqli_real_escape_string($conexion, $_POST['precio']);
$fecha_entrada           = mysqli_real_escape_string($conexion, $_POST['fecha_entrada']);
$id_proveedor_seleccionado = mysqli_real_escape_string($conexion, $_POST['id_proveedor']);
$numero_factura          = mysqli_real_escape_string($conexion, $_POST['numero_factura']);
$id_metodo_pago          = mysqli_real_escape_string($conexion, $_POST['id_metodopago']);

// Registrar la compra en la tabla de compras
$compra_registrada = mysqli_query($conexion, "
    INSERT INTO compras_insumos
        (id_metodopago, id_proveedor, id_insumos, numero_factura, cantidad, precio, fecha)
    VALUES
        ('$id_metodo_pago', '$id_proveedor_seleccionado', '$id_insumo_seleccionado',
         '$numero_factura', '$cantidad_comprada', '$precio_compra', '$fecha_entrada')
");

if (!$compra_registrada) {
    echo json_encode(['ok' => false, 'mensaje' => mysqli_error($conexion)]);
    exit;
}

// Actualizar el stock del insumo (suma si ya existe, inserta si no)
$stock_actualizado = mysqli_query($conexion, "
    INSERT INTO stock_insumos (id_insumo, cantidad)
    VALUES ('$id_insumo_seleccionado', '$cantidad_comprada')
    ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)
");

if (!$stock_actualizado) {
    echo json_encode(['ok' => false, 'mensaje' => mysqli_error($conexion)]);
    exit;
}

enviar_alerta_stock($conexion);

mysqli_close($conexion);
echo json_encode(['ok' => true, 'mensaje' => 'Entrada registrada correctamente.']);
