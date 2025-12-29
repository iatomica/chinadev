-- MySQL dump 10.13  Distrib 5.7.33, for Linux (i686)
--
-- Host: localhost    Database: oswa_inv
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.16.04.1

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (39,'BICYCLE LIGHT'),(35,'BRA'),(7,'CHRISTMAS'),(38,'CICYCLE LIGHT'),(1,'DAILY USE ITEM'),(24,'ELECTRONIC'),(36,'EVA CARPET'),(22,'FURNITURE'),(20,'GIFT'),(37,'GLASS BOW'),(32,'HAIR ACCESORIES'),(6,'HARDWARE STORE'),(33,'JEWELLERY'),(5,'KITCHEN TOOLS'),(10,'LEATHER GOODS'),(2,'LIBRARY / SCHOOL'),(40,'MODEL'),(41,'NOCAT'),(21,'PERFUMERY AND CLEANING'),(34,'PLUSH'),(26,'SAMPLES'),(25,'SPORTS'),(9,'TEXTILE/FOOTWEAR/COSTUME'),(3,'TOYS');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientcode`
--

DROP TABLE IF EXISTS `clientcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientcode` varchar(45) DEFAULT NULL,
  `clients_id` int(11) NOT NULL,
  `products_id` int(11) unsigned NOT NULL,
  `openclose` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_clientcode_clients1_idx` (`clients_id`),
  KEY `fk_clientcode_products1_idx` (`products_id`),
  CONSTRAINT `fk_clientcode_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientcode`
--

