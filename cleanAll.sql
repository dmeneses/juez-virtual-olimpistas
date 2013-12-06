-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: tis_mbravesoft
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.13.04.1

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
-- Table structure for table `training`
--

DROP TABLE IF EXISTS `training`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training` (
  `training_id` int(11) NOT NULL AUTO_INCREMENT,
  `training_name` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `start_time` varchar(45) NOT NULL,
  `end_date` datetime NOT NULL,
  `end_time` varchar(45) NOT NULL,
  `training_owner` int(11) NOT NULL,
  PRIMARY KEY (`training_id`),
  UNIQUE KEY `training_name_UNIQUE` (`training_name`),
  KEY `fk_training_user1` (`training_owner`),
  CONSTRAINT `fk_training_user1` FOREIGN KEY (`training_owner`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training`
--

LOCK TABLES `training` WRITE;
/*!40000 ALTER TABLE `training` DISABLE KEYS */;
/*!40000 ALTER TABLE `training` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `group_owner` int(11) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `group_id_UNIQUE` (`group_id`),
  UNIQUE KEY `group_name_UNIQUE` (`group_name`),
  KEY `fk_group_user1` (`group_owner`),
  CONSTRAINT `fk_group_user1` FOREIGN KEY (`group_owner`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_has_group`
--

DROP TABLE IF EXISTS `training_has_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_has_group` (
  `training_training_id` int(11) NOT NULL,
  `group_group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`training_training_id`,`group_group_id`),
  KEY `fk_training_has_group_group1` (`group_group_id`),
  KEY `fk_training_has_group_training1` (`training_training_id`),
  CONSTRAINT `fk_training_has_group_training1` FOREIGN KEY (`training_training_id`) REFERENCES `training` (`training_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_training_has_group_group1` FOREIGN KEY (`group_group_id`) REFERENCES `group` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_has_group`
--

LOCK TABLES `training_has_group` WRITE;
/*!40000 ALTER TABLE `training_has_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_has_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solution`
--

DROP TABLE IF EXISTS `solution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `solution` (
  `solution_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `solution_date` datetime NOT NULL,
  `solution_language` varchar(5) NOT NULL,
  `solution_source_file` varchar(200) NOT NULL,
  `grade` int(11) DEFAULT '0',
  `runtime` float unsigned DEFAULT '0',
  `used_memory` int(10) unsigned DEFAULT '0',
  `status` varchar(45) DEFAULT 'On Queue',
  `error_message` text,
  `solution_submitter` int(11) NOT NULL,
  `problem_problem_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`solution_id`),
  UNIQUE KEY `solution_id_UNIQUE` (`solution_id`),
  KEY `fk_solution_user1` (`solution_submitter`),
  KEY `fk_solution_problem1` (`problem_problem_id`),
  CONSTRAINT `fk_solution_user1` FOREIGN KEY (`solution_submitter`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_solution_problem1` FOREIGN KEY (`problem_problem_id`) REFERENCES `problem` (`problem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solution`
--

LOCK TABLES `solution` WRITE;
/*!40000 ALTER TABLE `solution` DISABLE KEYS */;
/*!40000 ALTER TABLE `solution` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,TRADITIONAL,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `save_solution_date` BEFORE INSERT ON `solution` FOR EACH ROW
BEGIN
    SET NEW.solution_date = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `training_has_problem`
--

DROP TABLE IF EXISTS `training_has_problem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `training_has_problem` (
  `training_training_id` int(11) NOT NULL,
  `problem_problem_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`training_training_id`,`problem_problem_id`),
  KEY `fk_training_has_problem_problem1` (`problem_problem_id`),
  KEY `fk_training_has_problem_training1` (`training_training_id`),
  CONSTRAINT `fk_training_has_problem_training1` FOREIGN KEY (`training_training_id`) REFERENCES `training` (`training_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_training_has_problem_problem1` FOREIGN KEY (`problem_problem_id`) REFERENCES `problem` (`problem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_has_problem`
--

LOCK TABLES `training_has_problem` WRITE;
/*!40000 ALTER TABLE `training_has_problem` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_has_problem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `birth_date` datetime NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `institution` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Daniela','Meneses','1990-12-01 00:00:00','daniela11290@gmail.com','1121990','UMSS','CBBA'),(2,'Fabio','Arandia','1990-12-01 00:00:00','fabio@gmail.com','1234567','UMSS','CBBA'),(3,'Richi','Daza','1990-12-01 00:00:00','richi@gmail.com','1234567','UMSS','CBBA');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_has_group`
--

DROP TABLE IF EXISTS `user_has_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_has_group` (
  `user_user_id` int(11) NOT NULL,
  `group_group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_user_id`,`group_group_id`),
  KEY `fk_user_has_group_group1` (`group_group_id`),
  KEY `fk_user_has_group_user1` (`user_user_id`),
  CONSTRAINT `fk_user_has_group_user1` FOREIGN KEY (`user_user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_group_group1` FOREIGN KEY (`group_group_id`) REFERENCES `group` (`group_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_has_group`
--

LOCK TABLES `user_has_group` WRITE;
/*!40000 ALTER TABLE `user_has_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_has_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `problem`
--

DROP TABLE IF EXISTS `problem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `problem` (
  `problem_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `problem_name` varchar(50) NOT NULL,
  `problem_author` varchar(100) NOT NULL,
  `is_simple` tinyint(1) NOT NULL DEFAULT '1',
  `compare_type` varchar(10) NOT NULL DEFAULT 'STRICT',
  `avoid_symbol` varchar(1) DEFAULT NULL,
  `time_constraint` int(10) unsigned DEFAULT '0',
  `memory_constraint` int(10) unsigned DEFAULT '0',
  `source_constraint` int(10) unsigned DEFAULT '0',
  `problem_creator` int(11) NOT NULL,
  PRIMARY KEY (`problem_id`),
  UNIQUE KEY `problem_id_UNIQUE` (`problem_id`),
  UNIQUE KEY `problem_name_UNIQUE` (`problem_name`),
  KEY `fk_problem_user` (`problem_creator`),
  CONSTRAINT `fk_problem_user` FOREIGN KEY (`problem_creator`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `problem`
--

LOCK TABLES `problem` WRITE;
/*!40000 ALTER TABLE `problem` DISABLE KEYS */;
/*!40000 ALTER TABLE `problem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_case`
--

DROP TABLE IF EXISTS `test_case`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_case` (
  `test_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `test_in` varchar(200) NOT NULL,
  `test_out` varchar(200) NOT NULL,
  `test_points` int(10) unsigned NOT NULL,
  `problem_problem_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`test_id`),
  UNIQUE KEY `test_id_UNIQUE` (`test_id`),
  KEY `fk_test_problem1` (`problem_problem_id`),
  CONSTRAINT `fk_test_problem1` FOREIGN KEY (`problem_problem_id`) REFERENCES `problem` (`problem_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_case`
--

LOCK TABLES `test_case` WRITE;
/*!40000 ALTER TABLE `test_case` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_case` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-12-06  1:42:31
