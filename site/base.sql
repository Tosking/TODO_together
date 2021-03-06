-- MariaDB dump 10.19  Distrib 10.7.3-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: todolist
-- ------------------------------------------------------
-- Server version	10.7.3-MariaDB

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
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `list` int(11) DEFAULT NULL,
  `item` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`item`),
  KEY `list` (`list`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`list`) REFERENCES `list` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES
(6,28,'ВОйти на сайт',0),
(NULL,32,'21',0),
(5,33,'Купить',0),
(1,34,'123',0),
(29,35,'123',0),
(27,36,'pop',0),
(27,37,'321',0),
(NULL,39,'321',0);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list`
--

DROP TABLE IF EXISTS `list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list` (
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list`
--

LOCK TABLES `list` WRITE;
/*!40000 ALTER TABLE `list` DISABLE KEYS */;
INSERT INTO `list` VALUES
('1',1),
('chel',2),
('test',3),
('test1',4),
('Продукты',5),
('1234',6),
('name',7),
('name',8),
('naming',9),
('321',22),
('321',26),
('123',27),
('lol',28),
('lol',29),
('321',32),
('321',36);
/*!40000 ALTER TABLE `list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_to_user`
--

DROP TABLE IF EXISTS `list_to_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_to_user` (
  `user` int(11) DEFAULT NULL,
  `list` int(11) DEFAULT NULL,
  `access` smallint(6) DEFAULT NULL,
  KEY `user` (`user`),
  KEY `list` (`list`),
  CONSTRAINT `list_to_user_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `list_to_user_ibfk_2` FOREIGN KEY (`list`) REFERENCES `list` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_to_user`
--

LOCK TABLES `list_to_user` WRITE;
/*!40000 ALTER TABLE `list_to_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `list_to_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sub_content`
--

DROP TABLE IF EXISTS `sub_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sub_content` (
  `item` int(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sub_content`
--

LOCK TABLES `sub_content` WRITE;
/*!40000 ALTER TABLE `sub_content` DISABLE KEYS */;
INSERT INTO `sub_content` VALUES
(29,1,'123'),
(29,2,'321'),
(29,3,'ывф'),
(29,4,'ads'),
(33,5,'Молоко'),
(33,6,'Яйца'),
(33,7,'Хлеб'),
(33,8,'Атомную элетростанцию '),
(35,9,'das'),
(36,10,'123'),
(38,13,'321');
/*!40000 ALTER TABLE `sub_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'test','test','test','test'),
(2,'test','test','test','test'),
(3,NULL,'jateren400@doerma.com','tal','tal'),
(4,'yak','jateren1400@doerma.com','yak','yak'),
(5,'don','jateren2400@doerma.com','don','don'),
(6,'gon','jatere1n400@doerma.com','gon','gon'),
(7,'yan','jateren4020@doerma.com','yan','yan'),
(8,'chel','jater1en400@doerma.com','chel','chel');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-23 18:27:18
