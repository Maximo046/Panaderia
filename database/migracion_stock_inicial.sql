-- ============================================================
-- Migración: inicializar stock para insumos y productos existentes
-- Fecha: 2026-10-26
--
-- Crea la fila de stock que falta para cada insumo o producto
-- registrado, con cantidad 0 y stock mínimo 10. Es idempotente:
-- si la fila ya existe, no la modifica.
-- ============================================================

START TRANSACTION;

-- ------------------------------------------------------------
-- Insumos registrados sin fila en stock_insumos
-- ------------------------------------------------------------
INSERT INTO `stock_insumos` (`id_insumo`, `cantidad`, `stock_minimo`)
SELECT i.`idinsumos`, 0, 10
FROM `insumos` i
LEFT JOIN `stock_insumos` s ON s.`id_insumo` = i.`idinsumos`
WHERE s.`id_insumo` IS NULL;

-- ------------------------------------------------------------
-- Productos registrados sin fila en stock_productos
-- ------------------------------------------------------------
INSERT INTO `stock_productos` (`id_producto`, `cantidad`, `stock_minimo`)
SELECT p.`idproductos`, 0, 10
FROM `productos` p
LEFT JOIN `stock_productos` s ON s.`id_producto` = p.`idproductos`
WHERE s.`id_producto` IS NULL;

COMMIT;
