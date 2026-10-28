-- ============================================================
-- Migración: reemplazo del módulo de ventas por salidas de productos
-- Fecha: 2026-10-25
--
-- 1. Crea la tabla salidas_productos.
-- 2. Elimina el usuario y el rol Vendedor.
-- 3. Elimina las tablas ventas y detalle_venta.
--
-- Respaldo previo de las ventas:
--   descartado/backup_ventas_2026-10-25.sql
-- ============================================================

START TRANSACTION;

-- ------------------------------------------------------------
-- Salidas de productos
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `salidas_productos` (
  `idsalida` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `motivo` varchar(40) NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idsalida`),
  KEY `fk_salida_producto_idx` (`id_producto`),
  KEY `fk_salida_usuario_idx` (`id_usuario`),
  KEY `idx_salidas_fecha` (`fecha`),
  CONSTRAINT `fk_salida_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`),
  CONSTRAINT `fk_salida_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`idusuarios`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Eliminar usuario y rol Vendedor
-- ------------------------------------------------------------
DELETE FROM `usuarios`
WHERE `id_rol` IN (SELECT `idroles` FROM `roles` WHERE `nombre` = 'Vendedor');

DELETE FROM `roles` WHERE `nombre` = 'Vendedor';

-- ------------------------------------------------------------
-- Eliminar tablas de ventas (respaldadas previamente)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `detalle_venta`;
DROP TABLE IF EXISTS `ventas`;

COMMIT;
