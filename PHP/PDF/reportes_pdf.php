<?php
// ============================================================
// reportes_pdf.php — Reporte de movimientos en PDF
// Diseño simple: encabezado, secciones y tablas con líneas finas.
// ============================================================

ob_start();

$pagina_actual = 'reportes';
session_start();
$prefijo_redireccion = '../../';
require_once '../../conexion.php';
require_once '../../auth.php';
require_once '../utilidades.php';

// ==================== Filtro del período y etiquetas ====================
$mes_seleccionado = '';
$filtro_produccion = '';
$filtro_compras    = '';
$filtro_salidas    = '';

$mes_solicitado = $_GET['mes'] ?? '';

if (is_string($mes_solicitado) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes_solicitado)) {
    $mes_seleccionado  = $mes_solicitado;
    $filtro_produccion = "WHERE DATE_FORMAT(p.fecha, '%Y-%m') = '$mes_seleccionado'";
    $filtro_compras    = "WHERE DATE_FORMAT(c.fecha, '%Y-%m') = '$mes_seleccionado'";
    $filtro_salidas    = "WHERE DATE_FORMAT(s.fecha, '%Y-%m') = '$mes_seleccionado'";
}

$nombres_meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
];

if ($mes_seleccionado !== '') {
    $marca_tiempo_mes = strtotime($mes_seleccionado . '-01');
    $etiqueta_periodo = $nombres_meses[(int) date('n', $marca_tiempo_mes)] . ' ' . date('Y', $marca_tiempo_mes);
} else {
    $etiqueta_periodo = 'Historial completo';
}

$sufijo_vacio = $mes_seleccionado ? ' para este mes' : '';

// ==================== Consultas de movimientos ====================
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

