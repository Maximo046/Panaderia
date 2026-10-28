-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 04:29 PM
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
-- Database: `panaderia_normal10`
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
-- Table structure for table `compras_insumos`
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

-- --------------------------------------------------------

--
-- Table structure for table `insumos`
--

CREATE TABLE `insumos` (
  `idinsumos` int(11) NOT NULL,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `idproductos` int(11) NOT NULL,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 2, 'Imanol', 'Balguenet', 'Imanolbalg@gmail.com', '$2y$10$LmAG8jThGFTEhZUGsRmv0eYWvc/QKuUL6Ohi9xzx7ZISGxIcQ8ZIi', '2026-10-26 14:06:01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asunto`
--
ALTER TABLE `asunto`
  ADD PRIMARY KEY (`idasunto`);

--
-- Indexes for table `compras_insumos`
--
ALTER TABLE `compras_insumos`
  ADD PRIMARY KEY (`idcompras_insumos`),
  ADD KEY `fk_compra_proveedor_idx` (`id_proveedor`),
  ADD KEY `fk_compra_insumo_idx` (`id_insumos`),
  ADD KEY `fk_compras_insumos_metodos_pago1_idx` (`id_metodopago`);

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`idcontacto`),
  ADD KEY `fk_asunto_idx` (`id_asunto`);

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
-- AUTO_INCREMENT for table `compras_insumos`
--
ALTER TABLE `compras_insumos`
  MODIFY `idcompras_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `contacto`
--
ALTER TABLE `contacto`
  MODIFY `idcontacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `insumos`
--
ALTER TABLE `insumos`
  MODIFY `idinsumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `produccion`
--
ALTER TABLE `produccion`
  MODIFY `idproduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `idproductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `receta`
--
ALTER TABLE `receta`
  MODIFY `idreceta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `idroles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salidas_productos`
--
ALTER TABLE `salidas_productos`
  MODIFY `idsalida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_insumos`
--
ALTER TABLE `stock_insumos`
  MODIFY `idstock_insumos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `stock_productos`
--
ALTER TABLE `stock_productos`
  MODIFY `idstock_productos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
-- Constraints for table `compras_insumos`
--
ALTER TABLE `compras_insumos`
  ADD CONSTRAINT `fk_compra_insumo` FOREIGN KEY (`id_insumos`) REFERENCES `insumos` (`idinsumos`),
  ADD CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`idproveedores`),
  ADD CONSTRAINT `fk_compras_insumos_metodos_pago1` FOREIGN KEY (`id_metodopago`) REFERENCES `metodos_pago` (`idmetodos_pago`);

--
-- Constraints for table `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `fk_asunto` FOREIGN KEY (`id_asunto`) REFERENCES `asunto` (`idasunto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
