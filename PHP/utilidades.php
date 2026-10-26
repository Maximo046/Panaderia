<?php
// ============================================================
// utilidades.php — Funciones compartidas
// ============================================================

if (!function_exists('formatear_cantidad')) {
    /**
     * Formatea una cantidad con coma decimal y sin ceros sobrantes.
     * Ejemplo: 10.500 -> "10,5" / 10.000 -> "10" / 0.000 -> "0"
     */
    function formatear_cantidad($numero) {
        $texto = number_format((float) $numero, 3, ',', '');
        return rtrim(rtrim($texto, '0'), ',') ?: '0';
    }
}
