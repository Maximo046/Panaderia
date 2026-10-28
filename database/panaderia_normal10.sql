-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2026 a las 00:34:46
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
(16, 1, 6, 21, '0010', 50.000, 70644.00, '2026-06-12'),
(17, 2, 4, 7, '0012', 100.000, 0.00, '2026-06-14'),
(18, 2, 3, 18, '0008', 40.000, 50000.00, '2026-06-14'),
(19, 1, 2, 6, '0029', 50.000, 50000.00, '2026-08-15'),
(20, 1, 3, 22, '0026', 60.000, 125000.00, '2026-08-16'),
(21, 1, 2, 21, '0031', 50.000, 70000.00, '2026-08-18'),
(22, 1, 7, 23, '0013', 70.000, 120000.00, '2026-08-28'),
(23, 2, 3, 8, '0023', 90.000, 120000.00, '2026-09-12'),
(24, 1, 6, 23, '0044', 30.000, 40000.00, '2026-09-20');

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
(13, 4, 'Máximo Emanuel Pedrozo', 'pedrozomaximo76@gmail.com', 'hola'),
(14, 1, 'Ryzen 5 5600G', 'admin@panaderia.com', 'chbgkmjmfgchykj'),
(15, 2, 'Ryzen 5 3200G', 'pedrozomaximo973@gmail.com', 'no anda el apartado de ayuda'),
(16, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(17, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),
(18, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'holaaaaaaaaaaaaa'),
(19, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'holaaaaaaaaaaaaa'),
(20, 1, 'pepe', 'admin1@panaderia.com', 'holaaaa'),
(21, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(22, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(23, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(24, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(25, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(26, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(27, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(28, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(29, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(30, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(31, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(32, 2, 'Maximosdfvc', 'pedrozomaximo973@gmail.com', 'hola'),
(33, 2, 'Maximosdfvc', 'pedrozomaximo973@gmail.com', 'hola'),
(34, 3, 'Ryzen 5 5600G', 'imabalg@gmail.com', 'jushxdcjasdfjghasd'),
(35, 2, 'Maximo', 'pedrozomaximo973@gmail.com', 'hola'),
(36, 2, 'Maximo', 'imabalg@gmail.com', 'jfkuhfjhgf'),
(37, 2, 'Maximo', 'imabalg@gmail.com', 'jfkuhfjhgf'),
(38, 2, 'pepe', 'imabalg@gmail.com', 'pppppppppppppppppppppppppppp');

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
(21, 1, 'Almidón de mandioca'),
(22, 1, 'Queso Crema'),
(23, 2, 'Aceite'),
(24, 1, 'Oregano');

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
(11, 3, 30.000, '2026-06-14 00:47:36'),
(12, 1, 2.000, '2026-08-12 23:55:26'),
(13, 1, 4.000, '2026-08-12 23:55:40'),
(14, 3, 10.000, '2026-08-18 16:52:17'),
(15, 3, 10.000, '2026-08-18 16:53:33'),
(16, 1, 2.000, '2026-08-30 20:51:48'),
(17, 5, 80.000, '2026-08-30 23:27:15'),
(18, 3, 10.000, '2026-10-01 14:00:23'),
(19, 1, 7.000, '2026-10-01 14:00:35'),
(20, 2, 6.000, '2026-10-01 14:01:18'),
(21, 1, 7.000, '2026-10-01 14:01:38'),
(22, 2, 6.000, '2026-10-01 14:02:00'),
(23, 4, 6.000, '2026-10-01 14:02:17'),
(24, 6, 10.000, '2026-10-25 23:24:09');

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
(4, 3, 'Torta'),
(5, 3, 'Torta frita'),
(6, 3, 'Bollos'),
(7, 3, 'Budin');

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
(23, 3, 18, 0.500),
(24, 5, 1, 0.020),
(25, 5, 3, 0.005),
(26, 5, 16, 0.010),
(28, 5, 23, 0.005),
(29, 6, 1, 0.500);

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
(1, 'Administrador'),
(2, 'Panadero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salidas_productos`
--

CREATE TABLE `salidas_productos` (
  `idsalida` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `motivo` varchar(40) NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salidas_productos`
--

INSERT INTO `salidas_productos` (`idsalida`, `id_producto`, `id_usuario`, `cantidad`, `motivo`, `observacion`, `fecha`) VALUES
(1, 5, 1, 10.000, 'Venta mostrador', 'Se vendió.', '2026-10-26 13:56:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_insumos`
--

CREATE TABLE `stock_insumos` (
  `idstock_insumos` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `stock_minimo` decimal(10,3) DEFAULT 10.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_insumos`
--

INSERT INTO `stock_insumos` (`idstock_insumos`, `id_insumo`, `cantidad`, `stock_minimo`) VALUES
(1, 1, 67.400, 10.000),
(3, 2, 10.730, 10.000),
(4, 8, 94.960, 10.000),
(5, 16, 15026.400, 10.000),
(7, 4, 12.150, 10.000),
(8, 3, 27.140, 10.000),
(9, 7, 44.000, 10.000),
(10, 18, 27.000, 10.000),
(13, 5, 49.250, 10.000),
(14, 6, 57.825, 10.000),
(15, 20, 46.610, 10.000),
(16, 21, 34.000, 10.000),
(20, 22, 60.000, 10.000),
(22, 23, 99.600, 10.000),
(25, 9, 0.000, 10.000),
(26, 10, 0.000, 10.000),
(27, 11, 0.000, 10.000),
(28, 12, 0.000, 10.000),
(29, 15, 0.000, 10.000),
(30, 17, 0.000, 10.000),
(31, 19, 0.000, 10.000),
(32, 13, 0.000, 10.000),
(33, 14, 0.000, 10.000),
(34, 24, 0.000, 10.000);

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

INSERT INTO `stock_productos` (`idstock_productos`, `id_producto`, `cantidad`, `stock_minimo`) VALUES
(1, 2, 9.000, 10.000),
(2, 1, 3.000, 10.000),
(3, 3, 0.000, 10.000),
(4, 4, 5.000, 10.000),
(22, 5, 42.000, 10.000),
(29, 6, 10.000, 10.000),
(30, 7, 0.000, 10.000);

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
(3, 'Unidad', 'Un');

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
(1, 1, 'Maximo', 'Pedrozo', 'pedrozomaximo973@gmail.com', '$2y$10$ftwWBIWaT51gZcka0A5WUOAu5C24ZyKs8VujaMo478IzE9UywnUNK', '2026-06-13 22:52:19', 1),
(10, 2, 'Imanol', 'Balguenet', 'Imanolbalg@gmail.com', '$2y$10$LmAG8jThGFTEhZUGsRmv0eYWvc/QKuUL6Ohi9xzx7ZISGxIcQ8ZIi', '2026-10-26 14:06:01', 1);

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
-- Indices de la tabla `salidas_productos`
--
ALTER TABLE `salidas_productos`
  ADD PRIMARY KEY (`idsalida`),
  ADD KEY `fk_salida_producto_idx` (`id_producto`),
  ADD KEY `fk_salida_usuario_idx` (`id_usuario`),
  ADD KEY `idx_salidas_fecha` (`fecha`);

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
  MODIFY `idcompras_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `idcontacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `insumos`
--
ALTER TABLE `insumos`
  MODIFY `idinsumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `idmetodos_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idproduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idproductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idproveedores` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `receta`
--
ALTER TABLE `receta`
  MODIFY `idreceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idroles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `salidas_productos`
--
ALTER TABLE `salidas_productos`
  MODIFY `idsalida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `stock_insumos`
--
ALTER TABLE `stock_insumos`
  MODIFY `idstock_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `stock_productos`
--
ALTER TABLE `stock_productos`
  MODIFY `idstock_productos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `unidad_de_medida`
--
ALTER TABLE `unidad_de_medida`
  MODIFY `idunidad_de_medida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Filtros para la tabla `salidas_productos`
--
ALTER TABLE `salidas_productos`
  ADD CONSTRAINT `fk_salida_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`),
  ADD CONSTRAINT `fk_salida_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`idusuarios`) ON DELETE SET NULL;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
