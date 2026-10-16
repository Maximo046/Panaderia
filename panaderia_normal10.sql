-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2026 a las 05:49:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `panaderia_normal10`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asunto`
--

CREATE TABLE `asunto` (
  `idasunto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asunto`
--

INSERT INTO `asunto` (`idasunto`, `nombre`) VALUES
(1, 'Consulta sobre el sistema'),
(2, 'Reporte de error'),
(3, 'Sugerencia de mejora'),
(4, 'Información del proyecto'),
(5, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_insumos`
--

CREATE TABLE `compras_insumos` (
  `idcompras_insumos` int(11) NOT NULL,
  `id_metodopago` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_insumos` int(11) NOT NULL,
  `numero_factura` varchar(20) DEFAULT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras_insumos`
--

INSERT INTO `compras_insumos` (`idcompras_insumos`, `id_metodopago`, `id_proveedor`, `id_insumos`, `numero_factura`, `cantidad`, `precio`, `fecha`) VALUES
(1, 1, 1, 1, '0001', 100.000, 80000.00, '2026-05-23'),
(2, 2, 2, 1, '0001', 50.000, 40000.00, '2026-05-22'),
(3, 1, 7, 2, '0001', 5.000, 5000.00, '2026-05-21'),
(4, 2, 3, 8, '0001', 5.000, 7000.00, '2026-05-20'),
(5, 2, 7, 16, '0004', 50.000, 50000.00, '2026-05-17'),
(6, 1, 3, 8, '0002', 20.000, 25000.00, '2026-05-18'),
(7, 2, 7, 4, '0003', 15.000, 20000.00, '2026-05-11'),
(8, 1, 7, 3, '0005', 30.000, 30000.00, '2026-05-14'),
(9, 1, 4, 7, '0001', 50.000, 30000.00, '2026-01-16'),
(10, 1, 3, 18, '0006', 20.000, 35000.00, '2026-05-24'),
(11, 1, 1, 16, '0005', 15000.000, 10000.00, '2025-12-18'),
(12, 1, 7, 2, '0007', 15.000, 10000.00, '2025-11-06'),
(13, 1, 7, 5, '0007', 50.000, 60000.00, '2026-02-10'),
(14, 1, 3, 6, '0012', 50.000, 65000.00, '2026-06-09'),
(15, 1, 2, 20, '0010', 50.000, 74299.00, '2026-06-11'),
(16, 1, 6, 21, '0010', 50.000, 70644.00, '2026-06-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `idcontacto` int(11) NOT NULL,
  `id_asunto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `mensaje` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`idcontacto`, `id_asunto`, `nombre`, `correo`, `mensaje`) VALUES
(1, 5, 'Máximo Emanuel Pedrozo', '', 'agreguen chipa al menu'),
(2, 5, 'Máximo Emanuel Pedrozo', '', 'agreguen chipa al menu'),
(3, 3, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'agreguen chipa al menu'),
(4, 3, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'agreguen chipa al menu'),
(5, 3, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'agreguen chipa al menu'),
(7, 3, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'agreguen chipa al menu'),
(8, 4, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'agreguen chipa al menu'),
(9, 1, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', ''),
(10, 3, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'agreguen chipa al menu'),
(12, 1, '', '', ''),
(13, 4, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'hola');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `iddetalle_venta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`iddetalle_venta`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 2, 4.000, 1200.00, 4800.00),
(2, 2, 2, 3.000, 1200.00, 3600.00),
(3, 3, 1, 4.000, 400.00, 1600.00),
(4, 3, 2, 2.000, 1200.00, 2400.00),
(5, 3, 4, 3.000, 6000.00, 18000.00),
(6, 4, 1, 1.000, 400.00, 400.00),
(7, 4, 2, 1.000, 1200.00, 1200.00),
(8, 4, 4, 1.000, 6000.00, 6000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumos`
--

CREATE TABLE `insumos` (
  `idinsumos` int(11) NOT NULL,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `insumos`
--

INSERT INTO `insumos` (`idinsumos`, `idunidad_de_medida`, `nombre`) VALUES
(1, 1, 'Harina 000'),
(2, 1, 'Azúcar'),
(3, 1, 'Sal'),
(4, 1, 'Levadura Fresca'),
(5, 2, 'Aceite Vegetal'),
(6, 2, 'Leche'),
(7, 3, 'Huevos'),
(8, 1, 'Manteca'),
(9, 1, 'Margarina'),
(10, 1, 'Dulce de Leche'),
(11, 1, 'Chocolate para Repostería'),
(12, 1, 'Fécula de Maíz'),
(13, 2, 'Crema de Leche'),
(14, 2, 'Esencia de Vainilla'),
(15, 1, 'Polvo de Hornear'),
(16, 2, 'Agua'),
(17, 1, 'Harina 0000'),
(18, 1, 'Queso'),
(19, 1, 'Grasa'),
(20, 1, 'Harina Leudante'),
(21, 1, 'Almidón de mandioca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `idmetodos_pago` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`idmetodos_pago`, `nombre`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta de Debito'),
(3, 'Tarjeta de Credito'),
(4, 'Cheque');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `idproduccion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `produccion`
--

INSERT INTO `produccion` (`idproduccion`, `id_producto`, `cantidad`, `fecha`) VALUES
(1, 2, 1.000, '2026-05-23 03:44:48'),
(2, 2, 3.000, '2026-05-23 03:45:17'),
(3, 2, 2.000, '2026-06-13 21:10:13'),
(4, 2, 10.000, '2026-06-13 21:10:17'),
(5, 4, 5.000, '2026-06-13 22:21:08'),
(6, 1, 6.000, '2026-06-13 22:21:12'),
(7, 3, 6.000, '2026-06-14 00:46:58'),
(8, 1, 10.000, '2026-06-14 00:47:03'),
(9, 2, 10.000, '2026-06-14 00:47:08'),
(10, 4, 4.000, '2026-06-14 00:47:29'),
(11, 3, 30.000, '2026-06-14 00:47:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idproductos` int(11) NOT NULL,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idproductos`, `idunidad_de_medida`, `nombre`) VALUES
(1, 3, 'Factura'),
(2, 1, 'Pan'),
(3, 1, 'Chipa'),
(4, 3, 'Torta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idproveedores` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idproveedores`, `nombre`, `telefono`, `correo`, `direccion`) VALUES
(1, 'Molino San José', '3764123456', 'ventas@molinosanjose.com', 'Av. Industrial 123'),
(2, 'Distribuidora El Trigal', '3764234567', 'contacto@eltrigal.com', 'Ruta Nacional 12 Km 15'),
(3, 'Lácteos La Esperanza', '3764345678', 'ventas@laesperanza.com', 'Calle Belgrano 456'),
(4, 'Huevos Don Carlos', '3764456789', 'pedidos@huevosdoncarlos.com', 'Barrio Rural S/N'),
(5, 'Dulces del Norte', '3764567890', 'info@dulcesdelnorte.com', 'Av. San Martín 890'),
(6, 'Insumos Panificados Misiones', '3764678901', 'ventas@ipm.com', 'Ruta Provincial 7 Km 3'),
(7, 'Distribuidora Central', '3764789012', 'central@distribuidora.com', 'Calle Corrientes 245');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receta`
--

CREATE TABLE `receta` (
  `idreceta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `receta`
--

INSERT INTO `receta` (`idreceta`, `id_producto`, `id_insumo`, `cantidad`) VALUES
(2, 2, 1, 1.000),
(3, 2, 4, 0.025),
(4, 2, 2, 0.015),
(5, 2, 8, 0.080),
(6, 2, 3, 0.030),
(7, 2, 16, 0.600),
(8, 1, 1, 1.000),
(9, 1, 6, 0.500),
(10, 1, 4, 0.050),
(11, 1, 2, 0.150),
(12, 1, 8, 0.100),
(13, 1, 7, 2.000),
(14, 4, 7, 2.000),
(15, 4, 5, 0.050),
(16, 4, 2, 0.200),
(17, 4, 20, 0.226),
(18, 4, 6, 0.225),
(19, 3, 21, 1.000),
(20, 3, 6, 0.300),
(21, 3, 3, 0.020),
(22, 3, 8, 0.200),
(23, 3, 18, 0.500);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idroles` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idroles`, `nombre`) VALUES
(1, 'Panadero'),
(2, 'Vendedor'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_insumos`
--

CREATE TABLE `stock_insumos` (
  `idstock_insumos` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `stock_minimo` decimal(10,3) NOT NULL DEFAULT 10.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_insumos`
--

INSERT INTO `stock_insumos` (`idstock_insumos`, `id_insumo`, `cantidad`, `stock_minimo`) VALUES
(1, 1, 108.000, 10.000),
(3, 2, 15.410, 10.000),
(4, 8, 14.120, 10.000),
(5, 16, 15034.400, 10.000),
(7, 4, 13.550, 10.000),
(8, 3, 28.500, 10.000),
(9, 7, 0.000, 10.000),
(10, 18, 2.000, 10.000),
(13, 5, 49.550, 10.000),
(14, 6, 29.175, 10.000),
(15, 20, 47.966, 10.000),
(16, 21, 14.000, 10.000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_productos`
--

CREATE TABLE `stock_productos` (
  `idstock_productos` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL DEFAULT 0.000,
  `stock_minimo` decimal(10,3) NOT NULL DEFAULT 10.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_productos`
--

INSERT INTO `stock_productos` (`idstock_productos`, `id_producto`, `cantidad`) VALUES
(1, 2, 12.000),
(2, 1, 11.000),
(3, 3, 36.000),
(4, 4, 5.000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_de_medida`
--

CREATE TABLE `unidad_de_medida` (
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `abreviatura` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad_de_medida`
--

INSERT INTO `unidad_de_medida` (`idunidad_de_medida`, `nombre`, `abreviatura`) VALUES
(1, 'Kilogramo', 'Kg'),
(2, 'Litros', 'Lt'),
(3, 'Unidad', 'Un.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuarios` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `id_rol`, `nombre`, `apellido`, `correo`, `contrasena`, `fecha_registro`, `activo`) VALUES
(1, 3, 'Admin', 'Sistema', 'admin@panaderia.com', '$2y$10$AtO6MovdgMPd3sdhclz2jeb6R1w2KQrvglqeU0TTHO3yr8tB6tXou', '2026-06-13 20:49:59', 1),
(2, 1, 'Maximo', 'Pedrozo', 'pedrozomaximo76@gmail.com', '$2y$10$ftwWBIWaT51gZcka0A5WUOAu5C24ZyKs8VujaMo478IzE9UywnUNK', '2026-06-13 22:52:19', 1),
(3, 2, 'Imanol', 'Balguenet', 'imabalg@gmail.com', '$2y$10$QQRXNhTcxKamSMcHsZNi6.t1Gae66SYTyPt.2SpPrj9f9FiyMuYVC', '2026-06-13 23:46:44', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `idventas` int(11) NOT NULL,
  `id_metodopago` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`idventas`, `id_metodopago`, `fecha`, `total`) VALUES
(1, 1, '2026-06-13 21:49:06', 4800.00),
(2, 2, '2026-06-13 21:49:22', 3600.00),
(3, 1, '2026-06-13 22:23:37', 22000.00),
(4, 3, '2026-06-13 23:49:43', 7600.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asunto`
--
ALTER TABLE `asunto`
  ADD PRIMARY KEY (`idasunto`);

--
-- Indices de la tabla `compras_insumos`
--
ALTER TABLE `compras_insumos`
  ADD PRIMARY KEY (`idcompras_insumos`),
  ADD KEY `fk_compra_proveedor_idx` (`id_proveedor`),
  ADD KEY `fk_compra_insumo_idx` (`id_insumos`),
  ADD KEY `fk_compras_insumos_metodos_pago1_idx` (`id_metodopago`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`idcontacto`),
  ADD KEY `fk_asunto_idx` (`id_asunto`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`iddetalle_venta`),
  ADD KEY `fk_detalle_venta_idx` (`id_venta`),
  ADD KEY `fk_detalle_producto_idx` (`id_producto`);

--
-- Indices de la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`idinsumos`),
  ADD KEY `fk_insumo_unidad_idx` (`idunidad_de_medida`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`idmetodos_pago`);

--
-- Indices de la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`idproduccion`),
  ADD KEY `fk_produccion_producto` (`id_producto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idproductos`),
  ADD KEY `fk_producto_unidad_idx` (`idunidad_de_medida`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idproveedores`);

--
-- Indices de la tabla `receta`
--
ALTER TABLE `receta`
  ADD PRIMARY KEY (`idreceta`),
  ADD UNIQUE KEY `uq_receta_producto_insumo` (`id_producto`,`id_insumo`),
  ADD KEY `fk_receta_producto_idx` (`id_producto`),
  ADD KEY `fk_receta_insumo_idx` (`id_insumo`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idroles`);

--
-- Indices de la tabla `stock_insumos`
--
ALTER TABLE `stock_insumos`
  ADD PRIMARY KEY (`idstock_insumos`),
  ADD UNIQUE KEY `id_insumo` (`id_insumo`),
  ADD KEY `fk_stock_ins_insumo_idx` (`id_insumo`);

--
-- Indices de la tabla `stock_productos`
--
ALTER TABLE `stock_productos`
  ADD PRIMARY KEY (`idstock_productos`),
  ADD UNIQUE KEY `uq_stock_producto` (`id_producto`);

--
-- Indices de la tabla `unidad_de_medida`
--
ALTER TABLE `unidad_de_medida`
  ADD PRIMARY KEY (`idunidad_de_medida`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuarios`),
  ADD UNIQUE KEY `uq_usuario_correo` (`correo`),
  ADD KEY `fk_usuario_rol_idx` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`idventas`),
  ADD KEY `fk_venta_metodopago_idx` (`id_metodopago`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asunto`
--
ALTER TABLE `asunto`
  MODIFY `idasunto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `compras_insumos`
--
ALTER TABLE `compras_insumos`
  MODIFY `idcompras_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `idcontacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `iddetalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `idinsumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `idmetodos_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idproduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idproveedores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `receta`
--
ALTER TABLE `receta`
  MODIFY `idreceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idroles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `stock_insumos`
--
ALTER TABLE `stock_insumos`
  MODIFY `idstock_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `stock_productos`
--
ALTER TABLE `stock_productos`
  MODIFY `idstock_productos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `unidad_de_medida`
--
ALTER TABLE `unidad_de_medida`
  MODIFY `idunidad_de_medida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `idventas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras_insumos`
--
ALTER TABLE `compras_insumos`
  ADD CONSTRAINT `fk_compra_insumo` FOREIGN KEY (`id_insumos`) REFERENCES `insumos` (`idinsumos`),
  ADD CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`idproveedores`),
  ADD CONSTRAINT `fk_compras_insumos_metodos_pago1` FOREIGN KEY (`id_metodopago`) REFERENCES `metodos_pago` (`idmetodos_pago`);

--
-- Filtros para la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `fk_asunto` FOREIGN KEY (`id_asunto`) REFERENCES `asunto` (`idasunto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`),
  ADD CONSTRAINT `fk_detalle_venta` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`idventas`);

--
-- Filtros para la tabla `insumos`
--
ALTER TABLE `insumos`
  ADD CONSTRAINT `fk_insumo_unidad` FOREIGN KEY (`idunidad_de_medida`) REFERENCES `unidad_de_medida` (`idunidad_de_medida`);

--
-- Filtros para la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD CONSTRAINT `fk_produccion_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_unidad` FOREIGN KEY (`idunidad_de_medida`) REFERENCES `unidad_de_medida` (`idunidad_de_medida`);

--
-- Filtros para la tabla `receta`
--
ALTER TABLE `receta`
  ADD CONSTRAINT `fk_receta_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`idinsumos`),
  ADD CONSTRAINT `fk_receta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`);

--
-- Filtros para la tabla `stock_insumos`
--
ALTER TABLE `stock_insumos`
  ADD CONSTRAINT `fk_stock_ins_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`idinsumos`);

--
-- Filtros para la tabla `stock_productos`
--
ALTER TABLE `stock_productos`
  ADD CONSTRAINT `fk_stock_prod_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`idroles`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_venta_metodopago` FOREIGN KEY (`id_metodopago`) REFERENCES `metodos_pago` (`idmetodos_pago`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
