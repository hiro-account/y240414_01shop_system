-- MariaDB dump 10.19  Distrib 10.4.22-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: from240414_01
-- ------------------------------------------------------
-- Server version	10.4.22-MariaDB

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
-- Table structure for table `mst_staff`
--

DROP TABLE IF EXISTS `mst_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mst_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name_kana` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name_kana` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` int(11) NOT NULL,
  `birthday` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_staff`
--

LOCK TABLES `mst_staff` WRITE;
/*!40000 ALTER TABLE `mst_staff` DISABLE KEYS */;
INSERT INTO `mst_staff` VALUES (1,'山田','太郎','ヤマダ','タロウ',1,'1934-04-01','$2y$10$DfctR61CXEYzFZ2P/wtGpOoaVrRW47SBCTFuiKHfJ8bpTUVD.6kxC',0,'2024-05-28 05:26:02','2024-05-28 05:26:02'),(2,'佐藤','一郎','サトウ','イチロウ',1,'1941-01-01','$2y$10$2BSs8BmOOmXuqSD77sYyXur0x9vQBSsBm.P99ZmQ6ewzZ1ypo5MA6',0,'2024-05-29 06:00:25','2024-05-29 06:00:25');
/*!40000 ALTER TABLE `mst_staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_staff_empty`
--

DROP TABLE IF EXISTS `mst_staff_empty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mst_staff_empty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name_kana` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name_kana` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` int(11) NOT NULL,
  `birthday` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_staff_empty`
--

LOCK TABLES `mst_staff_empty` WRITE;
/*!40000 ALTER TABLE `mst_staff_empty` DISABLE KEYS */;
INSERT INTO `mst_staff_empty` VALUES (1,'氏','名','シ','メイ',0,'2008-01-01','$2y$10$hYyGAShxDLxYnTwygY/sm.1LMvGNi138zD75Batr2NFehKf6TyJo6',0,'2024-06-01 10:04:12','2024-06-01 10:04:12');
/*!40000 ALTER TABLE `mst_staff_empty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_staff_for_dev`
--

DROP TABLE IF EXISTS `mst_staff_for_dev`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mst_staff_for_dev` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name_kana` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name_kana` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` int(11) NOT NULL,
  `birthday` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `created_date` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_staff_for_dev`
--

LOCK TABLES `mst_staff_for_dev` WRITE;
/*!40000 ALTER TABLE `mst_staff_for_dev` DISABLE KEYS */;
INSERT INTO `mst_staff_for_dev` VALUES (1,'山田','太郎','ヤマダ','タロウ',1,'1934-04-01','$2y$10$DfctR61CXEYzFZ2P/wtGpOoaVrRW47SBCTFuiKHfJ8bpTUVD.6kxC',0,'2024-05-28 05:26:02','2024-05-28 05:26:02'),(2,'佐藤','一郎','サトウ','イチロウ',1,'1941-01-01','$2y$10$2BSs8BmOOmXuqSD77sYyXur0x9vQBSsBm.P99ZmQ6ewzZ1ypo5MA6',0,'2024-05-29 06:00:25','2024-05-29 06:00:25'),(3,'伊藤','二郎','イトウ','ジロウ',1,'1942-02-02','$2y$10$EjkdSnRZY1MqI1Uzzdp4HuUpOmrdN6oU9L4t/60sQQaErZpYd8oVK',0,'2024-06-01 16:45:59','2024-06-01 16:45:59'),(4,'加藤','三郎','カトウ','サブロウ',1,'1943-03-03','$2y$10$YSiaqUt1jNU.kpbH10FUeO3I/Yk0MeiZuj3N.JX3Ifro6ptHNvAZu',0,'2024-06-01 17:23:38','2024-06-01 17:23:38');
/*!40000 ALTER TABLE `mst_staff_for_dev` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-01 17:53:08
