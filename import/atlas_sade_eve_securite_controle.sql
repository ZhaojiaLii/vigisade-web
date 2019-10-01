-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: atlas_sade
-- ------------------------------------------------------
-- Server version	5.7.20-log

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
-- Table structure for table `eve_securite_controle`
--

DROP TABLE IF EXISTS `eve_securite_controle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eve_securite_controle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_controle` date DEFAULT NULL,
  `id_instance` int(11) DEFAULT NULL,
  `marche_saisie` varchar(200) DEFAULT NULL,
  `agence_saisie` varchar(200) DEFAULT NULL,
  `nom_entreprise` varchar(1000) DEFAULT NULL,
  `controleur` varchar(200) DEFAULT NULL,
  `chantier` varchar(100) DEFAULT NULL,
  `immatriculation` varchar(20) DEFAULT NULL,
  `commentaire` varchar(1000) DEFAULT NULL,
  `activite` varchar(100) DEFAULT NULL,
  `secouristes` int(11) DEFAULT NULL,
  `ok` int(11) DEFAULT NULL,
  `ko` int(11) DEFAULT NULL,
  `nv` int(11) DEFAULT NULL,
  `fp` int(11) DEFAULT NULL,
  `ac` int(11) DEFAULT NULL,
  `commentaire_final` varchar(1000) DEFAULT NULL,
  `etat` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eve_securite_controle`
--

LOCK TABLES `eve_securite_controle` WRITE;
/*!40000 ALTER TABLE `eve_securite_controle` DISABLE KEYS */;
INSERT INTO `eve_securite_controle` VALUES (1,'2018-02-07',1,'','','','HAUMONTE Jean-David','0254','','','1',0,1,0,0,0,0,'','4'),(2,'2018-02-07',1,'','','','HAUMONTE Jean-David','05','1201','','1',0,52,1,0,0,0,'','4'),(6,'2018-04-09',2,'vfd','gfvds','vcx','HAUMONTE Jean-David','vcx','','','1',0,53,0,0,6,4,'','4'),(7,'2018-04-18',1,'','ETE Réseaux Aix','ttrrr','HAUMONTE Jean-David','0','','test','1',0,19,5,14,0,0,'Je suis dans la merde à cause de ce bouton de merde','1'),(8,'2018-05-15',1,'double pompe','ETE Réseaux Aix','','HAUMONTE Jean-David','0353654354','11-yy-54,22','Controle des pieds','2',2,5,2,7,1,1,'double xp èè èè','1'),(9,'2018-05-28',2,'dsq','Cottel Réseaux R2A','fdsq','HAUMONTE Jean-David','fdsq','','sans le truc de dire que','2',0,0,1,46,0,0,'','4'),(10,'2018-06-08',3,'654','Cottel Réseaux R2A','6354','hau jd','3564','','','2',0,0,0,0,0,0,'','1'),(14,'2018-07-10',1,'fdsqf','Cottel Réseaux R2A','fdsqfds','HAUMONTE Jean-David','dsqfds','254254','gfdsqg','2',10,8,0,53,1,1,NULL,'4'),(23,'2018-07-12',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,'1',NULL,1,0,46,0,0,NULL,'4'),(24,'2018-02-01',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'1'),(25,'2018-10-15',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'1'),(26,'2018-10-15',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,2,2,16,0,0,NULL,'1'),(27,'2018-12-13',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'1'),(28,'2019-01-14',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'1'),(29,'2019-01-14',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'1'),(30,'2019-01-22',1,'bvxcbvcx','ETE Réseaux Mérignac','bvcxbvcx','HAUMONTE Jean-David','bvcxb','bvxc','hgfd','2',1,3,3,0,0,0,NULL,'1'),(31,'2019-05-21',1,NULL,NULL,NULL,'HAUMONTE Jean-David',NULL,NULL,NULL,NULL,NULL,0,0,0,0,0,NULL,'1');
/*!40000 ALTER TABLE `eve_securite_controle` ENABLE KEYS */;
UNLOCK TABLES;

/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-05-09 10:16:27
