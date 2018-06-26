CREATE DATABASE  IF NOT EXISTS `dbchurchill` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci */;
USE `dbchurchill`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: dbchurchill
-- ------------------------------------------------------
-- Server version	5.7.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Temporary view structure for view `_vmenucombo`
--

DROP TABLE IF EXISTS `_vmenucombo`;
/*!50001 DROP VIEW IF EXISTS `_vmenucombo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `_vmenucombo` AS SELECT 
 1 AS `idDetalleOrdenMenu`,
 1 AS `idDetalleOrdenCombo`,
 1 AS `precioCombo`,
 1 AS `combo`,
 1 AS `idCombo`,
 1 AS `imagenCombo`,
 1 AS `idEstadoDetalleOrdenCombo`,
 1 AS `observacionCombo`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bitacoraestadocaja`
--

DROP TABLE IF EXISTS `bitacoraestadocaja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraestadocaja` (
  `idCaja` int(11) NOT NULL,
  `idEstadoCaja` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idCaja`,`idEstadoCaja`),
  KEY `fk_caja_has_estadoCaja_estadoCaja1_idx` (`idEstadoCaja`),
  KEY `fk_caja_has_estadoCaja_caja1_idx` (`idCaja`),
  KEY `fk_bitacoraEstadoCaja_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_bitacoraEstadoCaja_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_caja_has_estadoCaja_caja1` FOREIGN KEY (`idCaja`) REFERENCES `caja` (`idCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_caja_has_estadoCaja_estadoCaja1` FOREIGN KEY (`idEstadoCaja`) REFERENCES `estadocaja` (`idEstadoCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraestadocaja`
--

LOCK TABLES `bitacoraestadocaja` WRITE;
/*!40000 ALTER TABLE `bitacoraestadocaja` DISABLE KEYS */;
INSERT INTO `bitacoraestadocaja` VALUES (1,1,'2018-06-11 20:21:39','restaurante');
/*!40000 ALTER TABLE `bitacoraestadocaja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraestadocierre`
--

DROP TABLE IF EXISTS `bitacoraestadocierre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraestadocierre` (
  `idEstadoCuadre` int(11) NOT NULL,
  `idCuadreProducto` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`idEstadoCuadre`,`idCuadreProducto`),
  KEY `fk_bitacoraEstadoCierre_estadoCuadre1_idx` (`idEstadoCuadre`),
  KEY `fk_bitacoraEstadoCierre_cuadreProducto1_idx` (`idCuadreProducto`),
  CONSTRAINT `fk_bitacoraEstadoCierre_cuadreProducto1` FOREIGN KEY (`idCuadreProducto`) REFERENCES `cuadreproducto` (`idCuadreProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bitacoraEstadoCierre_estadoCuadre1` FOREIGN KEY (`idEstadoCuadre`) REFERENCES `estadocuadre` (`idEstadoCuadre`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraestadocierre`
--

LOCK TABLES `bitacoraestadocierre` WRITE;
/*!40000 ALTER TABLE `bitacoraestadocierre` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacoraestadocierre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraestadoevento`
--

DROP TABLE IF EXISTS `bitacoraestadoevento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraestadoevento` (
  `idEstadoEvento` int(11) NOT NULL,
  `idEvento` int(11) NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  KEY `fk_estadoEvento_has_evento_evento1_idx` (`idEvento`),
  KEY `fk_estadoEvento_has_evento_estadoEvento1_idx` (`idEstadoEvento`),
  KEY `fk_bitacoraEstadoEvento_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_bitacoraEstadoEvento_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoEvento_has_evento_estadoEvento1` FOREIGN KEY (`idEstadoEvento`) REFERENCES `estadoevento` (`idEstadoEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoEvento_has_evento_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraestadoevento`
--

LOCK TABLES `bitacoraestadoevento` WRITE;
/*!40000 ALTER TABLE `bitacoraestadoevento` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacoraestadoevento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraestadofactura`
--

DROP TABLE IF EXISTS `bitacoraestadofactura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraestadofactura` (
  `idFactura` int(11) NOT NULL,
  `idEstadoFactura` int(11) NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  KEY `fk_factura_has_estadoFactura_factura1_idx` (`idFactura`),
  KEY `fk_bitacoraEstadoFactura_usuario1_idx` (`usuario`),
  KEY `fk_bitacoraEstadoFactura_estadoFactura1_idx` (`idEstadoFactura`),
  CONSTRAINT `fk_bitacoraEstadoFactura_estadoFactura1` FOREIGN KEY (`idEstadoFactura`) REFERENCES `estadofactura` (`idEstadoFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bitacoraEstadoFactura_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_factura_has_estadoFactura_factura1` FOREIGN KEY (`idFactura`) REFERENCES `factura` (`idFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraestadofactura`
--

LOCK TABLES `bitacoraestadofactura` WRITE;
/*!40000 ALTER TABLE `bitacoraestadofactura` DISABLE KEYS */;
INSERT INTO `bitacoraestadofactura` VALUES (4,1,NULL,'restaurante','2018-06-11 20:28:07'),(4,1,NULL,'restaurante','2018-06-11 20:30:17'),(5,1,NULL,'restaurante','2018-06-11 21:05:41'),(5,1,NULL,'restaurante','2018-06-11 21:06:23'),(6,1,NULL,'restaurante','2018-06-11 21:07:16'),(7,1,NULL,'restaurante','2018-06-11 21:12:05'),(6,1,NULL,'restaurante','2018-06-11 21:12:22'),(4,1,NULL,'restaurante','2018-06-11 21:17:52');
/*!40000 ALTER TABLE `bitacoraestadofactura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraingreso`
--

DROP TABLE IF EXISTS `bitacoraingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraingreso` (
  `idProducto` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  `accion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  KEY `fk_bitacoraIngreso_producto1_idx` (`idProducto`),
  KEY `fk_bitacoraIngreso_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_bitacoraIngreso_producto1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_bitacoraIngreso_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraingreso`
--

LOCK TABLES `bitacoraingreso` WRITE;
/*!40000 ALTER TABLE `bitacoraingreso` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacoraingreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraordencliente`
--

DROP TABLE IF EXISTS `bitacoraordencliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraordencliente` (
  `idEstadoOrden` int(11) NOT NULL,
  `idOrdenCliente` int(11) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  KEY `fk_estadoOrden_has_ordenCliente_ordenCliente1_idx` (`idOrdenCliente`),
  KEY `fk_estadoOrden_has_ordenCliente_estadoOrden1_idx` (`idEstadoOrden`),
  KEY `fk_bitacoraOrdenCliente_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_bitacoraOrdenCliente_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoOrden_has_ordenCliente_estadoOrden1` FOREIGN KEY (`idEstadoOrden`) REFERENCES `estadoorden` (`idEstadoOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoOrden_has_ordenCliente_ordenCliente1` FOREIGN KEY (`idOrdenCliente`) REFERENCES `ordencliente` (`idOrdenCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraordencliente`
--

LOCK TABLES `bitacoraordencliente` WRITE;
/*!40000 ALTER TABLE `bitacoraordencliente` DISABLE KEYS */;
INSERT INTO `bitacoraordencliente` VALUES (4,1,'restaurante','2018-06-11 20:23:43',NULL),(6,1,'restaurante','2018-06-11 20:28:07',NULL),(4,2,'restaurante','2018-06-11 21:05:26',NULL),(6,2,'restaurante','2018-06-11 21:05:41',NULL),(4,3,'restaurante','2018-06-11 21:06:54',NULL),(6,3,'restaurante','2018-06-11 21:07:17',NULL),(4,4,'restaurante','2018-06-11 21:11:48',NULL),(6,4,'restaurante','2018-06-11 21:12:05',NULL);
/*!40000 ALTER TABLE `bitacoraordencliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraordencombo`
--

DROP TABLE IF EXISTS `bitacoraordencombo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraordencombo` (
  `idEstadoDetalleOrden` int(11) NOT NULL,
  `idDetalleOrdenCombo` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  KEY `fk_estadoDetalleOrden_has_detalleOdenCombo_detalleOdenCombo_idx` (`idDetalleOrdenCombo`),
  KEY `fk_estadoDetalleOrden_has_detalleOdenCombo_estadoDetalleOrd_idx` (`idEstadoDetalleOrden`),
  KEY `fk_bitacoraOdenCombo_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_bitacoraOdenCombo_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoDetalleOrden_has_detalleOdenCombo_detalleOdenCombo1` FOREIGN KEY (`idDetalleOrdenCombo`) REFERENCES `detalleordencombo` (`idDetalleOrdenCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoDetalleOrden_has_detalleOdenCombo_estadoDetalleOrden1` FOREIGN KEY (`idEstadoDetalleOrden`) REFERENCES `estadodetalleorden` (`idEstadoDetalleOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraordencombo`
--

LOCK TABLES `bitacoraordencombo` WRITE;
/*!40000 ALTER TABLE `bitacoraordencombo` DISABLE KEYS */;
INSERT INTO `bitacoraordencombo` VALUES (1,1,'2018-06-11 21:05:09','restaurante',NULL),(1,2,'2018-06-11 21:05:09','restaurante',NULL),(4,1,'2018-06-11 21:05:26','restaurante',NULL),(4,2,'2018-06-11 21:05:26','restaurante',NULL),(6,1,'2018-06-11 21:05:41','restaurante',NULL),(6,2,'2018-06-11 21:05:41','restaurante',NULL),(1,3,'2018-06-11 21:11:43','restaurante',NULL),(1,4,'2018-06-11 21:11:43','restaurante',NULL),(1,5,'2018-06-11 21:11:43','restaurante',NULL),(4,3,'2018-06-11 21:11:48','restaurante',NULL),(4,4,'2018-06-11 21:11:48','restaurante',NULL),(4,5,'2018-06-11 21:11:48','restaurante',NULL),(6,3,'2018-06-11 21:12:05','restaurante',NULL),(6,4,'2018-06-11 21:12:05','restaurante',NULL),(6,5,'2018-06-11 21:12:05','restaurante',NULL);
/*!40000 ALTER TABLE `bitacoraordencombo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacoraordenmenu`
--

DROP TABLE IF EXISTS `bitacoraordenmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacoraordenmenu` (
  `idEstadoDetalleOrden` int(11) NOT NULL,
  `idDetalleOrdenMenu` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  KEY `fk_estadoDetalleOrden_has_detalleOdenMenu_detalleOdenMenu1_idx` (`idDetalleOrdenMenu`),
  KEY `fk_estadoDetalleOrden_has_detalleOdenMenu_estadoDetalleOrde_idx` (`idEstadoDetalleOrden`),
  KEY `fk_bitacoraOdenMenu_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_bitacoraOdenMenu_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoDetalleOrden_has_detalleOdenMenu_detalleOdenMenu1` FOREIGN KEY (`idDetalleOrdenMenu`) REFERENCES `detalleordenmenu` (`idDetalleOrdenMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoDetalleOrden_has_detalleOdenMenu_estadoDetalleOrden1` FOREIGN KEY (`idEstadoDetalleOrden`) REFERENCES `estadodetalleorden` (`idEstadoDetalleOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacoraordenmenu`
--

LOCK TABLES `bitacoraordenmenu` WRITE;
/*!40000 ALTER TABLE `bitacoraordenmenu` DISABLE KEYS */;
INSERT INTO `bitacoraordenmenu` VALUES (1,1,'2018-06-11 20:22:19','restaurante',NULL),(1,2,'2018-06-11 20:22:19','restaurante',NULL),(1,3,'2018-06-11 20:22:19','restaurante',NULL),(1,4,'2018-06-11 20:22:19','restaurante',NULL),(1,5,'2018-06-11 20:22:19','restaurante',NULL),(4,1,'2018-06-11 20:23:42','restaurante',NULL),(4,2,'2018-06-11 20:23:42','restaurante',NULL),(4,3,'2018-06-11 20:23:42','restaurante',NULL),(4,4,'2018-06-11 20:23:43','restaurante',NULL),(4,5,'2018-06-11 20:23:43','restaurante',NULL),(6,4,'2018-06-11 20:28:07','restaurante',NULL),(6,5,'2018-06-11 20:28:07','restaurante',NULL),(6,1,'2018-06-11 20:28:07','restaurante',NULL),(6,2,'2018-06-11 20:28:07','restaurante',NULL),(6,3,'2018-06-11 20:28:07','restaurante',NULL),(1,6,'2018-06-11 21:05:09','restaurante',NULL),(1,7,'2018-06-11 21:05:09','restaurante',NULL),(1,8,'2018-06-11 21:05:09','restaurante',NULL),(1,9,'2018-06-11 21:05:09','restaurante',NULL),(1,10,'2018-06-11 21:05:09','restaurante',NULL),(1,11,'2018-06-11 21:05:09','restaurante',NULL),(1,12,'2018-06-11 21:05:09','restaurante',NULL),(1,13,'2018-06-11 21:05:09','restaurante',NULL),(1,14,'2018-06-11 21:05:09','restaurante',NULL),(1,15,'2018-06-11 21:05:09','restaurante',NULL),(4,6,'2018-06-11 21:05:26','restaurante',NULL),(4,7,'2018-06-11 21:05:26','restaurante',NULL),(4,8,'2018-06-11 21:05:26','restaurante',NULL),(4,9,'2018-06-11 21:05:26','restaurante',NULL),(4,10,'2018-06-11 21:05:26','restaurante',NULL),(4,11,'2018-06-11 21:05:26','restaurante',NULL),(4,12,'2018-06-11 21:05:26','restaurante',NULL),(4,13,'2018-06-11 21:05:26','restaurante',NULL),(4,14,'2018-06-11 21:05:26','restaurante',NULL),(4,15,'2018-06-11 21:05:26','restaurante',NULL),(6,8,'2018-06-11 21:05:41','restaurante',NULL),(6,9,'2018-06-11 21:05:41','restaurante',NULL),(6,10,'2018-06-11 21:05:41','restaurante',NULL),(6,11,'2018-06-11 21:05:41','restaurante',NULL),(6,12,'2018-06-11 21:05:41','restaurante',NULL),(6,13,'2018-06-11 21:05:41','restaurante',NULL),(6,14,'2018-06-11 21:05:41','restaurante',NULL),(6,15,'2018-06-11 21:05:41','restaurante',NULL),(6,6,'2018-06-11 21:05:41','restaurante',NULL),(6,7,'2018-06-11 21:05:41','restaurante',NULL),(1,16,'2018-06-11 21:06:50','restaurante',NULL),(1,17,'2018-06-11 21:06:50','restaurante',NULL),(1,18,'2018-06-11 21:06:50','restaurante',NULL),(1,19,'2018-06-11 21:06:50','restaurante',NULL),(4,16,'2018-06-11 21:06:54','restaurante',NULL),(4,17,'2018-06-11 21:06:54','restaurante',NULL),(4,18,'2018-06-11 21:06:54','restaurante',NULL),(4,19,'2018-06-11 21:06:54','restaurante',NULL),(6,16,'2018-06-11 21:07:16','restaurante',NULL),(6,17,'2018-06-11 21:07:16','restaurante',NULL),(6,18,'2018-06-11 21:07:16','restaurante',NULL),(6,19,'2018-06-11 21:07:17','restaurante',NULL),(1,20,'2018-06-11 21:11:43','restaurante',NULL),(1,21,'2018-06-11 21:11:43','restaurante',NULL),(1,22,'2018-06-11 21:11:43','restaurante',NULL),(1,23,'2018-06-11 21:11:43','restaurante',NULL),(1,24,'2018-06-11 21:11:43','restaurante',NULL),(1,25,'2018-06-11 21:11:43','restaurante',NULL),(1,26,'2018-06-11 21:11:43','restaurante',NULL),(1,27,'2018-06-11 21:11:43','restaurante',NULL),(1,28,'2018-06-11 21:11:43','restaurante',NULL),(1,29,'2018-06-11 21:11:43','restaurante',NULL),(1,30,'2018-06-11 21:11:43','restaurante',NULL),(1,31,'2018-06-11 21:11:43','restaurante',NULL),(4,20,'2018-06-11 21:11:48','restaurante',NULL),(4,21,'2018-06-11 21:11:48','restaurante',NULL),(4,22,'2018-06-11 21:11:48','restaurante',NULL),(4,23,'2018-06-11 21:11:48','restaurante',NULL),(4,24,'2018-06-11 21:11:48','restaurante',NULL),(4,25,'2018-06-11 21:11:48','restaurante',NULL),(4,26,'2018-06-11 21:11:48','restaurante',NULL),(4,27,'2018-06-11 21:11:48','restaurante',NULL),(4,28,'2018-06-11 21:11:48','restaurante',NULL),(4,29,'2018-06-11 21:11:48','restaurante',NULL),(4,30,'2018-06-11 21:11:48','restaurante',NULL),(4,31,'2018-06-11 21:11:48','restaurante',NULL),(6,20,'2018-06-11 21:12:05','restaurante',NULL),(6,21,'2018-06-11 21:12:05','restaurante',NULL),(6,22,'2018-06-11 21:12:05','restaurante',NULL),(6,23,'2018-06-11 21:12:05','restaurante',NULL),(6,24,'2018-06-11 21:12:05','restaurante',NULL),(6,25,'2018-06-11 21:12:05','restaurante',NULL),(6,26,'2018-06-11 21:12:05','restaurante',NULL),(6,27,'2018-06-11 21:12:05','restaurante',NULL),(6,28,'2018-06-11 21:12:05','restaurante',NULL),(6,29,'2018-06-11 21:12:05','restaurante',NULL),(6,30,'2018-06-11 21:12:05','restaurante',NULL),(6,31,'2018-06-11 21:12:05','restaurante',NULL);
/*!40000 ALTER TABLE `bitacoraordenmenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja`
--

DROP TABLE IF EXISTS `caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja` (
  `idCaja` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `idEstadoCaja` int(11) NOT NULL,
  `fechaApertura` date NOT NULL,
  `efectivoInicial` double(12,2) NOT NULL,
  `efectivoFinal` double(12,2) NOT NULL,
  `efectivoSobrante` double(10,2) NOT NULL,
  `efectivoFaltante` double(10,2) NOT NULL,
  PRIMARY KEY (`idCaja`),
  KEY `fk_caja_usuario1_idx` (`usuario`),
  KEY `fk_caja_estadoCaja1_idx` (`idEstadoCaja`),
  CONSTRAINT `fk_caja_estadoCaja1` FOREIGN KEY (`idEstadoCaja`) REFERENCES `estadocaja` (`idEstadoCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_caja_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja`
--

LOCK TABLES `caja` WRITE;
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
INSERT INTO `caja` VALUES (1,'restaurante',1,'2018-06-11',2500.00,0.00,0.00,0.00);
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`caja_AFTER_INSERT` AFTER INSERT ON `caja` FOR EACH ROW
BEGIN
	INSERT INTO bitacoraEstadoCaja( idCaja, idEstadoCaja, fechaRegistro, usuario ) 
		VALUES ( NEW.idCaja, NEW.idEstadoCaja, NOW(), @usuario );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`caja_AFTER_UPDATE` AFTER UPDATE ON `caja` FOR EACH ROW
BEGIN
	INSERT INTO bitacoraEstadoCaja( idCaja, idEstadoCaja, fechaRegistro, usuario ) 
		VALUES ( NEW.idCaja, NEW.idEstadoCaja, NOW(), @usuario );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `nit` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  `cui` varchar(13) COLLATE utf8_spanish_ci DEFAULT NULL,
  `correo` varchar(65) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(95) COLLATE utf8_spanish_ci DEFAULT NULL,
  `idTipoCliente` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_cliente_tipoCliente1_idx` (`idTipoCliente`),
  KEY `ix_usuario` (`usuario`),
  CONSTRAINT `fk_cliente_tipoCliente1` FOREIGN KEY (`idTipoCliente`) REFERENCES `tipocliente` (`idTipoCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cliente_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (1,'CF','Consumidor Final',NULL,NULL,NULL,'Ciudad',1,'2017-08-01 00:00:00','restaurante');
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `columnalista`
--

DROP TABLE IF EXISTS `columnalista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `columnalista` (
  `idColumnaLista` int(11) NOT NULL AUTO_INCREMENT,
  `idDocumentoDetalle` int(11) NOT NULL,
  `campo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `_index` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `width` int(11) NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`idColumnaLista`),
  KEY `fk_table1_documentoDetalle1_idx` (`idDocumentoDetalle`),
  CONSTRAINT `fk_table1_documentoDetalle1` FOREIGN KEY (`idDocumentoDetalle`) REFERENCES `documentodetalle` (`idDocumentoDetalle`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `columnalista`
--

LOCK TABLES `columnalista` WRITE;
/*!40000 ALTER TABLE `columnalista` DISABLE KEYS */;
INSERT INTO `columnalista` VALUES (2,4,'Descripción','descripcion',200,2),(3,4,'Cantidad','cantidad',55,1),(4,4,'P/U','precioReal',55,3),(5,4,'Subtotal','subTotal',55,4),(7,8,'Cantidad','cantidad',55,1),(8,8,'Descripción','descripcion',200,2),(9,8,'Subtotal','subTotal',60,3);
/*!40000 ALTER TABLE `columnalista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combo`
--

DROP TABLE IF EXISTS `combo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combo` (
  `idCombo` int(11) NOT NULL AUTO_INCREMENT,
  `combo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(125) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `idEstadoMenu` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCombo`),
  KEY `fk_combo_estadoMenu1_idx` (`idEstadoMenu`),
  CONSTRAINT `fk_combo_estadoMenu1` FOREIGN KEY (`idEstadoMenu`) REFERENCES `estadomenu` (`idEstadoMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combo`
--

LOCK TABLES `combo` WRITE;
/*!40000 ALTER TABLE `combo` DISABLE KEYS */;
INSERT INTO `combo` VALUES (1,'Combo Economico',NULL,'jkfja sdkjfl ksddjfl kds',1,5,33);
/*!40000 ALTER TABLE `combo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `combodetalle`
--

DROP TABLE IF EXISTS `combodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combodetalle` (
  `idCombo` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  PRIMARY KEY (`idCombo`,`idMenu`),
  KEY `fk_combo_has_menu_menu1_idx` (`idMenu`),
  KEY `fk_combo_has_menu_combo1_idx` (`idCombo`),
  CONSTRAINT `fk_combo_has_menu_combo1` FOREIGN KEY (`idCombo`) REFERENCES `combo` (`idCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_combo_has_menu_menu1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `combodetalle`
--

LOCK TABLES `combodetalle` WRITE;
/*!40000 ALTER TABLE `combodetalle` DISABLE KEYS */;
INSERT INTO `combodetalle` VALUES (1,1,2.00),(1,2,2.00);
/*!40000 ALTER TABLE `combodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comboprecio`
--

DROP TABLE IF EXISTS `comboprecio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comboprecio` (
  `idCombo` int(11) NOT NULL,
  `idTipoServicio` int(11) NOT NULL,
  `precio` double(10,2) NOT NULL,
  PRIMARY KEY (`idCombo`,`idTipoServicio`),
  KEY `fk_combo_has_tipoServicio_tipoServicio1_idx` (`idTipoServicio`),
  KEY `fk_combo_has_tipoServicio_combo1_idx` (`idCombo`),
  CONSTRAINT `fk_combo_has_tipoServicio_combo1` FOREIGN KEY (`idCombo`) REFERENCES `combo` (`idCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_combo_has_tipoServicio_tipoServicio1` FOREIGN KEY (`idTipoServicio`) REFERENCES `tiposervicio` (`idTipoServicio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comboprecio`
--

LOCK TABLES `comboprecio` WRITE;
/*!40000 ALTER TABLE `comboprecio` DISABLE KEYS */;
INSERT INTO `comboprecio` VALUES (1,1,35.00),(1,2,30.00),(1,3,35.00);
/*!40000 ALTER TABLE `comboprecio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuadreproducto`
--

DROP TABLE IF EXISTS `cuadreproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuadreproducto` (
  `idCuadreProducto` int(11) NOT NULL AUTO_INCREMENT,
  `fechaCuadre` date NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `todos` tinyint(1) NOT NULL,
  `idEstadoCuadre` int(11) NOT NULL,
  `idUbicacion` int(11) NOT NULL,
  PRIMARY KEY (`idCuadreProducto`),
  KEY `fk_cierreDiario_usuario1_idx` (`usuario`),
  KEY `ix_fechaCierre` (`fechaCuadre`),
  KEY `fk_cierreDiario_estadoCuadre1_idx` (`idEstadoCuadre`),
  KEY `fk_cuadreProducto_ubicacion1_idx` (`idUbicacion`),
  CONSTRAINT `fk_cierreDiario_estadoCuadre1` FOREIGN KEY (`idEstadoCuadre`) REFERENCES `estadocuadre` (`idEstadoCuadre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cierreDiario_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cuadreProducto_ubicacion1` FOREIGN KEY (`idUbicacion`) REFERENCES `ubicacion` (`idUbicacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuadreproducto`
--

LOCK TABLES `cuadreproducto` WRITE;
/*!40000 ALTER TABLE `cuadreproducto` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuadreproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuadreproductodetalle`
--

DROP TABLE IF EXISTS `cuadreproductodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuadreproductodetalle` (
  `idCuadreProducto` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidadApertura` double(10,2) NOT NULL,
  `cantidadCierre` double(10,2) NOT NULL,
  `diferenciaApertura` double(10,2) DEFAULT NULL,
  `diferenciaCierre` double(10,2) DEFAULT NULL,
  `comentarioApertura` text COLLATE utf8_spanish_ci,
  `comentarioCierre` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idCuadreProducto`,`idProducto`),
  KEY `fk_cuadreProducto_has_producto_producto1_idx` (`idProducto`),
  KEY `fk_cuadreProducto_has_producto_cuadreProducto1_idx` (`idCuadreProducto`),
  CONSTRAINT `fk_cuadreProducto_has_producto_cuadreProducto1` FOREIGN KEY (`idCuadreProducto`) REFERENCES `cuadreproducto` (`idCuadreProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cuadreProducto_has_producto_producto1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuadreproductodetalle`
--

LOCK TABLES `cuadreproductodetalle` WRITE;
/*!40000 ALTER TABLE `cuadreproductodetalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuadreproductodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denominacion`
--

DROP TABLE IF EXISTS `denominacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denominacion` (
  `denominacion` double(5,2) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `descripcion` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`denominacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denominacion`
--

LOCK TABLES `denominacion` WRITE;
/*!40000 ALTER TABLE `denominacion` DISABLE KEYS */;
INSERT INTO `denominacion` VALUES (0.01,0,'Moneda(s)'),(0.05,1,'Moneda(s)'),(0.10,1,'Moneda(s)'),(0.25,1,'Moneda(s)'),(0.50,1,'Moneda(s)'),(1.00,1,'Moneda(s)'),(5.00,1,'Billete(s)'),(10.00,1,'Billete(s)'),(20.00,1,'Billete(s)'),(50.00,1,'Billete(s)'),(100.00,1,'Billete(s)'),(200.00,1,'Billete(s)');
/*!40000 ALTER TABLE `denominacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `denominacioncaja`
--

DROP TABLE IF EXISTS `denominacioncaja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `denominacioncaja` (
  `idCaja` int(11) NOT NULL,
  `idEstadoCaja` int(11) NOT NULL,
  `denominacion` double(5,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`idCaja`,`idEstadoCaja`,`denominacion`),
  KEY `fk_bitacoraEstadoCaja_has_denominacion_bitacoraEstadoCaja1_idx` (`idCaja`,`idEstadoCaja`),
  KEY `fk_denominacionCaja_denominacion1_idx` (`denominacion`),
  CONSTRAINT `fk_bitacoraEstadoCaja_has_denominacion_bitacoraEstadoCaja1` FOREIGN KEY (`idCaja`, `idEstadoCaja`) REFERENCES `bitacoraestadocaja` (`idCaja`, `idEstadoCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_denominacionCaja_denominacion1` FOREIGN KEY (`denominacion`) REFERENCES `denominacion` (`denominacion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `denominacioncaja`
--

LOCK TABLES `denominacioncaja` WRITE;
/*!40000 ALTER TABLE `denominacioncaja` DISABLE KEYS */;
INSERT INTO `denominacioncaja` VALUES (1,1,0.05,0),(1,1,0.10,0),(1,1,0.25,0),(1,1,0.50,0),(1,1,1.00,0),(1,1,5.00,0),(1,1,10.00,0),(1,1,20.00,0),(1,1,50.00,50),(1,1,100.00,0),(1,1,200.00,0);
/*!40000 ALTER TABLE `denominacioncaja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinomenu`
--

DROP TABLE IF EXISTS `destinomenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `destinomenu` (
  `idDestinoMenu` int(11) NOT NULL AUTO_INCREMENT,
  `destinoMenu` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idDestinoMenu`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinomenu`
--

LOCK TABLES `destinomenu` WRITE;
/*!40000 ALTER TABLE `destinomenu` DISABLE KEYS */;
INSERT INTO `destinomenu` VALUES (1,'Cocina'),(2,'Barra');
/*!40000 ALTER TABLE `destinomenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detallecombomenu`
--

DROP TABLE IF EXISTS `detallecombomenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detallecombomenu` (
  `idDetalleOrdenMenu` int(11) NOT NULL,
  `idDetalleOrdenCombo` int(11) NOT NULL,
  PRIMARY KEY (`idDetalleOrdenMenu`,`idDetalleOrdenCombo`),
  KEY `fk_detalleOrdenMenu_has_detalleOrdenCombo_detalleOrdenCombo_idx` (`idDetalleOrdenCombo`),
  KEY `fk_detalleOrdenMenu_has_detalleOrdenCombo_detalleOrdenMenu1_idx` (`idDetalleOrdenMenu`),
  CONSTRAINT `fk_detalleOrdenMenu_has_detalleOrdenCombo_detalleOrdenCombo1` FOREIGN KEY (`idDetalleOrdenCombo`) REFERENCES `detalleordencombo` (`idDetalleOrdenCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOrdenMenu_has_detalleOrdenCombo_detalleOrdenMenu1` FOREIGN KEY (`idDetalleOrdenMenu`) REFERENCES `detalleordenmenu` (`idDetalleOrdenMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detallecombomenu`
--

LOCK TABLES `detallecombomenu` WRITE;
/*!40000 ALTER TABLE `detallecombomenu` DISABLE KEYS */;
INSERT INTO `detallecombomenu` VALUES (8,1),(9,1),(10,1),(11,1),(12,2),(13,2),(14,2),(15,2),(20,3),(21,3),(22,3),(23,3),(24,4),(25,4),(26,4),(27,4),(28,5),(29,5),(30,5),(31,5);
/*!40000 ALTER TABLE `detallecombomenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalleordencombo`
--

DROP TABLE IF EXISTS `detalleordencombo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalleordencombo` (
  `idDetalleOrdenCombo` int(11) NOT NULL AUTO_INCREMENT,
  `idOrdenCliente` int(11) NOT NULL,
  `idCombo` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  `idEstadoDetalleOrden` int(11) NOT NULL,
  `idTipoServicio` int(11) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `usuarioResponsable` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idDetalleOrdenCombo`),
  KEY `fk_detalleOdenMenu_ordenCliente1_idx` (`idOrdenCliente`),
  KEY `fk_detalleOdenMenu_estadoDetalleOrden1_idx` (`idEstadoDetalleOrden`),
  KEY `fk_detalleOdenCombo_combo1_idx` (`idCombo`),
  KEY `fk_detalleOdenCombo_tipoServicio1_idx` (`idTipoServicio`),
  KEY `fk_detalleOdenCombo_usuario1_idx` (`usuario`),
  KEY `fk_detalleOdenCombo_usuario2_idx` (`usuarioResponsable`),
  CONSTRAINT `fk_detalleOdenCombo_combo1` FOREIGN KEY (`idCombo`) REFERENCES `combo` (`idCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenCombo_tipoServicio1` FOREIGN KEY (`idTipoServicio`) REFERENCES `tiposervicio` (`idTipoServicio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenCombo_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenCombo_usuario2` FOREIGN KEY (`usuarioResponsable`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_estadoDetalleOrden10` FOREIGN KEY (`idEstadoDetalleOrden`) REFERENCES `estadodetalleorden` (`idEstadoDetalleOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_ordenCliente10` FOREIGN KEY (`idOrdenCliente`) REFERENCES `ordencliente` (`idOrdenCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalleordencombo`
--

LOCK TABLES `detalleordencombo` WRITE;
/*!40000 ALTER TABLE `detalleordencombo` DISABLE KEYS */;
INSERT INTO `detalleordencombo` VALUES (1,2,1,1.00,6,2,'restaurante','restaurante',NULL),(2,2,1,1.00,6,2,'restaurante','restaurante',NULL),(3,4,1,1.00,6,2,'restaurante','restaurante',NULL),(4,4,1,1.00,6,2,'restaurante','restaurante',NULL),(5,4,1,1.00,6,2,'restaurante','restaurante',NULL);
/*!40000 ALTER TABLE `detalleordencombo` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER dbChurchill.detalleOrdenCombo_AFTER_INSERT AFTER INSERT ON detalleOrdenCombo FOR EACH ROW
BEGIN
	INSERT INTO bitacoraOrdenCombo(idEstadoDetalleOrden, idDetalleOrdenCombo, fechaRegistro, usuario) 
		VALUES (NEW.idEstadoDetalleOrden, NEW.idDetalleOrdenCombo, now(), @usuario);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`detalleOrdenCombo_AFTER_UPDATE` AFTER UPDATE ON `detalleOrdenCombo` FOR EACH ROW
BEGIN
	IF IFNULL( OLD.idEstadoDetalleOrden, '' ) != IFNULL( NEW.idEstadoDetalleOrden, '' ) THEN
		INSERT INTO bitacoraOrdenCombo(idEstadoDetalleOrden, idDetalleOrdenCombo, fechaRegistro, usuario, comentario ) 
			VALUES (NEW.idEstadoDetalleOrden, NEW.idDetalleOrdenCombo, now(), @usuario, @comentario );
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `detalleordenfactura`
--

DROP TABLE IF EXISTS `detalleordenfactura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalleordenfactura` (
  `idFactura` int(11) NOT NULL,
  `idDetalleOrdenMenu` int(11) DEFAULT NULL,
  `idDetalleOrdenCombo` int(11) DEFAULT NULL,
  `idMenuPersonalizado` int(11) DEFAULT NULL,
  `precioMenu` double(10,2) NOT NULL,
  `descuento` double(10,2) NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  UNIQUE KEY `uq_detalle` (`idFactura`,`idDetalleOrdenMenu`,`idDetalleOrdenCombo`),
  KEY `fk_detalleOrdenMenu_has_factura_factura1_idx` (`idFactura`),
  KEY `fk_detalleOrdenMenu_has_factura_detalleOrdenMenu1_idx` (`idDetalleOrdenMenu`),
  KEY `fk_detalleOrdenMenuFactura_usuario1_idx` (`usuario`),
  KEY `fk_detalleOrdenFactura_detalleOrdenCombo1_idx` (`idDetalleOrdenCombo`),
  KEY `fk_detalleOrdenFactura_menuPersonalizado1_idx` (`idMenuPersonalizado`),
  CONSTRAINT `fk_detalleOrdenFactura_detalleOrdenCombo1` FOREIGN KEY (`idDetalleOrdenCombo`) REFERENCES `detalleordencombo` (`idDetalleOrdenCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOrdenFactura_menuPersonalizado1` FOREIGN KEY (`idMenuPersonalizado`) REFERENCES `menupersonalizado` (`idMenuPersonalizado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOrdenMenuFactura_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOrdenMenu_has_factura_detalleOrdenMenu1` FOREIGN KEY (`idDetalleOrdenMenu`) REFERENCES `detalleordenmenu` (`idDetalleOrdenMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOrdenMenu_has_factura_factura1` FOREIGN KEY (`idFactura`) REFERENCES `factura` (`idFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalleordenfactura`
--

LOCK TABLES `detalleordenfactura` WRITE;
/*!40000 ALTER TABLE `detalleordenfactura` DISABLE KEYS */;
INSERT INTO `detalleordenfactura` VALUES (4,4,NULL,NULL,5.00,0.00,NULL,'restaurante','2018-06-11 20:28:07'),(4,5,NULL,NULL,5.00,0.00,NULL,'restaurante','2018-06-11 20:28:07'),(4,1,NULL,NULL,10.00,5.00,'POr que da la gana cancelar el menu','restaurante','2018-06-11 20:28:07'),(4,2,NULL,NULL,10.00,0.00,NULL,'restaurante','2018-06-11 20:28:07'),(4,3,NULL,NULL,10.00,0.00,NULL,'restaurante','2018-06-11 20:28:07'),(4,NULL,NULL,1,3.00,3.00,NULL,'restaurante','2018-06-11 20:28:07'),(5,NULL,1,NULL,30.00,0.00,NULL,'restaurante','2018-06-11 21:05:41'),(5,NULL,2,NULL,30.00,0.00,NULL,'restaurante','2018-06-11 21:05:41'),(5,6,NULL,NULL,10.00,0.00,NULL,'restaurante','2018-06-11 21:05:41'),(5,7,NULL,NULL,10.00,0.00,NULL,'restaurante','2018-06-11 21:05:41'),(6,16,NULL,NULL,5.00,0.00,NULL,'restaurante','2018-06-11 21:07:16'),(6,17,NULL,NULL,5.00,0.00,NULL,'restaurante','2018-06-11 21:07:16'),(6,18,NULL,NULL,5.00,0.00,NULL,'restaurante','2018-06-11 21:07:17'),(6,19,NULL,NULL,5.00,0.00,NULL,'restaurante','2018-06-11 21:07:17'),(7,NULL,3,NULL,30.00,0.00,NULL,'restaurante','2018-06-11 21:12:05'),(7,NULL,4,NULL,30.00,0.00,NULL,'restaurante','2018-06-11 21:12:05'),(7,NULL,5,NULL,30.00,0.00,NULL,'restaurante','2018-06-11 21:12:05');
/*!40000 ALTER TABLE `detalleordenfactura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalleordenmenu`
--

DROP TABLE IF EXISTS `detalleordenmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalleordenmenu` (
  `idDetalleOrdenMenu` int(11) NOT NULL AUTO_INCREMENT,
  `idOrdenCliente` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  `idEstadoDetalleOrden` int(11) NOT NULL,
  `idTipoServicio` int(11) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `usuarioResponsable` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `perteneceCombo` tinyint(1) NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idDetalleOrdenMenu`),
  KEY `fk_detalleOdenMenu_ordenCliente1_idx` (`idOrdenCliente`),
  KEY `fk_detalleOdenMenu_menu1_idx` (`idMenu`),
  KEY `fk_detalleOdenMenu_estadoDetalleOrden1_idx` (`idEstadoDetalleOrden`),
  KEY `fk_detalleOdenMenu_tipoServicio1_idx` (`idTipoServicio`),
  KEY `fk_detalleOdenMenu_usuario1_idx` (`usuario`),
  KEY `fk_detalleOdenMenu_usuario2_idx` (`usuarioResponsable`),
  CONSTRAINT `fk_detalleOdenMenu_estadoDetalleOrden1` FOREIGN KEY (`idEstadoDetalleOrden`) REFERENCES `estadodetalleorden` (`idEstadoDetalleOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_menu1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_ordenCliente1` FOREIGN KEY (`idOrdenCliente`) REFERENCES `ordencliente` (`idOrdenCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_tipoServicio1` FOREIGN KEY (`idTipoServicio`) REFERENCES `tiposervicio` (`idTipoServicio`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleOdenMenu_usuario2` FOREIGN KEY (`usuarioResponsable`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalleordenmenu`
--

LOCK TABLES `detalleordenmenu` WRITE;
/*!40000 ALTER TABLE `detalleordenmenu` DISABLE KEYS */;
INSERT INTO `detalleordenmenu` VALUES (1,1,2,1.00,6,2,'restaurante','restaurante',0,NULL),(2,1,2,1.00,6,2,'restaurante','restaurante',0,NULL),(3,1,2,1.00,6,2,'restaurante','restaurante',0,NULL),(4,1,1,1.00,6,2,'restaurante','restaurante',0,NULL),(5,1,1,1.00,6,2,'restaurante','restaurante',0,NULL),(6,2,2,1.00,6,2,'restaurante','restaurante',0,NULL),(7,2,2,1.00,6,2,'restaurante','restaurante',0,NULL),(8,2,1,1.00,6,2,'restaurante','restaurante',1,NULL),(9,2,1,1.00,6,2,'restaurante','restaurante',1,NULL),(10,2,2,1.00,6,2,'restaurante','restaurante',1,NULL),(11,2,2,1.00,6,2,'restaurante','restaurante',1,NULL),(12,2,1,1.00,6,2,'restaurante','restaurante',1,NULL),(13,2,1,1.00,6,2,'restaurante','restaurante',1,NULL),(14,2,2,1.00,6,2,'restaurante','restaurante',1,NULL),(15,2,2,1.00,6,2,'restaurante','restaurante',1,NULL),(16,3,1,1.00,6,2,'restaurante','restaurante',0,NULL),(17,3,1,1.00,6,2,'restaurante','restaurante',0,NULL),(18,3,1,1.00,6,2,'restaurante','restaurante',0,NULL),(19,3,1,1.00,6,2,'restaurante','restaurante',0,NULL),(20,4,1,1.00,6,2,'restaurante','restaurante',1,NULL),(21,4,1,1.00,6,2,'restaurante','restaurante',1,NULL),(22,4,2,1.00,6,2,'restaurante','restaurante',1,NULL),(23,4,2,1.00,6,2,'restaurante','restaurante',1,NULL),(24,4,1,1.00,6,2,'restaurante','restaurante',1,NULL),(25,4,1,1.00,6,2,'restaurante','restaurante',1,NULL),(26,4,2,1.00,6,2,'restaurante','restaurante',1,NULL),(27,4,2,1.00,6,2,'restaurante','restaurante',1,NULL),(28,4,1,1.00,6,2,'restaurante','restaurante',1,NULL),(29,4,1,1.00,6,2,'restaurante','restaurante',1,NULL),(30,4,2,1.00,6,2,'restaurante','restaurante',1,NULL),(31,4,2,1.00,6,2,'restaurante','restaurante',1,NULL);
/*!40000 ALTER TABLE `detalleordenmenu` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER dbChurchill.detalleOrdenMenu_AFTER_INSERT AFTER INSERT ON detalleOrdenMenu FOR EACH ROW
BEGIN
	INSERT INTO bitacoraOrdenMenu(idEstadoDetalleOrden, idDetalleOrdenMenu, fechaRegistro, usuario) 
		VALUES (NEW.idEstadoDetalleOrden, NEW.idDetalleOrdenMenu, now(), @usuario);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`detalleOrdenMenu_AFTER_UPDATE` AFTER UPDATE ON `detalleOrdenMenu` FOR EACH ROW
BEGIN
	DECLARE _ordenesPendientes INT DEFAULT 0;
    
	IF IFNULL( OLD.idEstadoDetalleOrden, '' ) != IFNULL( NEW.idEstadoDetalleOrden, '' ) THEN
		INSERT INTO bitacoraOrdenMenu(idEstadoDetalleOrden, idDetalleOrdenMenu, fechaRegistro, usuario, comentario ) 
			VALUES (NEW.idEstadoDetalleOrden, NEW.idDetalleOrdenMenu, now(), @usuario, @comentario );

	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento` (
  `idDocumento` int(11) NOT NULL,
  `documento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idDocumento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
INSERT INTO `documento` VALUES (1,'Factura'),(2,'Factura Evento');
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentodetalle`
--

DROP TABLE IF EXISTS `documentodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentodetalle` (
  `idDocumentoDetalle` int(11) NOT NULL AUTO_INCREMENT,
  `idDocumento` int(11) NOT NULL,
  `idTipoItem` int(11) NOT NULL,
  `label` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `_index` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `mostrarTitulo` tinyint(1) NOT NULL,
  `relativo` tinyint(1) NOT NULL,
  `fontSize` int(11) NOT NULL,
  PRIMARY KEY (`idDocumentoDetalle`),
  KEY `fk_docDetalle_doc1_idx` (`idDocumento`),
  KEY `fk_docDetalle_tipoItem1_idx` (`idTipoItem`),
  CONSTRAINT `fk_docDetalle_doc1` FOREIGN KEY (`idDocumento`) REFERENCES `documento` (`idDocumento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_docDetalle_tipoItem1` FOREIGN KEY (`idTipoItem`) REFERENCES `tipoitem` (`idTipoItem`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentodetalle`
--

LOCK TABLES `documentodetalle` WRITE;
/*!40000 ALTER TABLE `documentodetalle` DISABLE KEYS */;
INSERT INTO `documentodetalle` VALUES (1,1,1,'NIT','nit',15,20,1,0,13),(2,1,1,'Nombre','nombre',15,35,1,0,12),(3,1,1,'Dirección','direccion',15,50,1,0,12),(4,1,2,'Detalle Factura','lstDetalle',15,75,0,0,12),(5,2,1,'NIT','nit',15,20,1,0,13),(6,2,1,'Nombre','nombre',15,35,1,0,12),(7,2,1,'Dirección','direccion',15,50,1,0,12),(8,2,2,'Detalle Factura','lstDetalle',15,75,0,0,12);
/*!40000 ALTER TABLE `documentodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadocaja`
--

DROP TABLE IF EXISTS `estadocaja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadocaja` (
  `idEstadoCaja` int(11) NOT NULL,
  `estadoCaja` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoCaja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadocaja`
--

LOCK TABLES `estadocaja` WRITE;
/*!40000 ALTER TABLE `estadocaja` DISABLE KEYS */;
INSERT INTO `estadocaja` VALUES (1,'Abierta'),(2,'Cerrada'),(3,'Cierre con Autorización');
/*!40000 ALTER TABLE `estadocaja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadocuadre`
--

DROP TABLE IF EXISTS `estadocuadre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadocuadre` (
  `idEstadoCuadre` int(11) NOT NULL,
  `estadoCuadre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoCuadre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadocuadre`
--

LOCK TABLES `estadocuadre` WRITE;
/*!40000 ALTER TABLE `estadocuadre` DISABLE KEYS */;
INSERT INTO `estadocuadre` VALUES (1,'Aperturado'),(2,'Cerrado'),(3,'Cuadre');
/*!40000 ALTER TABLE `estadocuadre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadodetalleorden`
--

DROP TABLE IF EXISTS `estadodetalleorden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadodetalleorden` (
  `idEstadoDetalleOrden` int(11) NOT NULL,
  `estadoDetalleOrden` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoDetalleOrden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadodetalleorden`
--

LOCK TABLES `estadodetalleorden` WRITE;
/*!40000 ALTER TABLE `estadodetalleorden` DISABLE KEYS */;
INSERT INTO `estadodetalleorden` VALUES (1,'Pendiente'),(2,'Cocinando'),(3,'Listo'),(4,'Servido'),(5,'En Factura'),(6,'Facturado'),(10,'CANCELADO');
/*!40000 ALTER TABLE `estadodetalleorden` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadoevento`
--

DROP TABLE IF EXISTS `estadoevento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadoevento` (
  `idEstadoEvento` int(11) NOT NULL,
  `estadoEvento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoEvento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadoevento`
--

LOCK TABLES `estadoevento` WRITE;
/*!40000 ALTER TABLE `estadoevento` DISABLE KEYS */;
INSERT INTO `estadoevento` VALUES (1,'Programado'),(5,'Finalizado'),(10,'Cancelado');
/*!40000 ALTER TABLE `estadoevento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadofactura`
--

DROP TABLE IF EXISTS `estadofactura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadofactura` (
  `idEstadoFactura` int(11) NOT NULL,
  `estadoFactura` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoFactura`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadofactura`
--

LOCK TABLES `estadofactura` WRITE;
/*!40000 ALTER TABLE `estadofactura` DISABLE KEYS */;
INSERT INTO `estadofactura` VALUES (1,'Pagado'),(2,'Pendiente'),(3,'Pagado parcialmente'),(10,'Cancelado');
/*!40000 ALTER TABLE `estadofactura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadomenu`
--

DROP TABLE IF EXISTS `estadomenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadomenu` (
  `idEstadoMenu` int(11) NOT NULL AUTO_INCREMENT,
  `estadoMenu` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoMenu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadomenu`
--

LOCK TABLES `estadomenu` WRITE;
/*!40000 ALTER TABLE `estadomenu` DISABLE KEYS */;
INSERT INTO `estadomenu` VALUES (1,'Activo'),(2,'Inactivo'),(3,'Eliminado');
/*!40000 ALTER TABLE `estadomenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadomovimiento`
--

DROP TABLE IF EXISTS `estadomovimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadomovimiento` (
  `idEstadoMovimiento` int(11) NOT NULL,
  `estadoMovimiento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoMovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadomovimiento`
--

LOCK TABLES `estadomovimiento` WRITE;
/*!40000 ALTER TABLE `estadomovimiento` DISABLE KEYS */;
INSERT INTO `estadomovimiento` VALUES (1,'Pendiente'),(5,'Realizado'),(10,'Reversado');
/*!40000 ALTER TABLE `estadomovimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadoorden`
--

DROP TABLE IF EXISTS `estadoorden`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadoorden` (
  `idEstadoOrden` int(11) NOT NULL,
  `estadoOrden` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoOrden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadoorden`
--

LOCK TABLES `estadoorden` WRITE;
/*!40000 ALTER TABLE `estadoorden` DISABLE KEYS */;
INSERT INTO `estadoorden` VALUES (1,'Pendiente'),(2,'Cocinando'),(3,'Pendiente Entrega'),(4,'Servido'),(5,'Finalizado'),(6,'Facturado'),(7,'Programado (Evento)'),(10,'CANCELADO');
/*!40000 ALTER TABLE `estadoorden` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estadousuario`
--

DROP TABLE IF EXISTS `estadousuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estadousuario` (
  `idEstadoUsuario` int(11) NOT NULL,
  `estadoUsuario` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idEstadoUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadousuario`
--

LOCK TABLES `estadousuario` WRITE;
/*!40000 ALTER TABLE `estadousuario` DISABLE KEYS */;
INSERT INTO `estadousuario` VALUES (1,'Activo'),(2,'Bloqueado'),(3,'Deshabilitado');
/*!40000 ALTER TABLE `estadousuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento` (
  `idEvento` int(11) NOT NULL AUTO_INCREMENT,
  `evento` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `idCliente` int(11) NOT NULL,
  `fechaEvento` date NOT NULL,
  `idSalon` int(11) NOT NULL,
  `idEstadoEvento` int(11) NOT NULL,
  `numeroPersonas` int(11) NOT NULL,
  `horaInicio` time NOT NULL,
  `horaFinal` time NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `idFactura` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEvento`),
  KEY `fk_evento_cliente1_idx` (`idCliente`),
  KEY `fk_evento_estadoEvento1_idx` (`idEstadoEvento`),
  KEY `fk_evento_usuario1_idx` (`usuario`),
  KEY `fk_evento_salon1_idx` (`idSalon`),
  KEY `fk_evento_factura1_idx` (`idFactura`),
  CONSTRAINT `fk_evento_cliente1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_estadoEvento1` FOREIGN KEY (`idEstadoEvento`) REFERENCES `estadoevento` (`idEstadoEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_factura1` FOREIGN KEY (`idFactura`) REFERENCES `factura` (`idFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_salon1` FOREIGN KEY (`idSalon`) REFERENCES `salon` (`idSalon`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`evento_AFTER_INSERT` AFTER INSERT ON `evento` FOR EACH ROW
BEGIN

	INSERT INTO bitacoraEstadoEvento ( idEstadoEvento, idEvento, comentario, usuario, fechaRegistro )
		VALUES( NEW.idEstadoEvento, NEW.idEvento, IFNULL( @comentario, '' ), @usuario, NOW() );

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`evento_AFTER_UPDATE` AFTER UPDATE ON `evento` FOR EACH ROW
BEGIN
	IF OLD.idEstadoEvento != NEW.idEstadoEvento THEN
		INSERT INTO bitacoraEstadoEvento ( idEstadoEvento, idEvento, comentario, usuario, fechaRegistro )
			VALUES( NEW.idEstadoEvento, NEW.idEvento, IFNULL( @comentario, '' ), @usuario, NOW() );
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `eventocombo`
--

DROP TABLE IF EXISTS `eventocombo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventocombo` (
  `idEventoCombo` int(11) NOT NULL AUTO_INCREMENT,
  `idEvento` int(11) NOT NULL,
  `idCombo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `horaDespacho` time DEFAULT NULL,
  `precioUnitario` double(10,2) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idEventoCombo`),
  KEY `fk_combo_has_evento_evento1_idx` (`idEvento`),
  KEY `fk_combo_has_evento_combo1_idx` (`idCombo`),
  KEY `fk_eventoCombo_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_combo_has_evento_combo1` FOREIGN KEY (`idCombo`) REFERENCES `combo` (`idCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_combo_has_evento_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_eventoCombo_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventocombo`
--

LOCK TABLES `eventocombo` WRITE;
/*!40000 ALTER TABLE `eventocombo` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventocombo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventofactura`
--

DROP TABLE IF EXISTS `eventofactura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventofactura` (
  `idEventoFactura` int(11) NOT NULL AUTO_INCREMENT,
  `idEvento` int(11) NOT NULL,
  `idFactura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `subTotal` double(10,2) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`idEventoFactura`),
  KEY `fk_evento_has_factura_factura1_idx` (`idFactura`),
  KEY `fk_evento_has_factura_evento1_idx` (`idEvento`),
  CONSTRAINT `fk_evento_has_factura_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_factura_factura1` FOREIGN KEY (`idFactura`) REFERENCES `factura` (`idFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventofactura`
--

LOCK TABLES `eventofactura` WRITE;
/*!40000 ALTER TABLE `eventofactura` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventofactura` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventomenu`
--

DROP TABLE IF EXISTS `eventomenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventomenu` (
  `idEventoMenu` int(11) NOT NULL AUTO_INCREMENT,
  `idEvento` int(11) NOT NULL,
  `idMenu` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `horaDespacho` time DEFAULT NULL,
  `precioUnitario` double(10,2) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idEventoMenu`),
  KEY `fk_evento_has_menu_menu1_idx` (`idMenu`),
  KEY `fk_evento_has_menu_evento1_idx` (`idEvento`),
  KEY `fk_eventoMenu_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_eventoMenu_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_menu_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_menu_menu1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventomenu`
--

LOCK TABLES `eventomenu` WRITE;
/*!40000 ALTER TABLE `eventomenu` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventomenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventoordencliente`
--

DROP TABLE IF EXISTS `eventoordencliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventoordencliente` (
  `idEvento` int(11) NOT NULL,
  `idOrdenCliente` int(11) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`idEvento`,`idOrdenCliente`),
  KEY `fk_evento_has_ordenCliente_ordenCliente1_idx` (`idOrdenCliente`),
  KEY `fk_evento_has_ordenCliente_evento1_idx` (`idEvento`),
  KEY `fk_eventoOrdenCliente_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_eventoOrdenCliente_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_ordenCliente_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_ordenCliente_ordenCliente1` FOREIGN KEY (`idOrdenCliente`) REFERENCES `ordencliente` (`idOrdenCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventoordencliente`
--

LOCK TABLES `eventoordencliente` WRITE;
/*!40000 ALTER TABLE `eventoordencliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `eventoordencliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factura`
--

DROP TABLE IF EXISTS `factura`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `factura` (
  `idFactura` int(11) NOT NULL AUTO_INCREMENT,
  `idEstadoFactura` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idCaja` int(11) NOT NULL,
  `nombre` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `total` double(12,2) NOT NULL,
  `fechaFactura` date NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(125) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idFactura`),
  KEY `fk_factura_cliente1_idx` (`idCliente`),
  KEY `fk_factura_caja1_idx` (`idCaja`),
  KEY `fk_factura_estadoFactura1_idx` (`idEstadoFactura`),
  KEY `fk_factura_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_factura_caja1` FOREIGN KEY (`idCaja`) REFERENCES `caja` (`idCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_factura_cliente1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_factura_estadoFactura1` FOREIGN KEY (`idEstadoFactura`) REFERENCES `estadofactura` (`idEstadoFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_factura_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factura`
--

LOCK TABLES `factura` WRITE;
/*!40000 ALTER TABLE `factura` DISABLE KEYS */;
INSERT INTO `factura` VALUES (4,1,1,1,'Consumidor Final','Ciudad',41.00,'2018-05-30','2018-06-09 20:28:07','restaurante',NULL),(5,1,1,1,'Consumidor Final','Ciudad',80.00,'2018-06-10','2018-06-10 21:05:41','restaurante',NULL),(6,1,1,1,'Consumidor Final','Ciudad',20.00,'2018-06-10','2018-06-10 21:07:16','restaurante',NULL),(7,1,1,1,'Consumidor Final','Ciudad',90.00,'2018-06-11','2018-06-11 21:12:05','restaurante',NULL);
/*!40000 ALTER TABLE `factura` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`factura_AFTER_INSERT` AFTER INSERT ON `factura` FOR EACH ROW
BEGIN
	INSERT INTO bitacoraEstadoFactura ( idFactura, idEstadoFactura, usuario, fechaRegistro ) 
		VALUES( NEW.idFactura, NEW.idEstadoFactura, @usuario, NOW() );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`factura_AFTER_UPDATE` AFTER UPDATE ON `factura` FOR EACH ROW
BEGIN
	INSERT INTO bitacoraEstadoFactura ( idFactura, idEstadoFactura, comentario, usuario, fechaRegistro ) 
		VALUES( NEW.idFactura, NEW.idEstadoFactura, @comentario, @usuario, NOW() );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `facturacompra`
--

DROP TABLE IF EXISTS `facturacompra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facturacompra` (
  `idFacturaCompra` int(11) NOT NULL AUTO_INCREMENT,
  `idEstadoFactura` int(11) NOT NULL,
  `noFactura` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `proveedor` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `fechaFactura` date NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idFacturaCompra`),
  KEY `fk_facturaCompra_estadoFactura1_idx` (`idEstadoFactura`),
  CONSTRAINT `fk_facturaCompra_estadoFactura1` FOREIGN KEY (`idEstadoFactura`) REFERENCES `estadofactura` (`idEstadoFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturacompra`
--

LOCK TABLES `facturacompra` WRITE;
/*!40000 ALTER TABLE `facturacompra` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturacompra` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`facturaCompra_AFTER_INSERT` AFTER INSERT ON `facturaCompra` FOR EACH ROW
BEGIN
	INSERT INTO facturaCompraEstado( idFacturaCompra, idEstadoFactura, usuario, fechaRegistro ) 
		VALUES( NEW.idFacturaCompra, NEW.idEstadoFactura, @usuario, NOW() );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`facturaCompra_AFTER_UPDATE` AFTER UPDATE ON `facturaCompra` FOR EACH ROW
BEGIN
	IF IFNULL( OLD.idEstadoFactura, '' ) != IFNULL( NEW.idEstadoFactura, '' ) THEN
		INSERT INTO facturaCompraEstado( idFacturaCompra, idEstadoFactura, usuario, fechaRegistro ) 
			VALUES( NEW.idFacturaCompra, NEW.idEstadoFactura, @usuario, NOW() );
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `facturacompraestado`
--

DROP TABLE IF EXISTS `facturacompraestado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facturacompraestado` (
  `idFacturaCompra` int(11) NOT NULL,
  `idEstadoFactura` int(11) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  KEY `fk_facturaCompra_has_estadoFactura_estadoFactura1_idx` (`idEstadoFactura`),
  KEY `fk_facturaCompra_has_estadoFactura_facturaCompra1_idx` (`idFacturaCompra`),
  KEY `fk_facturaCompraEstado_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_facturaCompraEstado_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_facturaCompra_has_estadoFactura_estadoFactura1` FOREIGN KEY (`idEstadoFactura`) REFERENCES `estadofactura` (`idEstadoFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_facturaCompra_has_estadoFactura_facturaCompra1` FOREIGN KEY (`idFacturaCompra`) REFERENCES `facturacompra` (`idFacturaCompra`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturacompraestado`
--

LOCK TABLES `facturacompraestado` WRITE;
/*!40000 ALTER TABLE `facturacompraestado` DISABLE KEYS */;
/*!40000 ALTER TABLE `facturacompraestado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facturaformapago`
--

DROP TABLE IF EXISTS `facturaformapago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facturaformapago` (
  `idFactura` int(11) NOT NULL,
  `idFormaPago` int(11) NOT NULL,
  `monto` double(10,2) NOT NULL,
  PRIMARY KEY (`idFactura`,`idFormaPago`),
  KEY `fk_factura_has_formaPago_formaPago1_idx` (`idFormaPago`),
  KEY `fk_factura_has_formaPago_factura1_idx` (`idFactura`),
  CONSTRAINT `fk_factura_has_formaPago_factura1` FOREIGN KEY (`idFactura`) REFERENCES `factura` (`idFactura`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_factura_has_formaPago_formaPago1` FOREIGN KEY (`idFormaPago`) REFERENCES `formapago` (`idFormaPago`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturaformapago`
--

LOCK TABLES `facturaformapago` WRITE;
/*!40000 ALTER TABLE `facturaformapago` DISABLE KEYS */;
INSERT INTO `facturaformapago` VALUES (4,1,6.00),(4,2,35.00),(5,1,80.00),(6,1,20.00),(7,1,50.00),(7,2,40.00);
/*!40000 ALTER TABLE `facturaformapago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formapago`
--

DROP TABLE IF EXISTS `formapago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formapago` (
  `idFormaPago` int(11) NOT NULL,
  `formaPago` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `porcentajeRecargo` int(2) DEFAULT NULL,
  `montoRecargo` double(8,2) DEFAULT NULL,
  PRIMARY KEY (`idFormaPago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formapago`
--

LOCK TABLES `formapago` WRITE;
/*!40000 ALTER TABLE `formapago` DISABLE KEYS */;
INSERT INTO `formapago` VALUES (1,'Efectivo',NULL,NULL),(2,'Tarjeta de Crédito',0,NULL);
/*!40000 ALTER TABLE `formapago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historialautenticacion`
--

DROP TABLE IF EXISTS `historialautenticacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historialautenticacion` (
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `idTipoRespuesta` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  KEY `fk_historialSesion_usuario1_idx` (`usuario`),
  KEY `fk_historialAutenticacion_tipoRespuesta1_idx` (`idTipoRespuesta`),
  CONSTRAINT `fk_historialAutenticacion_tipoRespuesta1` FOREIGN KEY (`idTipoRespuesta`) REFERENCES `tiporespuesta` (`idTipoRespuesta`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_historialSesion_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historialautenticacion`
--

LOCK TABLES `historialautenticacion` WRITE;
/*!40000 ALTER TABLE `historialautenticacion` DISABLE KEYS */;
INSERT INTO `historialautenticacion` VALUES ('restaurante',1,'2018-06-11 20:05:21'),('restaurante',1,'2018-06-11 20:09:34'),('restaurante',1,'2018-06-18 20:45:45'),('restaurante',1,'2018-06-19 22:47:23'),('restaurante',1,'2018-06-22 03:07:22'),('restaurante',1,'2018-06-25 18:54:03');
/*!40000 ALTER TABLE `historialautenticacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingreso`
--

DROP TABLE IF EXISTS `ingreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingreso` (
  `idIngreso` int(11) NOT NULL AUTO_INCREMENT,
  `idFacturaCompra` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  `costo` double(12,2) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`idIngreso`),
  KEY `fk_ingreso_producto1_idx` (`idProducto`),
  KEY `fk_ingreso_usuario1_idx` (`usuario`),
  KEY `fk_ingreso_facturaCompra1_idx` (`idFacturaCompra`),
  CONSTRAINT `fk_ingreso_facturaCompra1` FOREIGN KEY (`idFacturaCompra`) REFERENCES `facturacompra` (`idFacturaCompra`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ingreso_producto1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ingreso_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingreso`
--

LOCK TABLES `ingreso` WRITE;
/*!40000 ALTER TABLE `ingreso` DISABLE KEYS */;
/*!40000 ALTER TABLE `ingreso` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`ingreso_AFTER_INSERT` AFTER INSERT ON `ingreso` FOR EACH ROW
BEGIN
	INSERT INTO bitacoraIngreso ( idProducto, cantidad, accion, fechaRegistro, usuario )
		VALUES ( NEW.idProducto, NEW.cantidad, 'insert', NOW(), @usuario );
	
    UPDATE producto SET disponibilidad = disponibilidad + NEW.cantidad 
		WHERE idProducto = NEW.idProducto;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`ingreso_AFTER_DELETE` AFTER DELETE ON `ingreso` FOR EACH ROW
BEGIN
	INSERT INTO bitacoraIngreso ( idProducto, cantidad, accion, fechaRegistro, usuario )
		VALUES ( OLD.idProducto, OLD.cantidad, 'delete', NOW(), @usuario );
	
    UPDATE producto SET disponibilidad = disponibilidad - OLD.cantidad 
		WHERE idProducto = OLD.idProducto;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `log_estadousuario`
--

DROP TABLE IF EXISTS `log_estadousuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_estadousuario` (
  `idEstadoUsuario` int(11) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `usuarioRegistro` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  KEY `fk_estadoUsuario_has_usuario_usuario1_idx` (`usuario`),
  KEY `fk_estadoUsuario_has_usuario_estadoUsuario1_idx` (`idEstadoUsuario`),
  KEY `fk_estadoUsuario_has_usuario_usuario2_idx` (`usuarioRegistro`),
  CONSTRAINT `fk_estadoUsuario_has_usuario_estadoUsuario1` FOREIGN KEY (`idEstadoUsuario`) REFERENCES `estadousuario` (`idEstadoUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoUsuario_has_usuario_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estadoUsuario_has_usuario_usuario2` FOREIGN KEY (`usuarioRegistro`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_estadousuario`
--

LOCK TABLES `log_estadousuario` WRITE;
/*!40000 ALTER TABLE `log_estadousuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_estadousuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logestadomovimiento`
--

DROP TABLE IF EXISTS `logestadomovimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logestadomovimiento` (
  `idMovimiento` int(11) NOT NULL,
  `idEstadoMovimiento` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idMovimiento`,`idEstadoMovimiento`),
  KEY `fk_movimiento_has_estadoMovimiento_estadoMovimiento1_idx` (`idEstadoMovimiento`),
  KEY `fk_movimiento_has_estadoMovimiento_movimiento1_idx` (`idMovimiento`),
  KEY `fk_logEstadoMovimiento_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_logEstadoMovimiento_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimiento_has_estadoMovimiento_estadoMovimiento1` FOREIGN KEY (`idEstadoMovimiento`) REFERENCES `estadomovimiento` (`idEstadoMovimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimiento_has_estadoMovimiento_movimiento1` FOREIGN KEY (`idMovimiento`) REFERENCES `movimiento` (`idMovimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logestadomovimiento`
--

LOCK TABLES `logestadomovimiento` WRITE;
/*!40000 ALTER TABLE `logestadomovimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `logestadomovimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `lstcombo`
--

DROP TABLE IF EXISTS `lstcombo`;
/*!50001 DROP VIEW IF EXISTS `lstcombo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstcombo` AS SELECT 
 1 AS `idCombo`,
 1 AS `combo`,
 1 AS `imagen`,
 1 AS `descripcion`,
 1 AS `idEstadoMenu`,
 1 AS `estadoMenu`,
 1 AS `codigoCombo`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstcombodetalle`
--

DROP TABLE IF EXISTS `lstcombodetalle`;
/*!50001 DROP VIEW IF EXISTS `lstcombodetalle`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstcombodetalle` AS SELECT 
 1 AS `idCombo`,
 1 AS `cantidad`,
 1 AS `idMenu`,
 1 AS `menu`,
 1 AS `imagen`,
 1 AS `descripcion`,
 1 AS `idEstadoMenu`,
 1 AS `estadoMenu`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstcomboprecio`
--

DROP TABLE IF EXISTS `lstcomboprecio`;
/*!50001 DROP VIEW IF EXISTS `lstcomboprecio`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstcomboprecio` AS SELECT 
 1 AS `idCombo`,
 1 AS `precio`,
 1 AS `idTipoServicio`,
 1 AS `tipoServicio`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstfacturacompra`
--

DROP TABLE IF EXISTS `lstfacturacompra`;
/*!50001 DROP VIEW IF EXISTS `lstfacturacompra`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstfacturacompra` AS SELECT 
 1 AS `idFacturaCompra`,
 1 AS `noFactura`,
 1 AS `proveedor`,
 1 AS `fechaFactura`,
 1 AS `comentario`,
 1 AS `idEstadoFactura`,
 1 AS `estadoFactura`,
 1 AS `usuario`,
 1 AS `fechaRegistro`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstingresoproducto`
--

DROP TABLE IF EXISTS `lstingresoproducto`;
/*!50001 DROP VIEW IF EXISTS `lstingresoproducto`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstingresoproducto` AS SELECT 
 1 AS `idIngreso`,
 1 AS `idFacturaCompra`,
 1 AS `idProducto`,
 1 AS `producto`,
 1 AS `idMedida`,
 1 AS `medida`,
 1 AS `idTipoProducto`,
 1 AS `tipoProducto`,
 1 AS `perecedero`,
 1 AS `cantidadMinima`,
 1 AS `cantidadMaxima`,
 1 AS `disponibilidad`,
 1 AS `importante`,
 1 AS `cantidad`,
 1 AS `costo`,
 1 AS `usuarioIngreso`,
 1 AS `fechaIngreso`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstmenu`
--

DROP TABLE IF EXISTS `lstmenu`;
/*!50001 DROP VIEW IF EXISTS `lstmenu`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstmenu` AS SELECT 
 1 AS `idMenu`,
 1 AS `menu`,
 1 AS `imagen`,
 1 AS `descripcion`,
 1 AS `idEstadoMenu`,
 1 AS `estadoMenu`,
 1 AS `idDestinoMenu`,
 1 AS `destinoMenu`,
 1 AS `idTipoMenu`,
 1 AS `tipoMenu`,
 1 AS `codigoMenu`,
 1 AS `tiempoAlerta`,
 1 AS `seCocina`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstmenuprecio`
--

DROP TABLE IF EXISTS `lstmenuprecio`;
/*!50001 DROP VIEW IF EXISTS `lstmenuprecio`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstmenuprecio` AS SELECT 
 1 AS `idMenu`,
 1 AS `precio`,
 1 AS `idTipoServicio`,
 1 AS `tipoServicio`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstproducto`
--

DROP TABLE IF EXISTS `lstproducto`;
/*!50001 DROP VIEW IF EXISTS `lstproducto`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstproducto` AS SELECT 
 1 AS `idProducto`,
 1 AS `producto`,
 1 AS `idMedida`,
 1 AS `medida`,
 1 AS `idTipoProducto`,
 1 AS `tipoProducto`,
 1 AS `perecedero`,
 1 AS `cantidadMinima`,
 1 AS `cantidadMaxima`,
 1 AS `disponibilidad`,
 1 AS `importante`,
 1 AS `idUbicacion`,
 1 AS `ubicacion`,
 1 AS `usuarioProducto`,
 1 AS `fechaProducto`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstreajusteproducto`
--

DROP TABLE IF EXISTS `lstreajusteproducto`;
/*!50001 DROP VIEW IF EXISTS `lstreajusteproducto`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstreajusteproducto` AS SELECT 
 1 AS `idReajuste`,
 1 AS `idProducto`,
 1 AS `producto`,
 1 AS `idMedida`,
 1 AS `medida`,
 1 AS `idTipoProducto`,
 1 AS `tipoProducto`,
 1 AS `perecedero`,
 1 AS `cantidadMinima`,
 1 AS `cantidadMaxima`,
 1 AS `disponibilidad`,
 1 AS `importante`,
 1 AS `cantidad`,
 1 AS `esIncremento`,
 1 AS `observacion`,
 1 AS `usuarioReajuste`,
 1 AS `fechaReajuste`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstreceta`
--

DROP TABLE IF EXISTS `lstreceta`;
/*!50001 DROP VIEW IF EXISTS `lstreceta`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstreceta` AS SELECT 
 1 AS `idMenu`,
 1 AS `idProducto`,
 1 AS `producto`,
 1 AS `cantidad`,
 1 AS `medida`,
 1 AS `tipoProducto`,
 1 AS `observacion`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstsupercombo`
--

DROP TABLE IF EXISTS `lstsupercombo`;
/*!50001 DROP VIEW IF EXISTS `lstsupercombo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstsupercombo` AS SELECT 
 1 AS `idSuperCombo`,
 1 AS `superCombo`,
 1 AS `imagen`,
 1 AS `descripcion`,
 1 AS `idEstadoMenu`,
 1 AS `estadoMenu`,
 1 AS `codigoSuperCombo`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstsupercombodetalle`
--

DROP TABLE IF EXISTS `lstsupercombodetalle`;
/*!50001 DROP VIEW IF EXISTS `lstsupercombodetalle`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstsupercombodetalle` AS SELECT 
 1 AS `idSuperCombo`,
 1 AS `cantidad`,
 1 AS `idCombo`,
 1 AS `combo`,
 1 AS `imagen`,
 1 AS `descripcion`,
 1 AS `idEstadoMenu`,
 1 AS `estadoMenu`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `lstsupercomboprecio`
--

DROP TABLE IF EXISTS `lstsupercomboprecio`;
/*!50001 DROP VIEW IF EXISTS `lstsupercomboprecio`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `lstsupercomboprecio` AS SELECT 
 1 AS `idSuperCombo`,
 1 AS `precio`,
 1 AS `idTipoServicio`,
 1 AS `tipoServicio`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `medida`
--

DROP TABLE IF EXISTS `medida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medida` (
  `idMedida` int(11) NOT NULL AUTO_INCREMENT,
  `medida` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idMedida`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medida`
--

LOCK TABLES `medida` WRITE;
/*!40000 ALTER TABLE `medida` DISABLE KEYS */;
INSERT INTO `medida` VALUES (1,'Unidad'),(2,'Docena'),(3,'Onza');
/*!40000 ALTER TABLE `medida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `idMenu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(125) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `idEstadoMenu` int(11) NOT NULL,
  `idDestinoMenu` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `idTipoMenu` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `tiempoAlerta` int(11) DEFAULT NULL,
  `seCocina` tinyint(1) NOT NULL,
  PRIMARY KEY (`idMenu`),
  KEY `fk_menu_estadoMenu1_idx` (`idEstadoMenu`),
  KEY `fk_menu_destinoMenu1_idx` (`idDestinoMenu`),
  KEY `fk_menu_tipoMenu1_idx` (`idTipoMenu`),
  CONSTRAINT `fk_menu_destinoMenu1` FOREIGN KEY (`idDestinoMenu`) REFERENCES `destinomenu` (`idDestinoMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_estadoMenu1` FOREIGN KEY (`idEstadoMenu`) REFERENCES `estadomenu` (`idEstadoMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_tipoMenu1` FOREIGN KEY (`idTipoMenu`) REFERENCES `tipomenu` (`idTipoMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Papas Fritas',NULL,'j flsakd;jfas;kfj adslkfasdjfklsd',1,1,6,1,11,4,1),(2,'Pollo Delicioso',NULL,'jfdksajfks da',1,1,5,1,22,7,1);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menupersonalizado`
--

DROP TABLE IF EXISTS `menupersonalizado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menupersonalizado` (
  `idMenuPersonalizado` int(11) NOT NULL AUTO_INCREMENT,
  `idOrdenCliente` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `descripcion` varchar(125) COLLATE utf8_spanish_ci NOT NULL,
  `precioUnidad` double(10,2) NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci,
  `idEstadoDetalleOrden` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idMenuPersonalizado`),
  KEY `fk_table1_ordenCliente1_idx` (`idOrdenCliente`),
  KEY `fk_menuPersonalizado_estadoDetalleOrden1_idx` (`idEstadoDetalleOrden`),
  CONSTRAINT `fk_menuPersonalizado_estadoDetalleOrden1` FOREIGN KEY (`idEstadoDetalleOrden`) REFERENCES `estadodetalleorden` (`idEstadoDetalleOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_ordenCliente1` FOREIGN KEY (`idOrdenCliente`) REFERENCES `ordencliente` (`idOrdenCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menupersonalizado`
--

LOCK TABLES `menupersonalizado` WRITE;
/*!40000 ALTER TABLE `menupersonalizado` DISABLE KEYS */;
INSERT INTO `menupersonalizado` VALUES (1,1,3,'Tostada',3.00,NULL,6);
/*!40000 ALTER TABLE `menupersonalizado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menuprecio`
--

DROP TABLE IF EXISTS `menuprecio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menuprecio` (
  `idMenu` int(11) NOT NULL,
  `idTipoServicio` int(11) NOT NULL,
  `precio` double(10,2) NOT NULL,
  PRIMARY KEY (`idMenu`,`idTipoServicio`),
  KEY `fk_tipoServicio_has_menu_menu1_idx` (`idMenu`),
  KEY `fk_tipoServicio_has_menu_tipoServicio1_idx` (`idTipoServicio`),
  CONSTRAINT `fk_tipoServicio_has_menu_menu1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tipoServicio_has_menu_tipoServicio1` FOREIGN KEY (`idTipoServicio`) REFERENCES `tiposervicio` (`idTipoServicio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menuprecio`
--

LOCK TABLES `menuprecio` WRITE;
/*!40000 ALTER TABLE `menuprecio` DISABLE KEYS */;
INSERT INTO `menuprecio` VALUES (1,1,5.00),(1,2,5.00),(1,3,6.00),(2,1,11.00),(2,2,10.00),(2,3,11.00);
/*!40000 ALTER TABLE `menuprecio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `idModulo` int(11) NOT NULL,
  `modulo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `ruta` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `img` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `habilitado` tinyint(1) NOT NULL,
  PRIMARY KEY (`idModulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'ordenes','orden','img/orden.png',1),(2,'Admin. Orden','adminOrden','orden1.png',1),(3,'Facturacion','factura','img/factura.png',1),(4,'Eventos','evento','img/evento.png',1),(5,'Clientes','cliente','img/cliente.png',1),(6,'Caja','caja','img/caja.png',1),(7,'Inventario','inventario','img/inventario.png',1),(8,'Promociones','promocion','img/promocion.png',1),(9,'Administracion','admin','img/admins.png',1),(10,'Mantenimientos','mantenimiento','img/mantenimiento.png',1),(11,'Tendencias','tendencia','img/tendencia.png',1),(12,'Reportes','reporte','img/reporte.png',1);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimiento` (
  `idMovimiento` int(11) NOT NULL AUTO_INCREMENT,
  `idCaja` int(11) NOT NULL,
  `idTipoMovimiento` int(11) NOT NULL,
  `idEstadoMovimiento` int(11) NOT NULL,
  `idFormaPago` int(11) NOT NULL,
  `idEvento` int(11) DEFAULT NULL,
  `motivo` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `monto` double(10,2) NOT NULL,
  PRIMARY KEY (`idMovimiento`),
  KEY `fk_ingresoEgreso_caja1_idx` (`idCaja`),
  KEY `fk_ingresoEgreso_estadoMovimiento1_idx` (`idEstadoMovimiento`),
  KEY `fk_movimiento_evento1_idx` (`idEvento`),
  KEY `fk_movimiento_formaPago1_idx` (`idFormaPago`),
  KEY `fk_movimiento_tipoMovimiento1_idx` (`idTipoMovimiento`),
  CONSTRAINT `fk_ingresoEgreso_caja1` FOREIGN KEY (`idCaja`) REFERENCES `caja` (`idCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ingresoEgreso_estadoMovimiento1` FOREIGN KEY (`idEstadoMovimiento`) REFERENCES `estadomovimiento` (`idEstadoMovimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimiento_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimiento_formaPago1` FOREIGN KEY (`idFormaPago`) REFERENCES `formapago` (`idFormaPago`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_movimiento_tipoMovimiento1` FOREIGN KEY (`idTipoMovimiento`) REFERENCES `tipomovimiento` (`idTipoMovimiento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento`
--

LOCK TABLES `movimiento` WRITE;
/*!40000 ALTER TABLE `movimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimiento` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`movimiento_AFTER_INSERT` AFTER INSERT ON `movimiento` FOR EACH ROW
BEGIN
	INSERT INTO logEstadoMovimiento ( idMovimiento, idEstadoMovimiento, fechaRegistro, usuario, comentario ) 
		VALUES( NEW.idMovimiento, NEW.idEstadoMovimiento, NOW(), @usuario, @comentario );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`movimiento_AFTER_UPDATE` AFTER UPDATE ON `movimiento` FOR EACH ROW
BEGIN
	IF OLD.idEstadoMovimiento != NEW.idEstadoMovimiento THEN
    
		INSERT INTO logEstadoMovimiento ( idMovimiento, idEstadoMovimiento, fechaRegistro, usuario, comentario ) 
			VALUES( NEW.idMovimiento, NEW.idEstadoMovimiento, NOW(), @usuario, @comentario );
	
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `ordencliente`
--

DROP TABLE IF EXISTS `ordencliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordencliente` (
  `idOrdenCliente` int(11) NOT NULL AUTO_INCREMENT,
  `numeroTicket` int(11) DEFAULT NULL,
  `usuarioPropietario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `usuarioResponsable` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `idEstadoOrden` int(11) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `numMenu` int(11) NOT NULL,
  `numeroGrupo` int(11) NOT NULL,
  PRIMARY KEY (`idOrdenCliente`),
  KEY `ix_numeroTicket` (`numeroTicket`),
  KEY `fk_ordenCliente_estadoOrden1_idx` (`idEstadoOrden`),
  KEY `ix_usuarioResponsable` (`usuarioResponsable`),
  KEY `ix_usuarioPropietario` (`usuarioPropietario`),
  CONSTRAINT `fk_ordenCliente_estadoOrden1` FOREIGN KEY (`idEstadoOrden`) REFERENCES `estadoorden` (`idEstadoOrden`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarioPropietario` FOREIGN KEY (`usuarioPropietario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuarioResponsable` FOREIGN KEY (`usuarioResponsable`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordencliente`
--

LOCK TABLES `ordencliente` WRITE;
/*!40000 ALTER TABLE `ordencliente` DISABLE KEYS */;
INSERT INTO `ordencliente` VALUES (1,32,'restaurante','restaurante',6,'2018-06-11 20:21:53',5,1),(2,9,'restaurante','restaurante',6,'2018-06-11 20:31:09',4,1),(3,2,'restaurante','restaurante',6,'2018-06-11 21:06:41',4,1),(4,5,'restaurante','restaurante',6,'2018-06-11 21:11:36',3,1);
/*!40000 ALTER TABLE `ordencliente` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`ordenCliente_AFTER_UPDATE` AFTER UPDATE ON `ordenCliente` FOR EACH ROW
BEGIN
	IF IFNULL( OLD.idEstadoOrden, '' ) != IFNULL( NEW.idEstadoOrden, '' ) THEN
		INSERT INTO bitacoraOrdenCliente
			( idOrdenCliente, idEstadoOrden, usuario, fechaRegistro, comentario )
		VALUES( NEW.idOrdenCliente, NEW.idEstadoOrden, @usuario, NOW(), @comentario );
        
        SET @comentario = NULL;
	END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `otromenu`
--

DROP TABLE IF EXISTS `otromenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otromenu` (
  `idOtroMenu` int(11) NOT NULL AUTO_INCREMENT,
  `idEvento` int(11) NOT NULL,
  `otroMenu` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `horaDespacho` time DEFAULT NULL,
  `precioUnitario` double(10,2) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idOtroMenu`),
  KEY `fk_otroMenu_evento1_idx` (`idEvento`),
  KEY `fk_otroMenu_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_otroMenu_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_otroMenu_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otromenu`
--

LOCK TABLES `otromenu` WRITE;
/*!40000 ALTER TABLE `otromenu` DISABLE KEYS */;
/*!40000 ALTER TABLE `otromenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otroservicio`
--

DROP TABLE IF EXISTS `otroservicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `otroservicio` (
  `idOtroServicio` int(11) NOT NULL AUTO_INCREMENT,
  `idEvento` int(11) NOT NULL,
  `otroServicio` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `cantidad` double(8,2) NOT NULL,
  `precioUnitario` double(10,2) NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `comentario` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idOtroServicio`),
  KEY `fk_otroServicio_usuario1_idx` (`usuario`),
  KEY `fk_otroServicio_evento1_idx` (`idEvento`),
  CONSTRAINT `fk_otroServicio_evento1` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_otroServicio_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otroservicio`
--

LOCK TABLES `otroservicio` WRITE;
/*!40000 ALTER TABLE `otroservicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `otroservicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parametro`
--

DROP TABLE IF EXISTS `parametro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parametro` (
  `idParametro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `parametro` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `valor` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idParametro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parametro`
--

LOCK TABLES `parametro` WRITE;
/*!40000 ALTER TABLE `parametro` DISABLE KEYS */;
INSERT INTO `parametro` VALUES ('gruposCocina','Grupos de Cocina','1');
/*!40000 ALTER TABLE `parametro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `idPerfil` int(11) NOT NULL,
  `perfil` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idPerfil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Administrador'),(2,'Cajero'),(3,'Cocinero'),(4,'Barra'),(5,'Mesero');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfilmodulo`
--

DROP TABLE IF EXISTS `perfilmodulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfilmodulo` (
  `idPerfil` int(11) NOT NULL,
  `idModulo` int(11) NOT NULL,
  PRIMARY KEY (`idPerfil`,`idModulo`),
  KEY `fk_perfil_has_modulo_modulo1_idx` (`idModulo`),
  KEY `fk_perfil_has_modulo_perfil1_idx` (`idPerfil`),
  CONSTRAINT `fk_perfil_has_modulo_modulo1` FOREIGN KEY (`idModulo`) REFERENCES `modulo` (`idModulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_perfil_has_modulo_perfil1` FOREIGN KEY (`idPerfil`) REFERENCES `perfil` (`idPerfil`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfilmodulo`
--

LOCK TABLES `perfilmodulo` WRITE;
/*!40000 ALTER TABLE `perfilmodulo` DISABLE KEYS */;
INSERT INTO `perfilmodulo` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12);
/*!40000 ALTER TABLE `perfilmodulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
  `idProducto` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `idTipoProducto` int(11) NOT NULL,
  `idMedida` int(11) NOT NULL,
  `perecedero` tinyint(1) NOT NULL,
  `cantidadMinima` double(10,2) NOT NULL,
  `cantidadMaxima` double(10,2) DEFAULT NULL,
  `disponibilidad` double(10,2) NOT NULL,
  `importante` tinyint(1) NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `idUbicacion` int(11) NOT NULL,
  PRIMARY KEY (`idProducto`),
  KEY `fk_producto_tipoProducto1_idx` (`idTipoProducto`),
  KEY `fk_producto_medida1_idx` (`idMedida`),
  KEY `ix_usuario` (`usuario`),
  KEY `fk_producto_ubicacion1_idx` (`idUbicacion`),
  CONSTRAINT `fk_producto_medida1` FOREIGN KEY (`idMedida`) REFERENCES `medida` (`idMedida`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_tipoProducto1` FOREIGN KEY (`idTipoProducto`) REFERENCES `tipoproducto` (`idTipoProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_ubicacion1` FOREIGN KEY (`idUbicacion`) REFERENCES `ubicacion` (`idUbicacion`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,'Papas Fritas',1,3,0,10.00,100.00,50.00,1,'restaurante','2018-06-11 20:12:39',1),(2,'Pechuga de Pollo',5,1,0,20.00,100.00,50.00,1,'restaurante','2018-06-11 20:13:16',1),(3,'Rostizador',6,3,0,200.00,1000.00,500.00,1,'restaurante','2018-06-11 20:13:49',1);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reajuste`
--

DROP TABLE IF EXISTS `reajuste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reajuste` (
  `idReajuste` int(11) NOT NULL AUTO_INCREMENT,
  `observacion` text COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idReajuste`),
  KEY `fk_reajusteInventario_usuario2_idx` (`usuario`),
  CONSTRAINT `fk_reajusteInventario_usuario2` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reajuste`
--

LOCK TABLES `reajuste` WRITE;
/*!40000 ALTER TABLE `reajuste` DISABLE KEYS */;
/*!40000 ALTER TABLE `reajuste` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reajustecaja`
--

DROP TABLE IF EXISTS `reajustecaja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reajustecaja` (
  `idReajusteCaja` int(11) NOT NULL AUTO_INCREMENT,
  `idCaja` int(11) NOT NULL,
  `monto` double(10,2) NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `fechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`idReajusteCaja`),
  KEY `fk_reajusteCaja_caja1_idx` (`idCaja`),
  KEY `fk_reajusteCaja_usuario1_idx` (`usuario`),
  CONSTRAINT `fk_reajusteCaja_caja1` FOREIGN KEY (`idCaja`) REFERENCES `caja` (`idCaja`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reajusteCaja_usuario1` FOREIGN KEY (`usuario`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reajustecaja`
--

LOCK TABLES `reajustecaja` WRITE;
/*!40000 ALTER TABLE `reajustecaja` DISABLE KEYS */;
/*!40000 ALTER TABLE `reajustecaja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reajusteproducto`
--

DROP TABLE IF EXISTS `reajusteproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reajusteproducto` (
  `idReajusteProducto` int(11) NOT NULL AUTO_INCREMENT,
  `idReajuste` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  `esIncremento` tinyint(1) NOT NULL,
  PRIMARY KEY (`idReajusteProducto`),
  UNIQUE KEY `uq_reajuste_producto` (`idReajuste`,`idProducto`),
  KEY `fk_reajusteProducto_producto1_idx` (`idProducto`),
  KEY `fk_reajusteProducto_reajuste1_idx` (`idReajuste`),
  CONSTRAINT `fk_reajusteProducto_producto1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reajusteProducto_reajuste1` FOREIGN KEY (`idReajuste`) REFERENCES `reajuste` (`idReajuste`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reajusteproducto`
--

LOCK TABLES `reajusteproducto` WRITE;
/*!40000 ALTER TABLE `reajusteproducto` DISABLE KEYS */;
/*!40000 ALTER TABLE `reajusteproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receta`
--

DROP TABLE IF EXISTS `receta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receta` (
  `idMenu` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  `observacion` text COLLATE utf8_spanish_ci,
  PRIMARY KEY (`idMenu`,`idProducto`),
  KEY `fk_menu_has_producto_producto1_idx` (`idProducto`),
  KEY `fk_menu_has_producto_menu1_idx` (`idMenu`),
  CONSTRAINT `fk_menu_has_producto_menu1` FOREIGN KEY (`idMenu`) REFERENCES `menu` (`idMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_has_producto_producto1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receta`
--

LOCK TABLES `receta` WRITE;
/*!40000 ALTER TABLE `receta` DISABLE KEYS */;
INSERT INTO `receta` VALUES (1,1,5.00,'NULL'),(1,3,1.00,'NULL'),(2,2,1.00,'NULL'),(2,3,3.00,'NULL');
/*!40000 ALTER TABLE `receta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salon`
--

DROP TABLE IF EXISTS `salon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salon` (
  `idSalon` int(11) NOT NULL,
  `salon` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idSalon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salon`
--

LOCK TABLES `salon` WRITE;
/*!40000 ALTER TABLE `salon` DISABLE KEYS */;
INSERT INTO `salon` VALUES (1,'Salón Principal'),(2,'Salón # 2');
/*!40000 ALTER TABLE `salon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supercombo`
--

DROP TABLE IF EXISTS `supercombo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supercombo` (
  `idSuperCombo` int(11) NOT NULL AUTO_INCREMENT,
  `superCombo` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(125) COLLATE utf8_spanish_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `idEstadoMenu` int(11) NOT NULL,
  `top` int(11) NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSuperCombo`),
  KEY `fk_superCombo_estadoMenu1_idx` (`idEstadoMenu`),
  CONSTRAINT `fk_superCombo_estadoMenu1` FOREIGN KEY (`idEstadoMenu`) REFERENCES `estadomenu` (`idEstadoMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supercombo`
--

LOCK TABLES `supercombo` WRITE;
/*!40000 ALTER TABLE `supercombo` DISABLE KEYS */;
/*!40000 ALTER TABLE `supercombo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supercombodetalle`
--

DROP TABLE IF EXISTS `supercombodetalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supercombodetalle` (
  `idCombo` int(11) NOT NULL,
  `idSuperCombo` int(11) NOT NULL,
  `cantidad` double(10,2) NOT NULL,
  PRIMARY KEY (`idCombo`,`idSuperCombo`),
  KEY `fk_combo_has_superCombo_superCombo1_idx` (`idSuperCombo`),
  KEY `fk_combo_has_superCombo_combo1_idx` (`idCombo`),
  CONSTRAINT `fk_combo_has_superCombo_combo1` FOREIGN KEY (`idCombo`) REFERENCES `combo` (`idCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_combo_has_superCombo_superCombo1` FOREIGN KEY (`idSuperCombo`) REFERENCES `supercombo` (`idSuperCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supercombodetalle`
--

LOCK TABLES `supercombodetalle` WRITE;
/*!40000 ALTER TABLE `supercombodetalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `supercombodetalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supercomboprecio`
--

DROP TABLE IF EXISTS `supercomboprecio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supercomboprecio` (
  `idSuperCombo` int(11) NOT NULL,
  `idTipoServicio` int(11) NOT NULL,
  `precio` double(10,2) NOT NULL,
  PRIMARY KEY (`idSuperCombo`,`idTipoServicio`),
  KEY `fk_superCombo_has_tipoServicio_tipoServicio1_idx` (`idTipoServicio`),
  KEY `fk_superCombo_has_tipoServicio_superCombo1_idx` (`idSuperCombo`),
  CONSTRAINT `fk_superCombo_has_tipoServicio_superCombo1` FOREIGN KEY (`idSuperCombo`) REFERENCES `supercombo` (`idSuperCombo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_superCombo_has_tipoServicio_tipoServicio1` FOREIGN KEY (`idTipoServicio`) REFERENCES `tiposervicio` (`idTipoServicio`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supercomboprecio`
--

LOCK TABLES `supercomboprecio` WRITE;
/*!40000 ALTER TABLE `supercomboprecio` DISABLE KEYS */;
/*!40000 ALTER TABLE `supercomboprecio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipocliente`
--

DROP TABLE IF EXISTS `tipocliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipocliente` (
  `idTipoCliente` int(11) NOT NULL,
  `tipoCliente` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idTipoCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipocliente`
--

LOCK TABLES `tipocliente` WRITE;
/*!40000 ALTER TABLE `tipocliente` DISABLE KEYS */;
INSERT INTO `tipocliente` VALUES (1,'Individual'),(2,'Juridico');
/*!40000 ALTER TABLE `tipocliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoitem`
--

DROP TABLE IF EXISTS `tipoitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipoitem` (
  `idTipoItem` int(11) NOT NULL,
  `tipoItem` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idTipoItem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoitem`
--

LOCK TABLES `tipoitem` WRITE;
/*!40000 ALTER TABLE `tipoitem` DISABLE KEYS */;
INSERT INTO `tipoitem` VALUES (1,'Campo'),(2,'Lista');
/*!40000 ALTER TABLE `tipoitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipomenu`
--

DROP TABLE IF EXISTS `tipomenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipomenu` (
  `idTipoMenu` int(11) NOT NULL AUTO_INCREMENT,
  `tipoMenu` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idTipoMenu`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipomenu`
--

LOCK TABLES `tipomenu` WRITE;
/*!40000 ALTER TABLE `tipomenu` DISABLE KEYS */;
INSERT INTO `tipomenu` VALUES (1,'Comida'),(2,'Bebida'),(3,'Licor');
/*!40000 ALTER TABLE `tipomenu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipomovimiento`
--

DROP TABLE IF EXISTS `tipomovimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipomovimiento` (
  `idTipoMovimiento` int(11) NOT NULL,
  `tipoMovimiento` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `ingreso` tinyint(1) NOT NULL,
  PRIMARY KEY (`idTipoMovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipomovimiento`
--

LOCK TABLES `tipomovimiento` WRITE;
/*!40000 ALTER TABLE `tipomovimiento` DISABLE KEYS */;
INSERT INTO `tipomovimiento` VALUES (1,'Anticipo Evento',1),(2,'Pago Extra Evento',1),(3,'Ingreso',1),(4,'Egreso',0);
/*!40000 ALTER TABLE `tipomovimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipoproducto`
--

DROP TABLE IF EXISTS `tipoproducto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipoproducto` (
  `idTipoProducto` int(11) NOT NULL AUTO_INCREMENT,
  `tipoProducto` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idTipoProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipoproducto`
--

LOCK TABLES `tipoproducto` WRITE;
/*!40000 ALTER TABLE `tipoproducto` DISABLE KEYS */;
INSERT INTO `tipoproducto` VALUES (1,'Vegetales'),(2,'Bebidas'),(3,'Embutidos'),(4,'Extra'),(5,'Materia Principal'),(6,'Condimento');
/*!40000 ALTER TABLE `tipoproducto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiporespuesta`
--

DROP TABLE IF EXISTS `tiporespuesta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiporespuesta` (
  `idTipoRespuesta` int(11) NOT NULL,
  `tipoRespuesta` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idTipoRespuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiporespuesta`
--

LOCK TABLES `tiporespuesta` WRITE;
/*!40000 ALTER TABLE `tiporespuesta` DISABLE KEYS */;
INSERT INTO `tiporespuesta` VALUES (1,'Autenticación Exitosa'),(2,'Usuario o Clave inválida'),(3,'Usuario Bloqueado');
/*!40000 ALTER TABLE `tiporespuesta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiposervicio`
--

DROP TABLE IF EXISTS `tiposervicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tiposervicio` (
  `idTipoServicio` int(11) NOT NULL,
  `tipoServicio` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL,
  PRIMARY KEY (`idTipoServicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tiposervicio`
--

LOCK TABLES `tiposervicio` WRITE;
/*!40000 ALTER TABLE `tiposervicio` DISABLE KEYS */;
INSERT INTO `tiposervicio` VALUES (1,'Para llevar',2),(2,'Restaurante',1),(3,'A domicilio',3);
/*!40000 ALTER TABLE `tiposervicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicacion`
--

DROP TABLE IF EXISTS `ubicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ubicacion` (
  `idUbicacion` int(11) NOT NULL,
  `ubicacion` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idUbicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicacion`
--

LOCK TABLES `ubicacion` WRITE;
/*!40000 ALTER TABLE `ubicacion` DISABLE KEYS */;
INSERT INTO `ubicacion` VALUES (1,'Cocina'),(2,'Mostrador'),(3,'Bodega');
/*!40000 ALTER TABLE `ubicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `usuario` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `idEstadoUsuario` int(11) NOT NULL,
  `idPerfil` int(11) NOT NULL,
  `idDestinoMenu` int(11) NOT NULL,
  `codigo` int(4) NOT NULL,
  `clave` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
  `nombres` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(65) COLLATE utf8_spanish_ci NOT NULL,
  `usuarioRegistro` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaRegistro` datetime NOT NULL,
  PRIMARY KEY (`usuario`),
  UNIQUE KEY `codigo_UNIQUE` (`codigo`),
  KEY `fk_usuario_estadoUsuario1_idx` (`idEstadoUsuario`),
  KEY `fk_usuario_perfil1_idx` (`idPerfil`),
  KEY `fk_usuario_usuario1_idx` (`usuarioRegistro`),
  KEY `fk_usuario_destinoMenu1_idx` (`idDestinoMenu`),
  CONSTRAINT `fk_usuario_destinoMenu1` FOREIGN KEY (`idDestinoMenu`) REFERENCES `destinomenu` (`idDestinoMenu`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_estadoUsuario1` FOREIGN KEY (`idEstadoUsuario`) REFERENCES `estadousuario` (`idEstadoUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_perfil1` FOREIGN KEY (`idPerfil`) REFERENCES `perfil` (`idPerfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_usuario1` FOREIGN KEY (`usuarioRegistro`) REFERENCES `usuario` (`usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES ('joseIbarra',1,4,2,400,'9e1e06ec8e02f0a0074f2fcc6b26303b','Jose','Ibarra',NULL,'2017-01-01 23:59:59'),('restaurante',1,1,1,100,'e10adc3949ba59abbe56e057f20f883e','Pollo','Churchill',NULL,'2017-01-01 23:59:59'),('usuarioBarra',1,4,2,200,'9e1e06ec8e02f0a0074f2fcc6b26303b','Usuario','Barra',NULL,'2017-01-01 23:59:59'),('usuarioCocina',1,3,1,300,'9e1e06ec8e02f0a0074f2fcc6b26303b','Usuario','Cocina',NULL,'2017-01-01 23:59:59');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `dbChurchill`.`usuario_BEFORE_INSERT` BEFORE INSERT ON `usuario` FOR EACH ROW
BEGIN
	SET NEW.clave = md5( NEW.clave );
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `vcuadreproducto`
--

DROP TABLE IF EXISTS `vcuadreproducto`;
/*!50001 DROP VIEW IF EXISTS `vcuadreproducto`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vcuadreproducto` AS SELECT 
 1 AS `idCuadreProducto`,
 1 AS `fechaCuadre`,
 1 AS `comentario`,
 1 AS `usuario`,
 1 AS `fechaRegistroCuadre`,
 1 AS `todos`,
 1 AS `idUbicacion`,
 1 AS `ubicacion`,
 1 AS `idEstadoCuadre`,
 1 AS `estadoCuadre`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vcuadreproductodetalle`
--

DROP TABLE IF EXISTS `vcuadreproductodetalle`;
/*!50001 DROP VIEW IF EXISTS `vcuadreproductodetalle`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vcuadreproductodetalle` AS SELECT 
 1 AS `idCuadreProducto`,
 1 AS `fechaCuadre`,
 1 AS `comentario`,
 1 AS `usuario`,
 1 AS `fechaRegistroCuadre`,
 1 AS `cantidadApertura`,
 1 AS `cantidadCierre`,
 1 AS `diferenciaApertura`,
 1 AS `diferenciaCierre`,
 1 AS `comentarioApertura`,
 1 AS `comentarioCierre`,
 1 AS `idProducto`,
 1 AS `producto`,
 1 AS `idMedida`,
 1 AS `medida`,
 1 AS `idTipoProducto`,
 1 AS `tipoProducto`,
 1 AS `perecedero`,
 1 AS `cantidadMinima`,
 1 AS `cantidadMaxima`,
 1 AS `disponibilidad`,
 1 AS `importante`,
 1 AS `idUbicacion`,
 1 AS `ubicacion`,
 1 AS `usuarioProducto`,
 1 AS `fechaProducto`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vdenominacioncaja`
--

DROP TABLE IF EXISTS `vdenominacioncaja`;
/*!50001 DROP VIEW IF EXISTS `vdenominacioncaja`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vdenominacioncaja` AS SELECT 
 1 AS `idCaja`,
 1 AS `idEstadoCaja`,
 1 AS `denominacion`,
 1 AS `cantidad`,
 1 AS `monto`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venta` (
  `idVenta` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idVenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vevento`
--

DROP TABLE IF EXISTS `vevento`;
/*!50001 DROP VIEW IF EXISTS `vevento`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vevento` AS SELECT 
 1 AS `idEvento`,
 1 AS `evento`,
 1 AS `fechaEvento`,
 1 AS `idSalon`,
 1 AS `salon`,
 1 AS `horaInicio`,
 1 AS `horaFinal`,
 1 AS `observacion`,
 1 AS `usuario`,
 1 AS `fechaRegistro`,
 1 AS `numeroPersonas`,
 1 AS `idEstadoEvento`,
 1 AS `estadoEvento`,
 1 AS `idCliente`,
 1 AS `nit`,
 1 AS `nombre`,
 1 AS `cui`,
 1 AS `correo`,
 1 AS `telefono`,
 1 AS `direccion`,
 1 AS `idTipoCliente`,
 1 AS `tipoCliente`,
 1 AS `idFactura`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vordencliente`
--

DROP TABLE IF EXISTS `vordencliente`;
/*!50001 DROP VIEW IF EXISTS `vordencliente`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vordencliente` AS SELECT 
 1 AS `idOrdenCliente`,
 1 AS `numeroTicket`,
 1 AS `usuarioPropietario`,
 1 AS `usuarioResponsable`,
 1 AS `idEstadoOrden`,
 1 AS `estadoOrden`,
 1 AS `fechaRegistro`,
 1 AS `numMenu`,
 1 AS `numeroGrupo`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vordenes`
--

DROP TABLE IF EXISTS `vordenes`;
/*!50001 DROP VIEW IF EXISTS `vordenes`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vordenes` AS SELECT 
 1 AS `idDetalleOrdenMenu`,
 1 AS `idOrdenCliente`,
 1 AS `numeroTicket`,
 1 AS `responsableOrden`,
 1 AS `idEstadoOrden`,
 1 AS `numeroGrupo`,
 1 AS `cantidad`,
 1 AS `idMenu`,
 1 AS `menu`,
 1 AS `codigoMenu`,
 1 AS `tiempoAlerta`,
 1 AS `perteneceCombo`,
 1 AS `descripcion`,
 1 AS `imagen`,
 1 AS `precio`,
 1 AS `idCombo`,
 1 AS `combo`,
 1 AS `imagenCombo`,
 1 AS `precioCombo`,
 1 AS `idEstadoDetalleOrden`,
 1 AS `estadoDetalleOrden`,
 1 AS `idTipoServicio`,
 1 AS `tipoServicio`,
 1 AS `idDestinoMenu`,
 1 AS `destinoMenu`,
 1 AS `responsableDetalle`,
 1 AS `usuarioDetalle`,
 1 AS `observacion`,
 1 AS `nombres`,
 1 AS `codigo`,
 1 AS `idDetalleOrdenCombo`,
 1 AS `idEstadoDetalleOrdenCombo`,
 1 AS `fechaRegistro`,
 1 AS `usuarioRegistro`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `votroservicio`
--

DROP TABLE IF EXISTS `votroservicio`;
/*!50001 DROP VIEW IF EXISTS `votroservicio`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `votroservicio` AS SELECT 
 1 AS `idOtroServicio`,
 1 AS `idEvento`,
 1 AS `otroServicio`,
 1 AS `cantidad`,
 1 AS `precioUnitario`,
 1 AS `fechaRegistro`,
 1 AS `usuario`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vstcaja`
--

DROP TABLE IF EXISTS `vstcaja`;
/*!50001 DROP VIEW IF EXISTS `vstcaja`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vstcaja` AS SELECT 
 1 AS `idCaja`,
 1 AS `usuario`,
 1 AS `fechaApertura`,
 1 AS `efectivoInicial`,
 1 AS `efectivoFinal`,
 1 AS `efectivoSobrante`,
 1 AS `efectivoFaltante`,
 1 AS `idEstadoCaja`,
 1 AS `estadoCaja`,
 1 AS `nombres`,
 1 AS `apellidos`,
 1 AS `codigo`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vstcliente`
--

DROP TABLE IF EXISTS `vstcliente`;
/*!50001 DROP VIEW IF EXISTS `vstcliente`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vstcliente` AS SELECT 
 1 AS `idCliente`,
 1 AS `nit`,
 1 AS `nombre`,
 1 AS `cui`,
 1 AS `correo`,
 1 AS `telefono`,
 1 AS `direccion`,
 1 AS `idTipoCliente`,
 1 AS `tipoCliente`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vstdetalleordenfactura`
--

DROP TABLE IF EXISTS `vstdetalleordenfactura`;
/*!50001 DROP VIEW IF EXISTS `vstdetalleordenfactura`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vstdetalleordenfactura` AS SELECT 
 1 AS `idFactura`,
 1 AS `idOrdenCliente`,
 1 AS `numeroTicket`,
 1 AS `idDetalleOrdenMenu`,
 1 AS `idMenu`,
 1 AS `menu`,
 1 AS `imagen`,
 1 AS `perteneceCombo`,
 1 AS `idDetalleOrdenCombo`,
 1 AS `idCombo`,
 1 AS `combo`,
 1 AS `imagenCombo`,
 1 AS `idTipoServicio`,
 1 AS `tipoServicio`,
 1 AS `usuarioRegistro`,
 1 AS `precioMenu`,
 1 AS `descuento`,
 1 AS `precioReal`,
 1 AS `comentario`,
 1 AS `usuarioFacturaDetalle`,
 1 AS `fechaFacturaDetalle`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vstfactura`
--

DROP TABLE IF EXISTS `vstfactura`;
/*!50001 DROP VIEW IF EXISTS `vstfactura`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vstfactura` AS SELECT 
 1 AS `idFactura`,
 1 AS `idCliente`,
 1 AS `nit`,
 1 AS `idCaja`,
 1 AS `nombre`,
 1 AS `direccion`,
 1 AS `total`,
 1 AS `fechaFactura`,
 1 AS `usuario`,
 1 AS `idEstadoFactura`,
 1 AS `estadoFactura`,
 1 AS `fechaRegistro`,
 1 AS `descripcion`,
 1 AS `siDetalle`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vstformapago`
--

DROP TABLE IF EXISTS `vstformapago`;
/*!50001 DROP VIEW IF EXISTS `vstformapago`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vstformapago` AS SELECT 
 1 AS `idFactura`,
 1 AS `monto`,
 1 AS `idFormaPago`,
 1 AS `formaPago`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vusuario`
--

DROP TABLE IF EXISTS `vusuario`;
/*!50001 DROP VIEW IF EXISTS `vusuario`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vusuario` AS SELECT 
 1 AS `usuario`,
 1 AS `codigo`,
 1 AS `nombres`,
 1 AS `apellidos`,
 1 AS `fechaRegistro`,
 1 AS `idEstadoUsuario`,
 1 AS `estadoUsuario`,
 1 AS `idPerfil`,
 1 AS `perfil`,
 1 AS `idDestinoMenu`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'dbchurchill'
--
/*!50003 DROP FUNCTION IF EXISTS `codigoDuplicado` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `codigoDuplicado`( _codigo INT, _idMenu INT, _idCombo INT, _idSuperCombo INT ) RETURNS tinyint(1)
BEGIN
	DECLARE _response BOOLEAN DEFAULT TRUE;
	
    IF !ISNULL( _codigo ) AND _codigo > 0 THEN
		SELECT FALSE INTO _response FROM menu WHERE codigo = _codigo AND idMenu != IFNULL( _idMenu, 0 ) AND idEstadoMenu = 1 LIMIT 1;
        SELECT FALSE INTO _response FROM combo WHERE codigo = _codigo AND idCombo != IFNULL( _idCombo, 0 ) AND idEstadoMenu = 1 LIMIT 1;
        SELECT FALSE INTO _response FROM superCombo WHERE codigo = _codigo AND idSuperCombo != IFNULL( _idSuperCombo, 0 ) AND idEstadoMenu = 1 LIMIT 1;
    END IF;
    
    RETURN _response;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `obtenerDisponiblidad` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `obtenerDisponiblidad`( _idMenu INT, _idCombo INT, _cantidad INT ) RETURNS text CHARSET utf8 COLLATE utf8_spanish_ci
BEGIN
	DECLARE _resultado TEXT;
    
	IF !ISNULL( _idMenu ) AND _cantidad > 0 THEN
		SELECT
			GROUP_CONCAT(
				CONCAT(
					( r.cantidad * _cantidad ), '#i#',
					p.disponibilidad, '#i#',
					p.producto, '#i#',
					m.medida
				) SEPARATOR '=r='
			) 	 INTO 	_resultado
		FROM receta AS r
			
			JOIN producto AS p
				ON r.idProducto = p.idProducto
				
			JOIN medida AS m
				ON m.idMedida = p.idMedida
                
		WHERE r.idMenu = _idMenu AND p.disponibilidad < ( r.cantidad * _cantidad ) LIMIT 1;
        
	ELSEIF !ISNULL( _idCombo ) AND _cantidad > 0 THEN
		
        SELECT
			GROUP_CONCAT(
				CONCAT(
					( cd.cantidad * r.cantidad * _cantidad ), '#i#',
					p.disponibilidad, '#i#',
					p.producto, '#i#',
					m.medida
				) SEPARATOR '=r='
			) 	 INTO 	_resultado
		FROM comboDetalle AS cd
			
            JOIN receta AS r
				ON r.idMenu = cd.idMenu
			
			JOIN producto AS p
				ON r.idProducto = p.idProducto
				
			JOIN medida AS m
				ON m.idMedida = p.idMedida
                
		WHERE cd.idCombo = _idCombo AND p.disponibilidad < ( cd.cantidad * r.cantidad * _cantidad ) LIMIT 1;
        
	END IF;

	RETURN _resultado;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP FUNCTION IF EXISTS `sesionValida` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `sesionValida`() RETURNS tinyint(1)
BEGIN
	IF !ISNULL( @usuario ) AND !ISNULL( @idPerfil ) THEN
		RETURN TRUE;
    ELSE
		RETURN FALSE;
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `actualizarEstadoUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarEstadoUsuario`( _usuario varchar(15), _idEstadoUsuario INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1452
        SELECT 'danger' AS 'respuesta', 'Dato No Existe' AS 'mensaje';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF sesionValida() THEN
        IF _usuario != @usuario THEN
			UPDATE usuario SET idEstadoUsuario = _idEstadoUsuario where usuario = _usuario;
			
			INSERT INTO log_estadoUsuario (idEstadoUsuario, usuario, usuarioRegistro, fechaRegistro)
			VALUES (1, _usuario, @usuario, now());
            
            SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
            
		ELSE
			SELECT 'warning' AS 'respuesta', 'No es posible cambiar el estado a su propio usuario' AS 'mensaje';
		END IF;
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Sesión no válida o acceso denegado' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `cambiarClaveUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `cambiarClaveUsuario`(_usuario varchar(15), _clave varchar(75), _nuevaClave varchar(75))
BEGIN
	# DECLARE VARS
	DECLARE claveAnteriorValida INT DEFAULT 0;
    
    # VALIDATE USER&PASSWORD
    SELECT 1 INTO claveAnteriorValida
		FROM usuario WHERE usuario = _usuario AND clave = md5( _clave );
    
    # IF SESSION IS VALID
    IF claveAnteriorValida = 1 THEN
		UPDATE usuario SET clave = md5( _nuevaClave ) 
			WHERE usuario = _usuario;
            
        SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Clave anterior no válida' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `cambioEstadoOrdenCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `cambioEstadoOrdenCliente`( _idOrdenCliente INT, _idEstadoDetalleOrden INT )
BEGIN
	DECLARE _numEstadoAnterior INT;
	DECLARE _sinFacturar INT DEFAULT 0;
	DECLARE _idFactura INT;
	DECLARE _aDomicilio BOOLEAN DEFAULT FALSE;

	# NUMERO DE ORDENES CON ESTADO ANTERIOR
    SELECT COUNT( * ) INTO _numEstadoAnterior
		FROM detalleOrdenMenu
	WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden < _idEstadoDetalleOrden;

    # CAMBIA ESTADO A ORDEN PRINCIPAL SI TODOS CAMBIARON
    IF _numEstadoAnterior = 0 THEN
		UPDATE ordenCliente SET idEstadoOrden = _idEstadoDetalleOrden
			WHERE idOrdenCliente = _idOrdenCliente AND idEstadoOrden < _idEstadoDetalleOrden;

		# ESTADOS: 4, 6
		IF _idEstadoDetalleOrden >= 4 AND _idEstadoDetalleOrden < 10 THEN

			# VERIFICA NUMERO DE ORDENES SIN FACTURAR
			SELECT
				COUNT( IF( ISNULL( fac.idFactura ), 1, NULL ) ),
    			fac.idFactura 
    				INTO
    			_sinFacturar,
    			_idFactura
			FROM
			((SELECT dom.idDetalleOrdenMenu, dof.idFactura
				FROM detalleOrdenMenu AS dom
					LEFT JOIN detalleOrdenFactura AS dof
						ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu
			WHERE idOrdenCliente = _idOrdenCliente AND !perteneceCombo AND idEstadoDetalleOrden != 10)
			UNION ALL
			(SELECT doc.idDetalleOrdenCombo, dof.idFactura
				FROM detalleOrdenCombo AS doc
					LEFT JOIN detalleOrdenFactura AS dof
						ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo
			WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden != 10))fac ;

			# VERIFICA SI ES A DOMICILIO
			SELECT 
				IF( COUNT( IF( idTipoServicio = 3, 1, NULL ) ) = COUNT( * ),
					TRUE,
			        FALSE
			    ) INTO _aDomicilio
			FROM detalleOrdenMenu
			WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden != 10;

			# SI TODOS YA ESTAN FACTURADOS, ACTUALIZA ESTADO DE ORDENES A FACTURADOS
			IF _idEstadoDetalleOrden = 4 AND _sinFacturar = 0 THEN
				UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 6
				WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden < 6;

				UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 6
				WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden < 6;

				UPDATE ordenCliente SET idEstadoOrden = 6
				WHERE idOrdenCliente = _idOrdenCliente AND idEstadoOrden < 6;
			END IF;

			# TODAS LAS ORDENDES YA ESTAN FACTURADAS
			IF _aDomicilio AND _sinFacturar = 0 THEN
				UPDATE factura 
					SET idEstadoFactura = 2
				WHERE idFactura = _idFactura;

			END IF;

		END IF; # SI ES FINALIZADO
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `clienteActualizar` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `clienteActualizar`( _idCliente INT, _nit VARCHAR(15), _nombre VARCHAR(65), _cui VARCHAR(13), 
	_correo VARCHAR(65), _telefono VARCHAR(8),  _direccion VARCHAR(95), _idTipoCliente INT )
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
	IF sesionValida() THEN
		UPDATE cliente SET
			nit 		= _nit, 
			nombre 		= _nombre, 
			cui 		= _cui, 
			correo 		= _correo, 
			telefono 	= _telefono,
			direccion 	= _direccion,
			idTipoCliente = _idTipoCliente
		WHERE idCliente = _idCliente;
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Error sesión no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `clienteNuevo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `clienteNuevo`( _nit VARCHAR(15), _nombre VARCHAR(65), _cui VARCHAR(13), 
	_correo VARCHAR(65), _telefono VARCHAR(8),  _direccion VARCHAR(95), _idTipoCliente INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Error, NIT duplicado' AS 'mensaje';
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF sesionValida() THEN
		INSERT INTO cliente ( nit, nombre, cui, correo, telefono, direccion, idTipoCliente, fechaRegistro, usuario ) 
			VALUES ( _nit, _nombre, _cui, _correo, _telefono, _direccion, _idTipoCliente, now(), @usuario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', last_insert_id() AS 'id';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Error sesión no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCaja` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCaja`( _action VARCHAR(15), _idCaja INT, _idEstadoCaja INT, _efectivoInicial DOUBLE(12,2), _efectivoFinal DOUBLE(12,2), _efectivoSobrante DOUBLE(10,2), _efectivoFaltante DOUBLE(10,2) )
BEGIN
	DECLARE _cajaAbierta BOOLEAN DEFAULT FALSE;

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		# SI TIENE UNA APERTURA PENDIENTE
		SELECT TRUE INTO _cajaAbierta FROM caja WHERE usuario = @usuario AND idEstadoCaja = 1;
        
        IF _cajaAbierta THEN
			SELECT 'danger' AS 'respuesta', 'Ya tiene aperturada su caja' AS 'mensaje';
        
        ELSE
			INSERT INTO caja
				( usuario, idEstadoCaja, fechaApertura, efectivoInicial, efectivoFinal, efectivoSobrante, efectivoFaltante ) 
			VALUES
				( @usuario, 1, CURDATE(), _efectivoInicial, _efectivoFinal, _efectivoSobrante, _efectivoFaltante );
				
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;
	
    ELSEIF _action = 'cierre' THEN
		UPDATE caja SET
			idEstadoCaja 		= _idEstadoCaja,
            efectivoInicial 	= _efectivoInicial,
            efectivoFinal 		= _efectivoFinal,
            efectivoSobrante 	= _efectivoSobrante,
            efectivoFaltante 	= _efectivoFaltante
		WHERE idCaja = _idCaja;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCierreDiario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCierreDiario`( _action VARCHAR(20), _idCierreDiario INT, _fechaCierre DATE, _comentario TEXT, _todos BOOLEAN )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062
		SELECT 'info' AS 'respuesta', 'Error, ya se ha realizado el cierre de este día' AS 'mensaje';
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cierreDiario ( fechaCierre, comentario, usuario, fechaRegistro, todos )
			VALUES ( _fechaCierre, _comentario, @usuario, NOW(), _todos );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE cierreDiario SET 
			fechaCierre = _fechaCierre,
			comentario  = _comentario
		WHERE idCierreDiario = _idCierreDiario;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCierreDiarioProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCierreDiarioProducto`( _action VARCHAR(20), _idCierreDiario INT, _idProducto INT, 
	_cantidadCocina DOUBLE(10,2), _cantidadBodega DOUBLE(10,2), _cantidadMostrador DOUBLE(10,2), 
    _actualizarDisponibilidad BOOLEAN )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cierreDiarioProducto ( idCierreDiario, idProducto, cantidadCocina, cantidadBodega, cantidadMostrador ) 
			VALUES ( _idCierreDiario, _idProducto, _cantidadCocina, _cantidadBodega, _cantidadMostrador );

		# SI ACTUALIZA DISPONIBILIDAD DE PRODUCTO
		IF _actualizarDisponibilidad THEN
			UPDATE producto SET disponibilidad = ( _cantidadCocina + _cantidadBodega + _cantidadMostrador )
				WHERE idProducto = _idProducto;
		END IF;
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCliente`( _action VARCHAR(15), _idCliente INT, _nit VARCHAR(15), _nombre VARCHAR(65), _cui VARCHAR(13), 
	_correo VARCHAR(65), _telefono VARCHAR(8),  _direccion VARCHAR(95), _idTipoCliente INT )
BEGIN
	DECLARE _existeCliente BOOLEAN DEFAULT FALSE;
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		SELECT TRUE INTO _existeCliente FROM cliente WHERE nit = _nit AND LENGTH( nit ) > 2 LIMIT 1;
        
        IF _existeCliente THEN
			SELECT 'danger' AS 'respuesta', 'Error, NIT duplicado' AS 'mensaje';
		
        ELSE
			INSERT INTO cliente ( nit, nombre, cui, correo, telefono, direccion, idTipoCliente, fechaRegistro, usuario ) 
				VALUES ( _nit, _nombre, _cui, _correo, _telefono, _direccion, _idTipoCliente, now(), @usuario );
                
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', last_insert_id() AS 'id';
        END IF;
    ELSEIF _action = 'update' THEN
		SELECT TRUE INTO _existeCliente FROM cliente 
			WHERE idCliente != _idCliente AND nit = _nit AND LENGTH( nit ) > 2 LIMIT 1;
        
		IF _existeCliente THEN
			SELECT 'danger' AS 'respuesta', 'Error, NIT ya pertenece a otro cliente' AS 'mensaje';
		
        ELSEIF _idCliente = 1 THEN
			SELECT 'danger' AS 'respuesta', 'No es posible modificar este cliente' AS 'mensaje';
            
        ELSE
			UPDATE cliente SET
				nit 		= _nit, 
				nombre 		= _nombre, 
				cui 		= _cui, 
				correo 		= _correo, 
				telefono 	= _telefono,
				direccion 	= _direccion,
				idTipoCliente = _idTipoCliente
			WHERE idCliente = _idCliente;
			
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCombo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCombo`( _action VARCHAR(20), _idCombo INT, _combo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT, _codigo INT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			INSERT INTO combo ( combo, imagen, descripcion, idEstadoMenu, top, codigo ) 
				VALUES ( _combo, _imagen, _descripcion, _idEstadoMenu, 0, _codigo );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		IF ! codigoDuplicado( _codigo, NULL, _idCombo, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			UPDATE combo SET
				combo        = _combo,
				descripcion  = _descripcion,
				idEstadoMenu = _idEstadoMenu,
				codigo 		 = _codigo
			WHERE idCombo = _idCombo;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
	
    ELSEIF _action = 'image' THEN
		UPDATE combo SET imagen = _imagen WHERE idCombo = _idCombo;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
    
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaComboDetalle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaComboDetalle`( _action VARCHAR(20), _idCombo INT, _idMenu INT, _cantidad DOUBLE(10,2) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Menu ya registrado para este combo' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO comboDetalle ( idCombo, idMenu, cantidad ) 
			VALUES ( _idCombo, _idMenu, _cantidad );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE comboDetalle SET cantidad = _cantidad
			WHERE idCombo = _idCombo AND idMenu = _idMenu;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM comboDetalle WHERE idCombo = _idCombo AND idMenu = _idMenu;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaComboEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaComboEvento`( _action VARCHAR(15), _idEventoCombo INT, _idEvento INT, _idCombo INT, _cantidad INT, _horaDespacho TIME, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
    COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR COMBO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO eventoCombo( idEvento, idCombo, cantidad, horaDespacho, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _idCombo, _cantidad, _horaDespacho, _precioUnitario, NOW(), @usuario, comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE eventoCombo SET
			idCombo        = _idCombo,
			cantidad       = _cantidad,
			horaDespacho   = _horaDespacho,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idEventoCombo = _idEventoCombo;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM eventoCombo WHERE idEventoCombo = _idEventoCombo;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaComboPrecio` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaComboPrecio`( _action VARCHAR(20), _idCombo INT, _idTipoServicio INT, _precio DOUBLE(10,2) )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062 BEGIN END;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO comboPrecio ( idCombo, idTipoServicio, precio ) 
			VALUES ( _idCombo, _idTipoServicio, _precio )
            ON DUPLICATE KEY UPDATE precio =  _precio;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM comboPrecio
			WHERE idCombo = _idCombo AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCuadre` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCuadre`( _idCaja INT, _idFormaPago INT )
BEGIN
	DECLARE _montoDespacho DOUBLE(10,2) DEFAULT 0;
    DECLARE _ingresos DOUBLE(10,2) DEFAULT 0;
    DECLARE _egresos DOUBLE(10,2) DEFAULT 0;
    
    SELECT  SUM( ffp.monto )AS 'montoDespacho'
		INTO _montoDespacho
	FROM caja AS c
		JOIN factura AS f 				ON c.idCaja = f.idCaja AND ( f.idEstadoFactura = 1 OR f.idEstadoFactura = 2 )
		JOIN facturaFormaPago AS ffp 	ON f.idFactura = ffp.idFactura
	WHERE c.idCaja = _idCaja AND IF( !ISNULL( _idFormaPago ), ffp.idFormaPago = _idFormaPago, TRUE );

	SELECT SUM( IF( tm.ingreso, m.monto, 0 ) )AS 'ingresos', SUM( IF( !tm.ingreso, m.monto, 0 ) )AS 'egresos'
		INTO _ingresos, _egresos
	FROM caja AS c
		JOIN movimiento AS m 		ON c.idCaja = m.idCaja
		JOIN tipoMovimiento AS tm 	ON m.idTipoMovimiento = tm.idTipoMovimiento
	WHERE c.idCaja = _idCaja AND IF( !ISNULL( _idFormaPago ), m.idFormaPago = _idFormaPago, TRUE ) ;
    
    SELECT _montoDespacho AS 'montoDespacho', _ingresos AS 'ingresos', _egresos AS 'egresos';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCuadreDiario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCuadreDiario`( _action VARCHAR(20), _idCierreDiario INT, _fechaCierre DATE, _comentario TEXT, _todos BOOLEAN )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062
		SELECT 'info' AS 'respuesta', 'Error, ya se ha realizado el cierre de este día' AS 'mensaje';
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cierreDiario ( fechaCierre, comentario, usuario, fechaRegistro, todos )
			VALUES ( _fechaCierre, _comentario, @usuario, NOW(), _todos );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE cierreDiario SET 
			fechaCierre = _fechaCierre,
			comentario  = _comentario
		WHERE idCierreDiario = _idCierreDiario;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCuadreProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCuadreProducto`( _action VARCHAR(20), _idCuadreProducto INT, _fechaCuadre DATE, _comentario TEXT, _todos BOOLEAN, _idUbicacion CHAR(1), _idEstadoCuadre INT )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062
		SELECT 'info' AS 'respuesta', 'Error, ya se ha realizado el cierre de este día' AS 'mensaje';
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cuadreProducto ( fechaCuadre, comentario, usuario, fechaRegistro, todos, idUbicacion, idEstadoCuadre )
			VALUES ( _fechaCuadre, _comentario, @usuario, NOW(), _todos, _idUbicacion, _idEstadoCuadre );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE cuadreProducto SET 
			fechaCuadre    = _fechaCuadre,
			comentario     = _comentario,
			idEstadoCuadre = _idEstadoCuadre
		WHERE idCuadreProducto = _idCuadreProducto;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSEIF _action = 'status' THEN
		UPDATE cuadreProducto SET idEstadoCuadre = _idEstadoCuadre
		WHERE idCuadreProducto = _idCuadreProducto;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaCuadreProductoDetalle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCuadreProductoDetalle`( _action VARCHAR(20), _idCuadreProducto INT, _idProducto INT, _cantidadApertura DOUBLE(10,2), _cantidadCierre DOUBLE(10,2), _diferenciaApertura DOUBLE(10,2), _diferenciaCierre DOUBLE(10,2), _actualizarDisponibilidad BOOLEAN, _idEstadoCuadre INT, _comentarioApertura TEXT, _comentarioCierre TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cuadreProductoDetalle ( idCuadreProducto, idProducto, cantidadApertura, cantidadCierre, diferenciaApertura, diferenciaCierre, comentarioApertura )
			VALUES ( _idCuadreProducto, _idProducto, _cantidadApertura, _cantidadCierre, _diferenciaApertura, _diferenciaCierre, _comentarioApertura );

		# SI ACTUALIZA DISPONIBILIDAD DE PRODUCTO
		IF _actualizarDisponibilidad AND _idEstadoCuadre = 3 THEN
			UPDATE producto SET disponibilidad = _cantidadCierre WHERE idProducto = _idProducto;
		END IF;
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE cuadreProductoDetalle SET
			cantidadCierre   = _cantidadCierre,
			diferenciaCierre = _diferenciaCierre,
			comentarioCierre = _comentarioCierre
		WHERE idCuadreProducto = _idCuadreProducto AND idProducto = _idProducto;

		# SI ACTUALIZA DISPONIBILIDAD DE PRODUCTO
		IF _actualizarDisponibilidad THEN
			UPDATE producto SET disponibilidad = _cantidadCierre WHERE idProducto = _idProducto;
		END IF;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaDenominacionCaja` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaDenominacionCaja`( _action VARCHAR(15), _idCaja INT, _idEstadoCaja INT, _denominacion DOUBLE(5,2), _cantidad INT )
BEGIN
    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Ya se registro la denominación para este estado' AS 'mensaje';
        
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

    IF _action = 'insert' THEN
		INSERT INTO denominacionCaja( idCaja, idEstadoCaja, denominacion, cantidad ) 
			VALUES ( _idCaja, _idEstadoCaja, _denominacion, _cantidad );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaDetalleFactura` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaDetalleFactura`( _action VARCHAR(15), _idFactura INT, _idDetalleOrdenMenu INT, _idDetalleOrdenCombo INT, _precioMenu DOUBLE(10,2), _descuento DOUBLE(10,2), _comentario TEXT )
BEGIN
	DECLARE _perteneceCombo BOOLEAN DEFAULT FALSE;
    DECLARE _estadoActualDetalle INT;
    DECLARE _idTipoServicio INT;
    DECLARE _idOrdenCliente INT;
    DECLARE _yaFacturado BOOLEAN DEFAULT FALSE;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Ya existe este detalle en la Factura' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN

		# SI ES MENU
		IF !ISNULL( _idDetalleOrdenMenu ) THEN
			SET _idDetalleOrdenCombo = NULL;
            
			SELECT dom.perteneceCombo, dom.idEstadoDetalleOrden, dom.idTipoServicio, IF( !ISNULL( dof.idFactura ), TRUE, FALSE ), idOrdenCliente
				INTO _perteneceCombo, _estadoActualDetalle, _idTipoServicio, _yaFacturado, _idOrdenCliente
			FROM detalleOrdenMenu AS dom
				LEFT JOIN detalleOrdenFactura AS dof 
					ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu
            WHERE dom.idDetalleOrdenMenu = _idDetalleOrdenMenu LIMIT 1;
			
		# SI ES COMBO
		ELSE
			SET _idDetalleOrdenMenu = NULL;
            
            SELECT doc.idEstadoDetalleOrden, doc.idTipoServicio, IF( !ISNULL( dof.idFactura ), TRUE, FALSE ), idOrdenCliente
				INTO  _estadoActualDetalle, _idTipoServicio, _yaFacturado, _idOrdenCliente
			FROM detalleOrdenCombo AS doc
				LEFT JOIN detalleOrdenFactura AS dof 
					ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo
            WHERE doc.idDetalleOrdenCombo = _idDetalleOrdenCombo LIMIT 1;
		END IF;


		# SI ES PARA RESTAURANTE DEBE DE ESTAR := SERVIDO
        IF ( _idTipoServicio = 2 AND _estadoActualDetalle != 4 ) THEN
			
            SELECT 'danger' AS 'respuesta', 'Estado actual no permite Facturar' AS 'mensaje';
		
        # SI YA ESTA FACTURADO MUESTRA ERROR
        ELSEIF ( _yaFacturado ) THEN
			SELECT 'info' AS 'respuesta', 'Detalle de Orden ya facturado' AS 'mensaje';
            
		# SI PERTENECE A COMBO
		ELSEIF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'Error, detalle pertenece a combo' AS 'mensaje';

		# SI NO EXISTE NINGUN INCONVENIENTE
		ELSE

            # SI ES MENU
            IF !ISNULL( _idDetalleOrdenMenu ) THEN
				UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 6 
					WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu 		AND idEstadoDetalleOrden = 4;

			# SI ES COMBO
			ELSE
				UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 6
					WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo 	AND idEstadoDetalleOrden = 4;
					
				# ACTUALIZA LOS ESTADOS DETALLE DE COMBO
				UPDATE detalleOrdenMenu AS dom

					JOIN detalleComboMenu AS dcm
						ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
							AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
							AND dom.idEstadoDetalleOrden = 4

				SET dom.idEstadoDetalleOrden = 6;
			END IF;
            
            # VERIFICA ESTADO DE ORDEN CLIENTE
            CALL cambioEstadoOrdenCliente( _idOrdenCliente, 6 );
            
            # GUARDAR DETALLE DE FACTURA
			INSERT INTO detalleOrdenFactura
				( idFactura, idDetalleOrdenMenu, idDetalleOrdenCombo, precioMenu, descuento, comentario, usuario, fechaRegistro ) 
			VALUES
				( _idFactura, _idDetalleOrdenMenu, _idDetalleOrdenCombo, _precioMenu, _descuento, _comentario, @usuario, NOW() );
				
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		END IF;
	
    ELSEIF _action = 'update' THEN
		UPDATE detalleOrdenFactura SET
			precioMenu = _precioMenu,
            descuento  = _descuento,
            comentario = _comentario
		WHERE idFactura = _idFactura AND idDetalleOrdenMenu = _idDetalleOrdenMenu 
			AND idDetalleOrdenCombo = _idDetalleOrdenCombo;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
        
	ELSEIF _action = 'delete' THEN

		IF !ISNULL( _idDetalleOrdenMenu ) THEN
			UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 4 
				WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

		ELSE
			UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 4
				WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;
		END IF;

		DELETE FROM detalleOrdenFactura WHERE idFactura = _idFactura 
			AND idDetalleOrdenMenu = _idDetalleOrdenMenu AND idDetalleOrdenCombo = _idDetalleOrdenCombo;
    
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaDetalleOrdenCombo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaDetalleOrdenCombo`( _action VARCHAR(20), _idDetalleOrdenCombo INT, _idOrdenCliente INT, _idCombo INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15), _observacion TEXT, _comentario TEXT )
BEGIN
	DECLARE _estadoActualDetalle INT;
	DECLARE _estadoActualOrden INT;
    DECLARE _ids TEXT DEFAULT '';
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	SELECT idEstadoDetalleOrden, IFNULL( _idOrdenCliente, idOrdenCliente ), IFNULL( _idTipoServicio, idTipoServicio )
		INTO _estadoActualDetalle, _idOrdenCliente, _idTipoServicio
	FROM detalleOrdenCombo WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		# SUMA CANTIDAD DE MENUS ORDENADOS POR CLIENTE
        UPDATE ordenCliente SET numMenu = numMenu + _cantidad WHERE idOrdenCliente = _idOrdenCliente;
            
		WHILE _cantidad > 0 DO

			INSERT INTO detalleOrdenCombo (idOrdenCliente, idCombo, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, observacion )
			VALUES (_idOrdenCliente, _idCombo, 1, 1, _idTipoServicio, @usuario, IFNULL( _usuarioResponsable, @usuario ), _observacion );

			UPDATE combo SET top = top + 1 WHERE idCombo = _idCombo;

			SET @idDetalleOrdenCombo = LAST_INSERT_ID();
            
            SET _ids = CONCAT( _ids, '_', @idDetalleOrdenCombo );

			# INGRESA DETALLE DE MENU DE COMBO
			CALL _comboDetalleMenu( @idDetalleOrdenCombo, _idCombo, _idTipoServicio, 1, IFNULL( _usuarioResponsable, @usuario ), _idOrdenCliente, _observacion );

			SET _cantidad = _cantidad - 1;
		END WHILE;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', _ids AS 'ids';
        
	ELSEIF _action = 'cancel' THEN
		
        SET @comentario = _comentario;
    
        # SI ES DIFERENTE A PENDIENTE
		IF _estadoActualDetalle != 1 THEN
			SELECT 'warning' AS 'respuesta', 'Estado actual no permite cancelar' AS 'mensaje';
            
		ELSE
			UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 10
				WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;
			
            # DESCUENTA DE NUMERO MENUS ORDEN
            UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
            
			# ACTUALIZA LOS ESTADOS DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idEstadoDetalleOrden = 10;

			SELECT 'success' AS 'respuesta', 'Cancelado correctamente' AS 'mensaje';
		END IF;

	ELSEIF _action = 'estado' THEN
		
        SET @comentario = _comentario;
        
        # SI ES PARA RESTAURANTE DEBE DE ESTAR := SERVIDO
        IF ( _idTipoServicio = 2 AND _idEstadoDetalleOrden = 6 AND _estadoActualDetalle != 4 ) THEN
			
            SELECT 'danger' AS 'respuesta', 'Estado actual no permite Facturar' AS 'mensaje';
        
		ELSEIF ( ( _idEstadoDetalleOrden > _estadoActualDetalle ) OR @isAdmin ) THEN
			UPDATE detalleOrdenCombo SET
				idEstadoDetalleOrden = _idEstadoDetalleOrden
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;
            
            # DESCUENTA DE NUMERO MENUS ORDEN SI ES CANCELAR
            /*IF _idEstadoDetalleOrden = 10 THEN
				UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
            END IF;*/

			# ACTUALIZA LOS ESTADOS DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idEstadoDetalleOrden = _idEstadoDetalleOrden;
            
            # ACTUALIZA ESTADO ORDEN
            CALL cambioEstadoOrdenCliente( _idOrdenCliente, _idEstadoDetalleOrden );

			SELECT 'success' AS 'respuesta', 'Cambio de estado realizado correctamente' AS 'mensaje';
            
		ELSE
			SELECT 'danger' AS 'respuesta', 'No se puede retornar a un estado anterior' AS 'mensaje';
            
		END IF;

	ELSEIF _action = 'responsable' THEN
		UPDATE detalleOrdenCombo SET
			usuarioResponsable = _usuarioResponsable
		WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

		# ACTUALIZA RESPONSABLE DETALLE DE COMBO
		UPDATE detalleOrdenMenu AS dom
			JOIN detalleComboMenu AS dcm
				ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
					AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
		SET dom.usuarioResponsable = _usuarioResponsable;

		SELECT 'success' AS 'respuesta', 'Cambio de responsable exitoso' AS 'mensaje';

	ELSEIF _action = 'tipoServicio' THEN
		IF ( ( _estadoActualDetalle != 6 AND _estadoActualDetalle != 10 ) OR @isAdmin ) THEN
			UPDATE detalleOrdenCombo SET
				idTipoServicio = _idTipoServicio
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

			# ACTUALIZA TIPO SERVICIO DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idTipoServicio = _idTipoServicio;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'asignarOtroCliente' THEN

		IF ISNULL( _estadoActualDetalle ) THEN
			SELECT 'danger' AS 'respuesta', 'Existe información faltante' AS 'mensaje';

		# SI EL DETALLE ESTA EN ESTADO: Realizado (6), Cancelado (10)
		ELSEIF ( _estadoActualDetalle = 6 OR _estadoActualDetalle = 10 ) THEN
			SELECT 'danger' AS 'respuesta', 'No es posible realizar la asignación, por estado actual de pedido' AS 'mensaje';
			
		ELSE
			UPDATE detalleOrdenCombo SET 
				idOrdenCliente = _idOrdenCliente
			WHERE idDetalleOrdenCombo = _idDetalleOrdenCombo;

			# ACTUALIZA ORDEN CLIENTE DETALLE DE COMBO
			UPDATE detalleOrdenMenu AS dom
				JOIN detalleComboMenu AS dcm
					ON dom.idDetalleOrdenMenu = dcm.idDetalleOrdenMenu
						AND dcm.idDetalleOrdenCombo = _idDetalleOrdenCombo
			SET dom.idOrdenCliente = _idOrdenCliente;

			SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaDetalleOrdenEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaDetalleOrdenEvento`( _idEvento INT )
    COMMENT 'CONSULTAR DETALLE ORDEN'
BEGIN
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSE
		
		# DETALLE DE MENU
		(SELECT
			em.idEventoMenu AS 'id',
			em.cantidad,
			TIME_FORMAT( em.horaDespacho, '%H:%i' ) AS 'horaDespacho',
			em.precioUnitario,
			( em.cantidad * em.precioUnitario )AS 'subTotal',
			em.fechaRegistro,
			em.comentario,
			m.idMenu AS 'idMenu',
			m.menu AS 'menu',
			m.imagen,
			'menu' AS 'idTipo',
			'Menú' AS 'tipo'
		FROM eventoMenu AS em
			JOIN lstMenu AS m
				ON em.idMenu = m.idMenu
		WHERE em.idEvento = _idEvento)
			UNION ALL
		# DETALLE DE COMBO
		(SELECT
			ec.idEventoCombo AS 'id',
			ec.cantidad,
            TIME_FORMAT( ec.horaDespacho, '%H:%i' ) AS 'horaDespacho',
			ec.precioUnitario,
			( ec.cantidad * ec.precioUnitario )AS 'subTotal',
			ec.fechaRegistro,
			ec.comentario,
			c.idCombo AS 'idMenu',
			c.combo AS 'menu',
			c.imagen,
			'combo' AS 'idTipo',
			'Combo' AS 'tipo'
		FROM eventoCombo AS ec
			JOIN lstCombo AS c
				ON ec.idCombo = c.idCombo
		WHERE ec.idEvento = _idEvento)
			UNION ALL
		# DETALLE DE MENU PERSONALIZADO
		(SELECT
			idOtroMenu AS 'id',
			cantidad,
			TIME_FORMAT( horaDespacho, '%H:%i' ) AS 'horaDespacho',
			precioUnitario,
			( cantidad * precioUnitario )AS 'subTotal',
			fechaRegistro,
			comentario,
			NULL AS 'idMenu',
			otroMenu AS 'menu',
			'' AS 'imagen',
			'otroMenu' AS 'idTipo',
			'Otro Menú' AS 'tipo'
		FROM otroMenu
		WHERE idEvento = _idEvento)
        UNION ALL
        # DETALLE DE OTRO SERVICIO
		(SELECT
			idOtroServicio AS 'id',
			cantidad,
			NULL AS 'horaDespacho',
			precioUnitario,
			( cantidad * precioUnitario )AS 'subTotal',
			fechaRegistro,
			comentario,
			NULL AS 'idMenu',
			otroServicio AS 'menu',
			'' AS 'imagen',
			'otroServicio' AS 'idTipo',
			'Otro Servicio' AS 'tipo'
		FROM otroServicio
		WHERE idEvento = _idEvento)
        ORDER BY fechaRegistro ASC;
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaDetalleOrdenMenu` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaDetalleOrdenMenu`( _action VARCHAR(20), _idDetalleOrdenMenu INT, _idOrdenCliente INT, _idMenu INT, _cantidad DOUBLE(10,2), _idEstadoDetalleOrden INT, _idTipoServicio INT, _idFactura INT, _usuarioResponsable VARCHAR(15), _observacion TEXT, _comentario TEXT )
BEGIN
	DECLARE _estadoActualDetalle INT;
	DECLARE _estadoActualOrden INT;
    DECLARE _idMenuActual INT;
	DECLARE _perteneceCombo BOOLEAN;
	DECLARE _ids TEXT DEFAULT '';
	DECLARE _yaFacturado BOOLEAN DEFAULT FALSE;
    DECLARE _seCocina BOOLEAN DEFAULT TRUE;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	# ESTADO DETALLE ORDEN MENU 
	IF !ISNULL( _idDetalleOrdenMenu ) THEN
		SELECT dom.idEstadoDetalleOrden, dom.perteneceCombo, dom.idMenu, IFNULL( _idOrdenCliente, dom.idOrdenCliente ), 
			IFNULL( _idTipoServicio, dom.idTipoServicio), IF( !ISNULL( dof.idFactura ), TRUE, FALSE )
		INTO
			_estadoActualDetalle, _perteneceCombo, _idMenuActual, _idOrdenCliente, 
            _idTipoServicio, _yaFacturado
		FROM detalleOrdenMenu AS dom
			JOIN menu AS m 
				ON dom.idMenu = m.idMenu
                
			LEFT JOIN detalleOrdenFactura AS dof
				ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu
                
		WHERE dom.idDetalleOrdenMenu = _idDetalleOrdenMenu
		LIMIT 1;
	END IF;

	# ESTADO ORDEN CLIENTE 
	SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente WHERE idOrdenCliente = _idOrdenCliente;

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		# SUMA CANTIDAD DE MENUS ORDENADOS POR CLIENTE
        UPDATE ordenCliente SET numMenu = numMenu + _cantidad WHERE idOrdenCliente = _idOrdenCliente;
        
        SELECT seCocina INTO _seCocina FROM menu WHERE idMenu = _idMenu;
		
        WHILE _cantidad > 0 DO
			INSERT INTO detalleOrdenMenu 
				(idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, perteneceCombo, observacion )
			VALUES (_idOrdenCliente, _idMenu, 1, IF( _seCocina, 1, 3 ), _idTipoServicio, @usuario, IFNULL( _usuarioResponsable, @usuario ), 0, _observacion );

			SET _ids = CONCAT( _ids, '_', LAST_INSERT_ID() );
			
			UPDATE menu SET top = top + 1 WHERE idMenu = _idMenu;
            
            SET _cantidad = _cantidad - 1;
        END WHILE;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', _ids AS 'ids';

	ELSEIF _action = 'cancel' THEN
		SET @comentario = _comentario;
        
        # SI ES DIFERENTE A PENDIENTE
		IF _estadoActualDetalle != 1 THEN
			SELECT 'warning' AS 'respuesta', 'Estado actual no permite cancelar' AS 'mensaje';
            
		ELSE
			UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 10
				WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;
			
            # DESCUENTA DE NUMERO MENUS DE TICKET
            IF ! _perteneceCombo THEN
				UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
            END IF;

			SELECT 'success' AS 'respuesta', 'Cancelado correctamente' AS 'mensaje';
		END IF;
        
    ELSEIF _action = 'estado' THEN
		SET @comentario = _comentario;
        
        # SI ES PARA RESTAURANTE DEBE DE ESTAR := SERVIDO
        IF ( _idTipoServicio = 2 AND _idEstadoDetalleOrden = 6 AND _estadoActualDetalle != 4 ) THEN
			
            SELECT 'danger' AS 'respuesta', 'Estado actual no permite Facturar' AS 'mensaje';
        
		ELSEIF ( ( _idEstadoDetalleOrden > _estadoActualDetalle ) OR @isAdmin ) THEN
			
			# CAMBIA ESTADO A DETALLE DE ORDEN
			UPDATE detalleOrdenMenu    SET   idEstadoDetalleOrden = _idEstadoDetalleOrden
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;
			
            # SI PERTENECE A COMOB
            IF _perteneceCombo THEN
            	SET @detalleComboPendiente = NULL;

				# NUMERO DE ORDENES CON ESTADO ANTERIOR
				SELECT SUM( IF( dom.idEstadoDetalleOrden < _idEstadoDetalleOrden, 1, 0 ) ), dcmo.idDetalleOrdenCombo
					INTO @detalleComboPendiente, @idDetalleOrdenCombo
				FROM detalleComboMenu AS dcm
					JOIN detalleComboMenu AS dcmo
						ON dcmo.idDetalleOrdenCombo = dcm.idDetalleOrdenCombo
					LEFT JOIN detalleOrdenMenu AS dom
						ON dom.idDetalleOrdenMenu = dcmo.idDetalleOrdenMenu
							AND dcm.idDetalleOrdenMenu != dom.idDetalleOrdenMenu
				WHERE dcm.idDetalleOrdenMenu = _idDetalleOrdenMenu;
                
                # SI DETALLE ES IGUAL A CERO CAMBIA DE ESTADO COMBO
                IF @detalleComboPendiente = 0 THEN
					UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = _idEstadoDetalleOrden 
						WHERE idDetalleOrdenCombo = @idDetalleOrdenCombo;

					# VERIFICA SI YA ESTA FACTURADO
					SELECT TRUE INTO _yaFacturado FROM detalleOrdenFactura WHERE idDetalleOrdenCombo = @idDetalleOrdenCombo;

					# SI TODOS LOS MENUS DEL COMBO ESTAN SERVIDOS Y ESTA FACTURADO, CAMBIA ESTADO DE COMBO A FACTURADO
					IF _idEstadoDetalleOrden = 4 AND _yaFacturado THEN
						UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 6 
							WHERE idDetalleOrdenCombo = @idDetalleOrdenCombo;

					END IF;

                END IF;
            END IF;
            
            # SI YA ESTA FACTURADO
            IF _idEstadoDetalleOrden = 4 AND _yaFacturado AND ! _perteneceCombo THEN
				UPDATE detalleOrdenMenu    SET   idEstadoDetalleOrden = 6
				WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;
                
            END IF;
			
            # ACTUALIZA ESTADO ORDEN
            CALL cambioEstadoOrdenCliente( _idOrdenCliente, _idEstadoDetalleOrden );
            
            # DESCUENTA DE NUMERO MENUS DE TICKET
			/*IF ! _perteneceCombo AND _idEstadoDetalleOrden = 10 THEN
				UPDATE ordenCliente SET numMenu = numMenu - 1 WHERE idOrdenCliente = _idOrdenCliente;
			END IF;*/

			SELECT 'success' AS 'respuesta', 'Cambio de estado guardado correctamente' AS 'mensaje';
		
        ELSE
			SELECT 'danger' AS 'respuesta', 'No se puede retornar a un estado anterior' AS 'mensaje';
		END IF;

	ELSEIF _action = 'responsable' THEN
		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSE
			UPDATE detalleOrdenMenu SET
				usuarioResponsable = _usuarioResponsable
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Cambio de responsable exitoso' AS 'mensaje';
		END IF;

	ELSEIF _action = 'tipoServicio' THEN

		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSEIF ( ( _estadoActualDetalle != 6 AND _estadoActualDetalle != 10 ) OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET
				idTipoServicio = _idTipoServicio
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'menu' THEN

		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSEIF ( _estadoActualDetalle = 1 OR @isAdmin ) THEN
			UPDATE detalleOrdenMenu SET 
				idMenu = _idMenu
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
		ELSE
			SELECT 'danger' AS 'respuesta', 'El estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'asignarOtroCliente' THEN

		# SI EL MENU PERTENECE A UN COMBO
		IF _perteneceCombo THEN
			SELECT 'danger' AS 'respuesta', 'No es posible, menú pertenece a un combo' AS 'mensaje';

		ELSEIF ISNULL( _estadoActualDetalle ) THEN
			SELECT 'danger' AS 'respuesta', 'Existe información faltante' AS 'mensaje';

		# SI EL DETALLE ESTA EN ESTADO: Realizado (6), Cancelado (10)
		ELSEIF ( _estadoActualDetalle = 6 OR _estadoActualDetalle = 10 ) THEN
			SELECT 'danger' AS 'respuesta', 'No es posible realizar la asignación, por estado actual de pedido' AS 'mensaje';
			
		ELSE
			UPDATE detalleOrdenMenu SET 
				idOrdenCliente = _idOrdenCliente
			WHERE idDetalleOrdenMenu = _idDetalleOrdenMenu;

			SELECT 'success' AS 'respuesta', 'Realizado correctamente' AS 'mensaje';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaEvento`( _action VARCHAR(15), _idEvento INT, _evento VARCHAR(75), _idCliente INT, _fechaEvento DATE, _idSalon INT, _idEstadoEvento INT, _numeroPersonas INT, _horaInicio TIME, _horaFinal TIME, _observacion TEXT, _comentario TEXT )
    COMMENT 'INSERTA / ACTUALIZA EVENTO'
