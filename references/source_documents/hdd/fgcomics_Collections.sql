CREATE DATABASE  IF NOT EXISTS `fgcomics` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `fgcomics`;
-- MySQL dump 10.13  Distrib 8.0.29, for Win64 (x86_64)
--
-- Host: corehttp1.index.one    Database: fgcomics
-- ------------------------------------------------------
-- Server version	8.0.29-0ubuntu0.20.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Collections`
--

DROP TABLE IF EXISTS `Collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Collections` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Title` varchar(150) NOT NULL,
  `Sequence` int DEFAULT NULL,
  `Series` int DEFAULT NULL,
  `RangeMin` int DEFAULT NULL,
  `RangeMax` int DEFAULT NULL,
  `Parent` int DEFAULT NULL,
  `Cover` varchar(150) DEFAULT NULL,
  `BackCover` varchar(150) DEFAULT NULL,
  `ArchiveImage` varchar(150) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`ID`),
  KEY `Parent` (`Parent`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Collections`
--

LOCK TABLES `Collections` WRITE;
/*!40000 ALTER TABLE `Collections` DISABLE KEYS */;
INSERT INTO `Collections` VALUES (1,'The Koven Wars',1,1,1,50,NULL,'NULL','NULL','fg_book1.gif','The comics that started it all! In many of these comics, we were just seeing what we could do, and the comic grid we still use was developed and perfected at this stage. A word of caution to all who would enter... You are about to witness comics so old that they\'re starting to show up on the collective radar of the local archaeologists. Take them with a truckload of salt.'),(2,'Some Nice Comix',2,1,51,90,NULL,'NULL','NULL','fg_book2.gif','After the fiasco the previous comics indirectly caused, one of my, um, \"supervisors\" to ask me, \"Adam, why don\'t you make some nice comics?\" So I did just that. Gone is Funny Guy kicking the butts of people I may or may not know in real life in favor of more \"appropriate\" humor.'),(3,'The Quest for Serious Man',3,1,91,120,NULL,'NULL','NULL','fg_book3.gif','With Repair Man and Koven mostly gone, it was time for a new Evilie to step in... Serious Man. He despises everything silly and humorous, which happens to be everything Funny Guy and his comrades stand for. Naturally, there was going to be some animosity.'),(4,'Serious Man Returns',4,1,121,160,NULL,NULL,NULL,'fg_book4.gif','What\'s a saga without a story arc in which the big baddie comes back to get his butt kicked some more? Serious Man is back, and with a team of new comrades to help him succeed in his quest to turn Funnyland into a serious, bland, and insipid waste.'),(5,'Live From Funnytown',5,1,161,200,NULL,'NULL',NULL,'fg_book5.gif','This book is HUGE. No, seriously. It features our two &quot;super size&quot; comic books within its pages. It also features the &quot;F.G. Telethon&quot;, a collection of random adventures Funny Guy and co. get into for Oxy-Moron... so he and his techie, Jack, can raise money for... an unknown cause.'),(6,'Action Comics',6,1,201,240,NULL,NULL,NULL,'fg_book6.gif','Ready for AKSHUN?! These extended adventures spanning a few comics in which random action happens. Target uses a shrink ray and some heavy equipment to stop Junior\'s cold one on one? Junior gets a new pet and it turns out to be a roach? It\'s all here.'),(7,'The Power of Graphite',7,1,241,280,NULL,NULL,NULL,'fg_book7.gif','This book features some of the most detailed comics to date, and the adventures get weirder and weirder yet...'),(8,'Epic Comics',8,1,281,320,NULL,NULL,NULL,'fg_book8.gif','The Funny Guys are once again teleported to a strange, new world... this time to the mysterious Ever Grande Metropolis, a city spanning three interconnected planets and the structure connecting those planets... and the city police need them captured. But why? And will the FGs actually have to put in the effort to get out? Features the NEO Photorealism process.'),(9,'An F.G. Christmas Carol',NULL,1,175,NULL,5,'NULL','NULL','NULL','NULL'),(10,'Attack of the Drones',NULL,1,186,NULL,5,'NULL','NULL','NULL','NULL'),(11,'Kare Bear Killer',NULL,NULL,NULL,NULL,NULL,'NULL','NULL','NULL','NULL');
/*!40000 ALTER TABLE `Collections` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-07-16 21:57:55
