-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: panaderia_normal10
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asunto`
--

DROP TABLE IF EXISTS `asunto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asunto` (
  `idasunto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idasunto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asunto`
--

LOCK TABLES `asunto` WRITE;
/*!40000 ALTER TABLE `asunto` DISABLE KEYS */;
INSERT INTO `asunto` VALUES (1,'Consulta sobre el sistema'),(2,'Reporte de error'),(3,'Sugerencia de mejora'),(4,'Información del proyecto'),(5,'Otro');
/*!40000 ALTER TABLE `asunto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_insumos`
--

DROP TABLE IF EXISTS `compras_insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras_insumos` (
  `idcompras_insumos` int(11) NOT NULL AUTO_INCREMENT,
  `id_metodopago` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_insumos` int(11) NOT NULL,
  `numero_factura` varchar(20) DEFAULT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`idcompras_insumos`),
  KEY `fk_compra_proveedor_idx` (`id_proveedor`),
  KEY `fk_compra_insumo_idx` (`id_insumos`),
  KEY `fk_compras_insumos_metodos_pago1_idx` (`id_metodopago`),
  CONSTRAINT `fk_compra_insumo` FOREIGN KEY (`id_insumos`) REFERENCES `insumos` (`idinsumos`),
  CONSTRAINT `fk_compra_proveedor` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`idproveedores`),
  CONSTRAINT `fk_compras_insumos_metodos_pago1` FOREIGN KEY (`id_metodopago`) REFERENCES `metodos_pago` (`idmetodos_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_insumos`
--

LOCK TABLES `compras_insumos` WRITE;
/*!40000 ALTER TABLE `compras_insumos` DISABLE KEYS */;
INSERT INTO `compras_insumos` VALUES (1,1,1,1,'0001',100.000,80000.00,'2026-05-23'),(2,2,2,1,'0001',50.000,40000.00,'2026-05-22'),(3,1,7,2,'0001',5.000,5000.00,'2026-05-21'),(4,2,3,8,'0001',5.000,7000.00,'2026-05-20'),(5,2,7,16,'0004',50.000,50000.00,'2026-05-17'),(6,1,3,8,'0002',20.000,25000.00,'2026-05-18'),(7,2,7,4,'0003',15.000,20000.00,'2026-05-11'),(8,1,7,3,'0005',30.000,30000.00,'2026-05-14'),(9,1,4,7,'0001',50.000,30000.00,'2026-01-16'),(10,1,3,18,'0006',20.000,35000.00,'2026-05-24'),(11,1,1,16,'0005',15000.000,10000.00,'2025-12-18'),(12,1,7,2,'0007',15.000,10000.00,'2025-11-06'),(13,1,7,5,'0007',50.000,60000.00,'2026-02-10'),(14,1,3,6,'0012',50.000,65000.00,'2026-06-09'),(15,1,2,20,'0010',50.000,74299.00,'2026-06-11'),(16,1,6,21,'0010',50.000,70644.00,'2026-06-12'),(17,2,4,7,'0012',100.000,0.00,'2026-06-14'),(18,2,3,18,'0008',40.000,50000.00,'2026-06-14'),(19,1,2,6,'0029',50.000,50000.00,'2026-08-15'),(20,1,3,22,'0026',60.000,125000.00,'2026-08-16'),(21,1,2,21,'0031',50.000,70000.00,'2026-08-18'),(22,1,7,23,'0013',70.000,120000.00,'2026-08-28'),(23,2,3,8,'0023',90.000,120000.00,'2026-09-12'),(24,1,6,23,'0044',30.000,40000.00,'2026-09-20');
/*!40000 ALTER TABLE `compras_insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacto`
--

DROP TABLE IF EXISTS `contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacto` (
  `idcontacto` int(11) NOT NULL AUTO_INCREMENT,
  `id_asunto` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `mensaje` longtext NOT NULL,
  PRIMARY KEY (`idcontacto`),
  KEY `fk_asunto_idx` (`id_asunto`),
  CONSTRAINT `fk_asunto` FOREIGN KEY (`id_asunto`) REFERENCES `asunto` (`idasunto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacto`
--

LOCK TABLES `contacto` WRITE;
/*!40000 ALTER TABLE `contacto` DISABLE KEYS */;
INSERT INTO `contacto` VALUES (1,5,'Máximo Emanuel Pedrozo','','agreguen chipa al menu'),(2,5,'Máximo Emanuel Pedrozo','','agreguen chipa al menu'),(3,3,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','agreguen chipa al menu'),(4,3,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','agreguen chipa al menu'),(5,3,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','agreguen chipa al menu'),(7,3,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','agreguen chipa al menu'),(8,4,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','agreguen chipa al menu'),(9,1,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com',''),(10,3,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','agreguen chipa al menu'),(12,1,'','',''),(13,4,'Máximo Emanuel Pedrozo','pedrozomaximo76@gmail.com','hola'),(14,1,'Ryzen 5 5600G','admin@panaderia.com','chbgkmjmfgchykj'),(15,2,'Ryzen 5 3200G','pedrozomaximo973@gmail.com','no anda el apartado de ayuda'),(16,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(17,2,'Maximo','pedrozomaximo973@gmail.com','holaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'),(18,2,'Maximo','pedrozomaximo973@gmail.com','holaaaaaaaaaaaaa'),(19,2,'Maximo','pedrozomaximo973@gmail.com','holaaaaaaaaaaaaa'),(20,1,'pepe','admin1@panaderia.com','holaaaa'),(21,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(22,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(23,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(24,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(25,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(26,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(27,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(28,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(29,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(30,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(31,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(32,2,'Maximosdfvc','pedrozomaximo973@gmail.com','hola'),(33,2,'Maximosdfvc','pedrozomaximo973@gmail.com','hola'),(34,3,'Ryzen 5 5600G','imabalg@gmail.com','jushxdcjasdfjghasd'),(35,2,'Maximo','pedrozomaximo973@gmail.com','hola'),(36,2,'Maximo','imabalg@gmail.com','jfkuhfjhgf'),(37,2,'Maximo','imabalg@gmail.com','jfkuhfjhgf'),(38,2,'pepe','imabalg@gmail.com','pppppppppppppppppppppppppppp');
/*!40000 ALTER TABLE `contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insumos` (
  `idinsumos` int(11) NOT NULL AUTO_INCREMENT,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idinsumos`),
  KEY `fk_insumo_unidad_idx` (`idunidad_de_medida`),
  CONSTRAINT `fk_insumo_unidad` FOREIGN KEY (`idunidad_de_medida`) REFERENCES `unidad_de_medida` (`idunidad_de_medida`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
INSERT INTO `insumos` VALUES (1,1,'Harina 000'),(2,1,'Azúcar'),(3,1,'Sal'),(4,1,'Levadura Fresca'),(5,2,'Aceite Vegetal'),(6,2,'Leche'),(7,3,'Huevos'),(8,1,'Manteca'),(9,1,'Margarina'),(10,1,'Dulce de Leche'),(11,1,'Chocolate para Repostería'),(12,1,'Fécula de Maíz'),(13,2,'Crema de Leche'),(14,2,'Esencia de Vainilla'),(15,1,'Polvo de Hornear'),(16,2,'Agua'),(17,1,'Harina 0000'),(18,1,'Queso'),(19,1,'Grasa'),(20,1,'Harina Leudante'),(21,1,'Almidón de mandioca'),(22,1,'Queso Crema'),(23,2,'Aceite');
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metodos_pago`
--

DROP TABLE IF EXISTS `metodos_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metodos_pago` (
  `idmetodos_pago` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idmetodos_pago`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodos_pago`
--

LOCK TABLES `metodos_pago` WRITE;
/*!40000 ALTER TABLE `metodos_pago` DISABLE KEYS */;
INSERT INTO `metodos_pago` VALUES (1,'Efectivo'),(2,'Tarjeta de Debito'),(3,'Tarjeta de Credito'),(4,'Cheque');
/*!40000 ALTER TABLE `metodos_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produccion`
--

DROP TABLE IF EXISTS `produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produccion` (
  `idproduccion` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idproduccion`),
  KEY `fk_produccion_producto` (`id_producto`),
  CONSTRAINT `fk_produccion_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produccion`
--

LOCK TABLES `produccion` WRITE;
/*!40000 ALTER TABLE `produccion` DISABLE KEYS */;
INSERT INTO `produccion` VALUES (1,2,1.000,'2026-05-23 03:44:48'),(2,2,3.000,'2026-05-23 03:45:17'),(3,2,2.000,'2026-06-13 21:10:13'),(4,2,10.000,'2026-06-13 21:10:17'),(5,4,5.000,'2026-06-13 22:21:08'),(6,1,6.000,'2026-06-13 22:21:12'),(7,3,6.000,'2026-06-14 00:46:58'),(8,1,10.000,'2026-06-14 00:47:03'),(9,2,10.000,'2026-06-14 00:47:08'),(10,4,4.000,'2026-06-14 00:47:29'),(11,3,30.000,'2026-06-14 00:47:36'),(12,1,2.000,'2026-08-12 23:55:26'),(13,1,4.000,'2026-08-12 23:55:40'),(14,3,10.000,'2026-08-18 16:52:17'),(15,3,10.000,'2026-08-18 16:53:33'),(16,1,2.000,'2026-08-30 20:51:48'),(17,5,80.000,'2026-08-30 23:27:15'),(18,3,10.000,'2026-10-01 14:00:23'),(19,1,7.000,'2026-10-01 14:00:35'),(20,2,6.000,'2026-10-01 14:01:18'),(21,1,7.000,'2026-10-01 14:01:38'),(22,2,6.000,'2026-10-01 14:02:00'),(23,4,6.000,'2026-10-01 14:02:17'),(24,6,10.000,'2026-10-25 23:24:09');
/*!40000 ALTER TABLE `produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `idproductos` int(11) NOT NULL AUTO_INCREMENT,
  `idunidad_de_medida` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idproductos`),
  KEY `fk_producto_unidad_idx` (`idunidad_de_medida`),
  CONSTRAINT `fk_producto_unidad` FOREIGN KEY (`idunidad_de_medida`) REFERENCES `unidad_de_medida` (`idunidad_de_medida`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,3,'Factura'),(2,1,'Pan'),(3,1,'Chipa'),(4,3,'Torta'),(5,3,'Torta frita'),(6,3,'Bollos'),(7,3,'Budin');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `idproveedores` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idproveedores`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,'Molino San José','3764123456','ventas@molinosanjose.com','Av. Industrial 123'),(2,'Distribuidora El Trigal','3764234567','contacto@eltrigal.com','Ruta Nacional 12 Km 15'),(3,'Lácteos La Esperanza','3764345678','ventas@laesperanza.com','Calle Belgrano 456'),(4,'Huevos Don Carlos','3764456789','pedidos@huevosdoncarlos.com','Barrio Rural S/N'),(5,'Dulces del Norte','3764567890','info@dulcesdelnorte.com','Av. San Martín 890'),(6,'Insumos Panificados Misiones','3764678901','ventas@ipm.com','Ruta Provincial 7 Km 3'),(7,'Distribuidora Central','3764789012','central@distribuidora.com','Calle Corrientes 245');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receta`
--

DROP TABLE IF EXISTS `receta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receta` (
  `idreceta` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  PRIMARY KEY (`idreceta`),
  UNIQUE KEY `uq_receta_producto_insumo` (`id_producto`,`id_insumo`),
  KEY `fk_receta_producto_idx` (`id_producto`),
  KEY `fk_receta_insumo_idx` (`id_insumo`),
  CONSTRAINT `fk_receta_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`idinsumos`),
  CONSTRAINT `fk_receta_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta`
--

LOCK TABLES `receta` WRITE;
/*!40000 ALTER TABLE `receta` DISABLE KEYS */;
INSERT INTO `receta` VALUES (2,2,1,1.000),(3,2,4,0.025),(4,2,2,0.015),(5,2,8,0.080),(6,2,3,0.030),(7,2,16,0.600),(8,1,1,1.000),(9,1,6,0.500),(10,1,4,0.050),(11,1,2,0.150),(12,1,8,0.100),(13,1,7,2.000),(14,4,7,2.000),(15,4,5,0.050),(16,4,2,0.200),(17,4,20,0.226),(18,4,6,0.225),(19,3,21,1.000),(20,3,6,0.300),(21,3,3,0.020),(22,3,8,0.200),(23,3,18,0.500),(24,5,1,0.020),(25,5,3,0.005),(26,5,16,0.010),(28,5,23,0.005),(29,6,1,0.500);
/*!40000 ALTER TABLE `receta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `idroles` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idroles`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador'),(2,'Panadero');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salidas_productos`
--

DROP TABLE IF EXISTS `salidas_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salidas_productos` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salidas_productos`
--

LOCK TABLES `salidas_productos` WRITE;
/*!40000 ALTER TABLE `salidas_productos` DISABLE KEYS */;
INSERT INTO `salidas_productos` VALUES (1,5,1,10.000,'Venta mostrador','Se vendió.','2026-10-26 13:56:28');
/*!40000 ALTER TABLE `salidas_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_insumos`
--

DROP TABLE IF EXISTS `stock_insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_insumos` (
  `idstock_insumos` int(11) NOT NULL AUTO_INCREMENT,
  `id_insumo` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL,
  `stock_minimo` decimal(10,3) DEFAULT 10.000,
  PRIMARY KEY (`idstock_insumos`),
  UNIQUE KEY `id_insumo` (`id_insumo`),
  KEY `fk_stock_ins_insumo_idx` (`id_insumo`),
  CONSTRAINT `fk_stock_ins_insumo` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`idinsumos`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_insumos`
--

LOCK TABLES `stock_insumos` WRITE;
/*!40000 ALTER TABLE `stock_insumos` DISABLE KEYS */;
INSERT INTO `stock_insumos` VALUES (1,1,67.400,10.000),(3,2,10.730,10.000),(4,8,94.960,10.000),(5,16,15026.400,10.000),(7,4,12.150,10.000),(8,3,27.140,10.000),(9,7,44.000,10.000),(10,18,27.000,10.000),(13,5,49.250,10.000),(14,6,57.825,10.000),(15,20,46.610,10.000),(16,21,34.000,10.000),(20,22,60.000,10.000),(22,23,99.600,10.000),(25,9,0.000,10.000),(26,10,0.000,10.000),(27,11,0.000,10.000),(28,12,0.000,10.000),(29,15,0.000,10.000),(30,17,0.000,10.000),(31,19,0.000,10.000),(32,13,0.000,10.000),(33,14,0.000,10.000);
/*!40000 ALTER TABLE `stock_insumos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_productos`
--

DROP TABLE IF EXISTS `stock_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_productos` (
  `idstock_productos` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL DEFAULT 0.000,
  `stock_minimo` decimal(10,3) NOT NULL DEFAULT 10.000,
  PRIMARY KEY (`idstock_productos`),
  UNIQUE KEY `uq_stock_producto` (`id_producto`),
  CONSTRAINT `fk_stock_prod_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idproductos`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_productos`
--

LOCK TABLES `stock_productos` WRITE;
/*!40000 ALTER TABLE `stock_productos` DISABLE KEYS */;
INSERT INTO `stock_productos` VALUES (1,2,9.000,10.000),(2,1,3.000,10.000),(3,3,0.000,10.000),(4,4,5.000,10.000),(22,5,42.000,10.000),(29,6,10.000,10.000),(30,7,0.000,10.000);
/*!40000 ALTER TABLE `stock_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad_de_medida`
--

DROP TABLE IF EXISTS `unidad_de_medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad_de_medida` (
  `idunidad_de_medida` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `abreviatura` varchar(10) NOT NULL,
  PRIMARY KEY (`idunidad_de_medida`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad_de_medida`
--

LOCK TABLES `unidad_de_medida` WRITE;
/*!40000 ALTER TABLE `unidad_de_medida` DISABLE KEYS */;
INSERT INTO `unidad_de_medida` VALUES (1,'Kilogramo','Kg'),(2,'Litros','Lt'),(3,'Unidad','Un');
/*!40000 ALTER TABLE `unidad_de_medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `idusuarios` int(11) NOT NULL AUTO_INCREMENT,
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idusuarios`),
  UNIQUE KEY `uq_usuario_correo` (`correo`),
  KEY `fk_usuario_rol_idx` (`id_rol`),
  CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`idroles`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,1,'Maximo','Pedrozo','pedrozomaximo973@gmail.com','$2y$10$ftwWBIWaT51gZcka0A5WUOAu5C24ZyKs8VujaMo478IzE9UywnUNK','2026-06-13 22:52:19',1),(10,2,'Imanol','Balguenet','Imanolbalg@gmail.com','$2y$10$LmAG8jThGFTEhZUGsRmv0eYWvc/QKuUL6Ohi9xzx7ZISGxIcQ8ZIi','2026-10-26 14:06:01',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'panaderia_normal10'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-26 14:18:13