BEGIN

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    SET @comentario = _comentario;
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
        
	ELSEIF _fechaEvento < CURDATE() THEN
		SELECT 'danger' AS 'respuesta', 'Fecha de evento NO puede ser menor al actual' AS 'mensaje';
	
	ELSEIF _action = 'insert' THEN
		INSERT INTO evento ( evento, idCliente, fechaEvento, idSalon, idEstadoEvento, numeroPersonas, horaInicio, horaFinal, observacion, usuario, fechaRegistro ) 
			VALUES ( _evento, _idCliente, _fechaEvento, _idSalon, _idEstadoEvento, _numeroPersonas, _horaInicio, _horaFinal, _observacion, @usuario, now() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		IF _idEstadoEvento = 5 AND _fechaEvento != curdate() THEN
			SELECT 'warning' AS 'respuesta', CONCAT( 'No se puede finalizar evento, corresponde a otra fecha: ', DATE_FORMAT( _fechaEvento, '%d/%m/%Y' ) )AS 'mensaje';
        
        ELSE
			UPDATE evento SET
				evento         = _evento,
				idCliente      = _idCliente,
				fechaEvento    = _fechaEvento,
				idSalon        = _idSalon,
				idEstadoEvento = _idEstadoEvento, 
				numeroPersonas = _numeroPersonas,
				horaInicio     = _horaInicio,
				horaFinal      = _horaFinal,
				observacion    = _observacion
			WHERE idEvento = _idEvento;
			
			SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
        END IF;
        
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaFactura` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaFactura`( _action VARCHAR(20), _idFacturaCompra INT, _idEstadoFactura INT, _noFactura VARCHAR(15), _proveedor VARCHAR(45), _fechaFactura DATE, _comentario TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO facturaCompra( idEstadoFactura, noFactura, proveedor, fechaFactura, comentario )
			VALUES ( _idEstadoFactura, _noFactura, _proveedor, _fechaFactura, _comentario );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE facturaCompra SET
			idEstadoFactura = _idEstadoFactura,
			noFactura       = _noFactura,
			proveedor       = _proveedor,
			fechaFactura    = _fechaFactura,
			comentario      = _comentario
		WHERE idFacturaCompra = _idFacturaCompra;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaFacturaCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaFacturaCliente`( _action VARCHAR(15), _idFactura INT, _idEstadoFactura INT, _idCliente INT, 
	_idCaja INT, _nombre VARCHAR(60), _direccion VARCHAR(75), _total DOUBLE(12,2), _descripcion VARCHAR(125) )
BEGIN
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO factura
			( idEstadoFactura, idCliente, idCaja, nombre, direccion, total, fechaFactura, fechaRegistro, usuario, descripcion ) 
		VALUES
			( IFNULL( _idEstadoFactura, 1 ), _idCliente, _idCaja, _nombre, _direccion, _total, CURDATE(), NOW(), @usuario, _descripcion );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
	
    ELSEIF _action = 'update' THEN
		UPDATE factura SET
			idCliente 	= _idCliente,
            nombre 	  	= _nombre,
            direccion 	= _direccion,
            total 	  	= _total,
            descripcion = _descripcion
		WHERE idFactura = _idFactura;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
        
	ELSEIF _action = 'status' THEN
		# SI NO ES ADMINISTRADOR Y ES CANCELAR FACTURA
		IF @idPerfil != 1 AND _idEstadoFactura = 10 THEN
			SELECT 'danger' AS 'respuesta', 'No tiene acceso para cancelar la factura' AS 'mensaje';
            
        ELSE
			UPDATE factura SET idEstadoFactura = _idEstadoFactura
			WHERE idFactura = _idFactura;
            
            SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
            
		END IF;
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaFacturaCompra` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaFacturaCompra`( _action VARCHAR(20), _idFacturaCompra INT, _idEstadoFactura INT, _noFactura VARCHAR(15), _proveedor VARCHAR(45), _fechaFactura DATE, _comentario TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO facturaCompra( idEstadoFactura, noFactura, proveedor, fechaFactura, comentario )
			VALUES ( _idEstadoFactura, _noFactura, _proveedor, _fechaFactura, _comentario );
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE facturaCompra SET
			idEstadoFactura = _idEstadoFactura,
			noFactura       = _noFactura,
			proveedor       = _proveedor,
			fechaFactura    = _fechaFactura,
			comentario      = _comentario
		WHERE idFacturaCompra = _idFacturaCompra;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaFormaPago` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaFormaPago`( _action VARCHAR(15), _idFactura INT, _idFormaPago INT, _monto DOUBLE(10,2) )
BEGIN
    DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Forma de pago duplicado' AS 'mensaje';
    
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO facturaFormaPago
			( idFactura, idFormaPago, monto ) 
		VALUES
			( _idFactura, _idFormaPago, _monto );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
	
    ELSEIF _action = 'delete' THEN
		DELETE FROM facturaFormaPago WHERE idFactura = _idFactura AND idFormaPago = _idFormaPago;
    
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaIngreso` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaIngreso`( _action VARCHAR(20), _idIngreso INT, _cantidad DOUBLE(10,2), _costo DOUBLE(12,2), _idProducto INT, _idFacturaCompra INT )
BEGIN

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO ingreso ( idFacturaCompra, idProducto, cantidad, costo, usuario, fechaRegistro ) 
			VALUES ( _idFacturaCompra, _idProducto, _cantidad, _costo, @usuario, NOW() );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM ingreso WHERE idIngreso = _idIngreso;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaMedida` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaMedida`( _action VARCHAR(20), _idMedida INT, _medida VARCHAR(45) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Medida duplicada' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO medida VALUES ( _idMedida, _medida );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE medida  SET medida = _medida
			WHERE idMedida = _idMedida;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaMenu` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaMenu`( _action VARCHAR(20), _idMenu INT, _menu VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, 
	_idEstadoMenu INT, _idDestinoMenu INT, _idTipoMenu INT, _codigo INT, _tiempoAlerta INT, _seCocina BOOLEAN )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			INSERT INTO menu ( menu, imagen, descripcion, idEstadoMenu, idDestinoMenu, top, idTipoMenu, codigo, tiempoAlerta, seCocina ) 
				VALUES ( _menu, _imagen, _descripcion, _idEstadoMenu, _idDestinoMenu, 0, _idTipoMenu, _codigo, _tiempoAlerta, _seCocina );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		IF ! codigoDuplicado( _codigo, _idMenu, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
            
        ELSE
			UPDATE menu SET 
				menu          = _menu, 
				descripcion   = _descripcion, 
				idEstadoMenu  = _idEstadoMenu, 
				idDestinoMenu = _idDestinoMenu,
				idTipoMenu    = _idTipoMenu,
				codigo 		  = _codigo,
                tiempoAlerta  = _tiempoAlerta,
                seCocina 	  = _seCocina
			WHERE idMenu = _idMenu;
			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
	
    ELSEIF _action = 'image' THEN
		UPDATE menu SET imagen = _imagen WHERE idMenu = _idMenu;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaMenuEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaMenuEvento`( _action VARCHAR(15), _idEventoMenu INT, _idEvento INT, _idMenu INT, _cantidad INT, _horaDespacho TIME, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
    COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR MENU DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO eventoMenu( idEvento, idMenu, cantidad, horaDespacho, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _idMenu, _cantidad, _horaDespacho, _precioUnitario, NOW(), @usuario, _comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE eventoMenu SET
			idMenu         = _idMenu,
			cantidad       = _cantidad,
			horaDespacho   = _horaDespacho,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idEventoMenu = _idEventoMenu;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM eventoMenu WHERE idEventoMenu = _idEventoMenu;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida o no tiene acceso' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaMenuPrecio` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaMenuPrecio`( _action VARCHAR(20), _idMenu INT, _idTipoServicio INT, _precio DOUBLE(10,2) )
BEGIN
	DECLARE CONTINUE HANDLER FOR 1062 BEGIN END;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO menuPrecio ( idMenu, idTipoServicio, precio ) 
			VALUES ( _idMenu, _idTipoServicio, _precio ) 
            ON DUPLICATE KEY UPDATE precio =  _precio;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM menuPrecio
			WHERE idMenu = _idMenu AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaMovimiento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaMovimiento`( _action VARCHAR(15), _idMovimiento INT, _idTipoMovimiento INT, 
	_idEstadoMovimiento INT, _idFormaPago INT, _idEvento INT, _motivo VARCHAR(60), _monto DOUBLE(10,2), _comentario TEXT )
BEGIN
	DECLARE _idCaja INT;

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
    SET @comentario = _comentario;
    
    # SI TIENE UNA APERTURA PENDIENTE
	SELECT idCaja INTO _idCaja FROM caja WHERE usuario = @usuario AND idEstadoCaja = 1 ORDER BY idCaja DESC LIMIT 1;
    
	# SI LA SESION ES INVALIDA
    IF !sesionValida() THEN
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';
	
    # SI LA CAJA NO ESTA HABILITADA
	ELSEIF ISNULL( _idCaja ) THEN
		SELECT 'danger' AS 'respuesta', 'No tiene ningúna Caja Abierta' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
    
		INSERT INTO movimiento ( idCaja, idTipoMovimiento, idEstadoMovimiento, idFormaPago, idEvento, motivo, monto ) 
		VALUES ( _idCaja, _idTipoMovimiento, _idEstadoMovimiento, _idFormaPago, _idEvento, _motivo, _monto );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
	 
    ELSEIF _action = 'delete' AND @isAdmin THEN
    
		DELETE FROM movimiento WHERE idMovimiento = _idMovimiento;
			
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaOrdenCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaOrdenCliente`( _action VARCHAR(20), _idOrdenCliente INT, _numeroTicket INT, _usuarioResponsable VARCHAR(15), _idEstadoOrden INT, _comentario TEXT )
BEGIN
	DECLARE _tktPendiente BOOLEAN DEFAULT FALSE;
	DECLARE _estadoActualOrden INT DEFAULT 0;
    DECLARE _ordenesPreparacion INT DEFAULT 0;
    DECLARE _gruposCocina INT DEFAULT 1;
    DECLARE _numeroGrupo INT DEFAULT 0;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		
		IF !ISNULL( _numeroTicket ) THEN
			SELECT TRUE INTO _tktPendiente FROM ordenCliente 
				WHERE numeroTicket = _numeroTicket AND ( idEstadoOrden BETWEEN 1 AND 4 )
			LIMIT 1;
		END IF;
        
        IF _tktPendiente THEN
			SELECT 'danger' AS 'respuesta', 'Error, EXISTE una orden pendiente con este # de Ticket' AS 'mensaje';
        
        ELSE
			SELECT valor INTO _gruposCocina
            FROM parametro WHERE idParametro = 'gruposCocina' LIMIT 1;
            
            SELECT numeroGrupo INTO _numeroGrupo
				FROM ordenCliente WHERE numMenu > 0
            ORDER BY idOrdenCliente DESC LIMIT 1;
            
            # SU ES MENOR AL LIMITE DE GRUPOS SUMA 1
            IF _numeroGrupo < _gruposCocina THEN
				SET _numeroGrupo = ( _numeroGrupo + 1 );
            
            # SI LLEGO AL TOTAL REGRESA AL INICIO
			ELSE
				SET _numeroGrupo = 1;
                
            END IF;
        
			INSERT INTO ordenCliente ( numeroTicket, usuarioPropietario, usuarioResponsable, idEstadoOrden, fechaRegistro, numMenu, numeroGrupo ) 
				VALUES ( _numeroTicket, @usuario, IFNULL( _usuarioResponsable, @usuario ), 1, NOW(), 0, _numeroGrupo );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID()AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		SELECT idEstadoOrden INTO _estadoActualOrden FROM ordenCliente 
			WHERE idOrdenCliente = _idOrdenCliente;

		IF _estadoActualOrden = 1 OR _estadoActualOrden = 2 THEN
			UPDATE ordenCliente SET 
				numeroTicket       = _numeroTicket,
				usuarioResponsable = _usuarioResponsable
			WHERE idOrdenCliente = _idOrdenCliente;
			SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';

		ELSE

			SELECT 'danger' AS 'respuesta', 'Estado actual no permite modificación' AS 'mensaje';
		END IF;

	ELSEIF _action = 'cancel' THEN

		SET @comentario = _comentario;
		
        SELECT COUNT(*) INTO _ordenesPreparacion FROM detalleOrdenMenu 
			WHERE idOrdenCliente = _idOrdenCliente AND idEstadoDetalleOrden > 1 AND idEstadoDetalleOrden != 10;
		
        IF _ordenesPreparacion > 0 THEN
			SELECT 'danger' AS 'respuesta', 'Error, existen menús en preparación' AS 'mensaje';
        
        ELSE
			# CANCELAR ORDENES
			UPDATE detalleOrdenMenu SET idEstadoDetalleOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            UPDATE detalleOrdenCombo SET idEstadoDetalleOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            
            # CANCELA ORDEN PRINCIPAL
            UPDATE ordenCliente SET idEstadoOrden = 10 WHERE idOrdenCliente = _idOrdenCliente;
            
			SELECT 'success' AS 'respuesta', 'Cancelado correctamente' AS 'mensaje';
        END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaOrdenEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaOrdenEvento`( _action VARCHAR(15), _idEvento INT, _idOrdenCliente INT )
    COMMENT 'INSERTAR / ELIMINAR ORDEN CLIENTE A EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Error, orden ya pertence a este evento' AS 'mensaje';
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO eventoOrdenCliente ( idEvento, idOrdenCliente, usuario, fechaRegistro ) 
			VALUES ( _idEvento, _idOrdenCliente, @usuario, NOW() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        
    ELSEIF _action = 'delete' THEN
		DELETE FROM eventoOrdenCliente WHERE idEvento = _idEvento AND idOrdenCliente = _idOrdenCliente;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaOtroMenuEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaOtroMenuEvento`( _action VARCHAR(15), _idOtroMenu INT, _idEvento INT, _otroMenu VARCHAR(45), _cantidad INT, _horaDespacho TIME, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
    COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR OTRO COMBO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO otroMenu( idEvento, otroMenu, cantidad, horaDespacho, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _otroMenu, _cantidad, _horaDespacho, _precioUnitario, NOW(), @usuario, _comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE otroMenu SET
			otroMenu       = _otroMenu,
			cantidad       = _cantidad,
			horaDespacho   = _horaDespacho,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idOtroMenu = _idOtroMenu;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM otroMenu WHERE idOtroMenu = _idOtroMenu;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaOtroServicio` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaOtroServicio`( _action VARCHAR(15), _idOtroServicio INT, _idEvento INT, _otroServicio VARCHAR(45), _cantidad INT, _precioUnitario DOUBLE( 10,2 ), _comentario TEXT )
    COMMENT 'INSERTAR / ACTUALIZAR / ELIMINAR OTRO SERVICIO DE EVENTO'
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO otroServicio( idEvento, otroServicio, cantidad, precioUnitario, fechaRegistro, usuario, comentario ) 
			VALUES ( _idEvento, _otroServicio, _cantidad, _precioUnitario, NOW(), @usuario, _comentario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        
    ELSEIF _action = 'update' THEN
		UPDATE otroServicio SET
			otroServicio   = _otroServicio,
			cantidad       = _cantidad,
			precioUnitario = _precioUnitario,
			comentario     = _comentario
		WHERE idOtroServicio = _idOtroServicio;
		
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
	
	ELSEIF _action = 'delete' AND @isAdmin THEN
		DELETE FROM otroServicio WHERE idOtroServicio = _idOtroServicio;
		
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaProducto`( _action VARCHAR(20), _idProducto INT, _producto VARCHAR(45), _idTipoProducto INT, _idMedida INT, _perecedero BOOLEAN, _cantidadMinima DOUBLE(10,2), _cantidadMaxima DOUBLE(10,2), _disponibilidad DOUBLE(10,2), _importante BOOLEAN, _idUbicacion CHAR(1) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Error, producto duplicado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO producto ( producto, idTipoProducto, idMedida, perecedero, cantidadMinima, cantidadMaxima, disponibilidad, importante, idUbicacion, fechaRegistro, usuario )
			VALUES ( _producto, _idTipoProducto, _idMedida, _perecedero, _cantidadMinima, _cantidadMaxima, _disponibilidad, _importante, _idUbicacion, NOW(), @usuario );
		
        SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE producto SET 
			producto       = _producto, 
			idTipoProducto = _idTipoProducto, 
            idUbicacion    = _idUbicacion,
			idMedida       = _idMedida, 
			perecedero     = _perecedero, 
			cantidadMinima = _cantidadMinima, 
			cantidadMaxima = _cantidadMaxima,
			importante     = _importante,
            idUbicacion    = _idUbicacion
		WHERE idProducto = _idProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaReajuste` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaReajuste`( _action VARCHAR(20), _observacion TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO reajuste( observacion, usuario, fechaRegistro) 
			VALUES ( _observacion, @usuario, NOW() );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaReajusteCaja` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaReajusteCaja`( _action VARCHAR(15), _idReajusteCaja INT, _idCaja INT, _monto DOUBLE(10,2), _observacion TEXT )
BEGIN
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
    
		INSERT INTO reajusteCaja
			( idCaja, monto, observacion, usuario, fechaRegistro ) 
		VALUES
			( _idCaja, _monto, _observacion, @usuario, NOW() );
			
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
	
    ELSEIF _action = 'delete' THEN
    
		DELETE FROM reajusteCaja WHERE idReajusteCaja = _idReajusteCaja;
			
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaReajusteInventario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaReajusteInventario`( _action VARCHAR(20), _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT, _esIncremento BOOLEAN )
BEGIN
	DECLARE _disponibilidad DOUBLE(10,2);

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		SELECT disponibilidad INTO _disponibilidad FROM producto WHERE idProducto = _idProducto;

		IF ! _esIncremento AND _disponibilidad < _cantidad THEN
			SELECT 'danger' AS 'respuesta', 'La disponibilidad no puede ser menor a cero' AS 'mensaje';

		ELSE
			INSERT INTO reajusteInventario (idProducto, cantidad, observacion, esIncremento, usuario, fechaRegistro) 
				VALUES ( _idProducto, _cantidad, _observacion, _esIncremento, @usuario, NOW() );
			
			IF _esIncremento THEN
				UPDATE producto SET disponibilidad = disponibilidad + _cantidad
					WHERE idProducto = _idProducto;
			ELSE
				UPDATE producto SET disponibilidad = disponibilidad - _cantidad
					WHERE idProducto = _idProducto;
			END IF;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaReajusteProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaReajusteProducto`( _action VARCHAR(20), _idReajuste INT, _idProducto INT, _cantidad DOUBLE(10,2), _esIncremento BOOLEAN )
BEGIN
	DECLARE _disponibilidad DOUBLE(10,2);

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		SELECT disponibilidad INTO _disponibilidad FROM producto WHERE idProducto = _idProducto;

		IF ! _esIncremento AND _disponibilidad < _cantidad THEN
			SELECT 'danger' AS 'respuesta', 'La disponibilidad no puede ser menor a cero' AS 'mensaje';

		ELSE
			INSERT INTO reajusteProducto( idReajuste, idProducto, cantidad, esIncremento ) 
				VALUES ( _idReajuste, _idProducto, _cantidad, _esIncremento );
			
			IF _esIncremento THEN
				UPDATE producto SET disponibilidad = disponibilidad + _cantidad
					WHERE idProducto = _idProducto;
			ELSE
				UPDATE producto SET disponibilidad = disponibilidad - _cantidad
					WHERE idProducto = _idProducto;
			END IF;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
		END IF;
	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaReceta` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaReceta`( _action VARCHAR(20), _idMenu INT, _idProducto INT, _cantidad DOUBLE(10,2), _observacion TEXT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Error, el producto ya existe en el menú ' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO receta ( idMenu, idProducto, cantidad, observacion ) 
			VALUES ( _idMenu, _idProducto, _cantidad, _observacion );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE receta SET 
			cantidad    = _cantidad,
			observacion = _observacion
		WHERE idMenu = _idMenu AND idProducto = _idProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
	
    ELSEIF _action = 'delete' THEN
		DELETE FROM receta WHERE idMenu = _idMenu AND idProducto = _idProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaSuperCombo` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaSuperCombo`( _action VARCHAR(20), _idSuperCombo INT, _superCombo VARCHAR(45), _imagen VARCHAR(125), _descripcion TEXT, _idEstadoMenu INT, codigo INT )
BEGIN
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';


	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, NULL ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			INSERT INTO superCombo ( superCombo, imagen, descripcion, idEstadoMenu, top, codigo ) 
				VALUES ( _superCombo, _imagen, _descripcion, _idEstadoMenu, 0, _codigo );

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';
        END IF;

	ELSEIF _action = 'update' THEN
		IF ! codigoDuplicado( _codigo, NULL, NULL, _idSuperCombo ) THEN
			SELECT 'danger' AS 'respuesta', 'Código rápido ya pertenece a otro menú' AS 'mensaje';
        
        ELSE
			UPDATE superCombo SET
				superCombo   = _superCombo,
				descripcion  = _descripcion,
				idEstadoMenu = _idEstadoMenu,
				codigo 		 = _codigo
			WHERE idSuperCombo = _idSuperCombo;

			SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
        END IF;
	
    ELSEIF _action = 'image' THEN
		UPDATE superCombo SET imagen = _imagen WHERE idSuperCombo = _idSuperCombo;

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaSuperComboDetalle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaSuperComboDetalle`( _action VARCHAR(20), _idSuperCombo INT, _idCombo INT, _cantidad DOUBLE(10,2) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Combo ya registrado para este Super Combo' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO superComboDetalle ( idSuperCombo, idCombo, cantidad ) 
			VALUES ( _idSuperCombo, _idCombo, _cantidad );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE superComboDetalle SET cantidad = _cantidad
			WHERE idSuperCombo = _idSuperCombo AND idCombo = _idCombo;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM superComboDetalle WHERE idSuperCombo = _idSuperCombo AND idCombo = _idCombo;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaSuperComboPrecio` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaSuperComboPrecio`( _action VARCHAR(20), _idSuperCombo INT, _idTipoServicio INT, _precio DOUBLE(10,2) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Precio para Tipo de Servicio ya registrado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO superComboPrecio ( idSuperCombo, idTipoServicio, precio ) 
			VALUES ( _idSuperCombo, _idTipoServicio, _precio );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'update' THEN
		UPDATE superComboPrecio SET precio = _precio
			WHERE idSuperCombo = _idSuperCombo AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSEIF _action = 'delete' THEN
		DELETE FROM superComboPrecio 
			WHERE idSuperCombo = _idSuperCombo AND idTipoServicio = _idTipoServicio;
		SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaTipoProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaTipoProducto`( _action VARCHAR(20), _idTipoProducto INT, _tipoProducto VARCHAR(45) )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Tipo de Producto duplicado' AS 'mensaje';

	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO tipoProducto VALUES ( _idTipoProducto, _tipoProducto );
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', LAST_INSERT_ID() AS 'id';

	ELSEIF _action = 'update' THEN
		UPDATE tipoProducto  SET tipoProducto = _tipoProducto
			WHERE idTipoProducto = _idTipoProducto;
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';

	ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
	END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultaUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaUsuario`( _action VARCHAR(15), _usuario varchar(15), _codigo INT(4), _nombres VARCHAR(65), _apellidos VARCHAR(65), _idPerfil INT, _idDestinoMenu INT )
BEGIN

	# IF DUPLICATE
	DECLARE EXIT HANDLER FOR 1062
		SELECT 'danger' AS 'respuesta', 'Usuario o Código Duplicado' AS 'mensaje';

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO usuario
			(usuario, codigo, clave, nombres, apellidos, fechaRegistro, idEstadoUsuario, idPerfil, usuarioRegistro, idDestinoMenu ) 
		VALUES
			(_usuario, _codigo, _usuario, _nombres, _apellidos, now(), 1, _idPerfil, @usuario, _idDestinoMenu );
			
		INSERT INTO log_estadoUsuario (idEstadoUsuario, usuario, usuarioRegistro, fechaRegistro)
		VALUES (1, _usuario, @usuario, now());
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
	
    ELSEIF _action = 'update' THEN
		UPDATE usuario SET
			codigo 		  = _codigo,
            nombres 	  = _nombres,
            apellidos 	  = _apellidos,
            idPerfil 	  = _idPerfil,
            idDestinoMenu = _idDestinoMenu
		WHERE usuario = _usuario;
    
		SELECT 'success' AS 'respuesta', 'Actualizado correctamente' AS 'mensaje';
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `consultCliente` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `consultCliente`( _action VARCHAR(15), _nit VARCHAR(15), _nombre VARCHAR(65), _cui VARCHAR(13), 
	_correo VARCHAR(65), _telefono VARCHAR(8),  _direccion VARCHAR(95), _idTipoCliente INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Error, NIT duplicado' AS 'mensaje';
    
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error al guardar la información' AS 'mensaje';
	
    
    IF !sesionValida() THEN # SI LA SESION ES INVALIDA
		SELECT 'danger' AS 'respuesta', 'Sesión no válida' AS 'mensaje';

	ELSEIF _action = 'insert' THEN
		INSERT INTO cliente ( nit, nombre, cui, correo, telefono, direccion, idTipoCliente, fechaRegistro, usuario ) 
			VALUES ( _nit, _nombre, _cui, _correo, _telefono, _direccion, _idTipoCliente, now(), @usuario );

		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje', last_insert_id() AS 'id';
    ELSEIF _action = 'update' THEN
		UPDATE cliente SET
			nit 		= _nit, 
			nombre 		= _nombre, 
			cui 		= _cui, 
			correo 		= _correo, 
			telefono 	= _telefono,
			direccion 	= _direccion,
			idTipoCliente = _idTipoCliente
		WHERE idCliente = _idCliente;
		
		SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
    ELSE
		SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `definirSesion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `definirSesion`( _usuario varchar(15) )
BEGIN
	
    SET @usuario = NULL, @idPerfil = NULL, @isAdmin = NULL, @idDestinoMenu= NULL;
    
    SELECT usuario, idPerfil, idDestinoMenu, IF( idPerfil = 1, TRUE, FALSE ) 
		INTO @usuario, @idPerfil, @idDestinoMenu, @isAdmin
	FROM usuario WHERE usuario = _usuario AND idEstadoUsuario = 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `destinoMenuUsuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `destinoMenuUsuario`( _action varchar(15), _usuario varchar(15), _idDestinoMenu INT )
BEGIN
	DECLARE EXIT HANDLER FOR 1062
        SELECT 'danger' AS 'respuesta', 'Ya tiene este destino asignado' AS 'mensaje';

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

	IF sesionValida() THEN
        IF _action = 'insert' THEN
			INSERT INTO destinoMenuUsuario( usuario, idDestinoMenu )
			VALUES ( _usuario, _idDestinoMenu );
            
            SELECT 'success' AS 'respuesta', 'Guardado correctamente' AS 'mensaje';
            
		ELSEIF _action = 'delete' THEN
			DELETE FROM destinoMenuUsuario WHERE usuario = _usuario AND idDestinoMenu = _idDestinoMenu;
				
            SELECT 'success' AS 'respuesta', 'Eliminado correctamente' AS 'mensaje';
            
		ELSE
        
			SELECT 'danger' AS 'respuesta', 'Acción no válida' AS 'mensaje';
		END IF;
    
    ELSE
		SELECT 'danger' AS 'respuesta', 'Sesión no válida o acceso denegado' AS 'mensaje';
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `detalleFacturaEvento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `detalleFacturaEvento`( _idFactura INT, _idEvento INT )
BEGIN
	DECLARE _perteneceCombo BOOLEAN DEFAULT FALSE;
    DECLARE _estadoActualDetalle INT;
    DECLARE _idTipoServicio INT;
    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';

    # MENUS DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, cantidad, descripcion, subTotal, fechaRegistro ) 
    SELECT
    	_idEvento, _idFactura, em.cantidad, m.menu, ( em.cantidad * em.precioUnitario), NOW()
    FROM eventoMenu AS em
    	JOIN menu AS m 		ON em.idMenu = m.idMenu
    WHERE em.idEvento = _idEvento;

    # COMBOS DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, cantidad, descripcion, subTotal, fechaRegistro ) 
    SELECT
    	_idEvento, _idFactura, ec.cantidad, c.combo, ( ec.cantidad * ec.precioUnitario), NOW()
    FROM eventoCombo AS ec
    	JOIN combo AS c 		ON ec.idCombo = c.idCombo
    WHERE ec.idEvento = _idEvento;

    # MENU PERSONALIZADO DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, cantidad, descripcion, subTotal, fechaRegistro ) 
    SELECT
    	_idEvento, _idFactura, cantidad, otroMenu, ( cantidad * precioUnitario), NOW()
    FROM otroMenu
    WHERE idEvento = _idEvento;

    # OTRO SERVICIO DEL EVENTO
    INSERT INTO eventoFactura
    	( idEvento, idFactura, cantidad, descripcion, subTotal, fechaRegistro ) 
    SELECT
    	_idEvento, _idFactura, cantidad, otroServicio, ( cantidad * precioUnitario), NOW()
    FROM otroServicio
    WHERE idEvento = _idEvento;
    
    SELECT 'success' AS 'respuesta', 'Detalle de factura guardado correctamente' AS 'mensaje';
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `guardarProducto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `guardarProducto`( _producto VARCHAR(45), _idTipoProducto INT, _idMedida INT, 
	_perecedero BOOLEAN, _cantidadMinima DOUBLE(10,2), _cantidadMaxima DOUBLE(10,2), _disponibilidad DOUBLE(10,2), _importante BOOLEAN )
BEGIN

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
    
	IF sesionValida() THEN
		INSERT INTO producto
		(producto, idTipoProducto, idMedida, perecedero, cantidadMinima, cantidadMaxima, disponibilidad, 
			importante, fechaRegistro, usuario)
        VALUES
		(_producto, _idTipoProducto, _idMedida, _perecedero, _cantidadMinima, _cantidadMaxima, _disponibilidad, 
			_importante, NOW(), @usuario);
    END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `inicioSesion` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `inicioSesion`( _usuario varchar(15) )
BEGIN
	
    SET @usuario = NULL, @idNivel = NULL, @idPerfil = NULL;
    
    SELECT usuario, idNivel, idPerfil INTO @usuario, @idNivel, @idPerfil 
		FROM usuario WHERE usuario = _usuario AND idEstadoUsuario = 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `login` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `login`( _usuario varchar(15), _clave varchar(75), _codigo INT )
BEGIN
	# DECLARE VARS
	DECLARE _sesionValida int default 0;
    DECLARE _nombre varchar(90) default '';
    DECLARE _nombreCorto varchar(35) default '';
    DECLARE _idPerfil INT;
    DECLARE _idDestinoMenu INT;
    DECLARE _codigoUsuario INT;

    # OTHERS ERRORS
    DECLARE EXIT HANDLER FOR SQLEXCEPTION BEGIN
        SELECT 'danger' AS 'respuesta', 'Ocurrio un error desconocido' AS 'mensaje';
	END;
    
    # VALIDATE USER&PASSWORD
    SELECT 
		IF( idEstadoUsuario = 1, 1, -1 ), usuario, CONCAT(nombres, ' ', apellidos), idPerfil, codigo, nombres, idDestinoMenu
			INTO 
        _sesionValida, _usuario, _nombre, _idPerfil, _codigoUsuario, _nombreCorto, _idDestinoMenu
	FROM usuario 
    WHERE IF( ISNULL( _codigo ), usuario = _usuario, codigo = _codigo ) 
		AND clave = md5( _clave );
    
    # IF SESSION IS VALID
    IF _sesionValida = 1 THEN
		IF _usuario = _clave THEN
			SELECT 'warning' AS 'respuesta', 'Debe de Cambiar su Contraseña antes de ingresar' AS 'mensaje';
		ELSE
			SELECT 'success' AS 'respuesta', 'Autenticado correctamente' AS 'mensaje', _nombre AS 'nombre', 
				_idPerfil AS 'idPerfil', _codigoUsuario AS 'codigoUsuario', _nombreCorto AS 'nombreCorto', _usuario AS 'usuario', _idDestinoMenu AS 'idDestinoMenu';

			INSERT INTO historialAutenticacion (usuario, fechaRegistro, idTipoRespuesta) 
				VALUES(_usuario, now(), 1);
        END IF;
	# IF SESSION IS VALID
    ELSEIF _sesionValida = -1 THEN
		SELECT 'danger' AS 'respuesta', 'Usuario Bloqueado' AS 'mensaje';
        INSERT INTO historialAutenticacion (usuario, fechaRegistro, idTipoRespuesta) 
			VALUES(_usuario, now(), 3);
	# IF SESSION IS NOT VALID
    ELSE
		SELECT 'danger' AS 'respuesta', 'Usuario/Password no valido' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `repCierreCaja` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `repCierreCaja`( _deFecha DATE, _paraFecha DATE )
BEGIN
	SELECT
		c.idCaja,
		c.fechaApertura,
		TIME( beca.fechaRegistro )AS 'horaApertura',
		DATE( becc.fechaRegistro )AS 'fechaCierre',
		TIME( becc.fechaRegistro )AS 'horaCierre',
		c.efectivoInicial,
		c.efectivoFinal,
		c.efectivoSobrante,
		c.efectivoFaltante,
        u.usuario,
		CONCAT( u.nombres, ' ', u.apellidos )AS 'nombre'
    FROM caja AS c
        JOIN usuario AS u
			ON u.usuario = c.usuario

		LEFT JOIN bitacoraEstadoCaja AS beca #FECHA APERTURA
			ON beca.idCaja = c.idCaja AND beca.idEstadoCaja = 1

		LEFT JOIN bitacoraEstadoCaja AS becc #FECHA CIERRE
			ON becc.idCaja = c.idCaja AND ( becc.idEstadoCaja = 2 OR becc.idEstadoCaja = 3 )

	WHERE ( c.fechaApertura BETWEEN _deFecha AND _paraFecha )
	GROUP BY c.idCaja
	ORDER BY c.idCaja ASC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `repDescuento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `repDescuento`( _deFecha DATE, _paraFecha DATE )
BEGIN
	SELECT
		f.idFactura,
		f.fechaFactura,
		c.nit,
		f.nombre,
		f.direccion,
		c.telefono,
		IF( dof.idMenuPersonalizado > 0,
			mp.cantidad,
			COUNT( dof.idFactura )
		)AS 'numeroMenus',
		IF( dof.idMenuPersonalizado > 0,
			mp.precioUnidad,
			dof.precioMenu
		)AS 'precioUnitario',
		SUM( dof.descuento )AS 'totalDescuento',
		dof.comentario,
		IFNULL( m.menu, IFNULL( cm.combo, mp.descripcion ) )AS 'menu',
        u.usuario,
		CONCAT( u.nombres, ' ', u.apellidos )AS 'nombreUsuario'
    FROM factura AS f
    	JOIN cliente AS c
    		ON c.idCliente = f.idCliente

		JOIN detalleOrdenFactura AS dof
			ON dof.idFactura = f.idFactura
		
        JOIN usuario AS u
			ON u.usuario = dof.usuario

		LEFT JOIN (detalleOrdenMenu AS dom
			JOIN menu AS m  ON  dom.idMenu = m.idMenu
		) ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu

		LEFT JOIN (detalleOrdenCombo AS doc
			JOIN combo AS cm  ON  doc.idCombo = cm.idCombo
		) ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo

		LEFT JOIN menuPersonalizado AS mp
			ON mp.idMenuPersonalizado = dof.idMenuPersonalizado

	WHERE ( f.fechaFactura BETWEEN _deFecha AND _paraFecha )
		AND dof.descuento > 0
	GROUP BY
		dof.idDetalleOrdenMenu, 
		dof.idDetalleOrdenCombo,
		dof.idMenuPersonalizado
	ORDER BY f.idFactura ASC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `repVentasCajero` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `repVentasCajero`( _deFecha DATE, _paraFecha DATE )
BEGIN
	SELECT
		f.idFactura,
		f.fechaFactura,
		DATE_FORMAT( f.fechaRegistro, '%H:%i:%s' )AS 'horaFactura',
		c.nit,
		f.nombre,
		f.direccion,
		c.telefono,
		IF( dof.idMenuPersonalizado > 0,
			mp.cantidad,
			COUNT( IFNULL( dof.idDetalleOrdenMenu, dof.idDetalleOrdenCombo ) )
		)AS 'numeroMenus',
		SUM(
			(IF( dof.idMenuPersonalizado > 0,
				mp.cantidad,
				1
			) * dof.precioMenu)
			- dof.descuento
		)AS 'precioTotal',
        SUM( dof.descuento )AS 'totalDescuento',
		dof.comentario,
		IFNULL( m.menu, IFNULL( cm.combo, mp.descripcion ) )AS 'menu',
        u.usuario,
		CONCAT( u.nombres, ' ', u.apellidos )AS 'nombreUsuario'
    FROM factura AS f
    	JOIN cliente AS c
    		ON c.idCliente = f.idCliente

        JOIN usuario AS u
			ON u.usuario = f.usuario

		JOIN detalleOrdenFactura AS dof
			ON dof.idFactura = f.idFactura

		LEFT JOIN (detalleOrdenMenu AS dom
			JOIN menu AS m  ON  dom.idMenu = m.idMenu
		) ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu

		LEFT JOIN (detalleOrdenCombo AS doc
			JOIN combo AS cm  ON  doc.idCombo = cm.idCombo
		) ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo

		LEFT JOIN menuPersonalizado AS mp
			ON mp.idMenuPersonalizado = dof.idMenuPersonalizado

	WHERE ( f.fechaFactura BETWEEN _deFecha AND _paraFecha )
		AND f.idEstadoFactura = 1
	GROUP BY
		f.idFactura,
		m.idMenu, 
		cm.idCombo,
		dof.idMenuPersonalizado
	ORDER BY f.idFactura ASC;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `repVentasGeneral` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `repVentasGeneral`( _deFecha DATE, _paraFecha DATE, _tipoReporte VARCHAR(25) )
    COMMENT 'VENTAS GENERALES: AGRUPADOS'
BEGIN
	SELECT
		MONTH( fechaFactura )AS 'mesFactura',
	    DAYOFWEEK( fechaFactura )AS 'diaSemana',
		idFactura,
	    fechaFactura,
	    horaFactura,
	    nit,
	    nombre,
	    direccion,
	    telefono,
	    SUM( numeroMenus )AS 'numeroMenus',
	    SUM( precio )AS 'totalPrecio',
	    SUM( descuento )AS 'totalDescuento',
	    comentario,
	    menuDesc,
	    SUM( efectivo )AS 'totalEfectivo',
	    SUM( tarjeta )AS 'totalTarjeta',
	    SUM( total )AS 'total',
	    idMenu,
	    idCombo,
	    idMenuPersonalizado,
	    servicio
	FROM(SELECT
			f.idFactura,
			f.fechaFactura,
			DATE_FORMAT( f.fechaRegistro, '%H:%i:%s' )AS 'horaFactura',
			c.nit,
			f.nombre,
			f.direccion,
			c.telefono,
			SUM( IF( dof.idMenuPersonalizado > 0,
				mp.cantidad,
				1
			) )'numeroMenus',
			SUM(
				(IF( dof.idMenuPersonalizado > 0,
					mp.cantidad,
					1
				) * dof.precioMenu)
				- dof.descuento
			)AS 'precio',
			SUM( dof.descuento )AS 'descuento',
			GROUP_CONCAT( DISTINCT comentario )AS 'comentario',
			IFNULL( m.menu, IFNULL( cm.combo, mp.descripcion ) )AS 'menuDesc',
			IFNULL( fpe.monto, 0 )AS 'efectivo',
			IFNULL( fpt.monto, 0 )AS 'tarjeta',
			f.total,
			m.idMenu, 
			cm.idCombo,
			dof.idMenuPersonalizado,
			IFNULL( tsm.tipoServicio, IFNULL( tsc.tipoServicio, 'Restaurante' ) )AS 'servicio'
		FROM factura AS f
			JOIN cliente AS c
				ON c.idCliente = f.idCliente

			JOIN detalleOrdenFactura AS dof
				ON dof.idFactura = f.idFactura

			LEFT JOIN facturaFormaPago AS fpe #FORMA DE PAGO EFECTIVO
				ON fpe.idFactura = f.idFactura AND fpe.idFormaPago = 1

			LEFT JOIN facturaFormaPago AS fpt #FORMA DE PAGO TARJETA
				ON fpt.idFactura = f.idFactura AND fpt.idFormaPago = 2

			LEFT JOIN (detalleOrdenMenu AS dom
				JOIN menu AS m  		 ON dom.idMenu = m.idMenu
				JOIN tipoServicio AS tsm ON tsm.idTipoServicio = dom.idTipoServicio
			) ON dom.idDetalleOrdenMenu = dof.idDetalleOrdenMenu

			LEFT JOIN (detalleOrdenCombo AS doc
				JOIN combo AS cm  		 ON doc.idCombo = cm.idCombo
				JOIN tipoServicio AS tsc ON tsc.idTipoServicio = doc.idTipoServicio
			) ON doc.idDetalleOrdenCombo = dof.idDetalleOrdenCombo

			LEFT JOIN menuPersonalizado AS mp
				ON mp.idMenuPersonalizado = dof.idMenuPersonalizado

		WHERE ( f.fechaFactura BETWEEN _deFecha AND _paraFecha )
			AND f.idEstadoFactura = 1
		GROUP BY
			(CASE WHEN ( _tipoReporte = 'detalle' OR _tipoReporte = 'factura' OR _tipoReporte = 'dia' OR _tipoReporte = 'mes' ) THEN f.idFactura END),
	        (CASE WHEN ( _tipoReporte = 'detalle' OR _tipoReporte = 'factura' OR _tipoReporte = 'menu' ) THEN menuDesc END),
	        (CASE WHEN ( _tipoReporte = 'detalle' OR _tipoReporte = 'factura' OR _tipoReporte = 'servicio' ) THEN servicio END)
		ORDER BY f.idFactura ASC)rep
	GROUP BY (CASE 
		WHEN _tipoReporte = 'dia' THEN DAYOFWEEK( fechaFactura )
		WHEN _tipoReporte = 'mes' THEN MONTH( fechaFactura ) 	
	    WHEN _tipoReporte = 'servicio' THEN servicio END), 		
	    (CASE WHEN _tipoReporte = 'menu' THEN menuDesc END), 	
	    (CASE WHEN ( _tipoReporte = 'detalle' OR _tipoReporte = 'factura' ) THEN idFactura END),
	    (CASE WHEN ( _tipoReporte = 'detalle' OR _tipoReporte = 'factura' ) THEN menuDesc END), 
	    (CASE WHEN ( _tipoReporte = 'detalle' OR _tipoReporte = 'factura' ) THEN servicio END);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `resetearClave` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `resetearClave`( _usuario varchar(15) )
BEGIN
	IF sesionValida() THEN    
		UPDATE usuario SET clave = md5( _usuario ) where usuario = _usuario;

		SELECT 'success' AS 'respuesta', 'Usuario reseteado correctamente' AS 'mensaje';
        
    ELSE
		SELECT 'danger' AS 'respuesta', 'Sesión no válida o acceso denegado' AS 'mensaje';
    END IF;
end ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `_comboDetalleMenu` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ALLOW_INVALID_DATES,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `_comboDetalleMenu`( _idDetalleOrdenCombo INT, _idCombo INT, _idTipoServicio INT, _idEstadoDetalleOrden INT, _usuarioResponsable VARCHAR( 15 ), _idOrdenCliente INT, _observacion TEXT )
BEGIN
	DECLARE finCursor BOOLEAN DEFAULT FALSE;
	DECLARE _idMenu INT;
    DECLARE _cantidad INT;
    DECLARE _seCocina BOOLEAN;

    # DECLARACION DE CURSOR PARA OBTENER DETALLE DE COMBO
    DEClARE cursorMenu CURSOR FOR 
		SELECT cd.idMenu, cd.cantidad, m.seCocina
        FROM comboDetalle AS cd
			JOIN menu AS m
				ON cd.idMenu = m.idMenu
                
        WHERE cd.idCombo = _idCombo;

	# SI YA NO HAY MAS DETALLE DE COMBO
    DECLARE CONTINUE HANDLER FOR NOT FOUND 
		SET finCursor = TRUE;

	OPEN cursorMenu;

	loopMenu: LOOP

		FETCH cursorMenu INTO _idMenu, _cantidad, _seCocina;
        
		IF finCursor THEN 
			LEAVE loopMenu;
		END IF;
        
        # MIENTRAS EXISTA CANTIDAD, SE AGREGA UNO A UNO
        WHILE _cantidad >= 1 DO
			INSERT INTO detalleOrdenMenu
				( idOrdenCliente, idMenu, cantidad, idEstadoDetalleOrden, idTipoServicio, usuario, usuarioResponsable, perteneceCombo, observacion )
			VALUES ( _idOrdenCliente, _idMenu, 1, IF( _seCocina, _idEstadoDetalleOrden, 3 ), _idTipoServicio, @usuario, _usuarioResponsable, 1, _observacion );
            
            SET @idDetalleOrdenMenu = LAST_INSERT_ID();
            
            INSERT INTO detalleComboMenu( idDetalleOrdenMenu, idDetalleOrdenCombo ) 
				VALUES ( @idDetalleOrdenMenu, _idDetalleOrdenCombo );
			
            SET _cantidad = _cantidad - 1;
        END WHILE;

	END LOOP loopMenu;

	CLOSE cursorMenu;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `_vmenucombo`
--

/*!50001 DROP VIEW IF EXISTS `_vmenucombo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `_vmenucombo` AS select `dcm`.`idDetalleOrdenMenu` AS `idDetalleOrdenMenu`,`dcm`.`idDetalleOrdenCombo` AS `idDetalleOrdenCombo`,`cp`.`precio` AS `precioCombo`,`c`.`combo` AS `combo`,`c`.`idCombo` AS `idCombo`,`c`.`imagen` AS `imagenCombo`,`doc`.`idEstadoDetalleOrden` AS `idEstadoDetalleOrdenCombo`,`doc`.`observacion` AS `observacionCombo` from (((`detallecombomenu` `dcm` join `detalleordencombo` `doc` on((`dcm`.`idDetalleOrdenCombo` = `doc`.`idDetalleOrdenCombo`))) join `combo` `c` on((`doc`.`idCombo` = `c`.`idCombo`))) join `comboprecio` `cp` on(((`doc`.`idTipoServicio` = `cp`.`idTipoServicio`) and (`doc`.`idCombo` = `cp`.`idCombo`)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstcombo`
--

/*!50001 DROP VIEW IF EXISTS `lstcombo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstcombo` AS select `c`.`idCombo` AS `idCombo`,`c`.`combo` AS `combo`,ifnull(`c`.`imagen`,'') AS `imagen`,`c`.`descripcion` AS `descripcion`,`em`.`idEstadoMenu` AS `idEstadoMenu`,`em`.`estadoMenu` AS `estadoMenu`,`c`.`codigo` AS `codigoCombo` from (`combo` `c` join `estadomenu` `em` on((`c`.`idEstadoMenu` = `em`.`idEstadoMenu`))) order by `c`.`top` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstcombodetalle`
--

/*!50001 DROP VIEW IF EXISTS `lstcombodetalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstcombodetalle` AS select `cd`.`idCombo` AS `idCombo`,`cd`.`cantidad` AS `cantidad`,`m`.`idMenu` AS `idMenu`,`m`.`menu` AS `menu`,`m`.`imagen` AS `imagen`,`m`.`descripcion` AS `descripcion`,`m`.`idEstadoMenu` AS `idEstadoMenu`,`m`.`estadoMenu` AS `estadoMenu` from (`combodetalle` `cd` join `lstmenu` `m` on((`m`.`idMenu` = `cd`.`idMenu`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstcomboprecio`
--

/*!50001 DROP VIEW IF EXISTS `lstcomboprecio`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstcomboprecio` AS select `cp`.`idCombo` AS `idCombo`,`cp`.`precio` AS `precio`,`ts`.`idTipoServicio` AS `idTipoServicio`,`ts`.`tipoServicio` AS `tipoServicio` from (`comboprecio` `cp` join `tiposervicio` `ts` on((`cp`.`idTipoServicio` = `ts`.`idTipoServicio`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstfacturacompra`
--

/*!50001 DROP VIEW IF EXISTS `lstfacturacompra`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstfacturacompra` AS select `fc`.`idFacturaCompra` AS `idFacturaCompra`,`fc`.`noFactura` AS `noFactura`,`fc`.`proveedor` AS `proveedor`,`fc`.`fechaFactura` AS `fechaFactura`,`fc`.`comentario` AS `comentario`,`ef`.`idEstadoFactura` AS `idEstadoFactura`,`ef`.`estadoFactura` AS `estadoFactura`,`fce`.`usuario` AS `usuario`,`fce`.`fechaRegistro` AS `fechaRegistro` from ((`facturacompra` `fc` join `facturacompraestado` `fce` on(((`fc`.`idFacturaCompra` = `fce`.`idFacturaCompra`) and (`fc`.`idEstadoFactura` = `fce`.`idEstadoFactura`)))) join `estadofactura` `ef` on((`fc`.`idEstadoFactura` = `ef`.`idEstadoFactura`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstingresoproducto`
--

/*!50001 DROP VIEW IF EXISTS `lstingresoproducto`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstingresoproducto` AS select `i`.`idIngreso` AS `idIngreso`,`i`.`idFacturaCompra` AS `idFacturaCompra`,`p`.`idProducto` AS `idProducto`,`p`.`producto` AS `producto`,`p`.`idMedida` AS `idMedida`,`p`.`medida` AS `medida`,`p`.`idTipoProducto` AS `idTipoProducto`,`p`.`tipoProducto` AS `tipoProducto`,`p`.`perecedero` AS `perecedero`,`p`.`cantidadMinima` AS `cantidadMinima`,`p`.`cantidadMaxima` AS `cantidadMaxima`,`p`.`disponibilidad` AS `disponibilidad`,`p`.`importante` AS `importante`,`i`.`cantidad` AS `cantidad`,`i`.`costo` AS `costo`,`i`.`usuario` AS `usuarioIngreso`,`i`.`fechaRegistro` AS `fechaIngreso` from (`lstproducto` `p` join `ingreso` `i` on((`i`.`idProducto` = `p`.`idProducto`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstmenu`
--

/*!50001 DROP VIEW IF EXISTS `lstmenu`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstmenu` AS select `m`.`idMenu` AS `idMenu`,`m`.`menu` AS `menu`,ifnull(`m`.`imagen`,'') AS `imagen`,`m`.`descripcion` AS `descripcion`,`em`.`idEstadoMenu` AS `idEstadoMenu`,`em`.`estadoMenu` AS `estadoMenu`,`dm`.`idDestinoMenu` AS `idDestinoMenu`,`dm`.`destinoMenu` AS `destinoMenu`,`tm`.`idTipoMenu` AS `idTipoMenu`,`tm`.`tipoMenu` AS `tipoMenu`,`m`.`codigo` AS `codigoMenu`,`m`.`tiempoAlerta` AS `tiempoAlerta`,`m`.`seCocina` AS `seCocina` from (((`menu` `m` join `estadomenu` `em` on((`em`.`idEstadoMenu` = `m`.`idEstadoMenu`))) join `destinomenu` `dm` on((`dm`.`idDestinoMenu` = `m`.`idDestinoMenu`))) join `tipomenu` `tm` on((`m`.`idTipoMenu` = `tm`.`idTipoMenu`))) order by `m`.`top` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstmenuprecio`
--

/*!50001 DROP VIEW IF EXISTS `lstmenuprecio`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstmenuprecio` AS select `mp`.`idMenu` AS `idMenu`,`mp`.`precio` AS `precio`,`ts`.`idTipoServicio` AS `idTipoServicio`,`ts`.`tipoServicio` AS `tipoServicio` from (`menuprecio` `mp` join `tiposervicio` `ts` on((`mp`.`idTipoServicio` = `ts`.`idTipoServicio`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstproducto`
--

/*!50001 DROP VIEW IF EXISTS `lstproducto`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstproducto` AS select `p`.`idProducto` AS `idProducto`,`p`.`producto` AS `producto`,`m`.`idMedida` AS `idMedida`,`m`.`medida` AS `medida`,`tp`.`idTipoProducto` AS `idTipoProducto`,`tp`.`tipoProducto` AS `tipoProducto`,`p`.`perecedero` AS `perecedero`,`p`.`cantidadMinima` AS `cantidadMinima`,`p`.`cantidadMaxima` AS `cantidadMaxima`,`p`.`disponibilidad` AS `disponibilidad`,`p`.`importante` AS `importante`,`u`.`idUbicacion` AS `idUbicacion`,`u`.`ubicacion` AS `ubicacion`,`p`.`usuario` AS `usuarioProducto`,`p`.`fechaRegistro` AS `fechaProducto` from (((`producto` `p` join `medida` `m` on((`m`.`idMedida` = `p`.`idMedida`))) join `tipoproducto` `tp` on((`tp`.`idTipoProducto` = `p`.`idTipoProducto`))) join `ubicacion` `u` on((`u`.`idUbicacion` = `p`.`idUbicacion`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstreajusteproducto`
--

/*!50001 DROP VIEW IF EXISTS `lstreajusteproducto`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstreajusteproducto` AS select `r`.`idReajuste` AS `idReajuste`,`p`.`idProducto` AS `idProducto`,`p`.`producto` AS `producto`,`p`.`idMedida` AS `idMedida`,`p`.`medida` AS `medida`,`p`.`idTipoProducto` AS `idTipoProducto`,`p`.`tipoProducto` AS `tipoProducto`,`p`.`perecedero` AS `perecedero`,`p`.`cantidadMinima` AS `cantidadMinima`,`p`.`cantidadMaxima` AS `cantidadMaxima`,`p`.`disponibilidad` AS `disponibilidad`,`p`.`importante` AS `importante`,`rp`.`cantidad` AS `cantidad`,`rp`.`esIncremento` AS `esIncremento`,`r`.`observacion` AS `observacion`,`r`.`usuario` AS `usuarioReajuste`,`r`.`fechaRegistro` AS `fechaReajuste` from ((`reajuste` `r` join `reajusteproducto` `rp` on((`r`.`idReajuste` = `rp`.`idReajuste`))) join `lstproducto` `p` on((`rp`.`idProducto` = `p`.`idProducto`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstreceta`
--

/*!50001 DROP VIEW IF EXISTS `lstreceta`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstreceta` AS select `r`.`idMenu` AS `idMenu`,`p`.`idProducto` AS `idProducto`,`p`.`producto` AS `producto`,`r`.`cantidad` AS `cantidad`,`p`.`medida` AS `medida`,`p`.`tipoProducto` AS `tipoProducto`,`r`.`observacion` AS `observacion` from (`receta` `r` join `lstproducto` `p` on((`p`.`idProducto` = `r`.`idProducto`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstsupercombo`
--

/*!50001 DROP VIEW IF EXISTS `lstsupercombo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstsupercombo` AS select `sc`.`idSuperCombo` AS `idSuperCombo`,`sc`.`superCombo` AS `superCombo`,ifnull(`sc`.`imagen`,'') AS `imagen`,`sc`.`descripcion` AS `descripcion`,`em`.`idEstadoMenu` AS `idEstadoMenu`,`em`.`estadoMenu` AS `estadoMenu`,`sc`.`codigo` AS `codigoSuperCombo` from (`supercombo` `sc` join `estadomenu` `em` on((`sc`.`idEstadoMenu` = `em`.`idEstadoMenu`))) order by `sc`.`top` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstsupercombodetalle`
--

/*!50001 DROP VIEW IF EXISTS `lstsupercombodetalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstsupercombodetalle` AS select `scd`.`idSuperCombo` AS `idSuperCombo`,`scd`.`cantidad` AS `cantidad`,`c`.`idCombo` AS `idCombo`,`c`.`combo` AS `combo`,`c`.`imagen` AS `imagen`,`c`.`descripcion` AS `descripcion`,`c`.`idEstadoMenu` AS `idEstadoMenu`,`c`.`estadoMenu` AS `estadoMenu` from (`supercombodetalle` `scd` join `lstcombo` `c` on((`c`.`idCombo` = `scd`.`idCombo`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `lstsupercomboprecio`
--

/*!50001 DROP VIEW IF EXISTS `lstsupercomboprecio`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `lstsupercomboprecio` AS select `scp`.`idSuperCombo` AS `idSuperCombo`,`scp`.`precio` AS `precio`,`ts`.`idTipoServicio` AS `idTipoServicio`,`ts`.`tipoServicio` AS `tipoServicio` from (`supercomboprecio` `scp` join `tiposervicio` `ts` on((`scp`.`idTipoServicio` = `ts`.`idTipoServicio`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vcuadreproducto`
--

/*!50001 DROP VIEW IF EXISTS `vcuadreproducto`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vcuadreproducto` AS select `cp`.`idCuadreProducto` AS `idCuadreProducto`,`cp`.`fechaCuadre` AS `fechaCuadre`,`cp`.`comentario` AS `comentario`,`cp`.`usuario` AS `usuario`,`cp`.`fechaRegistro` AS `fechaRegistroCuadre`,`cp`.`todos` AS `todos`,`u`.`idUbicacion` AS `idUbicacion`,`u`.`ubicacion` AS `ubicacion`,`ec`.`idEstadoCuadre` AS `idEstadoCuadre`,`ec`.`estadoCuadre` AS `estadoCuadre` from ((`cuadreproducto` `cp` join `estadocuadre` `ec` on((`ec`.`idEstadoCuadre` = `cp`.`idEstadoCuadre`))) join `ubicacion` `u` on((`u`.`idUbicacion` = `cp`.`idUbicacion`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vcuadreproductodetalle`
--

/*!50001 DROP VIEW IF EXISTS `vcuadreproductodetalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vcuadreproductodetalle` AS select `cp`.`idCuadreProducto` AS `idCuadreProducto`,`cp`.`fechaCuadre` AS `fechaCuadre`,`cp`.`comentario` AS `comentario`,`cp`.`usuario` AS `usuario`,`cp`.`fechaRegistroCuadre` AS `fechaRegistroCuadre`,`cpd`.`cantidadApertura` AS `cantidadApertura`,`cpd`.`cantidadCierre` AS `cantidadCierre`,`cpd`.`diferenciaApertura` AS `diferenciaApertura`,`cpd`.`diferenciaCierre` AS `diferenciaCierre`,`cpd`.`comentarioApertura` AS `comentarioApertura`,`cpd`.`comentarioCierre` AS `comentarioCierre`,`p`.`idProducto` AS `idProducto`,`p`.`producto` AS `producto`,`p`.`idMedida` AS `idMedida`,`p`.`medida` AS `medida`,`p`.`idTipoProducto` AS `idTipoProducto`,`p`.`tipoProducto` AS `tipoProducto`,`p`.`perecedero` AS `perecedero`,`p`.`cantidadMinima` AS `cantidadMinima`,`p`.`cantidadMaxima` AS `cantidadMaxima`,`p`.`disponibilidad` AS `disponibilidad`,`p`.`importante` AS `importante`,`p`.`idUbicacion` AS `idUbicacion`,`p`.`ubicacion` AS `ubicacion`,`p`.`usuarioProducto` AS `usuarioProducto`,`p`.`fechaProducto` AS `fechaProducto` from ((`vcuadreproducto` `cp` join `cuadreproductodetalle` `cpd` on((`cp`.`idCuadreProducto` = `cpd`.`idCuadreProducto`))) join `lstproducto` `p` on((`cpd`.`idProducto` = `p`.`idProducto`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vdenominacioncaja`
--

/*!50001 DROP VIEW IF EXISTS `vdenominacioncaja`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vdenominacioncaja` AS select `denominacioncaja`.`idCaja` AS `idCaja`,`denominacioncaja`.`idEstadoCaja` AS `idEstadoCaja`,`denominacioncaja`.`denominacion` AS `denominacion`,`denominacioncaja`.`cantidad` AS `cantidad`,(`denominacioncaja`.`denominacion` * `denominacioncaja`.`cantidad`) AS `monto` from `denominacioncaja` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vevento`
--

/*!50001 DROP VIEW IF EXISTS `vevento`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vevento` AS select `e`.`idEvento` AS `idEvento`,`e`.`evento` AS `evento`,`e`.`fechaEvento` AS `fechaEvento`,`s`.`idSalon` AS `idSalon`,`s`.`salon` AS `salon`,`e`.`horaInicio` AS `horaInicio`,`e`.`horaFinal` AS `horaFinal`,`e`.`observacion` AS `observacion`,`e`.`usuario` AS `usuario`,`e`.`fechaRegistro` AS `fechaRegistro`,`e`.`numeroPersonas` AS `numeroPersonas`,`ee`.`idEstadoEvento` AS `idEstadoEvento`,`ee`.`estadoEvento` AS `estadoEvento`,`c`.`idCliente` AS `idCliente`,`c`.`nit` AS `nit`,`c`.`nombre` AS `nombre`,`c`.`cui` AS `cui`,`c`.`correo` AS `correo`,`c`.`telefono` AS `telefono`,`c`.`direccion` AS `direccion`,`c`.`idTipoCliente` AS `idTipoCliente`,`c`.`tipoCliente` AS `tipoCliente`,`e`.`idFactura` AS `idFactura` from (((`evento` `e` join `vstcliente` `c` on((`e`.`idCliente` = `c`.`idCliente`))) join `estadoevento` `ee` on((`e`.`idEstadoEvento` = `ee`.`idEstadoEvento`))) join `salon` `s` on((`s`.`idSalon` = `e`.`idSalon`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vordencliente`
--

/*!50001 DROP VIEW IF EXISTS `vordencliente`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vordencliente` AS select `oc`.`idOrdenCliente` AS `idOrdenCliente`,`oc`.`numeroTicket` AS `numeroTicket`,`oc`.`usuarioPropietario` AS `usuarioPropietario`,`oc`.`usuarioResponsable` AS `usuarioResponsable`,`eo`.`idEstadoOrden` AS `idEstadoOrden`,`eo`.`estadoOrden` AS `estadoOrden`,`oc`.`fechaRegistro` AS `fechaRegistro`,`oc`.`numMenu` AS `numMenu`,`oc`.`numeroGrupo` AS `numeroGrupo` from (`ordencliente` `oc` join `estadoorden` `eo` on((`oc`.`idEstadoOrden` = `eo`.`idEstadoOrden`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vordenes`
--

/*!50001 DROP VIEW IF EXISTS `vordenes`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vordenes` AS select `dom`.`idDetalleOrdenMenu` AS `idDetalleOrdenMenu`,`oc`.`idOrdenCliente` AS `idOrdenCliente`,`oc`.`numeroTicket` AS `numeroTicket`,`oc`.`usuarioResponsable` AS `responsableOrden`,`oc`.`idEstadoOrden` AS `idEstadoOrden`,`oc`.`numeroGrupo` AS `numeroGrupo`,`dom`.`cantidad` AS `cantidad`,`m`.`idMenu` AS `idMenu`,`m`.`menu` AS `menu`,`m`.`codigo` AS `codigoMenu`,`m`.`tiempoAlerta` AS `tiempoAlerta`,`dom`.`perteneceCombo` AS `perteneceCombo`,`m`.`descripcion` AS `descripcion`,ifnull(`m`.`imagen`,'') AS `imagen`,`mp`.`precio` AS `precio`,`mc`.`idCombo` AS `idCombo`,`mc`.`combo` AS `combo`,`mc`.`imagenCombo` AS `imagenCombo`,`mc`.`precioCombo` AS `precioCombo`,`edo`.`idEstadoDetalleOrden` AS `idEstadoDetalleOrden`,`edo`.`estadoDetalleOrden` AS `estadoDetalleOrden`,`ts`.`idTipoServicio` AS `idTipoServicio`,`ts`.`tipoServicio` AS `tipoServicio`,`dm`.`idDestinoMenu` AS `idDestinoMenu`,`dm`.`destinoMenu` AS `destinoMenu`,`dom`.`usuarioResponsable` AS `responsableDetalle`,`dom`.`usuario` AS `usuarioDetalle`,`dom`.`observacion` AS `observacion`,`u`.`nombres` AS `nombres`,`u`.`codigo` AS `codigo`,`mc`.`idDetalleOrdenCombo` AS `idDetalleOrdenCombo`,`mc`.`idEstadoDetalleOrdenCombo` AS `idEstadoDetalleOrdenCombo`,`bom`.`fechaRegistro` AS `fechaRegistro`,`bom`.`usuario` AS `usuarioRegistro` from (((((((((`detalleordenmenu` `dom` join `menu` `m` on((`dom`.`idMenu` = `m`.`idMenu`))) join `estadodetalleorden` `edo` on((`dom`.`idEstadoDetalleOrden` = `edo`.`idEstadoDetalleOrden`))) join `tiposervicio` `ts` on((`dom`.`idTipoServicio` = `ts`.`idTipoServicio`))) join `usuario` `u` on((`dom`.`usuarioResponsable` = `u`.`usuario`))) join `bitacoraordenmenu` `bom` on(((`dom`.`idDetalleOrdenMenu` = `bom`.`idDetalleOrdenMenu`) and (`dom`.`idEstadoDetalleOrden` = `bom`.`idEstadoDetalleOrden`)))) join `destinomenu` `dm` on((`dm`.`idDestinoMenu` = `m`.`idDestinoMenu`))) join `ordencliente` `oc` on((`dom`.`idOrdenCliente` = `oc`.`idOrdenCliente`))) left join `menuprecio` `mp` on(((`m`.`idMenu` = `mp`.`idMenu`) and (`dom`.`idTipoServicio` = `mp`.`idTipoServicio`) and (not(`dom`.`perteneceCombo`))))) left join `_vmenucombo` `mc` on((`dom`.`idDetalleOrdenMenu` = `mc`.`idDetalleOrdenMenu`))) order by `dom`.`idDetalleOrdenMenu` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `votroservicio`
--

/*!50001 DROP VIEW IF EXISTS `votroservicio`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `votroservicio` AS select `otroservicio`.`idOtroServicio` AS `idOtroServicio`,`otroservicio`.`idEvento` AS `idEvento`,`otroservicio`.`otroServicio` AS `otroServicio`,`otroservicio`.`cantidad` AS `cantidad`,`otroservicio`.`precioUnitario` AS `precioUnitario`,`otroservicio`.`fechaRegistro` AS `fechaRegistro`,`otroservicio`.`usuario` AS `usuario` from `otroservicio` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vstcaja`
--

/*!50001 DROP VIEW IF EXISTS `vstcaja`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vstcaja` AS select `c`.`idCaja` AS `idCaja`,`c`.`usuario` AS `usuario`,`c`.`fechaApertura` AS `fechaApertura`,`c`.`efectivoInicial` AS `efectivoInicial`,`c`.`efectivoFinal` AS `efectivoFinal`,`c`.`efectivoSobrante` AS `efectivoSobrante`,`c`.`efectivoFaltante` AS `efectivoFaltante`,`ec`.`idEstadoCaja` AS `idEstadoCaja`,`ec`.`estadoCaja` AS `estadoCaja`,`u`.`nombres` AS `nombres`,`u`.`apellidos` AS `apellidos`,`u`.`codigo` AS `codigo` from ((`caja` `c` join `estadocaja` `ec` on((`c`.`idEstadoCaja` = `ec`.`idEstadoCaja`))) join `vusuario` `u` on((`c`.`usuario` = `u`.`usuario`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vstcliente`
--

/*!50001 DROP VIEW IF EXISTS `vstcliente`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vstcliente` AS select `c`.`idCliente` AS `idCliente`,`c`.`nit` AS `nit`,`c`.`nombre` AS `nombre`,`c`.`cui` AS `cui`,`c`.`correo` AS `correo`,`c`.`telefono` AS `telefono`,`c`.`direccion` AS `direccion`,`c`.`idTipoCliente` AS `idTipoCliente`,`tc`.`tipoCliente` AS `tipoCliente` from (`cliente` `c` join `tipocliente` `tc` on((`tc`.`idTipoCliente` = `c`.`idTipoCliente`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vstdetalleordenfactura`
--

/*!50001 DROP VIEW IF EXISTS `vstdetalleordenfactura`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vstdetalleordenfactura` AS select `dof`.`idFactura` AS `idFactura`,`mn`.`idOrdenCliente` AS `idOrdenCliente`,`mn`.`numeroTicket` AS `numeroTicket`,`mn`.`idDetalleOrdenMenu` AS `idDetalleOrdenMenu`,`mn`.`idMenu` AS `idMenu`,`mn`.`menu` AS `menu`,`mn`.`imagen` AS `imagen`,`mn`.`perteneceCombo` AS `perteneceCombo`,`mn`.`idDetalleOrdenCombo` AS `idDetalleOrdenCombo`,`mn`.`idCombo` AS `idCombo`,`mn`.`combo` AS `combo`,`mn`.`imagenCombo` AS `imagenCombo`,`mn`.`idTipoServicio` AS `idTipoServicio`,`mn`.`tipoServicio` AS `tipoServicio`,`mn`.`usuarioRegistro` AS `usuarioRegistro`,`dof`.`precioMenu` AS `precioMenu`,`dof`.`descuento` AS `descuento`,(`dof`.`precioMenu` - `dof`.`descuento`) AS `precioReal`,`dof`.`comentario` AS `comentario`,`dof`.`usuario` AS `usuarioFacturaDetalle`,`dof`.`fechaRegistro` AS `fechaFacturaDetalle` from (`vordenes` `mn` join `detalleordenfactura` `dof` on((((`mn`.`idDetalleOrdenMenu` is not null) and (`mn`.`idDetalleOrdenMenu` = `dof`.`idDetalleOrdenMenu`)) or ((`mn`.`idDetalleOrdenCombo` is not null) and (`mn`.`idDetalleOrdenCombo` = `dof`.`idDetalleOrdenCombo`))))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vstfactura`
--

/*!50001 DROP VIEW IF EXISTS `vstfactura`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vstfactura` AS select `f`.`idFactura` AS `idFactura`,`f`.`idCliente` AS `idCliente`,`c`.`nit` AS `nit`,`f`.`idCaja` AS `idCaja`,`f`.`nombre` AS `nombre`,`f`.`direccion` AS `direccion`,`f`.`total` AS `total`,`f`.`fechaFactura` AS `fechaFactura`,`f`.`usuario` AS `usuario`,`ef`.`idEstadoFactura` AS `idEstadoFactura`,`ef`.`estadoFactura` AS `estadoFactura`,`f`.`fechaRegistro` AS `fechaRegistro`,`f`.`descripcion` AS `descripcion`,if(length(`f`.`descripcion`),0,1) AS `siDetalle` from ((`factura` `f` join `vstcliente` `c` on((`c`.`idCliente` = `f`.`idCliente`))) join `estadofactura` `ef` on((`f`.`idEstadoFactura` = `ef`.`idEstadoFactura`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vstformapago`
--

/*!50001 DROP VIEW IF EXISTS `vstformapago`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vstformapago` AS select `ffp`.`idFactura` AS `idFactura`,`ffp`.`monto` AS `monto`,`fp`.`idFormaPago` AS `idFormaPago`,`fp`.`formaPago` AS `formaPago` from (`facturaformapago` `ffp` join `formapago` `fp` on((`ffp`.`idFormaPago` = `fp`.`idFormaPago`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vusuario`
--

/*!50001 DROP VIEW IF EXISTS `vusuario`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vusuario` AS select `u`.`usuario` AS `usuario`,`u`.`codigo` AS `codigo`,`u`.`nombres` AS `nombres`,`u`.`apellidos` AS `apellidos`,`u`.`fechaRegistro` AS `fechaRegistro`,`eu`.`idEstadoUsuario` AS `idEstadoUsuario`,`eu`.`estadoUsuario` AS `estadoUsuario`,`p`.`idPerfil` AS `idPerfil`,`p`.`perfil` AS `perfil`,`u`.`idDestinoMenu` AS `idDestinoMenu` from ((`usuario` `u` join `estadousuario` `eu` on((`u`.`idEstadoUsuario` = `eu`.`idEstadoUsuario`))) join `perfil` `p` on((`p`.`idPerfil` = `u`.`idPerfil`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-06-25 19:03:23
