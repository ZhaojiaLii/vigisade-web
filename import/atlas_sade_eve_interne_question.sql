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
-- Table structure for table `eve_interne_question`
--

DROP TABLE IF EXISTS `eve_interne_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eve_interne_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_categorie` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `important` varchar(10) DEFAULT NULL,
  `ordre` int(11) DEFAULT NULL,
  `nom` varchar(200) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `securite` varchar(10) DEFAULT NULL,
  `environement` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

LOCK TABLES `eve_interne_question` WRITE;
/*!40000 ALTER TABLE `eve_interne_question` DISABLE KEYS */;
INSERT INTO `eve_interne_question` VALUES (2,2,'multiple','n',1,'Aptitude médicale','Aptitude médicale','n','n'),(3,2,'multiple','n',2,'Accueil','Accueil','n','n'),(4,2,'multiple','n',3,'Carte Professionelle du BTP','Carte Professionelle du BTP','n','n'),(5,2,'multiple','n',4,'Titre Habilitation','Titre Habilitation','n','n'),(6,2,'multiple','n',5,'Port des EPI adaptés','Port des EPI adaptés','n','n'),(7,2,'multiple','n',6,'Etat des EPI','Etat des EPI','n','n'),(8,2,'multiple','n',7,'Alcoolémie / Conduite addictive','Alcoolémie / Conduite addictive','n','n'),(9,3,'simple','n',1,'PPSPS, PdP, FIC','PPSPS, PdP, FIC','n','n'),(10,3,'simple','n',2,'PPE, FIC','PPE, FIC','n','n'),(11,3,'simple','n',3,'Dossier chantier','Dossier chantier','n','n'),(12,3,'simple','n',4,'Permission-autorisation de voierie','Permission-autorisation de voierie','n','n'),(13,3,'simple','n',5,'Arrêté de circulation / stationnement','Arrêté de circulation / stationnement','n','n'),(14,3,'simple','n',6,'Documents réglementaires / Pochette SQE','Documents réglementaires / Pochette SQE','n','n'),(15,4,'simple','n',1,'Aspect - Propreté - Rangement','Aspect - Propreté - Rangement','n','o'),(16,4,'simple','n',2,'Absence de pollution','Absence de pollution','n','o'),(17,4,'simple','n',3,'Stockage des produits dangereux','Stockage des produits dangereux','n','o'),(18,4,'simple','n',4,'Kit antipollution','Kit antipollution','n','o'),(19,4,'simple','n',5,'Circulation respectée pour les tiers','Circulation respectée pour les tiers','n','o'),(20,4,'simple','n',6,'Respect du tri des déchets','Respect du tri des déchets','n','o'),(21,5,'simple','n',1,'FDS ou FDS simplifiée présente','FDS ou FDS simplifiée présente','n','n'),(22,5,'simple','n',2,'Produits identifiés (étiquette)','Produits identifiés (étiquette)','n','n'),(23,6,'simple','n',1,'Balisage - Protection du chantier','Balisage - Protection du chantier','o','n'),(24,6,'simple','n',2,'Signalisation routière','Signalisation routière','o','n'),(25,6,'simple','n',3,'Accès au poste de travail','Accès au poste de travail','o','n'),(26,6,'simple','n',4,'Sécurité au poste de travail','Sécurité au poste de travail','o','n'),(27,6,'simple','n',5,'Chambre ouverte protégée','Chambre ouverte protégée','o','n'),(28,6,'simple','n',6,'Protection des tranchées','Protection des tranchées','o','n'),(29,6,'simple','n',7,'DT-DICT','DT-DICT','o','n'),(30,7,'simple','n',1,'Travail en hauteur','Travail en hauteur','n','n'),(31,7,'simple','n',2,'Espace confiné','Espace confiné','n','n'),(32,7,'simple','n',3,'Manutention des plaques','Manutention des plaques','n','n'),(33,7,'simple','n',4,'Risque électromagnétique','Risque électromagnétique','n','n'),(34,7,'simple','n',5,'Risque électrique','Risque électrique','n','n'),(35,7,'simple','n',6,'Travail sur matériaux dangereux','Amiante, plomb, HAP, silice','n','n'),(36,10,'simple','o',1,'ALERERTTE','OK : si pas de situation, NOK : si situation','o','n'),(37,10,'simple','n',2,'Connaissance du dernier QH','Connaissance du dernier QH','n','n'),(38,10,'simple','n',3,'Connaissance du dernier AT','Connaissance du dernier AT','n','n'),(39,8,'simple','n',1,'Matériel portatif','Matériel portatif','n','n'),(40,8,'simple','n',2,'Equipement balisage à disposition','Equipement balisage à disposition','n','n'),(41,8,'simple','n',3,'Trousse de secours présente','Trousse de secours présente','n','n'),(42,8,'simple','n',4,'Produits non périmés','Produits non périmés','n','n'),(43,8,'simple','n',5,'Appareil et accessoire de levage','Appareil et accessoire de levage','n','n'),(44,8,'simple','n',6,'Echelle et escabeau conforme','Echelle et escabeau conforme','n','n'),(45,9,'simple','n',1,'Installation de chantier','Installation de chantier','n','n'),(46,9,'simple','n',2,'Extincteur','Extincteur','n','n'),(47,9,'simple','n',3,'Permis de feu','Permis de feu','n','n'),(48,5,'simple','n',1,'fgedsgfds','gfdsgfds','n','n');
/*!40000 ALTER TABLE `eve_interne_question` ENABLE KEYS */;
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
