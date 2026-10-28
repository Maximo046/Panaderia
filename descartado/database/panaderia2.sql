-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 08:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `panaderia`
--

-- --------------------------------------------------------

--
-- Table structure for table `asunto`
--

CREATE TABLE `asunto` (
  `idasunto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asunto`
--

INSERT INTO `asunto` (`idasunto`, `nombre`) VALUES
(1, 'Consulta sobre el sistema'),
(2, 'Reporte de error'),
(3, 'Sugerencia de mejora'),
(4, 'Información del proyecto'),
(5, 'Otro');

-- --------------------------------------------------------

--
-- Table structure for table `contacto`
--

CREATE TABLE `contacto` (
  `idcontacto` int(11) NOT NULL,
  `id_asunto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `mensaje` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacto`
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
-- Table structure for table `entrada_insumos`
--

CREATE TABLE `entrada_insumos` (
  `idcompras_insumos` int(11) NOT NULL,
  `id_insumos` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entrada_insumos`
--

INSERT INTO `entrada_insumos` (`idcompras_insumos`, `id_insumos`, `cantidad`, `fecha`) VALUES
(1, 1, 100.000, '2026-05-23'),
(2, 1, 50.000, '2026-05-22'),
(3, 2, 5.000, '2026-05-21'),
(4, 8, 5.000, '2026-05-20'),
(5, 16, 50.000, '2026-05-17'),
(6, 8, 20.000, '2026-05-18'),
(7, 4, 15.000, '2026-05-11'),
(8, 3, 30.000, '2026-05-14'),
(9, 7, 50.000, '2026-01-16'),
(10, 18, 20.000, '2026-05-24'),
(11, 16, 15000.000, '2025-12-18'),
(12, 2, 15.000, '2025-11-06'),
(13, 5, 50.000, '2026-02-10'),
(14, 6, 50.000, '2026-06-09'),
(15, 20, 50.000, '2026-06-11'),
(16, 21, 50.000, '2026-06-12'),
(17, 7, 100.000, '2026-06-14'),
(18, 18, 40.000, '2026-06-14'),
(19, 6, 50.000, '2026-08-15'),
(20, 22, 60.000, '2026-08-16'),
(21, 21, 50.000, '2026-08-18'),
(22, 23, 70.000, '2026-08-28'),
(23, 8, 90.000, '2026-09-12'),
(24, 23, 30.000, '2026-09-20'),
(25, 1, 1.500, '2026-10-27'),
(26, 2, 2.000, '2026-10-27'),
(27, 1, 1.000, '2026-10-27'),
(28, 2, 3.000, '2026-10-27'),
(29, 3, 5.000, '2026-10-27'),
(30, 1, 2.000, '2026-10-28'),
(31, 1, 2.000, '2026-10-28'),
(32, 9, 10.000, '2026-10-28'),
(33, 24, 10.000, '2026-10-28'),
(34, 15, 10.000, '2026-10-28'),
(35, 11, 10.000, '2026-10-28'),
(36, 13, 10.000, '2026-10-28'),
(37, 10, 10.000, '2026-10-28'),
(38, 19, 10.000, '2026-10-28'),
(39, 14, 10.000, '2026-10-28'),
(40, 12, 10.000, '2026-10-28'),
(41, 17, 10.000, '2026-10-28'),
(42, 1, 3.000, '2026-10-28'),
(43, 1, 1.000, '2026-10-28'),
(44, 9, 5.000, '2026-10-28'),
(45, 24, 5.000, '2026-10-28'),
(46, 7, 1.000, '2026-10-28'),
(47, 23, 0.400, '2026-10-28'),
(48, 5, 0.750, '2026-10-28'),
(49, 16, 0.600, '2026-10-28'),
(50, 2, 0.270, '2026-10-28'),
(51, 1, 0.100, '2026-10-28'),
(52, 20, 0.390, '2026-10-28'),
(53, 6, 0.175, '2026-10-28'),
(54, 4, 0.850, '2026-10-28'),
(55, 8, 0.040, '2026-10-28'),
(56, 3, 8.000, '2026-10-28'),
(57, 7, 83.000, '2026-10-28'),
(58, 2, 10.000, '2026-10-28'),
(59, 25, 15.000, '2026-10-28'),
(60, 14, 10.000, '2026-10-28');

-- --------------------------------------------------------

--
-- Table structure for table `insumos`
--

CREATE TABLE `insumos` (
  `idinsumos` int(11) NOT NULL,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insumos`
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
(24, 1, 'Oregano'),
(25, 1, 'Leche Condensada');

-- --------------------------------------------------------

--
-- Table structure for table `produccion`
--

CREATE TABLE `produccion` (
  `idproduccion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produccion`
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
(24, 6, 10.000, '2026-10-25 23:24:09'),
(25, 1, 9.000, '2026-10-28 15:13:11'),
(26, 4, 5.000, '2026-10-28 15:13:31'),
(27, 6, 10.000, '2026-10-28 15:22:13'),
(28, 3, 10.000, '2026-10-28 15:22:21'),
(29, 2, 10.000, '2026-10-28 15:22:33'),
(30, 5, 10.000, '2026-10-28 15:22:42'),
(31, 3, 10.000, '2026-10-28 15:23:12'),
(32, 1, 10.000, '2026-10-28 15:23:17'),
(33, 2, 10.000, '2026-10-28 15:23:21'),
(34, 4, 10.000, '2026-10-28 15:23:24'),
(35, 7, 10.000, '2026-10-28 15:34:06');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `idproductos` int(11) NOT NULL,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
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
-- Table structure for table `receta`
--

CREATE TABLE `receta` (
  `idreceta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receta`
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
(29, 6, 17, 0.042),
(30, 7, 20, 0.200),
(31, 7, 2, 0.150),
(32, 7, 8, 0.100),
(33, 7, 7, 2.000),
(34, 7, 6, 0.050),
(35, 7, 14, 0.005),
(36, 6, 2, 0.008),
(37, 6, 8, 0.004),
(38, 6, 6, 0.020),
(39, 6, 4, 0.001),
(40, 6, 7, 1.000),
(41, 6, 14, 0.001),
(42, 6, 10, 0.033);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `idroles` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`idroles`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Panadero');

-- --------------------------------------------------------

--
-- Table structure for table `salidas_productos`
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
-- Dumping data for table `salidas_productos`
--

INSERT INTO `salidas_productos` (`idsalida`, `id_producto`, `id_usuario`, `cantidad`, `motivo`, `observacion`, `fecha`) VALUES
(1, 5, 1, 10.000, 'Venta mostrador', 'Se vendió.', '2026-10-26 13:56:28'),
(2, 6, 1, 5.000, 'Venta mostrador', 'Se vendió.', '2026-10-27 13:32:22'),
(3, 1, 1, 2.000, 'Venta mostrador', 'Se vendió.', '2026-10-27 13:32:22'),
(4, 7, 1, 4.000, 'Venta mostrador', 'Se vendio.', '2026-10-28 15:58:14');

-- --------------------------------------------------------

--
-- Table structure for table `stock_insumos`
--

CREATE TABLE `stock_insumos` (
  `idstock_insumos` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `stock_minimo` decimal(10,3) DEFAULT 10.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_insumos`
--

INSERT INTO `stock_insumos` (`idstock_insumos`, `id_insumo`, `cantidad`, `stock_minimo`) VALUES
(1, 1, 33.800, 10.000),
(3, 2, 18.350, 10.000),
(4, 8, 86.500, 10.000),
(5, 16, 15014.900, 10.000),
(7, 4, 11.550, 10.000),
(8, 3, 39.090, 10.000),
(9, 7, 40.000, 10.000),
(10, 18, 17.000, 10.000),
(13, 5, 49.250, 10.000),
(14, 6, 38.625, 10.000),
(15, 20, 41.610, 10.000),
(16, 21, 14.000, 10.000),
(20, 22, 60.000, 10.000),
(22, 23, 99.950, 10.000),
(25, 9, 15.000, 10.000),
(26, 10, 10.000, 10.000),
(27, 11, 10.000, 10.000),
(28, 12, 10.000, 10.000),
(29, 15, 10.000, 10.000),
(30, 17, 10.000, 10.000),
(31, 19, 10.000, 10.000),
(32, 13, 10.000, 10.000),
(33, 14, 19.950, 10.000),
(34, 24, 15.000, 10.000),
(67, 25, 15.000, 10.000);

-- --------------------------------------------------------

--
-- Table structure for table `stock_productos`
--

CREATE TABLE `stock_productos` (
  `idstock_productos` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL DEFAULT 0.000,
  `stock_minimo` decimal(10,3) NOT NULL DEFAULT 10.000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_productos`
--

INSERT INTO `stock_productos` (`idstock_productos`, `id_producto`, `cantidad`, `stock_minimo`) VALUES
(1, 2, 29.000, 10.000),
(2, 1, 20.000, 10.000),
(3, 3, 20.000, 10.000),
(4, 4, 20.000, 10.000),
(22, 5, 52.000, 10.000),
(29, 6, 15.000, 10.000),
(30, 7, 6.000, 10.000);

-- --------------------------------------------------------

--
-- Table structure for table `unidad_de_medida`
--

CREATE TABLE `unidad_de_medida` (
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `abreviatura` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unidad_de_medida`
--

INSERT INTO `unidad_de_medida` (`idunidad_de_medida`, `nombre`, `abreviatura`) VALUES
(1, 'Kilogramo', 'Kg'),
(2, 'Litros', 'Lt'),
(3, 'Unidad', 'Un');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
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
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`idusuarios`, `id_rol`, `nombre`, `apellido`, `correo`, `contrasena`, `fecha_registro`, `activo`) VALUES
(1, 1, 'Maximo', 'Pedrozo', 'pedrozomaximo973@gmail.com', '$2y$10$ftwWBIWaT51gZcka0A5WUOAu5C24ZyKs8VujaMo478IzE9UywnUNK', '2026-06-13 22:52:19', 1),
(10, 2, 'Imanol', 'Balguenet', 'Imanolbalg@gmail.com', '$2y$10$LmAG8jThGFTEhZUGsRmv0eYWvc/QKuUL6Ohi9xzx7ZISGxIcQ8ZIi', '2026-10-26 14:06:01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asunto`
--
ALTER TABLE `asunto`
  ADD PRIMARY KEY (`idasunto`);

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`idcontacto`),
  ADD KEY `fk_asunto_idx` (`id_asunto`);

--
-- Indexes for table `entrada_insumos`
--
ALTER TABLE `entrada_insumos`
  ADD PRIMARY KEY (`idcompras_insumos`),
  ADD KEY `fk_compra_insumo_idx` (`id_insumos`);

--
-- Indexes for table `insumos`
--
ALTER TABLE `insumos`
  ADD PRIMARY KEY (`idinsumos`),
  ADD KEY `fk_insumo_unidad_idx` (`idunidad_de_medida`);

--
-- Indexes for table `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`idproduccion`),
  ADD KEY `fk_produccion_producto` (`id_producto`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idproductos`),
  ADD KEY `fk_producto_unidad_idx` (`idunidad_de_medida`);

--
-- Indexes for table `receta`
--
ALTER TABLE `receta`
  ADD PRIMARY KEY (`idreceta`),
  ADD UNIQUE KEY `uq_receta_producto_insumo` (`id_producto`,`id_insumo`),
  ADD KEY `fk_receta_producto_idx` (`id_producto`),
  ADD KEY `fk_receta_insumo_idx` (`id_insumo`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idroles`);

--
-- Indexes for table `salidas_productos`
--
ALTER TABLE `salidas_productos`
  ADD PRIMARY KEY (`idsalida`),
  ADD KEY `fk_salida_producto_idx` (`id_producto`),
  ADD KEY `fk_salida_usuario_idx` (`id_usuario`),
  ADD KEY `idx_salidas_fecha` (`fecha`);

--
-- Indexes for table `stock_insumos`
--
ALTER TABLE `stock_insumos`
  ADD PRIMARY KEY (`idstock_insumos`),
  ADD UNIQUE KEY `id_insumo` (`id_insumo`),
  ADD KEY `fk_stock_ins_insumo_idx` (`id_insumo`);

--
-- Indexes for table `stock_productos`
--
ALTER TABLE `stock_productos`
  ADD PRIMARY KEY (`idstock_productos`),
  ADD UNIQUE KEY `uq_stock_producto` (`id_producto`);

--
-- Indexes for table `unidad_de_medida`
--
ALTER TABLE `unidad_de_medida`
  ADD PRIMARY KEY (`idunidad_de_medida`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuarios`),
  ADD UNIQUE KEY `uq_usuario_correo` (`correo`),
  ADD KEY `fk_usuario_rol_idx` (`id_rol`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asunto`
--
ALTER TABLE `asunto`
  MODIFY `idasunto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contacto`
--
ALTER TABLE `contacto`
  MODIFY `idcontacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `entrada_insumos`
--
ALTER TABLE `entrada_insumos`
  MODIFY `idcompras_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `insumos`
--
ALTER TABLE `insumos`
  MODIFY `idinsumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idproduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `idproductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `receta`
--
ALTER TABLE `receta`
  MODIFY `idreceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `idroles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salidas_productos`
--
ALTER TABLE `salidas_productos`
  MODIFY `idsalida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_insumos`
--
ALTER TABLE `stock_insumos`
  MODIFY `idstock_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `stock_productos`
--
ALTER TABLE `stock_productos`
  MODIFY `idstock_productos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `unidad_de_medida`
--
ALTER TABLE `unidad_de_medida`
  MODIFY `idunidad_de_medida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `fk_asunto` FOREIGN KEY (`id_asunto`) REFERENCES `asunto` (`idasunto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `entrada_insumos`
--
ALTER TABLE `entrada_insumos`
  ADD CONSTRAINT `fk_compra_insumo` FOREIGN KEY (`id_insumos`) REFERENCES `insumos` (`idinsumos`);

--
-- Constraints for table `insumos`
--
ALTER TABLE `insumos`
  ADD CONSTRAINT `fk_insumo_unidad` FOREIGN KEY (`idunidad_de_medida`) REFERENCES `unidad_de_medida` (`idunidad_de_medida`);

--
-- Constraints for table `produccion`
--
ALTER TABLE `produccion`
  ADD CONSTRAINT `fk_produccion_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`);

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_unidad` FOREIGN KEY (`idunidad_de_medida`) REFERENCES `unidad_de_medida` (`idunidad_de_medida`);

--
-- Constraints for table `receta`
--
ALTER TABLE `receta`
  ADD CONSTRAINT `fk_receta_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`idinsumos`),
  ADD CONSTRAINT `fk_receta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`);

--
-- Constraints for table `salidas_productos`
--
ALTER TABLE `salidas_productos`
  ADD CONSTRAINT `fk_salida_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`),
  ADD CONSTRAINT `fk_salida_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`idusuarios`) ON DELETE SET NULL;

--
-- Constraints for table `stock_insumos`
--
ALTER TABLE `stock_insumos`
  ADD CONSTRAINT `fk_stock_ins_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`idinsumos`);

--
-- Constraints for table `stock_productos`
--
ALTER TABLE `stock_productos`
  ADD CONSTRAINT `fk_stock_prod_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`idroles`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
