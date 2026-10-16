<?php

ob_start();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    
    <title>Reporte PDF</title>
    
    <style>

    * { margin: 0; padding: 0; }
    
    body { 
        font-family: 'Arial', sans-serif; 
        line-height: 1.6; 
        color: #333; 
        padding: 20px; 
    }
    h2 { 
        margin: 30px 0 15px 0; 
        color: #2e1503; 
        font-size: 16px; 
        border-bottom: 2px solid #d4822a; 
        padding-bottom: 8px; 
    }
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-bottom: 40px; 
        border: 1px solid #d4822a; 
    }
    th { 
        background: #d4822a; 
        color: #fff; 
        padding: 12px; 
        text-align: left; 
        font-weight: bold; 
        font-size: 11px; 
        border: 1px solid #d4822a; 
    }
    td { 
        padding: 10px 12px; 
        border: 1px solid #ddd; 
        font-size: 10px; 
    }
    tr:nth-child(even) { 
        background: #f9f9f9; 
    }
    .sin-datos { 
        padding: 20px; 
        text-align: center; 
        color: #999; 
        font-style: italic; 
        font-size: 12px;
    }
    </style>
</head>

<body>
    <?php
    // ============================================================
    // reportes.php — Historial de consumos, ingresos, producción y ventas
    // ============================================================

    $pagina_actual = 'reportes';
    session_start();
    require_once '../../conexion.php';
    require_once '../../auth.php';

    // Filtro por mes (formato YYYY-MM)
    $mes_seleccionado = '';
    $filtro_produccion = '';
    $filtro_compras    = '';
    $filtro_ventas     = '';

    if (!empty($_GET['mes']) && preg_match('/^\d{4}-\d{2}$/', $_GET['mes'])) {
        $mes_seleccionado  = $_GET['mes'];
        $filtro_produccion = "WHERE DATE_FORMAT(p.fecha, '%Y-%m') = '$mes_seleccionado'";
        $filtro_compras    = "WHERE DATE_FORMAT(c.fecha, '%Y-%m') = '$mes_seleccionado'";
        $filtro_ventas     = "WHERE DATE_FORMAT(v.fecha, '%Y-%m') = '$mes_seleccionado'";
    }

    // Función auxiliar para formatear cantidades (limpia ceros y usa coma decimal)
    function formatearCantidad($numero) {
    
        return str_replace('.', ',', rtrim(rtrim($numero, '0'), '.')) ?: '0';
    }
    ?>

        <?php
        $sufijo_vacio = $mes_seleccionado ? ' para este mes' : '';
        $consumos_insumos = mysqli_query($conexion, "
            SELECT p.fecha, i.nombre AS nombre_insumo,
                p.cantidad * r.cantidad AS cantidad_consumida, u.abreviatura
            FROM produccion p
            INNER JOIN receta r              ON p.id_producto = r.id_producto
            INNER JOIN insumos i             ON r.id_insumo = i.idinsumos
            INNER JOIN unidad_de_medida u    ON i.idunidad_de_medida = u.idunidad_de_medida
            $filtro_produccion
            ORDER BY p.fecha DESC
        ");
        ?>

        <h2>Insumos: Consumos</h2>
 
        <?php if (mysqli_num_rows($consumos_insumos) === 0): ?>
            <p class="sin-datos">No hay consumos registrados<?= $sufijo_vacio ?>.</p>
        <?php else: ?>
 
            <table>
                <thead>

                    <tr>
                        <th>Fecha</th>
                        <th>Insumo</th>
                        <th>Cantidad Consumida</th>
                    </tr>

                </thead>
 
                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($consumos_insumos)): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                        <td><?= formatearCantidad($fila['cantidad_consumida']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
 
            </table>
 
        <?php endif; ?>

        <?php
        // ---- INGRESOS DE INSUMOS ----
        $ingresos_insumos = mysqli_query($conexion, "
            SELECT c.fecha, i.nombre AS nombre_insumo, c.cantidad, u.abreviatura
            FROM compras_insumos c
            INNER JOIN insumos i          ON c.id_insumos = i.idinsumos
            INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
            $filtro_compras
            ORDER BY c.fecha DESC
        ");
        ?>

        <h2>Insumos: Ingresos</h2>

        <?php if (mysqli_num_rows($ingresos_insumos) === 0): ?>
            <p class="sin-datos">No hay ingresos registrados<?= $sufijo_vacio ?>.</p>
        <?php else: ?>

            <table>
                <thead>
                    
                    <tr>
                        <th>Fecha</th>
                        <th>Insumo</th>
                        <th>Cantidad Ingresada</th>
                    </tr>
                
                </thead>

                <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($ingresos_insumos)): ?>

                    <tr>
                        <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                        <td><?= formatearCantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>
            </table>

        <?php endif; ?>

        <?php
        // ---- PRODUCCIÓN ----
        $produccion_registrada = mysqli_query($conexion, "
            SELECT p.fecha, pr.nombre AS nombre_producto, p.cantidad, u.abreviatura
            FROM produccion p
            INNER JOIN productos pr       ON p.id_producto = pr.idproductos
            INNER JOIN unidad_de_medida u ON pr.idunidad_de_medida = u.idunidad_de_medida
            $filtro_produccion
            ORDER BY p.fecha DESC
        ");
        ?>

        <h2>Productos: Producción</h2>

        <?php if (mysqli_num_rows($produccion_registrada) === 0): ?>
            <p class="sin-datos">No hay producción registrada<?= $sufijo_vacio ?>.</p>
        <?php else: ?>

            <table>
                <thead>
            
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad Producida</th>
                    </tr>
                
                </thead>

                <tbody>

                    <?php while ($fila = mysqli_fetch_assoc($produccion_registrada)): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                        <td><?= formatearCantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                    </tr>
   
                    <?php endwhile; ?>
                </tbody>
   
            </table>
   
        <?php endif; ?>

        <?php
        // ---- VENTAS ----
        $ventas_registradas = mysqli_query($conexion, "
            SELECT v.fecha, pr.nombre AS nombre_producto,
                dv.cantidad, dv.precio_unitario, dv.subtotal, u.abreviatura
            FROM detalle_venta dv
            INNER JOIN ventas v            ON dv.id_venta = v.idventas
            INNER JOIN productos pr        ON dv.id_producto = pr.idproductos
            INNER JOIN unidad_de_medida u  ON pr.idunidad_de_medida = u.idunidad_de_medida
            $filtro_ventas
            ORDER BY v.fecha DESC
        ");
        ?>

        <h2>Productos: Ventas</h2>
        
        <?php if (mysqli_num_rows($ventas_registradas) === 0): ?>
            <p class="sin-datos">No hay ventas registradas<?= $sufijo_vacio ?>.</p>
        <?php else: ?>
        
            <table>
                <thead>

                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
    
                </thead>
        
                <tbody>
    
                    <?php while ($fila = mysqli_fetch_assoc($ventas_registradas)): ?>
        
                    <tr>
                        <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                        <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                        <td><?= formatearCantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                        <td>$<?= number_format($fila['precio_unitario'], 2, ',', '.') ?></td>
                        <td>$<?= number_format($fila['subtotal'], 2, ',', '.') ?></td>
                    </tr>
            
                    <?php endwhile; ?>
            
                </tbody>
            </table>
        
        <?php endif; ?>
    </body>
</html>


<?php

$html = ob_get_clean();
//echo $html;

require_once '../../libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$options = $dompdf -> getOptions();
$options -> set(array('isRemoteEnabled' => true));
$dompdf -> setOptions($options);

$dompdf -> loadHtml($html);
$dompdf -> setPaper('A4', 'portrait');

$dompdf -> render();
$dompdf -> stream("Reporte.pdf", array("Attachment" => false));

?>