<?php
$pagina_actual = 'reportes';
session_start();
require_once '../../conexion.php';
require_once '../../auth.php';

$venta_resultado = mysqli_query($conexion, "
    SELECT v.idventas, v.fecha, v.total, mp.nombre AS metodo_pago
    FROM ventas v
    INNER JOIN metodos_pago mp ON mp.idmetodos_pago = v.id_metodopago
    ORDER BY v.idventas DESC
    LIMIT 1
");

if ($venta_resultado === false) {
    http_response_code(500);
    exit('Error al consultar la última venta: ' . htmlspecialchars(mysqli_error($conexion), ENT_QUOTES, 'UTF-8'));
}

$venta = mysqli_fetch_assoc($venta_resultado);
$detalle_venta = null;

if ($venta !== null) {
    $id_venta = (int) $venta['idventas'];
    $detalle_venta = mysqli_query($conexion, "
        SELECT p.nombre AS nombre_producto, dv.cantidad, dv.precio_unitario,
               dv.subtotal, u.abreviatura
        FROM detalle_venta dv
        INNER JOIN productos p ON p.idproductos = dv.id_producto
        INNER JOIN unidad_de_medida u ON u.idunidad_de_medida = p.idunidad_de_medida
        WHERE dv.id_venta = $id_venta
        ORDER BY dv.iddetalle_venta
    ");

    if ($detalle_venta === false) {
        http_response_code(500);
        exit('Error al consultar el detalle de la última venta: ' . htmlspecialchars(mysqli_error($conexion), ENT_QUOTES, 'UTF-8'));
    }
}

ob_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <title>Ticket de venta</title>
    <style>
        body { font-family: sans-serif; color: #222; font-size: 12px; }
        .ticket { width: 100%; }
        h1, .centrado { text-align: center; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .datos { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 7px 5px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f3f3f3; }
        .numero { text-align: right; }
        .total { margin-top: 16px; text-align: right; font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="ticket">
        <h1>Panadería</h1>
        <p class="centrado">Ticket de venta</p>

        <?php if ($venta === null): ?>
            <p class="centrado">No hay ventas registradas.</p>
        <?php else: ?>
            <div class="datos">
                <strong>Venta #:</strong> <?= (int) $venta['idventas'] ?><br>
                <strong>Fecha:</strong> <?= htmlspecialchars(date('d/m/Y H:i', strtotime($venta['fecha'])), ENT_QUOTES, 'UTF-8') ?><br>
                <strong>Método de pago:</strong> <?= htmlspecialchars($venta['metodo_pago'], ENT_QUOTES, 'UTF-8') ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="numero">Cantidad</th>
                        <th class="numero">Precio unitario</th>
                        <th class="numero">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($detalle_venta) === 0): ?>
                        <tr><td colspan="4">La venta no tiene productos registrados.</td></tr>
                    <?php else: ?>
                        <?php while ($linea = mysqli_fetch_assoc($detalle_venta)): ?>
                            <tr>
                                <td><?= htmlspecialchars($linea['nombre_producto'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="numero">
                                    <?= rtrim(rtrim(number_format((float) $linea['cantidad'], 3, ',', '.'), '0'), ',') ?>
                                    <?= htmlspecialchars($linea['abreviatura'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="numero">$<?= number_format((float) $linea['precio_unitario'], 2, ',', '.') ?></td>
                                <td class="numero">$<?= number_format((float) $linea['subtotal'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <p class="total">Total: $<?= number_format((float) $venta['total'], 2, ',', '.') ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

require_once '../../libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(['isRemoteEnabled' => true]);
$dompdf->setOptions($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();
$dompdf->stream('Ticket-venta.pdf', ['Attachment' => false]);
?>