LOCK TABLES `clientcode` WRITE;
/*!40000 ALTER TABLE `clientcode` DISABLE KEYS */;
INSERT INTO `clientcode` VALUES (3,'Producto1',3,4,1),(4,'Prueba',3,4,0),(5,'NuevoItem',3,23,1),(6,'adffdsss',3,25,1),(7,'erewr',3,26,1),(8,'asd',3,28,1),(9,'5001',3,29,1),(10,'600',3,29,1);
/*!40000 ALTER TABLE `clientcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  `contact` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `bank` varchar(45) DEFAULT NULL,
  `comission` int(11) DEFAULT '0',
  `openclose` tinyint(1) DEFAULT '1',
  `country` varchar(45) DEFAULT NULL,
  `taxpayer` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (3,'Empresa','empresa 551','Buenos aires','115666565688','a@a.a','asd','','',6,1,'Argentina','campo tax payer');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_movements`
--

DROP TABLE IF EXISTS `company_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `company_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observation` varchar(90) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `concept_company_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_movements_concept_company1_idx` (`concept_company_id`),
  CONSTRAINT `fk_company_movements_concept_company1` FOREIGN KEY (`concept_company_id`) REFERENCES `concept_company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_movements`
--

LOCK TABLES `company_movements` WRITE;
/*!40000 ALTER TABLE `company_movements` DISABLE KEYS */;
INSERT INTO `company_movements` VALUES (1,'aasd',5000.00,1,NULL),(2,'asdasd',4500.00,2,NULL),(3,'sdfsdf',4324.00,1,NULL),(4,'asdddasd',15000.00,1,NULL),(5,'asdasd',1600.00,1,NULL),(6,'gggfdfg',323232.00,1,'2022-11-14'),(7,'wsedfsdfsdf',6000000.00,1,'2022-11-14'),(8,'gfdgfgdh',45435.00,2,'2022-11-14');
/*!40000 ALTER TABLE `company_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `concept_company`
--

DROP TABLE IF EXISTS `concept_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `concept_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concept_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `concept_company`
--

LOCK TABLES `concept_company` WRITE;
/*!40000 ALTER TABLE `concept_company` DISABLE KEYS */;
INSERT INTO `concept_company` VALUES (1,'concepto1'),(2,'concepto2');
/*!40000 ALTER TABLE `concept_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `container_sales`
--

DROP TABLE IF EXISTS `container_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `container_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `sealn` varchar(45) DEFAULT NULL,
  `sales_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_container_sales_sales1_idx` (`sales_id`),
  CONSTRAINT `fk_container_sales_sales1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `container_sales`
--

LOCK TABLES `container_sales` WRITE;
/*!40000 ALTER TABLE `container_sales` DISABLE KEYS */;
INSERT INTO `container_sales` VALUES (3,'15551155','21314123',10);
/*!40000 ALTER TABLE `container_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currentaccount`
--

DROP TABLE IF EXISTS `currentaccount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currentaccount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `credit` decimal(20,2) DEFAULT NULL,
  `debit` decimal(20,2) DEFAULT NULL,
  `vendors_id` int(11) DEFAULT NULL,
  `clients_id` int(11) DEFAULT NULL,
  `receivedgoods_id` int(11) DEFAULT NULL,
  `currentaccount_type_id` int(11) DEFAULT NULL,
  `balance` decimal(20,2) DEFAULT NULL,
  `date` date NOT NULL,
  `sales_id` int(11) unsigned DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_currentaccount_vendors_idx` (`vendors_id`),
  KEY `fk_currentaccount_clients1_idx` (`clients_id`),
  KEY `fk_currentaccount_receivedgoods1_idx` (`receivedgoods_id`),
  KEY `fk_currentaccount_currentaccount_type1_idx` (`currentaccount_type_id`),
  KEY `fk_currentaccount_sales1_idx` (`sales_id`),
  CONSTRAINT `fk_currentaccount_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_currentaccount_currentaccount_type1` FOREIGN KEY (`currentaccount_type_id`) REFERENCES `currentaccount_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_currentaccount_receivedgoods1` FOREIGN KEY (`receivedgoods_id`) REFERENCES `receivedgoods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_currentaccount_sales1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_currentaccount_vendors` FOREIGN KEY (`vendors_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currentaccount`
--

LOCK TABLES `currentaccount` WRITE;
/*!40000 ALTER TABLE `currentaccount` DISABLE KEYS */;
INSERT INTO `currentaccount` VALUES (17,'NULL','NULL',4140.00,0.00,3,NULL,15,4,-4140.00,'2022-09-28',NULL,'2022-09-30'),(18,'NULL','NULL',0.00,3900.80,NULL,3,NULL,5,3900.80,'2022-09-28',10,NULL),(19,'NULL','NULL',460.00,0.00,3,NULL,18,4,-4600.00,'2022-11-02',NULL,'2022-11-26'),(20,'NULL','NULL',4600.00,0.00,3,NULL,19,4,-9200.00,'2022-11-02',NULL,'2022-11-12');
/*!40000 ALTER TABLE `currentaccount` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currentaccount_type`
--

DROP TABLE IF EXISTS `currentaccount_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currentaccount_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currentaccount_type`
--

LOCK TABLES `currentaccount_type` WRITE;
/*!40000 ALTER TABLE `currentaccount_type` DISABLE KEYS */;
INSERT INTO `currentaccount_type` VALUES (1,'Payment'),(2,'Debitnote'),(3,'Creditnote'),(4,'Receivedgoods'),(5,'Sales');
/*!40000 ALTER TABLE `currentaccount_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_po`
--

DROP TABLE IF EXISTS `detail_po`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_po` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientcode_po` varchar(45) DEFAULT NULL,
  `desc_pr_po` varchar(45) DEFAULT NULL,
  `uxb_po` int(11) DEFAULT '0',
  `price_po` decimal(10,2) DEFAULT '0.00',
  `cbm_po` decimal(10,4) DEFAULT '0.0000',
  `qty_po` int(20) DEFAULT '0',
  `gw` decimal(10,2) DEFAULT '0.00',
  `nw` decimal(10,2) DEFAULT '0.00',
  `ctn` int(11) DEFAULT '0',
  `cbm_total` decimal(10,4) DEFAULT '0.0000',
  `gw_total` decimal(10,2) DEFAULT '0.00',
  `nw_total` decimal(10,2) DEFAULT '0.00',
  `price_total` decimal(10,2) DEFAULT '0.00',
  `eta` date DEFAULT NULL,
  `purchaseorder_id` int(11) NOT NULL,
  `products_id` int(11) unsigned NOT NULL,
  `finalized` tinyint(1) DEFAULT NULL,
  `pendent` int(30) DEFAULT NULL,
  `volume` int(30) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_detail_po_purchaseorder1_idx` (`purchaseorder_id`),
  KEY `fk_detail_po_products1_idx` (`products_id`),
  CONSTRAINT `fk_detail_po_purchaseorder1` FOREIGN KEY (`purchaseorder_id`) REFERENCES `purchaseorder` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_po`
--

LOCK TABLES `detail_po` WRITE;
/*!40000 ALTER TABLE `detail_po` DISABLE KEYS */;
INSERT INTO `detail_po` VALUES (8,'Producto1','Prueeba',20,2.30,0.6800,5000,3.70,2.80,250,170.0000,925.00,700.00,11500.00,'2022-09-30',7,4,1,1000,0);
/*!40000 ALTER TABLE `detail_po` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_rg`
--

DROP TABLE IF EXISTS `detail_rg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_rg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientcode_rg` varchar(45) DEFAULT NULL,
  `desc_pr_rg` varchar(45) DEFAULT NULL,
  `uxb_rg` int(11) DEFAULT '0',
  `price_rg` decimal(10,2) DEFAULT '0.00',
  `cbm_rg` decimal(10,4) DEFAULT '0.0000',
  `qty_rg` int(20) DEFAULT '0',
  `gw` decimal(10,2) DEFAULT '0.00',
  `nw` decimal(10,2) DEFAULT '0.00',
  `ctn` int(11) DEFAULT '0',
  `cbm_total` decimal(10,4) DEFAULT '0.0000',
  `gw_total` decimal(10,2) DEFAULT '0.00',
  `nw_total` decimal(10,2) DEFAULT '0.00',
  `price_total` decimal(10,2) DEFAULT '0.00',
  `purchaseorder_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `finalized` tinyint(1) DEFAULT NULL,
  `receivedgoods_id` int(11) NOT NULL,
  `pendent` int(20) DEFAULT NULL,
  `invoiced` tinyint(1) DEFAULT NULL,
  `volume` int(30) DEFAULT '0',
  `partial_invoice` int(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detail_rg_purchaseorder1_idx` (`purchaseorder_id`),
  KEY `fk_detail_rg_products1_idx` (`products_id`),
  KEY `fk_detail_rg_receivedgoods1_idx` (`receivedgoods_id`),
  CONSTRAINT `fk_detail_rg_purchaseorder1` FOREIGN KEY (`purchaseorder_id`) REFERENCES `purchaseorder` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_detail_rg_receivedgoods1` FOREIGN KEY (`receivedgoods_id`) REFERENCES `receivedgoods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_rg`
--

LOCK TABLES `detail_rg` WRITE;
/*!40000 ALTER TABLE `detail_rg` DISABLE KEYS */;
INSERT INTO `detail_rg` VALUES (12,'Producto1 ','Prueeba',20,2.30,0.6800,1800,3.70,2.80,90,61.2000,333.00,252.00,4140.00,7,4,1,15,NULL,3,0,200),(13,'Producto1 ','Prueeba',20,2.30,0.6800,200,3.70,2.80,10,6.8000,37.00,28.00,460.00,7,4,1,18,NULL,0,0,NULL),(14,'Producto1 ','Prueeba',40,2.30,0.6800,2000,3.70,2.80,50,34.0000,185.00,140.00,4600.00,7,4,1,19,NULL,0,0,NULL);
/*!40000 ALTER TABLE `detail_rg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_sales`
--

DROP TABLE IF EXISTS `detail_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientcode_sales` varchar(45) DEFAULT NULL,
  `desc_pr_sales` varchar(90) DEFAULT NULL,
  `uxb_sales` int(11) DEFAULT NULL,
  `price_sales` decimal(10,2) DEFAULT NULL,
  `cbm_sales` decimal(10,4) DEFAULT NULL,
  `qty_sales` int(20) DEFAULT NULL,
  `gw_sales` decimal(10,2) DEFAULT NULL,
  `nw_sales` decimal(10,2) DEFAULT NULL,
  `ctn_sales` decimal(10,2) DEFAULT NULL,
  `cbm_total_sales` decimal(10,2) DEFAULT NULL,
  `gw_total_sales` decimal(10,2) DEFAULT NULL,
  `nw_total_sales` decimal(10,2) DEFAULT NULL,
  `price_total_sales` decimal(20,2) DEFAULT NULL,
  `products_id` int(11) NOT NULL,
  `sales_id` int(11) unsigned NOT NULL,
  `tp` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detail_sales_products_idx` (`products_id`),
  KEY `fk_detail_sales_sales1_idx` (`sales_id`),
  CONSTRAINT `fk_detail_sales_sales1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_sales`
--

LOCK TABLES `detail_sales` WRITE;
/*!40000 ALTER TABLE `detail_sales` DISABLE KEYS */;
INSERT INTO `detail_sales` VALUES (11,'Producto1 ','Prueeba',20,2.30,0.6800,1600,3.70,2.80,80.00,54.40,296.00,224.00,3680.00,4,10,'7');
/*!40000 ALTER TABLE `detail_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detail_sm`
--

DROP TABLE IF EXISTS `detail_sm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_sm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc_pr_sm` varchar(100) DEFAULT NULL,
  `clientcode_sm` varchar(45) DEFAULT NULL,
  `qty_sm` int(11) DEFAULT '0',
  `products_id` int(11) NOT NULL,
  `stockmovements_id` int(11) NOT NULL,
  `finalized` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_detail_sm_products1_idx` (`products_id`),
  KEY `fk_detail_sm_stockmovements1_idx` (`stockmovements_id`),
  CONSTRAINT `fk_detail_sm_stockmovements1` FOREIGN KEY (`stockmovements_id`) REFERENCES `stockmovements` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_sm`
--

LOCK TABLES `detail_sm` WRITE;
/*!40000 ALTER TABLE `detail_sm` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_sm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (3,'4.jpg','image/jpeg'),(6,'25.jpg','image/jpeg'),(34,'29.jpg','image/jpeg');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `moneys`
--

DROP TABLE IF EXISTS `moneys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `moneys` (
  `id` int(11) NOT NULL,
  `moneytype` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `moneytype` (`moneytype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `moneys`
--

LOCK TABLES `moneys` WRITE;
/*!40000 ALTER TABLE `moneys` DISABLE KEYS */;
INSERT INTO `moneys` VALUES (0,'NO MONEY'),(2,'RMB'),(1,'USD');
/*!40000 ALTER TABLE `moneys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movements_type`
--

DROP TABLE IF EXISTS `movements_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movements_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movementtype` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movements_type`
--

LOCK TABLES `movements_type` WRITE;
/*!40000 ALTER TABLE `movements_type` DISABLE KEYS */;
INSERT INTO `movements_type` VALUES (1,'Adjustment'),(2,'Deposit to deopsit');
/*!40000 ALTER TABLE `movements_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packaging`
--

DROP TABLE IF EXISTS `packaging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `packaging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `packagingtype` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5373 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packaging`
--

LOCK TABLES `packaging` WRITE;
/*!40000 ALTER TABLE `packaging` DISABLE KEYS */;
INSERT INTO `packaging` VALUES (0,'NOPACK'),(1,'OPP BAG'),(2,'OPP'),(3,'OPP W/HEADER'),(4,'BLISTER'),(5,'DOUBLE BLISTER'),(6,'BROWN BOX'),(7,'BULK'),(8,'EGG TRAY'),(9,'WHITE BOX'),(10,'FLOWER BOX'),(11,'COLOR BOX'),(12,'WINDOW BOX'),(13,'NET BAG'),(14,'HANG TAG'),(15,'TIE CARD'),(16,'PVC BOX'),(17,'SHOWBOX'),(19,'SHRINK PACKING'),(20,'12PCS/HANG TAG+OPP BAG'),(21,'PAPER PACK'),(22,'12PCS/OPP BAG'),(23,'4PCS/OPP BAG'),(24,'10PCS/OPP BAG'),(25,'12PCS/BROWN BOX'),(26,'PVC BAG'),(27,'PAPER BOX'),(28,'4 CORNER PAPER'),(29,'SHRINK+PLASTIC BOX'),(30,'6PCS/OPP +HEADER'),(31,'EXPORT CARTON'),(32,'6PCS/BROWN BOX'),(33,'PP BAG'),(34,'BLISTER CARD'),(35,'SLID CARD'),(36,'8PCS/BROWN BOX'),(37,'2PCS/COLOR BOX'),(38,'BUBBLE+COLOR BOX'),(39,'24PCS/HANG CARD+OPP BAG'),(40,'OPP+TIE CARD'),(41,'BUBBLE+WHITE BOX'),(42,'20PCS/OPP BAG'),(43,'OPP+WHITE BOX'),(44,'10PCS CARD +OPP'),(45,'6PCS/WHITE BOX'),(46,'12PCS/PLASTIC BOX'),(47,'24PCS/COLOR BOX'),(48,'2PCS/OPP BAG'),(49,'12PCS/WHITE BOX'),(50,'100PCS/OPP'),(52,'6PCS/COLOR BOX'),(5350,'50PCS/BAG'),(5351,'DISPLAY BOX'),(5352,'4 PCS/BRONW BOX'),(5353,'BUBBLE BAG'),(5354,'4PCS/WHITE'),(5355,'CLOSED IN OPP BAG'),(5356,'PE BAG'),(5357,'8PCS/SHRINK'),(5358,'SHRINK+WINDOW BOX'),(5359,'PP COVER'),(5360,'50 PCS/OPP BAG'),(5361,'50/OPP BAG'),(5362,'OPP+COLOR PAPER'),(5363,'6/OPP'),(5364,'10PCS/BROWN BOX'),(5365,'12PCS WHITE BOX'),(5366,'WINDOW COLOR BOX'),(5367,'24PCS/OPP'),(5368,'CLOTH BAG'),(5369,'3PCS/BROWN BOX'),(5370,'PLASTIC CARD'),(5371,'5 PCS /PAPER CARD'),(5372,'4PCSC/OPP');
/*!40000 ALTER TABLE `packaging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `price_type`
--

DROP TABLE IF EXISTS `price_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `price_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_type`
--

LOCK TABLES `price_type` WRITE;
/*!40000 ALTER TABLE `price_type` DISABLE KEYS */;
INSERT INTO `price_type` VALUES (1,'E.X.W'),(2,'F.O.B');
/*!40000 ALTER TABLE `price_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc_english` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT '2001-01-01 00:00:00',
  `code` int(11) DEFAULT '0',
  `moneys_id` int(11) DEFAULT NULL,
  `desc_spanish` varchar(45) DEFAULT NULL,
  `desc_chinese` varchar(45) DEFAULT NULL,
  `desc_portuguese` varchar(45) DEFAULT NULL,
  `color` varchar(45) DEFAULT NULL,
  `material` varchar(45) DEFAULT NULL,
  `size` varchar(45) DEFAULT NULL,
  `cbm` decimal(10,4) DEFAULT NULL,
  `openclose` tinyint(1) NOT NULL DEFAULT '1',
  `uxb` int(11) DEFAULT NULL,
  `inners` int(11) DEFAULT NULL,
  `units_id` int(11) DEFAULT NULL,
  `packaging_id` int(11) DEFAULT NULL,
  `ean13` int(50) DEFAULT NULL,
  `dun14` int(50) DEFAULT NULL,
  `price_type_id` int(11) DEFAULT NULL,
  `netweight` decimal(10,2) DEFAULT NULL,
  `grossweight` decimal(10,2) DEFAULT NULL,
  `volume` int(30) DEFAULT NULL,
  `product_weight` int(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_id` (`media_id`),
  KEY `fk_products_moneys1_idx` (`moneys_id`),
  KEY `fk_products_units1_idx` (`units_id`),
  KEY `fk_products_packaging1_idx` (`packaging_id`),
  KEY `fk_products_price_type1_idx` (`price_type_id`),
  CONSTRAINT `fk_products_moneys1` FOREIGN KEY (`moneys_id`) REFERENCES `moneys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_packaging1` FOREIGN KEY (`packaging_id`) REFERENCES `packaging` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_price_type1` FOREIGN KEY (`price_type_id`) REFERENCES `price_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_units1` FOREIGN KEY (`units_id`) REFERENCES `units` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (4,'Prueeba',2.30,3,'2001-01-01 00:00:00',0,2,'','','','','wood','20x50',0.6800,1,40,60,1,1,444555666,445456566,1,2.80,3.70,0,500),(23,NULL,NULL,0,'2001-01-01 00:00:00',0,0,NULL,NULL,NULL,NULL,'1',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(24,NULL,NULL,0,'2001-01-01 00:00:00',0,0,NULL,NULL,NULL,NULL,'1',NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(25,'sdfdsf',0.00,6,'2001-01-01 00:00:00',0,2,'','','','red','asd','20x20',1.0000,1,20,15,1,3,123123123,123123123,1,1.50,6.50,0,6),(26,NULL,NULL,0,'2001-01-01 00:00:00',0,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(27,NULL,NULL,0,'2001-01-01 00:00:00',0,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(28,NULL,NULL,0,'2001-01-01 00:00:00',0,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(29,'Prueba foto',5.00,34,'2001-01-01 00:00:00',0,2,'Prueba','asd','asd','color','wood','20x80',0.6000,1,50,800,2,1,155566556,5898998,1,600.00,700.00,0,500);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productscatsub`
--

DROP TABLE IF EXISTS `productscatsub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productscatsub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `products_id` int(11) NOT NULL,
  `categories_id` int(11) unsigned NOT NULL DEFAULT '0',
  `subcategories_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_productscatsub_products_idx` (`products_id`),
  KEY `fk_productscatsub_categories1_idx` (`categories_id`),
  KEY `fk_productscatsub_subcategories1_idx` (`subcategories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productscatsub`
--

LOCK TABLES `productscatsub` WRITE;
/*!40000 ALTER TABLE `productscatsub` DISABLE KEYS */;
INSERT INTO `productscatsub` VALUES (1,2,0,0),(2,1,39,0),(3,2,0,0),(4,3,0,0),(5,4,7,325);
/*!40000 ALTER TABLE `productscatsub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchaseorder`
--

DROP TABLE IF EXISTS `purchaseorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchaseorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `tp` varchar(30) DEFAULT NULL,
  `tpname` varchar(45) DEFAULT NULL,
  `observation` varchar(80) DEFAULT NULL,
  `openclose` tinyint(4) DEFAULT NULL,
  `finalized` tinyint(4) DEFAULT NULL,
  `clients_id` int(11) NOT NULL,
  `moneys_id` int(11) NOT NULL,
  `total_fob` int(50) DEFAULT '0',
  `vendors_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_buyorder_clients_idx` (`clients_id`),
  KEY `fk_buyorder_moneys1_idx` (`moneys_id`),
  KEY `fk_purchaseorder_vendors1` (`vendors_id`),
  CONSTRAINT `fk_buyorder_clients` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_buyorder_moneys1` FOREIGN KEY (`moneys_id`) REFERENCES `moneys` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_purchaseorder_vendors1` FOREIGN KEY (`vendors_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchaseorder`
--

LOCK TABLES `purchaseorder` WRITE;
/*!40000 ALTER TABLE `purchaseorder` DISABLE KEYS */;
INSERT INTO `purchaseorder` VALUES (7,'2022-09-28','963','asd','asdasd',0,1,3,2,0,3);
/*!40000 ALTER TABLE `purchaseorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `receivedgoods`
--

DROP TABLE IF EXISTS `receivedgoods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `receivedgoods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `observation` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `openclose` tinyint(1) DEFAULT NULL,
  `total_price` int(50) DEFAULT NULL,
  `finalized` tinyint(1) DEFAULT NULL,
  `vendors_id` int(11) NOT NULL,
  `stock_deposit_id` int(11) NOT NULL,
  `numbers` varchar(45) DEFAULT NULL,
  `clients_id` int(11) NOT NULL,
  `paycondition` varchar(100) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_receivedgoods_vendors1_idx` (`vendors_id`),
  KEY `fk_receivedgoods_stock_deposit1_idx` (`stock_deposit_id`),
  KEY `fk_receivedgoods_clients1` (`clients_id`),
  CONSTRAINT `fk_receivedgoods_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receivedgoods_stock_deposit1` FOREIGN KEY (`stock_deposit_id`) REFERENCES `stock_deposit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_receivedgoods_vendors1` FOREIGN KEY (`vendors_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receivedgoods`
--

LOCK TABLES `receivedgoods` WRITE;
/*!40000 ALTER TABLE `receivedgoods` DISABLE KEYS */;
INSERT INTO `receivedgoods` VALUES (15,'asdasdd','2022-09-23',1,4140,1,3,1,'863636',3,'asdasd','2022-09-30'),(16,'asdasd','2022-11-03',0,0,0,3,1,'569333',3,'8 days','2022-11-12'),(17,'asdasd','2022-11-04',0,0,0,3,1,'34443',3,'90 days','2022-11-19'),(18,'asd','2022-11-04',1,460,1,3,1,'9635241',3,'6 days','2022-11-26'),(19,'asdasd','2022-11-10',1,4600,1,3,1,'78777',3,'8 days','2022-11-12');
/*!40000 ALTER TABLE `receivedgoods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `totalprice_rmb` decimal(25,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `totalprice_usd` decimal(25,2) DEFAULT NULL,
  `currencyrate` decimal(25,4) DEFAULT NULL,
  `numbers` varchar(70) DEFAULT NULL,
  `clients_id` int(11) NOT NULL,
  `observation` varchar(100) DEFAULT NULL,
  `stock_deposit_id` int(11) NOT NULL,
  `comission` int(11) DEFAULT NULL,
  `finalized` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sales_clients1_idx` (`clients_id`),
  KEY `fk_sales_stock_deposit1_idx` (`stock_deposit_id`),
  CONSTRAINT `fk_sales_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_sales_stock_deposit1` FOREIGN KEY (`stock_deposit_id`) REFERENCES `stock_deposit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (10,3900.80,'2022-09-28',696.57,5.6000,'198766',3,'adasdasd',1,6,1),(11,0.00,'2022-11-02',0.00,3.6000,'899898',3,'fdsfsdf',1,6,NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_cost`
--

DROP TABLE IF EXISTS `shipping_cost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_cost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) unsigned NOT NULL,
  `concept` varchar(45) DEFAULT NULL,
  `currencyrate` decimal(25,4) DEFAULT NULL,
  `price_rmb` decimal(25,2) DEFAULT NULL,
  `price_usd` decimal(25,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_shipping_cost_sales1_idx` (`sales_id`),
  CONSTRAINT `fk_shipping_cost_sales1` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_cost`
--

LOCK TABLES `shipping_cost` WRITE;
/*!40000 ALTER TABLE `shipping_cost` DISABLE KEYS */;
INSERT INTO `shipping_cost` VALUES (19,10,'SENYE FEE',5.6000,6000.00,1071.43),(20,10,'DELFIN FEE',5.6000,8000.00,1428.57),(21,10,'WAREHOUSE FEE',5.6000,9000.00,1607.14),(22,10,'LOADING FEE',5.6000,0.00,0.00),(23,10,'INVOICE LEGALIZE COST',5.6000,0.00,0.00),(24,10,'PRICE LIST LEGALIZE COST',5.6000,0.00,0.00),(25,10,'EXPO DECLARATION COST',5.6000,0.00,0.00),(26,10,'CO LEGALIZE COST',5.6000,0.00,0.00);
/*!40000 ALTER TABLE `shipping_cost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qty` int(11) DEFAULT '0',
  `stock_deposit_id` int(11) NOT NULL DEFAULT '0',
  `products_id` int(11) DEFAULT NULL,
  `clients_id` int(11) NOT NULL,
  `codeclient` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stock_stock_deposit_idx` (`stock_deposit_id`),
  KEY `fk_stock_products1_idx` (`products_id`),
  KEY `fk_stock_clients1_idx` (`clients_id`),
  CONSTRAINT `fk_stock_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stock_stock_deposit` FOREIGN KEY (`stock_deposit_id`) REFERENCES `stock_deposit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` VALUES (3,2400,1,4,3,'Producto1'),(4,0,1,4,3,'BE-asdaas'),(5,0,1,4,3,'DE4444'),(6,0,1,4,3,'gaaaaaaa'),(7,0,1,4,3,'ddsdddd'),(8,0,1,4,3,'Prueba'),(9,0,1,23,3,'NuevoItem'),(10,0,1,25,3,'adffdsss'),(11,0,1,26,3,'erewr'),(12,0,1,28,3,'asd'),(13,0,1,29,3,'5001'),(14,0,1,29,3,'600');
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_deposit`
--

DROP TABLE IF EXISTS `stock_deposit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depositname` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_deposit`
--

LOCK TABLES `stock_deposit` WRITE;
/*!40000 ALTER TABLE `stock_deposit` DISABLE KEYS */;
INSERT INTO `stock_deposit` VALUES (1,'1','Desposit 1'),(2,'2','Desposit 2'),(3,'3','Desposit 3'),(4,'4','Desposit 4'),(5,'5','Desposit 5'),(6,'6','Desposit 6'),(7,'0','Deposit 0');
/*!40000 ALTER TABLE `stock_deposit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stockmovements`
--

DROP TABLE IF EXISTS `stockmovements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stockmovements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT NULL,
  `observation` varchar(100) DEFAULT NULL,
  `finalized` tinyint(1) DEFAULT NULL,
  `movements_type_id` int(11) NOT NULL,
  `stock_deposit_id` int(11) NOT NULL,
  `stock_deposit_to_id` int(11) DEFAULT NULL,
  `clients_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_stockmovements_movements_type_idx` (`movements_type_id`),
  KEY `fk_stockmovements_stock_deposit1_idx` (`stock_deposit_id`),
  KEY `fk_stockmovements_stock_deposit2_idx` (`stock_deposit_to_id`),
  KEY `fk_stockmovements_clients1_idx` (`clients_id`),
  CONSTRAINT `fk_stockmovements_clients1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stockmovements_movements_type` FOREIGN KEY (`movements_type_id`) REFERENCES `movements_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stockmovements_stock_deposit1` FOREIGN KEY (`stock_deposit_id`) REFERENCES `stock_deposit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_stockmovements_stock_deposit2` FOREIGN KEY (`stock_deposit_to_id`) REFERENCES `stock_deposit` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stockmovements`
--

LOCK TABLES `stockmovements` WRITE;
/*!40000 ALTER TABLE `stockmovements` DISABLE KEYS */;
/*!40000 ALTER TABLE `stockmovements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `categories_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_subcategories_categories1_idx` (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (0,'NOSUBCAT',2),(1,'SLEEPING MAT',1),(2,'MEMO',2),(3,'PENCIL',2),(4,'PEN',2),(5,'NOTEBOOK',2),(6,'RULER',2),(7,'PUNCH',2),(8,'MARKER PEN',2),(9,'PENCIL BAG',2),(10,'TAPE AND TAPE HOLDER',2),(11,'PENCIL BOX',2),(12,'PORTFOLIO',2),(13,'DIARY',2),(14,'GLUE',2),(15,'DIARY W/ACCES.',2),(16,'CORRECTION PEN',2),(17,'WATER COLOUR',2),(18,'CRAYONS',2),(19,'SHARPENER',2),(20,'STAPLER',2),(21,'HOLDER CARD',2),(22,'DESK ORGANIZER',2),(23,'ELASTIC BAND AND HOOK',2),(24,'MAGNIFIER',2),(25,'CUTTER',2),(26,'LIBRARY SET',2),(27,'STATIONARY(COMPAS)',2),(28,'MUD',2),(29,'SCISSORS',2),(30,'STICKERS',2),(31,'ERASER',2),(32,'COLOUR PENCIL',2),(33,'PAINTBRUSH',2),(34,'MOUSE',2),(35,'FIBRAS',2),(36,'ENVELOPE',2),(37,'REPUESTOS DE HOJAS',2),(38,'BOW AND GIFT PAPER',2),(39,'STAMP',2),(40,'WRITING BOARD',2),(41,'CHESS',2),(42,'BELLEZA',3),(43,'ROMPECABEZAS',3),(44,'BLOQUES',3),(45,'MUEBLE',3),(46,'PISTOLA',3),(47,'MU¥ECA',3),(48,'MU¥ECO CON ACCESORIOS',3),(49,'PESCAMAGIC',3),(50,'JUEGOS DE MESA',3),(51,'NAIPES Y DADOS',3),(52,'PLAYA',3),(53,'GUERRA',3),(54,'JUEGO DE JARDIN',3),(55,'INSTRUMENTO MUSICAL',3),(56,'TELEFONOS CELULARES',3),(57,'DISFRASES',3),(58,'PISTAS',3),(59,'PELOTA',3),(60,'YO-YO',3),(61,'CARRITO PARA BEBE',3),(62,'ESPADA',3),(63,'BINOCULARES',3),(64,'CUNERO MUSICAL',3),(65,'SOGA PARA SALTAR',3),(66,'ARCO Y FLECHA',3),(67,'ANIMALES',3),(68,'DOCTOR',3),(69,'PIZARRA',3),(70,'DIDACTICOS',3),(71,'PISTOLA DE AGUA',3),(72,'TROMPOS',3),(73,'TITERES Y MARIONETAS (NO PELUCHE)',3),(74,'MOTO',3),(75,'HELICOPTERO',3),(76,'MU¥ECO',3),(77,'TREN',3),(78,'ROBOT',3),(79,'WALKIE TALKIE',3),(80,'BATERIA DE COCINA',3),(81,'ZAPATOS',3),(82,'BA¥ERA',3),(83,'CANICAS',3),(84,'DE OSCURIDAD',3),(85,'PISTOLAS LANZADORAS',3),(86,'ROPA PARA MU¥ECAS',3),(87,'AVION',3),(88,'AUTO',3),(89,'AUTO SET',3),(90,'BICICLETA',3),(91,'BURBUJERO',3),(92,'BUS',3),(93,'CAMIONES',3),(94,'CAMIONETA Y 4 X 4',3),(95,'RODADOS CON TRAILER',3),(96,'CONSTRUCCION',3),(97,'EMBARCACIONES',3),(98,'HERRAMIENTAS',3),(99,'TIRO AL BLANCO',3),(100,'TRICICLO Y CUATRICICLO',3),(101,'SONAJERO',3),(102,'TRACTOR',3),(103,'VAJILLA',3),(104,'TANQUES',3),(105,'TRANVIA',3),(106,'MOTO DE AGUA',3),(107,'COHETE',3),(108,'ARRASTRES',3),(109,'PELOTA SOFT',3),(110,'BASKET',3),(111,'CASITA PLEGABLE',3),(112,'MAQUILLAJE',3),(113,'FLIPER Y JUEGOS CON AGUA',3),(114,'CA¥A Y PECES',3),(115,'ELECTRODOMESTICOS',3),(116,'LANZADOR DE AVIONES Y HELICOPTEROS',3),(117,'LANZA AGUA',3),(118,'BOX',3),(119,'PARA ARMAR COLLARES',3),(120,'COTILLON',3),(121,'PING PONG',3),(122,'CHIFLE',3),(123,'BEBE ACCESORIOS',3),(124,'MANO EXTENSIBLE',3),(125,'BASEBALL',3),(126,'PIRATAS',3),(127,'LIMPIEZA',3),(128,'MOLDE PARA MASA',3),(129,'BOWLING',3),(130,'CARRITO DE SUPER',3),(131,'GIMNASIO',3),(132,'MAGNETICO',3),(133,'CUBO MAGICO',3),(134,'RESORTE COLORIDO',3),(135,'MU¥ECAS DE TRAPO',3),(136,'AUTO F1 - CARTING',3),(137,'MU¥ECA CON ACCESORIO',3),(138,'NINJA',3),(139,'BALLESTA',3),(140,'ESTACION DE SERVICIO',3),(141,'VARITAS',3),(142,'CASTILLOS',3),(143,'EVA CARPET',3),(144,'FLUTE',3),(145,'MAGIC SAND',3),(146,'CUTLERY',5),(147,'SPICE BOTTLES',5),(148,'ACCESORIO DE PARRILLA',5),(149,'VACUUM FLUSK',5),(150,'KITCHEN TOOLS',5),(151,'SCISSORS AND SCISSORS W/ACCES.',5),(152,'BAMBOO TRAY',5),(153,'PLASTIC TRAY',5),(154,'PLASTIC FOOD CONTAINER',5),(155,'NUTCRACKER',5),(156,'CAKE ACCES.',5),(157,'COFFEE POT / KETTLE / TEAPOT',5),(158,'PLACEMAT',5),(159,'TABLECLOTH',5),(160,'MELAMINE DISH',5),(161,'BOWL AND SALAD BOWL',5),(162,'FUENTES',5),(163,'CERAMIC MUGS',5),(164,'CERAMIC CUP W/SAUCER',5),(165,'PLASTIC TUMBLER',5),(166,'GLASS TUMBLER',5),(167,'GLASS FOOD CONTAINER',5),(168,'BEAR MUG',5),(169,'SPICE BOTTLES W/STAND',5),(170,'COLANDER',5),(171,'PLASTIC BOX',5),(172,'CUTTING BOARD',5),(173,'CANS',5),(174,'TIMERS',5),(175,'KNIFE W/BOARD',5),(176,'SALT AND PEPPER BOTTLE',5),(177,'OIL AND VINEGAR BOTTLE',5),(178,'SUGAR AND CREAM BOTTLE',5),(179,'FOOD CARRYING BOX',5),(180,'ENAMEL CUPS',5),(181,'POT HOLDER',5),(182,'ENAMEL DISH',5),(183,'PLASTIC DISH',5),(184,'BREAD BASKET',5),(185,'STRAW',5),(186,'ICE BOX',5),(187,'TOOTHPICK',5),(188,'BUTTER TRAY',5),(189,'ELECTRIC KITCHEN TOOLS',5),(190,'STEEL TRAY',5),(191,'MELANIME BOWL',5),(192,'KITCHEN KNIFE',5),(193,'WINE OPENER',5),(194,'MAGNET',5),(195,'ALUMINIUM PAPER/FILM',5),(196,'CAZUELA DE CERAMICA',5),(197,'BATERIA DE COCINA',5),(198,'MELANIME TRAY',5),(199,'LIGHTER',5),(200,'MELANIME CUP',5),(201,'GLASS PLATE',5),(202,'CANDY BOX',5),(203,'CUTLERY HOLDER',5),(204,'COOLER BAG',5),(205,'CERAMIC DINNER SET',5),(206,'PET BOWL',5),(207,'PLASTIC JAR',5),(208,'GLASS CUP',5),(209,'CLIP FOOD',5),(210,'ESPATULA',5),(211,'GRATER',5),(212,'KITCHEN SCALE',5),(213,'SHAPER MOLD',5),(214,'DIFFERENT BABY ITEMS',5),(215,'CAN OPENER',5),(216,'BABY BOTTLE',5),(217,'METAL BASKET',5),(218,'GARLIC PRESSOR',5),(219,'STRAW',5),(220,'EGG BEATER',5),(221,'ROLL HOLDER',5),(222,'KNIFE SHARPENER',5),(223,'JUICE PRESSOR',5),(224,'TABLECLOTH HOOK',5),(225,'EGG CUTTER',5),(226,'CHEESE BOTTLE',5),(227,'CLOTH ELECTRIC SHAVER',5),(228,'PLATO DE SITIO',5),(229,'SNACK PLATE',5),(230,'NAPKIN HOLDER',5),(231,'STEEL CUP',5),(232,'BAG SEALER',5),(233,'PLASTIC ORGANIZER',5),(234,'SAUCE POT',5),(235,'WILLOW BASKET',5),(236,'PVC ROLL',5),(237,'GLASS JAR',5),(238,'BOTTLE HOLDER',5),(239,'KID`S CUP',5),(240,'DECANTER',5),(241,'WINE ACCES.',5),(242,'THERMIC CUPS',5),(243,'DESSERT CUP',5),(244,'POTS',5),(245,'FRY PANS',5),(246,'COCKTAIL SHAKER',5),(247,'WOODEN ROLLER',5),(248,'PEELER',5),(249,'MEAT HAMMER',5),(250,'PIZZA CUTTER',5),(251,'FUNNEL',5),(252,'GRILL TOOLS',5),(253,'ENAMEL FOOD CONTAINER',5),(254,'WINE CUP',5),(255,'HONEY BOTTLE',5),(256,'DISH WRACK',5),(257,'PUNCH BOWL',5),(258,'CERAMIC JAR',5),(259,'CERAMIC SOUP',5),(260,'FRUIT FORK',5),(261,'SPOON',5),(262,'MAT',5),(263,'CERAMIC VASE',5),(264,'GLASS BOWL',5),(265,'ICECREAM HOLDER',5),(266,'FRUIT HOLDER',5),(267,'CERAMIC BOWL',5),(268,'SILICON ITME',5),(269,'LEASH AND LOCK',6),(270,'BATTERY',6),(271,'PLIERS AND CLIPS',6),(272,'SAW',6),(273,'PET LEASH',6),(274,'SCREW DRIVER',6),(275,'HAMMERS',6),(276,'TORCH',6),(277,'WORK GLOVES',6),(278,'MEASURING TAPE AND RULERS',6),(279,'KEYS',6),(280,'ELECTRIC TAPE',6),(281,'LIMAS Y ESCOFINA',6),(282,'PAINT ACCES.',6),(283,'GARDEN ACCES.',6),(284,'CRIKET',6),(285,'FANS',6),(286,'LOW CONSUMPTION LAMP',6),(287,'OIL BOTTLE(TOOLS)',6),(288,'TARUGOS / MECHAS',6),(289,'PLUG',6),(290,'PUMP',6),(291,'PRENZA',6),(292,'ORGANIZER TOOLS',6),(293,'WINDOW SHADE',6),(294,'GLUE GUN',6),(295,'LUGGAGE TAPE',6),(296,'BICYCLE ACCES.',6),(297,'LANTERN',6),(298,'CAR ACCES.',6),(299,'DOOR BELL',6),(300,'TESTER(SCREWDRIVER FOR ELECTRICITY)',6),(301,'ESPATULA(TOOLS)',6),(302,'MOUSE TRAP',6),(303,'FILING STONE',6),(304,'ELASTIC BAND',6),(305,'LADDERS',6),(306,'INSECT REPELING DEVICES',6),(307,'TOPETIN PARA PUERTAS',6),(308,'SINK COVER',6),(309,'BARRA DE SILICONAS',6),(310,'HOOKS AND HANGERS',6),(311,'GLUE TAPE',6),(312,'TV ANTENA',6),(313,'METAL HAIR COMB',6),(314,'PROLONGADORES',6),(315,'TOOLS SET',6),(316,'REMACHADORA',6),(317,'GLUES',6),(318,'SOLDADOR',6),(319,'NIVEL',6),(320,'DOOR BREAK',6),(321,'IRON BOARD',6),(322,'BINOCULARES',6),(323,'TREE',7),(324,'BALL',7),(325,'TINCEL',7),(326,'XMAS TREE DECORATIONS',7),(327,'NATIVITY SETS',7),(328,'XMAS CANDLE',7),(329,'BOW',7),(330,'WREATH',7),(331,'XMAS LIGHT',7),(332,'XMAS HAT',7),(333,'CANDLE HOLDER',7),(334,'BOOT',7),(335,'BAGS AND PAPERS',7),(336,'CARDS',7),(337,'FLOWER',7),(338,'DOOR DECORATION',7),(339,'PINCHES',7),(340,'MUSICAL SANTA',7),(341,'XMAS CERAMIC DECORATION',7),(342,'XMAS TRAY',7),(343,'HATS',9),(344,'SCARF',9),(345,'BED SHEETS',9),(346,'SOCKS',9),(347,'LADY HANDKERCHIEF',9),(348,'MAN HANDKERCHIEF',9),(349,'SHOES',9),(350,'TIE',9),(351,'GLOVES',9),(352,'CARPET',9),(353,'SHOE LACES',9),(354,'RAIN COAT',9),(355,'FLEECE NECK',9),(356,'ORGANIZER AND COVERS',9),(357,'KITCHEN GLOVES',9),(358,'SCARFS',9),(359,'LOGOS',9),(360,'BABY COVERS',9),(361,'COVER BABY CAR',9),(362,'UNDERWEAR',9),(363,'GLASSES AND SUNGLASSES',9),(364,'PILLOW COVER',9),(365,'KITCHEN APRON',9),(366,'IRONING BOARD COVER',9),(367,'LAUNDRY BOX',9),(368,'EARMUFFS',9),(369,'ICE BAG',9),(370,'WALK STICK',9),(371,'BACPACK',10),(372,'WAIST BAG',10),(373,'SUITCASE',10),(374,'BACKPACK W/TROLLY',10),(375,'COSMETIC BAG',10),(376,'SPORTS BAG',10),(377,'COIN BAG',10),(378,'UMBRELLA',10),(379,'WALLET',10),(380,'SHOPPING BAG',10),(381,'KEYS HOLDER',10),(382,'BEACH UMBRELLA',10),(383,'SCHOOL BAG',10),(384,'LADY BAGS',10),(385,'MAN BAGS',10),(386,'TRIANGLE BACKPACK',10),(387,'UNIVERSITY BAG',10),(388,'BANDOLERA',10),(389,'BABY BAG',10),(390,'BELTS',10),(391,'PVC BAG',10),(392,'KID`S BAG',10),(393,'COMPUTER BAG',10),(394,'RAINCOAT',10),(395,'GLASS FLOWER VASE',20),(396,'CERAMIC FLOWER VASE',20),(397,'CERAMIC DECORATION',20),(398,'SAVING BOX',20),(399,'JEWELLERY BOX / HOLDER',20),(400,'ARTIFICIAL FLOWER',20),(401,'PLASTIC FRUIT/PLANT',20),(402,'GLASS STONE AND NATURE STONE',20),(403,'WIND BELL',20),(404,'CANDLE',20),(405,'CANDLE HOLDER',20),(406,'WOODEN PHOTOFRAME',20),(407,'PLASTIC PHOTOFRAME',20),(408,'METAL PHOTOFRAME',20),(409,'CD HOLDER',20),(410,'KEY CHAIN',20),(411,'FAN',20),(412,'PHOTO ALBUM',20),(413,'TRAVEL PILLOW',20),(414,'FRAMED DRAWING',20),(415,'PLASTIC FLOWER VASE',20),(416,'METAL FLOWER VASE',20),(417,'DECORATION BOTTLE',20),(418,'GLASS DECORATION',20),(419,'MOBILE PHONE HOLDER',20),(420,'EYEGLASS HOLDER',20),(421,'TABLE/WALL CALENDAR',20),(422,'PLASTIC BAG',20),(423,'ASHTRAY',20),(424,'POLYRESIN DECORATION',20),(425,'GLASS PHOTOFRAME',20),(426,'LAMP',20),(427,'PHOTOFRAME W/CLOCK',20),(428,'MUSIC BOX',20),(429,'WALL HANGER',20),(430,'POLYRESIN PHOTOFRAME',20),(431,'AIRFRESHER',20),(432,'WOODEN DECORATION',20),(433,'CARD HOLDER',20),(434,'PAPERWEIGHT',20),(435,'INSCENSE STICKS',20),(436,'PLASTIC DECORATION',20),(437,'THERMOMETER',20),(438,'FLOWER POT',20),(439,'FABRIC DECORATION',20),(440,'MOBILE PHONE ACCES.',20),(441,'WATER FOUNTAIN',20),(442,'CUP HOLDER',20),(443,'BANDS',20),(444,'OIL BURNER',20),(445,'CANDY HOLDER',20),(446,'WALLET',20),(447,'GIFT',20),(448,'HAT',20),(449,'MEN TRIMMER',21),(450,'TOOTH BRUSH',21),(451,'HAIR BRUSH',21),(452,'BATH SPONGE',21),(453,'MIRROR BAG',21),(454,'MIRROR',21),(455,'SHOWER CURTAIN',21),(456,'BATH ACCES.',21),(457,'NAIL BRUSH',21),(458,'TOILET BRUSH',21),(459,'TENDER',21),(460,'HANGER',21),(461,'MEDICINE ORGANIZER',21),(462,'SPRAY BOTTLE',21),(463,'BATH BRUSH',21),(464,'NAIL CUTTER',21),(465,'MASSAGER',21),(466,'SHOE POLISH SET',21),(467,'THREAD SET',21),(468,'SCISSORS',21),(469,'BATH SCALE',21),(470,'COTTON BUD',21),(471,'BATH MAT',21),(472,'LAUNDRY BASKET',21),(473,'TRASH BIN',21),(474,'CLOTHES BRUSH',21),(475,'PEDICURE AND MANICURE SET',21),(476,'SHOWER CAP',21),(477,'FOOT STONE',21),(478,'BATH ORGANIZER',21),(479,'HAIR ROLLER',21),(480,'TISSUE',21),(481,'HOT WATER BOTTLE',21),(482,'CLEANING FEATHER BRUSH',21),(483,'EYE LASH CURLER',21),(484,'PET BRUSH',21),(485,'HAIR DEPILATOR',21),(486,'FELPUDO',21),(487,'SHARPENER',21),(488,'KITCHEN GLOVES',21),(489,'SILICON TOOLS',21),(490,'BATH SPONGE',21),(491,'LAUNDRY BAG',21),(492,'BABY BRUSH',21),(493,'CAR BRUSH',21),(494,'SPADE',21),(495,'GLASS CLEANER',21),(496,'WASHING MACHINE COVER',21),(497,'COSMETIC BRUSH',21),(498,'HAIR BRUSH AND HAIR BRUSH SET',21),(499,'CLOTHES HANGER',21),(500,'MAKE UP',21),(501,'COSMETIC HOLDER BOX',21),(502,'TABLE BRUSH',21),(503,'PAPER NAPKIN',21),(504,'KITCHEN SPONGE',21),(505,'BATH TOWEL',21),(506,'SOAP HOLDER',21),(507,'BRA',21),(508,'SCAFE HANGER',21),(509,'APRON',21),(510,'DOG ITEM',21),(511,'PET ITEM',21),(512,'SHAVER',21),(513,'CURLING IRON',21),(514,'CHAIR AND SEAT',22),(515,'TABLE',22),(516,'CAR CLOCK',24),(517,'RADIO',24),(518,'REMOTE CONTROL',24),(519,'CINTA PARA VIDEO',24),(520,'WALK-MAN CASSETTE',24),(521,'CASSETTE LIMPIADOR',24),(522,'CASSETTE VIRGEN 60\'',24),(523,'LASER POINTER',24),(524,'ELECTRONIC GAME',24),(525,'WATCHES',24),(526,'EARPHONE',24),(527,'DESK CLOCK',24),(528,'ACCESORIOS DE PEINADO',24),(529,'IRON',24),(530,'WALL CLOCK',24),(531,'DESK LAMP',24),(532,'PRESSION LAMP',24),(533,'PHOTO CAMERA',24),(534,'COMPUTER ACCES.',24),(535,'CALCULATOR',24),(536,'DIGITAL CALENDAR',24),(537,'BICYCLE LIGHT',24),(538,'TORCH',24),(539,'TORCH',24),(540,'GAS LIGHTER',24),(541,'DOOR BEEL',24),(542,'CAMERA MOBILE PHONE HOLDER',24),(543,'LED LIGHT',24),(544,'INFLATABLE',25),(545,'BINOCULARES',25),(546,'COMPASS',25),(547,'CAMPING TOOLS',25),(548,'ACCE DE MUSCULACION',25),(549,'SWIMMING GOGGLES',25),(550,'MOTORCYCLE ACCES.',25),(551,'INFLATABLE MATRESS',25),(552,'NEOPRENE ACCES.',25),(553,'CRUTCH',25),(554,'SURFBOARD',25),(555,'SKATE',25),(556,'BASKET BALL',25),(557,'PINIG PANG BALL SET',25),(558,'BALL FOR TOY',25),(559,'DIFFERENT HAIR ITEMS',32),(560,'HAIR CLIP',32),(561,'HAIR CLAW',32),(562,'PURSE',32),(563,'HAIR BAND',32),(564,'ELASTIC BAND',32),(565,'CON RELOJ',32),(566,'EARINGS',33),(567,'NECKLACE',33),(568,'BRACELET',33),(569,'RINGS',33),(570,'PLUSH TOY',34),(571,'CUSHION',34),(572,'KEY CHAIN',34),(573,'BACKPACKS AND BAGS',34),(574,'FINGER TOY',34),(575,'LEG MODEL',40);
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unittype` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (0,'NOUNIT'),(1,'SET'),(2,'PCS'),(3,'DOZ');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(150) NOT NULL,
  `group_level` int(11) NOT NULL,
  `group_status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_level` (`group_level`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (1,'Admin',1,1),(2,'Special',2,1),(3,'User',3,1);
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_level` int(11) NOT NULL,
  `image` varchar(255) DEFAULT 'no_image.jpg',
  `status` int(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `user_level` (`user_level`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Users','Admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,'qu1qr3q1.jpg',1,'2022-11-20 18:41:03'),(2,'Special User','special','ba36b97a41e7faf742ab09bf88405ac04f99599a',2,'no_image.jpg',1,'2017-06-16 07:11:26'),(3,'Default User','user','12dea96fec20593566ab75692c9949596833adc9',3,'no_image.jpg',1,'2017-06-16 07:11:03'),(4,'eze','eze','d033e22ae348aeb5660fc2140aec35850c4da997',1,'no_image.jpg',1,'2021-03-22 18:08:15');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendorcode`
--

DROP TABLE IF EXISTS `vendorcode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendorcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendorcode` varchar(45) DEFAULT NULL,
  `vendors_id` int(11) NOT NULL,
  `products_id` int(11) NOT NULL,
  `openclose` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_vendorcode_vendors1_idx` (`vendors_id`),
  KEY `fk_vendorcode_products1_idx` (`products_id`),
  CONSTRAINT `fk_vendorcode_vendors1` FOREIGN KEY (`vendors_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendorcode`
--

LOCK TABLES `vendorcode` WRITE;
/*!40000 ALTER TABLE `vendorcode` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendorcode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `address` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `website` varchar(45) DEFAULT NULL,
  `contact` varchar(45) DEFAULT NULL,
  `bus_number` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `bank_account` varchar(45) DEFAULT NULL,
  `bank` varchar(45) DEFAULT NULL,
  `openclose` tinyint(1) DEFAULT '1',
  `beneficiary_name` varchar(45) DEFAULT NULL,
  `taxpayer` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (3,'Maker','Makeraddress','China','15566555223','a@a.a','asd','56632sdd','a@a.a','56669696','chino',1,'Pedro','');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-20 20:38:30