$ingresos_insumos = mysqli_query($conexion, "
    SELECT c.fecha, i.nombre AS nombre_insumo, c.cantidad, u.abreviatura
    FROM entrada_insumos c
    INNER JOIN insumos i          ON c.id_insumos = i.idinsumos
    INNER JOIN unidad_de_medida u ON i.idunidad_de_medida = u.idunidad_de_medida
    $filtro_compras
    ORDER BY c.fecha DESC, c.idcompras_insumos DESC
");

$produccion_registrada = mysqli_query($conexion, "
    SELECT p.fecha, pr.nombre AS nombre_producto, p.cantidad, u.abreviatura
    FROM produccion p
    INNER JOIN productos pr       ON p.id_producto = pr.idproductos
    INNER JOIN unidad_de_medida u ON pr.idunidad_de_medida = u.idunidad_de_medida
    $filtro_produccion
    ORDER BY p.fecha DESC
");

$salidas_registradas = mysqli_query($conexion, "
    SELECT s.fecha, pr.nombre AS nombre_producto, s.cantidad,
           s.motivo, s.observacion, u.abreviatura,
           CONCAT_WS(' ', us.nombre, us.apellido) AS nombre_usuario
    FROM salidas_productos s
    INNER JOIN productos pr        ON s.id_producto = pr.idproductos
    INNER JOIN unidad_de_medida u  ON pr.idunidad_de_medida = u.idunidad_de_medida
    LEFT  JOIN usuarios us         ON s.id_usuario = us.idusuarios
    $filtro_salidas
    ORDER BY s.fecha DESC
");

$total_consumos = $consumos_insumos ? mysqli_num_rows($consumos_insumos) : 0;
$total_ingresos = $ingresos_insumos ? mysqli_num_rows($ingresos_insumos) : 0;
$total_produccion = $produccion_registrada ? mysqli_num_rows($produccion_registrada) : 0;
$total_salidas = $salidas_registradas ? mysqli_num_rows($salidas_registradas) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ==================== Metadatos del documento PDF ==================== -->
    <meta charset="UTF-8"/>
    <title>Reporte de movimientos - Panadería Normal 10</title>
    <style>
        /* ==================== Estilos de impresión del reporte ==================== */
        @page {
            size: A4 portrait;
            margin: 18mm 16mm;
        }

        body {
            box-sizing: border-box;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #000;
        }

        div,
        header,
        footer,
        section,
        p,
        table,
        thead,
        tbody,
        tr,
        th,
        td,
        span {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .encabezado {
            padding-bottom: 8px;
            border-bottom: 1px solid #000;
        }

        .negocio {
            font-size: 15px;
            font-weight: bold;
        }

        .subtitulo {
            margin-top: 2px;
            font-size: 10px;
        }

        .meta {
            margin-top: 5px;
            font-size: 8px;
            color: #555;
        }

        .resumen {
            width: 100%;
            margin: 10px 0 15px;
            border-collapse: collapse;
        }

        .resumen td {
            width: 25%;
            padding: 6px 8px;
            border-top: 1px solid #bbb;
            border-bottom: 1px solid #bbb;
            text-align: left;
        }

        .resumen .etiqueta {
            display: block;
            font-size: 7.5px;
            color: #555;
        }

        .resumen .numero {
            display: block;
            font-size: 12px;
            font-weight: bold;
        }

        .seccion {
            margin-bottom: 14px;
        }

        .seccion-titulo {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 3px;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            font-size: 9.5px;
            font-weight: bold;
            page-break-after: avoid;
        }

        .seccion-titulo .cantidad {
            font-weight: normal;
            font-size: 8px;
            color: #555;
        }

        table.datos {
            width: 100%;
            border-collapse: collapse;
        }

        table.datos thead {
            display: table-header-group;
        }

        table.datos th {
            padding: 4px 5px;
            border-bottom: 1px solid #000;
            font-size: 8px;
            font-weight: bold;
            text-align: left;
        }

        table.datos td {
            padding: 4px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 8.5px;
            vertical-align: top;
        }

        table.datos tbody tr:last-child td {
            border-bottom: 1px solid #000;
        }

        table.datos tr {
            page-break-inside: avoid;
        }

        table.datos .numero {
            text-align: right;
            white-space: nowrap;
        }

        .vacio {
            padding: 4px 0;
            font-size: 8.5px;
            color: #555;
        }

        .pie {
            margin-top: 16px;
            padding-top: 6px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 7.5px;
            color: #666;
        }
    </style>
</head>
<body>
    <div>
        <!-- ==================== Encabezado y período del reporte ==================== -->
        <header class="encabezado">
            <div class="negocio">Panadería Normal 10</div>
            <div class="subtitulo">Reporte de movimientos</div>
            <div class="meta">
                Período: <?= htmlspecialchars($etiqueta_periodo) ?>
                &nbsp;·&nbsp;
                Emitido: <?= date('d/m/Y H:i') ?>
            </div>
        </header>

        <table class="resumen">
            <tr>
                <td><span class="etiqueta">Compras</span><span class="numero"><?= $total_ingresos ?></span></td>
                <td><span class="etiqueta">Consumos</span><span class="numero"><?= $total_consumos ?></span></td>
                <td><span class="etiqueta">Producción</span><span class="numero"><?= $total_produccion ?></span></td>
                <td><span class="etiqueta">Salidas</span><span class="numero"><?= $total_salidas ?></span></td>
            </tr>
        </table>

        <!-- ==================== Tabla de ingresos de insumos ==================== -->
        <section class="seccion">
            <div class="seccion-titulo">
                <span>Insumos: Ingresos</span>
                <span class="cantidad"><?= $total_ingresos ?> registro<?= $total_ingresos === 1 ? '' : 's' ?></span>
            </div>

            <?php if ($total_ingresos === 0): ?>
                <p class="vacio">No hay ingresos registrados<?= $sufijo_vacio ?>.</p>
            <?php else: ?>
                <table class="datos">
                    <thead>
                        <tr>
                            <th style="width:18%">Fecha</th>
                            <th>Insumo</th>
                            <th style="width:22%">Cantidad ingresada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($ingresos_insumos)): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                                <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                                <td class="numero"><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <!-- ==================== Tabla de consumos de insumos ==================== -->
        <section class="seccion">
            <div class="seccion-titulo">
                <span>Insumos: Consumos</span>
                <span class="cantidad"><?= $total_consumos ?> registro<?= $total_consumos === 1 ? '' : 's' ?></span>
            </div>

            <?php if ($total_consumos === 0): ?>
                <p class="vacio">No hay consumos registrados<?= $sufijo_vacio ?>.</p>
            <?php else: ?>
                <table class="datos">
                    <thead>
                        <tr>
                            <th style="width:18%">Fecha</th>
                            <th>Insumo</th>
                            <th style="width:22%">Cantidad consumida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($consumos_insumos)): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                                <td><?= htmlspecialchars($fila['nombre_insumo']) ?></td>
                                <td class="numero"><?= formatear_cantidad($fila['cantidad_consumida']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <!-- ==================== Tabla de producción ==================== -->
        <section class="seccion">
            <div class="seccion-titulo">
                <span>Productos: Producción</span>
                <span class="cantidad"><?= $total_produccion ?> registro<?= $total_produccion === 1 ? '' : 's' ?></span>
            </div>

            <?php if ($total_produccion === 0): ?>
                <p class="vacio">No hay producción registrada<?= $sufijo_vacio ?>.</p>
            <?php else: ?>
                <table class="datos">
                    <thead>
                        <tr>
                            <th style="width:18%">Fecha</th>
                            <th>Producto</th>
                            <th style="width:22%">Cantidad producida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($produccion_registrada)): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                                <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                                <td class="numero"><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <!-- ==================== Tabla de salidas de productos ==================== -->
        <section class="seccion">
            <div class="seccion-titulo">
                <span>Productos: Salidas</span>
                <span class="cantidad"><?= $total_salidas ?> registro<?= $total_salidas === 1 ? '' : 's' ?></span>
            </div>

            <?php if ($total_salidas === 0): ?>
                <p class="vacio">No hay salidas registradas<?= $sufijo_vacio ?>.</p>
            <?php else: ?>
                <table class="datos">
                    <thead>
                        <tr>
                            <th style="width:13%">Fecha</th>
                            <th>Producto</th>
                            <th style="width:16%">Cantidad</th>
                            <th style="width:18%">Motivo</th>
                            <th>Observación</th>
                            <th style="width:18%">Registró</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = mysqli_fetch_assoc($salidas_registradas)): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($fila['fecha'])) ?></td>
                                <td><?= htmlspecialchars($fila['nombre_producto']) ?></td>
                                <td class="numero"><?= formatear_cantidad($fila['cantidad']) ?> <?= htmlspecialchars($fila['abreviatura']) ?></td>
                                <td><?= htmlspecialchars($fila['motivo']) ?></td>
                                <td><?= $fila['observacion'] !== null && $fila['observacion'] !== '' ? htmlspecialchars($fila['observacion']) : '—' ?></td>
                                <td><?= htmlspecialchars($fila['nombre_usuario'] ?: '—') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>

        <!-- ==================== Pie del documento ==================== -->
        <footer class="pie">
            Documento generado automáticamente por el sistema de gestión · Escuela Normal N°10 · 2026
        </footer>
    </div>
</body>
</html>
<?php
// ==================== Renderizado y descarga del PDF ====================
$html = ob_get_clean();

require_once '../../Libreria/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$opciones = $dompdf->getOptions();
$opciones->set(['isRemoteEnabled' => false]);
$dompdf->setOptions($opciones);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Reporte-movimientos.pdf', ['Attachment' => false]);
